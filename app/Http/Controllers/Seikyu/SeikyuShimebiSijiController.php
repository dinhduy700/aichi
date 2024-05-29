<?php

namespace App\Http\Controllers\Seikyu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Carbon;

use App\Helpers\Formatter;
use App\Models\MBumon;
use App\Http\Repositories\Seikyu\SeikyuShimebiSijiRepository;
use App\Http\Requests\Seikyu\SeikyuShimebiSijiRequest;

class SeikyuShimebiSijiController extends Controller
{
    protected $seikyuShimebisijiRepository;

    public function __construct(
        SeikyuShimebiSijiRepository $seikyuShimebisijiRepository
    ) {
        $this->seikyuShimebisijiRepository = $seikyuShimebisijiRepository;
    }

    public function index(Request $request)
    {
        $bumons     = MBumon::all();
        $setting    = require(app_path('Helpers/Grid/config/t_seikyu_shimebi_siji.php'));

        $defaultSeikyuSimeDt = DB::table('m_user_pg_function')
                                ->where($this->getConfWhereUserPgFunction())
                                ->value('choice1_dt');
      
        
        return view('seikyu.seikyu-shimebi.index', compact('setting', 'bumons', 'request', 'defaultSeikyuSimeDt'));
    }

    public function validateSearchForm(SeikyuShimebiSijiRequest $request)
    {
        return response()->json([]);
    }

    public function dataList(Request $request)
    {
        $page       = $request->page ?? 1;
        $perPage    = config('params.PAGE_SIZE');
        $listData   = $this->seikyuShimebisijiRepository->getListWithTotalCount($request);

        $data['total'] = $listData['total'];
        $data['rows'] = $listData['rows']
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();

        // sheet 請求書発行状況
        $seikyuSimeDts = $listData['rows']->get()->pluck('seikyu_sime_dt');
       
        $qbListSeikyuJoinSeikyuSimebi   = $this->seikyuShimebisijiRepository->getListSeikyuJoinSeikyuSimebi($seikyuSimeDts);
        $grouped                        = $qbListSeikyuJoinSeikyuSimebi->get()->groupBy('seikyu_sime_dt');
        
        $arrTmpHako     = [];
        $arrTmpKatutei  = [];

        foreach ($grouped as $seikyuSimeDt => $items) {
            foreach ($items as $item) {
                $arrTmpHako[$seikyuSimeDt][]    = data_get($item, 'seikyu_hako_flg');
                $arrTmpKatutei[$seikyuSimeDt][] = data_get($item, 'seikyu_kakutei_flg');
            }
        }

        $collectHako    = $this->createCollect($arrTmpHako, 'seikyu_hako_flg');
        $collectKakutei = $this->createCollect($arrTmpKatutei, 'seikyu_kakutei_flg');
       
        foreach ($data['rows'] as $key => $row) {
            $seikyuSimeDt = data_get($row, 'seikyu_sime_dt');
            data_set($data['rows'][$key], 'seikyu_hako_flg', null);
            data_set($data['rows'][$key], 'seikyu_kakutei_flg', null);

            if ($collectHako->has($seikyuSimeDt)) {
                data_set($data['rows'][$key], 'seikyu_hako_flg', data_get($collectHako, $seikyuSimeDt));
            }
            
            if ($collectKakutei->has($seikyuSimeDt)) {
                data_set($data['rows'][$key], 'seikyu_kakutei_flg', data_get($collectKakutei, $seikyuSimeDt));
            }
        }

        return response()->json($data);
    }

    public function store(SeikyuShimebiSijiRequest $request)
    {
        $response = $this->seikyuShimebisijiRepository->store($request);
        
        return response()->json($response);
    }

    public function destroy($seikyuSimeDt)
    {
        $response = $this->seikyuShimebisijiRepository->delete($seikyuSimeDt);
      
        return response()->json($response);
    }

    public function handleSeikyuZaiko(Request $request, $seikyuSimeDt)
    {   
        try {
            DB::beginTransaction();

            // sheet 締更新
            $this->handleSeikyu($request->bumons, $seikyuSimeDt);
        
            // sheet データマッピングシート(ECT) (2)
            $this->insertTZaikoHokanryo($seikyuSimeDt);

            DB::commit();

            return response()->json([
                'success' => true,
                'msg' => __('messages.updated2'),
            ]);
        } catch (Exception $e) {
            DB::rollback();
            \Log::info(print_r($e->getMessage(), TRUE) );
            return response()->json([
                'success' => false,
                'msg' => __('messages.E0012'),
            ]);
        }
    }

    private function handleSeikyu($bumons, $seikyuSimeDt)
    {
        try {
            $repo           = $this->seikyuShimebisijiRepository;
            $i              = 0;
            $seikyuSimeDt   = Formatter::date($seikyuSimeDt);
            
            $qbListUriageJoinNinusi = $repo->listUriageJoinNinusi($bumons, $seikyuSimeDt);
            
            if ($qbListUriageJoinNinusi->count() == 0) {
                \Log::info('qbListUriageJoinNinusi is empty.');
                return [
                    'success' => false,
                    'msg' => __('messages.E0016'),
                ];
            }
           
            $dataListUriageJoinNinusi = $qbListUriageJoinNinusi->get();

            // prepare data insert t_seikyu
            $qbListUriageJoinNinusiNoGroupBy = $repo->listUriageJoinNinusiNoGroupBy($bumons, $seikyuSimeDt);

            if ($qbListUriageJoinNinusiNoGroupBy->count() == 0) {
                \Log::info('qbListUriageJoinNinusiNoGroupBy is empty.');
                return [
                    'success' => false,
                    'msg' => __('messages.E0016'),
                ];
            }
            
            $groupedListUriageJoinNinusiNoGroupBy = $qbListUriageJoinNinusiNoGroupBy->get()->groupBy('ninusi_cd');

            $arrTmpByMenzeiKbn = $this->caclByMenzeiKbn($groupedListUriageJoinNinusiNoGroupBy);
            
            // get data_t_nyukin
            $qbNyukin = $repo->listNyukin($seikyuSimeDt);

            // get nyukin_no
            $collectionNyukin   = $qbNyukin->get()->keyBy('ninusi_cd');
            $listNyukinNo       = $repo->listNyukinNo($collectionNyukin, $seikyuSimeDt);
           
            // get uriage_den_no
            $collectionUriage   = $dataListUriageJoinNinusi->keyBy('ninusi_cd');
            $listUriageDenNo    = $repo->listUriageDenNo($collectionUriage, $seikyuSimeDt);
           
            foreach ($dataListUriageJoinNinusi as $key => $item) {
                $i                      += 1;
                $sumGenkinKin           = 0;
                $sumFurikomiKin         = 0;
                $sumFurikomiTesuryoKin  = 0;
                $sumTegataKin           = 0;
                $sumSousaiKin           = 0;
                $sumNebikiKin           = 0;
                $sumSonotaNyuKin        = 0;
                $hikazeiUnchinKin       = 0;
                $hikazeiTyukeiKin       = 0;
                $hikazeiTukouryoKin     = 0;
                $hikazeiNiyakuryoKin    = 0;
                $kazeiUnchinKin         = 0;
                $kazeiTyukeiKin         = 0;
                $kazeiTukouryouKin      = 0;
                $kazeiNiyakuryoKin      = 0;
                $zeiKin                 = 0;
                $ninusiCd               = $item->ninusi_cd;
                $qbSeikyu               = $repo->getListSeikyu($ninusiCd, $seikyuSimeDt);
                $zeiHasuKbn             = (int) $item->zei_hasu_kbn;
                $zeiHasuTani            = (int) $item->zei_hasu_tani;
                $kinHasuKbn             = (int) $item->kin_hasu_kbn;
                $kinHasuTani            = (int) $item->kin_hasu_tani;

                if ($collectionNyukin->has($ninusiCd)) {
                    $nyuKinWhereNinusiCd    = $collectionNyukin->get($ninusiCd);
                    $sumGenkinKin           = $nyuKinWhereNinusiCd->sum__genkin_kin;
                    $sumFurikomiKin         = $nyuKinWhereNinusiCd->sum__furikomi_kin;
                    $sumFurikomiTesuryoKin  = $nyuKinWhereNinusiCd->sum__furikomi_tesuryo_kin;
                    $sumTegataKin           = $nyuKinWhereNinusiCd->sum__tegata_kin;
                    $sumSousaiKin           = $nyuKinWhereNinusiCd->sum__sousai_kin;
                    $sumNebikiKin           = $nyuKinWhereNinusiCd->sum__nebiki_kin;
                    $sumSonotaNyuKin        = $nyuKinWhereNinusiCd->sum__sonota_nyu_kin;
                } 

                if (collect($arrTmpByMenzeiKbn)->has($ninusiCd)) {
                    $dataByMenzeiKbnFromNinusiCd    = collect($arrTmpByMenzeiKbn)->get($ninusiCd);
                    $hikazeiUnchinKin               = data_get($dataByMenzeiKbnFromNinusiCd, 'hikazei_unchin_kin', 0);
                    $hikazeiTyukeiKin               = data_get($dataByMenzeiKbnFromNinusiCd, 'hikazei_tyukei_kin', 0);
                    $hikazeiTukouryoKin             = data_get($dataByMenzeiKbnFromNinusiCd, 'hikazei_tukouryo_kin', 0);
                    $hikazeiNiyakuryoKin            = data_get($dataByMenzeiKbnFromNinusiCd, 'hikazei_niyakuryo_kin', 0);
                    $kazeiUnchinKin                 = data_get($dataByMenzeiKbnFromNinusiCd, 'kazei_unchin_kin', 0);
                    $kazeiTyukeiKin                 = data_get($dataByMenzeiKbnFromNinusiCd, 'kazei_tyukei_kin', 0);
                    $kazeiTukouryouKin              = data_get($dataByMenzeiKbnFromNinusiCd, 'kazei_tukouryou_kin', 0);
                    $kazeiNiyakuryoKin              = data_get($dataByMenzeiKbnFromNinusiCd, 'kazei_niyakuryo_kin', 0);
                    $zeiKin                         = roundFromKbnTani(data_get($dataByMenzeiKbnFromNinusiCd, 'zei_kin', 0), $zeiHasuKbn, $zeiHasuTani) ;
                }

                $konkaiTorihikiKin = $repo->getKonkaiTorihikiKin($ninusiCd, $seikyuSimeDt);
                
                $values = [
                    'seikyu_no'             => Formatter::datetime($seikyuSimeDt, 'Ymd') . sprintf("%03d", $i),
                    'zenkai_seikyu_kin'     => $konkaiTorihikiKin,
                    'genkin_kin'            => $sumGenkinKin,
                    'furikomi_kin'          => $sumFurikomiKin,
                    'furikomi_tesuryo_kin'  => $sumFurikomiTesuryoKin,
                    'tegata_kin'            => $sumTegataKin,
                    'sousai_kin'            => $sumSousaiKin,
                    'nebiki_kin'            => $sumNebikiKin,
                    'sonota_nyu_kin'        => $sumSonotaNyuKin,
                    'kjrikosi_kin'          => $konkaiTorihikiKin - $sumGenkinKin - $sumFurikomiKin - $sumFurikomiTesuryoKin - $sumTegataKin - $sumSousaiKin - $sumNebikiKin - $sumSonotaNyuKin,
                    'kazei_unchin_kin'      => $kazeiUnchinKin,
                    'kazei_tyukei_kin'      => $kazeiTyukeiKin,
                    'kazei_tukouryou_kin'   => $kazeiTukouryouKin,
                    'kazei_niyakuryo_kin'   => $kazeiNiyakuryoKin,
                    'zei_kin'               => $zeiKin,
                    'hikazei_unchin_kin'    => $hikazeiUnchinKin,
                    'hikazei_tyukei_kin'    => $hikazeiTyukeiKin,
                    'hikazei_tukouryo_kin'  => $hikazeiTukouryoKin,
                    'hikazei_niyakuryo_kin' => $hikazeiNiyakuryoKin,
                    'konkai_torihiki_kin'   => roundFromKbnTani(($kazeiUnchinKin + $kazeiTyukeiKin 
                                                                + $kazeiTukouryouKin + $kazeiNiyakuryoKin 
                                                                + $zeiKin + $hikazeiUnchinKin 
                                                                + $hikazeiTyukeiKin + $hikazeiTukouryoKin 
                                                                + $hikazeiNiyakuryoKin),
                                               $kinHasuKbn, $kinHasuTani),
                    'seikyu_hako_flg'       => 0,
                    'upd_user_cd'           => Auth::id(),
                    'upd_dt'                => now(),
                ];
                
                if (!$qbSeikyu->exists()) {
                    //3.1.1
                    $repo->insertTSeikyu([
                        'ninusi_cd'             => $ninusiCd,
                        'seikyu_sime_dt'        => $seikyuSimeDt,
                        'seikyu_kakutei_flg'    => 0,
                        'add_user_cd'           => Auth::id(),
                        'add_dt'                => now(),
                    ] + $values);
                    
                } else {
                    // 3.1.2
                    $seikyu     = $qbSeikyu->first();
                    $seikyuNo   = $seikyu->seikyu_no;
                    $where = [
                        'ninusi_cd'         => $ninusiCd,
                        'seikyu_sime_dt'    => $seikyuSimeDt,
                    ];

                    $valuesUpdate = [
                        'seikyu_no'    => $seikyuNo,
                        'upd_user_cd'  => Auth::id(),
                        'upd_dt'       => now(),
                    ];

                    $repo->emptySeikyuNo('t_uriage', $seikyuNo);

                    $repo->updateTSeikyu($where, $values);

                    // 3.3. Update data t_uriage
                    $repo->updateTUriage($where, $listUriageDenNo, $valuesUpdate);
                }
            }
        } catch (Exception $e) {
            \Log::info(print_r($e->getMessage(), TRUE) );
            return [
                'success' => false,
                'msg' => __('messages.E0012'),
            ];
        }
       
    }

    private function insertTZaikoHokanryo($seikyuSimeDt)
    {
        $repo               = $this->seikyuShimebisijiRepository;
        $seikyuSimeDt       = Formatter::date($seikyuSimeDt);
        $date               = Carbon::createFromFormat(Formatter::DF_DATE, $seikyuSimeDt);
        $isLastDayOfMonth   = $date->isLastOfMonth();
        $dayOfDate          = !$isLastDayOfMonth ? $date->day : 31;
        $arrTmp             = [];
        $dataInsert         = [];
       
        // 1. get data m_ninusi
        $listNinusi = $repo->getListNinusiBySimebi($dayOfDate)->get();
        $ninusiCds  = $listNinusi->pluck('ninusi_cd')->toArray();
       
        if ($listNinusi->count() == 0) {
            \Log::info('listNinusi is empty.');
            return [
                'success' => false,
                'msg' => __('messages.E0012'),
            ];
        }
       
        foreach ($listNinusi as $item) {
            $ninusiCd       = data_get($item, 'ninusi_cd');
            $kiseiKbn       = data_get($item, 'kisei_kbn');
            $hokanTanka     = data_get($item, 'hokan_tanka');
            $nyukoTanka     = data_get($item, 'nyuko_tanka');
            $syukoTanka     = data_get($item, 'syuko_tanka');
           
            if (($item->sekisu_kbn != 0 || $item->sekisu_kbn == null) 
              && ($item->kisei_kbn != 0 || $item->kisei_kbn == null)
            ) {
                // 2. get data multiple kix_from, kix_to
                if (data_get($item, 'ki1_from') < $dayOfDate) {
                    $dtKi1From          = $date->copy()->setDay(data_get($item, 'ki1_from'))->toDateString();
                    $dtKi1To            = $date->copy()->setDay(data_get($item, 'ki1_to'))->toDateString();
                } elseif (data_get($item, 'ki1_from') > $dayOfDate && data_get($item, 'kisei_kbn') == 1) {
                    $dtKi1From          = $this->getLastDatePreMonth($date, data_get($item, 'ki1_from'))->toDateString();
                    $dtKi1To            = $date->copy()->setDay(data_get($item, 'ki1_to'))->toDateString();
                } elseif (data_get($item, 'ki1_from') > $dayOfDate && data_get($item, 'kisei_kbn') > 1) {
                    $dtKi1From          = $this->getLastDatePreMonth($date, data_get($item, 'ki1_from'))->toDateString();
                    $dtKi1To            = $this->getLastDatePreMonth($date, data_get($item, 'ki1_to'))->toDateString();
                }

                $dtKi2From       = $date->copy()->setDay(data_get($item, 'ki2_from'))->toDateString();
                $dtKi2To         = $date->copy()->setDay(data_get($item, 'ki2_to'))->toDateString();
                $dtKi3From       = $date->copy()->setDay(data_get($item, 'ki3_from'))->toDateString();
                $dtKi3To         = $date->copy()->setDay(data_get($item, 'ki3_to'))->toDateString();

                $listSumSuEachKi = $repo->getSumSuEachKi($ninusiCd, $dtKi1From, $dtKi1To, $dtKi2From, $dtKi2To, $dtKi3From, $dtKi3To)
                                ->get();
                          
                $grouped = $listSumSuEachKi->groupBy(function (&$item, $key) {
                    $colsGroupBy = [
                        $item->bumon_cd,
                        $item->hinmei_cd,
                        $item->ninusi_cd,
                    ];
                    
                    return implode('__', $colsGroupBy);
                });

                
                foreach ($grouped as $k => $item2) {
                    $arrTmp[$k]['bumon_cd']                 = data_get($item2, '0.bumon_cd');
                    $arrTmp[$k]['hinmei_cd']                = data_get($item2, '0.hinmei_cd');
                    $arrTmp[$k]['ninusi_cd']                = data_get($item2, '0.ninusi_cd');
                    $arrTmp[$k]['sum_su_ki1__kbn_1']        = data_get($item2, '0.sum_su_ki1__kbn_1') ?? 0;
                    $arrTmp[$k]['sum_su_ki1__kbn_2']        = data_get($item2, '0.sum_su_ki1__kbn_2') ?? 0;
                    $arrTmp[$k]['sum_su_ki2__kbn_1']        = data_get($item2, '0.sum_su_ki2__kbn_1') ?? 0;
                    $arrTmp[$k]['sum_su_ki2__kbn_2']        = data_get($item2, '0.sum_su_ki2__kbn_2') ?? 0;
                    $arrTmp[$k]['sum_su_ki3__kbn_1']        = data_get($item2, '0.sum_su_ki3__kbn_1') ?? 0;
                    $arrTmp[$k]['sum_su_ki3__kbn_2']        = data_get($item2, '0.sum_su_ki3__kbn_2') ?? 0;
                    $arrTmp[$k]['kisei_kbn']                = $kiseiKbn;
                    $arrTmp[$k]['hokan_tanka']              = $hokanTanka;
                    $arrTmp[$k]['nyuko_tanka']              = $nyukoTanka;
                    $arrTmp[$k]['syuko_tanka']              = $syukoTanka;
                }

                // 3. get data t_zaiko_kijyun
                $dateOfPreMonth         = $this->getPreMonth($date);   
                $listSumZaikoAllSu      = $repo->getSumZaikoAllSu($ninusiCd, $dateOfPreMonth)->get();

                $grouped2 = $listSumZaikoAllSu->groupBy(function ($item, $key) {
                    $colsGroupBy = [
                        $item->bumon_cd,
                        $item->hinmei_cd,
                        $item->ninusi_cd,
                    ];
                    
                    return implode('__', $colsGroupBy);
                });
               
                foreach ($grouped2 as $k2 => $item2) {
                    if (isset($arrTmp[$k2])) {
                        $arrTmp[$k2]['kijyun_dt']           = data_get($item2, '0.kijyun_dt');
                        $arrTmp[$k2]['sum__zaiko_all_su']   = data_get($item2, '0.sum__zaiko_all_su');
                    }
                }
            }
        }

        foreach ($arrTmp as $k => $item) {
            $kiseiKbn       = data_get($item, 'kisei_kbn');
            
            $sumSuKi1Kbn1   = data_get($item, 'sum_su_ki1__kbn_1', 0) ?? 0;
            $sumSuKi1Kbn2   = data_get($item, 'sum_su_ki1__kbn_2', 0) ?? 0;
            $sumSuKi2Kbn1   = data_get($item, 'sum_su_ki2__kbn_1', 0) ?? 0;
            $sumSuKi2Kbn2   = data_get($item, 'sum_su_ki2__kbn_2', 0) ?? 0;
            $sumSuKi3Kbn1   = data_get($item, 'sum_su_ki3__kbn_1', 0) ?? 0;
            $sumSuKi3Kbn2   = data_get($item, 'sum_su_ki3__kbn_2', 0) ?? 0;
            $hokanTanka     = data_get($item, 'hokan_tanka', 0) ?? 0;
            $nyukoTanka     = data_get($item, 'nyuko_tanka', 0) ?? 0;
            $syukoTanka     = data_get($item, 'syuko_tanka', 0) ?? 0;

            $ki1KurikosiSu  = data_get($item, 'sum__zaiko_all_su', 0) ?? 0;
            $ki2KurikosiSu  = $ki1KurikosiSu + $sumSuKi1Kbn1 - $sumSuKi1Kbn2;
            $ki3KurikosiSu  = $ki2KurikosiSu + $sumSuKi2Kbn1 - $sumSuKi2Kbn2;
            $touzanSu       = 0;

            switch ($kiseiKbn) {
                case 1:
                    $ki2KurikosiSu  = 0;
                    $ki3KurikosiSu  = 0;
                    $sumSuKi2Kbn1   = 0;
                    $sumSuKi3Kbn1   = 0;
                    $sumSuKi2Kbn2   = 0;
                    $sumSuKi3Kbn2   = 0;
                    $touzanSu       = $ki1KurikosiSu + $sumSuKi1Kbn1 - $sumSuKi1Kbn2;
                    break;
                case 2:
                    $ki3KurikosiSu  = 0;
                    $sumSuKi3Kbn1   = 0;
                    $sumSuKi3Kbn2   = 0;
                    $touzanSu       = $ki2KurikosiSu + $sumSuKi2Kbn1 - $sumSuKi2Kbn2;
                    break;
                case 3:
                    $touzanSu       = $ki3KurikosiSu + $sumSuKi3Kbn1 - $sumSuKi3Kbn2;
                    break;
            }
            
            $sekiSu         = $ki1KurikosiSu + $ki2KurikosiSu + $ki3KurikosiSu;

            $hokanKin      = $sekiSu * $hokanTanka;
            $nyukoSu       = $sumSuKi1Kbn1 + $sumSuKi2Kbn1 + $sumSuKi3Kbn1;
            $nyukoKin      = $nyukoSu * $nyukoTanka;
            $syukoSu       = $sumSuKi1Kbn2 + $sumSuKi2Kbn2 + $sumSuKi3Kbn2;
            $syukoKin      = $syukoSu * $syukoTanka;

            data_set($dataInsert[$k], 'bumon_cd', data_get($item, 'bumon_cd'));
            data_set($dataInsert[$k], 'hinmei_cd', data_get($item, 'hinmei_cd'));
            data_set($dataInsert[$k], 'ninusi_cd', data_get($item, 'ninusi_cd'));
            data_set($dataInsert[$k], 'seikyu_sime_dt', $seikyuSimeDt);
            data_set($dataInsert[$k], 'seikyu_no', $repo->getListSeikyu(data_get($item, 'ninusi_cd'), $seikyuSimeDt)->value('seikyu_no'));

            data_set($dataInsert[$k], 'ki1_kurikosi_su', $ki1KurikosiSu);
            data_set($dataInsert[$k], 'ki1_nyuko_su', $sumSuKi1Kbn1);
            data_set($dataInsert[$k], 'ki1_syuko_su', $sumSuKi1Kbn2);

            data_set($dataInsert[$k], 'ki2_kurikosi_su', $ki2KurikosiSu);
            data_set($dataInsert[$k], 'ki2_nyuko_su', $sumSuKi2Kbn1);
            data_set($dataInsert[$k], 'ki2_syuko_su', $sumSuKi2Kbn2);

            data_set($dataInsert[$k], 'ki3_kurikosi_su', $ki3KurikosiSu);
            data_set($dataInsert[$k], 'ki3_nyuko_su', $sumSuKi3Kbn1);
            data_set($dataInsert[$k], 'ki3_syuko_su', $sumSuKi3Kbn2);
          
            data_set($dataInsert[$k], 'touzan_su', $touzanSu);

            data_set($dataInsert[$k], 'seki_su', $sekiSu);
            data_set($dataInsert[$k], 'tanka', $hokanTanka);
            data_set($dataInsert[$k], 'hokan_kin', $hokanKin);
            data_set($dataInsert[$k], 'nyuko_su', $nyukoSu);
            data_set($dataInsert[$k], 'nyuko_tanka', $nyukoTanka);
            data_set($dataInsert[$k], 'nyuko_kin', $nyukoKin);
            data_set($dataInsert[$k], 'syuko_su', $syukoSu);
            data_set($dataInsert[$k], 'syuko_tanka', $syukoTanka);
            data_set($dataInsert[$k], 'syuko_kin', $syukoKin);
            data_set($dataInsert[$k], 'total_kin', $hokanKin + $nyukoKin + $syukoKin);
            data_set($dataInsert[$k], 'add_user_cd', Auth::id());
            data_set($dataInsert[$k], 'add_dt', Carbon::now());
            data_set($dataInsert[$k], 'upd_user_cd', Auth::id());
            data_set($dataInsert[$k], 'upd_dt', Carbon::now());

            switch ($kiseiKbn) {
                case 1:
                    data_set($dataInsert[$k], 'ki2_kurikosi_su', null);
                    data_set($dataInsert[$k], 'ki2_nyuko_su', null);
                    data_set($dataInsert[$k], 'ki2_syuko_su', null);
                    data_set($dataInsert[$k], 'ki3_kurikosi_su', null);
                    data_set($dataInsert[$k], 'ki3_nyuko_su', null);
                    data_set($dataInsert[$k], 'ki3_syuko_su', null);
                    break;
                case 2:
                    data_set($dataInsert[$k], 'ki3_kurikosi_su', null);
                    data_set($dataInsert[$k], 'ki3_nyuko_su', null);
                    data_set($dataInsert[$k], 'ki3_syuko_su', null);
                    break;
            }
        }

        $bumonCds       = collect($dataInsert)->pluck('bumon_cd');
        $ninusiCds      = collect($dataInsert)->pluck('ninusi_cd');
        $seikyuSimeDts  = collect($dataInsert)->pluck('seikyu_sime_dt');
       
        DB::table('t_zaiko_hokanryo')
                ->whereIn('bumon_cd', $bumonCds)
                ->whereIn('ninusi_cd', $ninusiCds)
                ->whereIn('seikyu_sime_dt', $seikyuSimeDts)
                ->delete();
        
        DB::table('t_zaiko_hokanryo')->insert($dataInsert);

        // 5. insert t_zaiko_kijyun
        $this->insertTZaikoKijyun($ninusiCds, $seikyuSimeDt);
    }

    public function insertTZaikoKijyun($ninusiCds, $seikyuSimeDt)
    {
        $repo                       = $this->seikyuShimebisijiRepository;
        $dataInsert                 = [];
        $listSumSuInsZaikoKijyun    = $repo->getSumSuInsZaikoKijyun($ninusiCds, $seikyuSimeDt)->get();

        foreach ($listSumSuInsZaikoKijyun as $i => $item) {
            data_set($dataInsert[$i], 'kijyun_dt', $seikyuSimeDt);
            data_set($dataInsert[$i], 'bumon_cd', data_get($item, 'bumon_cd'));
            data_set($dataInsert[$i], 'ninusi_cd', data_get($item, 'ninusi_cd'));
            data_set($dataInsert[$i], 'hinmei_cd', data_get($item, 'hinmei_cd'));
            data_set($dataInsert[$i], 'location', data_get($item, 'location') ?? '');
            data_set($dataInsert[$i], 'case_su', data_get($item, 'case_su') ?? 0);
            data_set($dataInsert[$i], 'hasu_su', data_get($item, 'hasu_su') ?? 0);
            data_set($dataInsert[$i], 'zaiko_all_su', data_get($item, 'sum_su__kbn_1', 0) - data_get($item, 'sum_su__kbn_2', 0) 
                                                    + data_get($item, 'sum_su__kbn_4', 0) + data_get($item, 'sum_su__kbn_5', 0));

            data_set($dataInsert[$i], 'add_user_cd', Auth::id());
            data_set($dataInsert[$i], 'add_dt', Carbon::now());
            data_set($dataInsert[$i], 'upd_user_cd', Auth::id());
            data_set($dataInsert[$i], 'upd_dt', Carbon::now());
        }

        $kijyunDts      = collect($dataInsert)->pluck('kijyun_dt');
        $ninusiCds      = collect($dataInsert)->pluck('ninusi_cd');
        $hinmeiCds      = collect($dataInsert)->pluck('hinmei_cd');

        DB::table('t_zaiko_kijyun')
                ->whereIn('kijyun_dt', $kijyunDts)
                ->whereIn('ninusi_cd', $ninusiCds)
                ->whereIn('hinmei_cd', $hinmeiCds)
                ->delete();

        DB::table('t_zaiko_kijyun')->insert($dataInsert);
    }

    public function handleMUserPg(Request $request)
    {
        $where = $this->getConfWhereUserPgFunction();

        $qb = DB::table('m_user_pg_function')->where($where);

        if (!$qb->exists()) { 
            // insert
            $where['choice1_dt'] = $request->seikyu_sime_dt;

            DB::table('m_user_pg_function')->insert($where);
          
        } else {
            DB::table('m_user_pg_function')
                ->where($where)
                ->update(['choice1_dt' => $request->seikyu_sime_dt]);
        }
    }

    public function createCollect($array, $key)
    {
        $collect = collect($array)->map(function ($values, $date) use($key){
            if (collect($values)->every(function ($value) {
                return $value == 1;
            })) {
                return data_get($this->getText(), $key . '.all_1');
            } elseif (collect($values)->contains(1) && collect($values)->contains(0)) {
                return data_get($this->getText(), $key . '.only_0_1');
            } else {
                return null;
            }
        });


        return $collect;
    }

    public function getText()
    {
        return [
            'seikyu_hako_flg' => [
                'all_1'     => '全発行済',
                'only_0_1'  => '発行中',
            ],
            'seikyu_kakutei_flg' => [
                'all_1'     => config('params.SEIKYU_KAKUTEI_FLG_ALL_1'),
                'only_0_1'  => '確定中',
            ],
        ];
    }

    private function getConfWhereUserPgFunction()
    {
        return [
            'user_cd'   => Auth::id(),
            'pg_nm'     => 'seikyu-shimebi',
            'function'  => 'seikyu_shimebi_search',
        ];
    }

    private function caclByMenzeiKbn($grouped) 
    {
        $arrTmpByMenzeiKbn = [];
        $arrTmpZeiKeisanKbn = [];

        foreach ($grouped as $ninusiCd => $items) {
            foreach ($items as $item) {
                
                $zeiHasuKbn = (int) $item->zei_hasu_kbn;
                $zeiHasuTani = (int) $item->zei_hasu_tani;

                switch (data_get($item, 'menzei_kbn')) {
                    case 0:
                        //kazei_unchin_kin
                        if (isset($arrTmpByMenzeiKbn[$ninusiCd]['kazei_unchin_kin'])) {
                            $sumKazeiUnchinKin = data_get($item, 'unchin_kin', 0) + data_get($item, 'syuka_kin', 0) + data_get($item, 'tesuryo_kin', 0) + $arrTmpByMenzeiKbn[$ninusiCd]['kazei_unchin_kin'];
                        } else {
                            $sumKazeiUnchinKin = data_get($item, 'unchin_kin', 0) + data_get($item, 'syuka_kin', 0) + data_get($item, 'tesuryo_kin', 0);
                        }
                        data_set($arrTmpByMenzeiKbn[$ninusiCd], 'kazei_unchin_kin', $sumKazeiUnchinKin);
                        

                        // kazei_tyukei_kin
                        if (isset($arrTmpByMenzeiKbn[$ninusiCd]['kazei_tyukei_kin'])) {
                            $sumKazeiTyukeiKin = data_get($item, 'tyukei_kin', 0) + $arrTmpByMenzeiKbn[$ninusiCd]['kazei_tyukei_kin'];
                        } else {
                            $sumKazeiTyukeiKin = data_get($item, 'tyukei_kin', 0);
                        }
                        data_set($arrTmpByMenzeiKbn[$ninusiCd], 'kazei_tyukei_kin', $sumKazeiTyukeiKin);

                        //kazei_tukouryou_kin
                        if (isset($arrTmpByMenzeiKbn[$ninusiCd]['kazei_tukouryou_kin'])) {
                            $sumKazeiTukouryoKin = data_get($item, 'tukoryo_kin', 0) + $arrTmpByMenzeiKbn[$ninusiCd]['kazei_tukouryou_kin'];
                        } else {
                            $sumKazeiTukouryoKin = data_get($item, 'tukoryo_kin', 0);
                        }
                        data_set($arrTmpByMenzeiKbn[$ninusiCd], 'kazei_tukouryou_kin', $sumKazeiTukouryoKin);

                        //kazei_niyakuryo_kin
                        if (isset($arrTmpByMenzeiKbn[$ninusiCd]['kazei_niyakuryo_kin'])) {
                            $sumKazeiNiyakuryoKin = data_get($item, 'nieki_kin', 0) + $arrTmpByMenzeiKbn[$ninusiCd]['kazei_niyakuryo_kin'];
                        } else {
                            $sumKazeiNiyakuryoKin = data_get($item, 'nieki_kin', 0);
                        }
                        data_set($arrTmpByMenzeiKbn[$ninusiCd], 'kazei_niyakuryo_kin', $sumKazeiNiyakuryoKin);

                        //zei_kin
                        switch (data_get($item, 'zei_keisan_kbn')) {
                            case 1:
                                //kazei_unchin_kin
                                $taxKazeiUnchinKin      = $sumKazeiUnchinKin - $sumKazeiUnchinKin / (1 + config('params.TAX_RATE'));
                                $roundTaxKazeiUnchinKin = roundFromKbnTani($taxKazeiUnchinKin, $zeiHasuKbn, $zeiHasuTani);
                                $sumKazeiUnchinKin      = $sumKazeiUnchinKin - $roundTaxKazeiUnchinKin;
                                data_set($arrTmpZeiKeisanKbn[$ninusiCd], 'kazei_unchin_kin', $sumKazeiUnchinKin);

                                // kezi_tyukei_kin
                                $taxKazeiTyukeiKin      = $sumKazeiTyukeiKin - $sumKazeiTyukeiKin / (1 + config('params.TAX_RATE'));
                                $roundTaxKazeiTyukeiKin = roundFromKbnTani($taxKazeiTyukeiKin, $zeiHasuKbn, $zeiHasuTani);
                                $sumKazeiTyukeiKin      = $sumKazeiTyukeiKin - $roundTaxKazeiTyukeiKin;
                                data_set($arrTmpZeiKeisanKbn[$ninusiCd], 'kazei_tyukei_kin', $sumKazeiTyukeiKin);

                                // kazei_niyakuryo_kin
                                $taxKazeiNiyakuryoKin      = $sumKazeiNiyakuryoKin - $sumKazeiNiyakuryoKin / (1 + config('params.TAX_RATE'));
                                $roundTaxKazeiNiyakuryoKin = roundFromKbnTani($taxKazeiNiyakuryoKin, $zeiHasuKbn, $zeiHasuTani);
                                $sumKazeiNiyakuryoKin      = $sumKazeiNiyakuryoKin - $roundTaxKazeiNiyakuryoKin;
                                data_set($arrTmpZeiKeisanKbn[$ninusiCd], 'kazei_niyakuryo_kin', $sumKazeiNiyakuryoKin);

                                // zei_kin
                                $zeiKin = $roundTaxKazeiUnchinKin + $roundTaxKazeiTyukeiKin + $roundTaxKazeiNiyakuryoKin;
                                data_set($arrTmpZeiKeisanKbn[$ninusiCd], 'zei_kin', $zeiKin);

                                break;
                            case 2:
                                $zeiKin = ($sumKazeiUnchinKin + $sumKazeiTyukeiKin + $sumKazeiNiyakuryoKin) * config('params.TAX_RATE');
                                data_set($arrTmpByMenzeiKbn[$ninusiCd], 'zei_kin', $zeiKin);
                                break;
                            case 3:
                                if (isset($arrTmpByMenzeiKbn[$ninusiCd]['zei_kin'])) {
                                    $zeiKin = data_get($item, 'seikyu_kin_tax', 0) + $arrTmpByMenzeiKbn[$ninusiCd]['zei_kin'];
                                } else {
                                    $zeiKin = data_get($item, 'seikyu_kin_tax', 0);
                                }
                                data_set($arrTmpByMenzeiKbn[$ninusiCd], 'zei_kin', $zeiKin);
                                break;
                            
                            default:
                                $zeiKin = 0;
                                data_set($arrTmpByMenzeiKbn[$ninusiCd], 'zei_kin', $zeiKin);
                                break;
                        }
                        
                        break;
                    case 1:
                        //hikazei_unchin_kin
                        if (isset($arrTmpByMenzeiKbn[$ninusiCd]['hikazei_unchin_kin'])) {
                            $sumHikazeiUnchinKin = data_get($item, 'unchin_kin', 0) + data_get($item, 'syuka_kin', 0) + data_get($item, 'tesuryo_kin', 0) + $arrTmpByMenzeiKbn[$ninusiCd]['hikazei_unchin_kin'];
                        } else {
                            $sumHikazeiUnchinKin = data_get($item, 'unchin_kin', 0) + data_get($item, 'syuka_kin', 0) + data_get($item, 'tesuryo_kin', 0);
                        }
                        data_set($arrTmpByMenzeiKbn[$ninusiCd], 'hikazei_unchin_kin', $sumHikazeiUnchinKin);

                        //hikazei_tyukei_kin
                        if (isset($arrTmpByMenzeiKbn[$ninusiCd]['hikazei_tyukei_kin'])) {
                            $sumHikaZeiTyukeiKin = data_get($item, 'tyukei_kin', 0) + $arrTmpByMenzeiKbn[$ninusiCd]['hikazei_tyukei_kin'];
                        } else {
                            $sumHikaZeiTyukeiKin = data_get($item, 'tyukei_kin', 0);
                        }
                        data_set($arrTmpByMenzeiKbn[$ninusiCd], 'hikazei_tyukei_kin', $sumHikaZeiTyukeiKin);

                        //hikazei_tukouryo_kin
                        if (isset($arrTmpByMenzeiKbn[$ninusiCd]['hikazei_tukouryo_kin'])) {
                            $sumHikazeiTukouryoKin = data_get($item, 'tukoryo_kin', 0) + $arrTmpByMenzeiKbn[$ninusiCd]['hikazei_tukouryo_kin'];
                        } else {
                            $sumHikazeiTukouryoKin = data_get($item, 'tukoryo_kin', 0);
                        }
                        data_set($arrTmpByMenzeiKbn[$ninusiCd], 'hikazei_tukouryo_kin', $sumHikazeiTukouryoKin);

                        //hikazei_niyakuryo_kin
                        if (isset($arrTmpByMenzeiKbn[$ninusiCd]['hikazei_niyakuryo_kin'])) {
                            $sumHikazeiNiyakuryoKin = data_get($item, 'nieki_kin', 0) + $arrTmpByMenzeiKbn[$ninusiCd]['hikazei_niyakuryo_kin'];
                        } else {
                            $sumHikazeiNiyakuryoKin = data_get($item, 'nieki_kin', 0);
                        }
                        data_set($arrTmpByMenzeiKbn[$ninusiCd], 'hikazei_niyakuryo_kin', $sumHikazeiNiyakuryoKin);
                        break;
                }
            }
        }


        // case zei_keisan_kbn == 1
        if (!empty($arrTmpZeiKeisanKbn)) {
            foreach ($arrTmpZeiKeisanKbn as $ninusiCd => $item) {
                if (isset($arrTmpByMenzeiKbn[$ninusiCd])) {
                    data_set($arrTmpByMenzeiKbn[$ninusiCd], 'kazei_unchin_kin', data_get($item, 'kazei_unchin_kin', 0));
                    data_set($arrTmpByMenzeiKbn[$ninusiCd], 'kazei_tyukei_kin', data_get($item, 'kazei_tyukei_kin', 0));
                    data_set($arrTmpByMenzeiKbn[$ninusiCd], 'kazei_niyakuryo_kin', data_get($item, 'kazei_niyakuryo_kin', 0));
                    data_set($arrTmpByMenzeiKbn[$ninusiCd], 'zei_kin', data_get($item, 'zei_kin', 0));
                }
            }
        }

        return $arrTmpByMenzeiKbn;
    }

    public function getLastDatePreMonth($objDate, $day)
    {
        $objDateCopy        = $objDate->copy()->setDay($day);
        $preMonth           = $objDateCopy->copy()->startOfMonth()->subMonth();
        $dayLastOfPreMonth  = $preMonth->lastOfMonth();
        $dayNeeding         = $day > $dayLastOfPreMonth->day ? $dayLastOfPreMonth->day : $day;

        $result             = $preMonth->setDay($dayNeeding);

        return $result;
    }

    public function getPreMonth($objDate)
    {
        $currentDay         = $objDate->day;
       
        $preMonth           = $objDate->copy()->startOfMonth()->subMonth();
        $dayLastOfPreMonth  = $preMonth->lastOfMonth();
        $dayNeeding         = $currentDay > $dayLastOfPreMonth->day ? $dayLastOfPreMonth->day : $currentDay;

        $result             = $preMonth->setDay($dayNeeding);
       
        return $result;
    }
}