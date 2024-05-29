<?php

namespace App\Http\Controllers\Nyusyuko\Nyuryoku;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Repositories\NyusyukoNyuryokuRepository;
use App\Http\Requests\Nyusyuko\Nyuryoku\NyusyukoMeisaiRequest;
use App\Http\Requests\Nyusyuko\Nyuryoku\NyusyukoInputsRequest;
use App\Http\Requests\Nyusyuko\Nyuryoku\FormNyuryokuSearchRequest;
use App\Http\Requests\Nyusyuko\Nyuryoku\FormSearchNyusyukoHeadRequest;
use Illuminate\Http\Response;
use App\Helpers\Excel\XlsNyusyukoNyuryokuIn;
use App\Helpers\Excel\XlsNyusyukoNyuryokuOut;
use Illuminate\Support\Facades\File;
use DB;

class NyuryokuController extends Controller
{
    //
    const NYUSYUKO_KBN_SUPPORT = [
        '1' => '入庫',
        '2' => '出庫',
        '4' => '棚卸',
        '5' => '在庫移動'
    ];
    protected $nyuryokuRepository;

    public function __construct(
        NyusyukoNyuryokuRepository $nyuryokuRepository
    ) {
        $this->nyuryokuRepository = $nyuryokuRepository;
    }

    public function index() 
    {   
        $listKbn = self::NYUSYUKO_KBN_SUPPORT;
        $setting = require(app_path('Helpers/Grid/config/nyusyuko/nyuryoku/nyuryoku.php'));
        $dataInit = array_column($setting, 'field');
        $unchinMikakuteiKbn = DB::table('m_meisyo')->where('meisyo_kbn', configParam('MEISYO_KBN_UNCHINKAKUTEI'))->get();
        return view('nyusyuko.nyuryoku.index', compact('setting', 'unchinMikakuteiKbn', 'dataInit', 'listKbn'));
    }

    public function destroy($id)
    {
        $result = $this->nyuryokuRepository->delete($id);
    }

    public function dataList(Request $request) 
    {
        $page = $request->page ?? 1;
        $perPage = config('params.PAGE_SIZE');        
        if($request->filled('hed_nyusyuko_den_no')) {
            $qb = DB::table('t_nyusyuko_head')
                ->select('t_nyusyuko_head.*', 'm_ninusi.ninusi_ryaku_nm', DB::raw('t_nyusyuko_head.bumon_cd as hed_bumon_cd'), DB::raw('m_bumon.bumon_nm as hed_bumon_nm'), 'm_ninusi.seikyu_kbn', 'm_ninusi.kin_hasu_tani', 'm_ninusi.kin_hasu_kbn', 'm_ninusi.lot_kanri_kbn')
                ->leftJoin('m_ninusi', 'm_ninusi.ninusi_cd', 't_nyusyuko_head.ninusi_cd')
                ->leftJoin('m_bumon', 'm_bumon.bumon_cd', 't_nyusyuko_head.bumon_cd')
                ->leftJoin('m_hachaku', 'm_hachaku.hachaku_cd', 't_nyusyuko_head.hachaku_cd')
                ->where('nyusyuko_den_no', $request->hed_nyusyuko_den_no);
            if($request->filled('hed_bumon_cd'))
            {
                $qb->where('t_nyusyuko_head.bumon_cd', '=', $request->hed_bumon_cd);
            }

            if($request->filled('hed_nyusyuko_kbn')) {
                $qb->where('nyusyuko_kbn', '=', $request->hed_nyusyuko_kbn);
            }

            $data['head'] = $qb->first();
            if(!empty($data['head'])) {
                $listData = $this->nyuryokuRepository->getListWithTotalCount($request, $data['head']);
                $data['total'] = $listData['total'];
                $data['rows'] = $listData['rows']->get();
            }
        } else {
            $data['head'] = null;
        }

        if(empty($data['head'])) {
            return [
                'total' => 0,
                'rows' => [],
                'head' => [],
                'uriage' => []
            ];
        }

        $data['uriage'] = DB::table('t_uriage')
                        ->select(
                            't_uriage.*',
                            'm_ninusi.kin_hasu_kbn',
                            'm_ninusi.kin_hasu_tani',
                            'm_jyomuin.jyomuin_nm',
                            DB::raw('m_yousya.yousya_ryaku_nm as yousya_nm')
                        )
                        ->leftJoin('m_ninusi', 'm_ninusi.ninusi_cd', 't_uriage.ninusi_cd')
                        ->leftJoin('m_yousya', 'm_yousya.yousya_cd', 't_uriage.yousya_cd')
                        ->leftJoin('m_jyomuin', 'm_jyomuin.jyomuin_cd', 't_uriage.jyomuin_cd')
                        ->where('uriage_den_no', $data['head']->uriage_den_no)
                        ->first();

        return response()->json($data);
    }

    public function getNyusyukoHead(Request $request) {
        $data = [];
        if($request->filled('hed_nyusyuko_den_no')) {
            $qb = DB::table('t_nyusyuko_head')
                ->select('t_nyusyuko_head.*', 'm_ninusi.ninusi_ryaku_nm', DB::raw('t_nyusyuko_head.bumon_cd as hed_bumon_cd'), DB::raw('m_bumon.bumon_nm as hed_bumon_nm'), 'm_ninusi.seikyu_kbn', 'm_ninusi.kin_hasu_tani', 'm_ninusi.kin_hasu_kbn', 'm_ninusi.lot_kanri_kbn', 'm_ninusi.lot1_nm', 'm_ninusi.lot2_nm', 'm_ninusi.lot3_nm')
                ->leftJoin('m_ninusi', 'm_ninusi.ninusi_cd', 't_nyusyuko_head.ninusi_cd')
                ->leftJoin('m_bumon', 'm_bumon.bumon_cd', 't_nyusyuko_head.bumon_cd')
                ->leftJoin('m_hachaku', 'm_hachaku.hachaku_cd', 't_nyusyuko_head.hachaku_cd')
                ->where('nyusyuko_den_no', $request->hed_nyusyuko_den_no);
            if($request->filled('hed_bumon_cd'))
            {
                $qb->where('t_nyusyuko_head.bumon_cd', '=', $request->hed_bumon_cd);
            }

            if($request->filled('hed_nyusyuko_kbn')) {
                $qb->where('nyusyuko_kbn', '=', $request->hed_nyusyuko_kbn);
            }

            $data['head'] = $qb->first();
        } else {
            $data['head'] = null;
        }
        if(!empty($data['head'])) {
            $data['setting'] = require(app_path('Helpers/Grid/config/nyusyuko/nyuryoku/nyuryoku.php'));
            if($data['head']->nyusyuko_kbn == 4) {
                $key = null;
                foreach ($data['setting'] as $index => $item) {
                    if (isset($item['field']) && $item['field'] === 'su') {
                        $key = $index;
                        break;
                    }
                }
                $data['setting'][$key]['title'] = '実棚差数';
            }
            if($data['head']->nyusyuko_kbn == 5) {
                $data['setting'] = require(app_path('Helpers/Grid/config/nyusyuko/nyuryoku/nyuryoku_nyusyuko_kbn_5.php'));
            }
        }
        return response()->json($data);
    }

    public function validateFormSearchNyusyukiHead(FormNyuryokuSearchRequest $request) 
    {   
        $setting = require(app_path('Helpers/Grid/config/nyusyuko/nyuryoku/nyuryoku.php'));
        if($request->hed_nyusyuko_kbn == 4) {
            $key = null;
            foreach ($setting as $index => $item) {
                if (isset($item['field']) && $item['field'] === 'su') {
                    $key = $index;
                    break;
                }
            }
            $setting[$key]['title'] = '実棚差数';
        }
        if($request->hed_nyusyuko_kbn == 5) {
            $setting = require(app_path('Helpers/Grid/config/nyusyuko/nyuryoku/nyuryoku_nyusyuko_kbn_5.php'));
        }
        return response()->json([
            'status' => 200,
            'data' => $request->all(),
            'setting' => $setting
        ]);
    }

    public function validateRowNyusyukoMeisai(NyusyukoMeisaiRequest $request) 
    {
        return response()->json([
            'status' => 200,
            'data' => $request->all()
        ]);
    }


    public function indexNyusyukoHead(Request $request) 
    {
        $setting = require(app_path('Helpers/Grid/config/nyusyuko/nyuryoku/nyuryoku-nyusyuko-head.php'));
        return view('nyusyuko.nyuryoku.nyusyukohead.index', compact('setting'));
    }


    public function dataListNyusyukoHead(Request $request) 
    {
        $page = $request->page ?? 1;
        $perPage = config('params.PAGE_SIZE');
        // $keyInitSearch = $this->__getKeyInitSearch();
        $listData = $this->nyuryokuRepository->getListWithTotalCountHead($request);
        
        $data['total'] = $listData['total'];
        $data['rows'] = $listData['rows']
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();
        return response()->json($data);
    }

    public function updateDataTable(NyusyukoInputsRequest $request) {
        $result = $this->nyuryokuRepository->updateData($request);
        return response()->json($result);
    }


    public function suggestionMultiple(Request $request) {
        $field = $request->field;
        $value = makeEscapeStr($request->value).'%';
        $data = [];
        
        switch ($field) {
            case 'hinmei_cd':
                $qb = DB::table('m_soko_hinmei')
                    ->select('m_soko_hinmei.*', DB::raw('m_meisyo.meisyo_nm as tani_nm'), DB::raw('m_soko_hinmei.bara_tani as tani_cd'))
                    ->leftJoin('m_bumon', 'm_bumon.bumon_cd', 'm_soko_hinmei.bumon_cd')
                    ->leftJoin('m_meisyo', function($join) {
                        $join->on('m_meisyo.meisyo_cd', '=', 'm_soko_hinmei.bara_tani');
                        $join->where('m_meisyo.meisyo_kbn', '=', configParam('MEISYO_KBN_TANI'));
                    });
                $qb->where($field, 'ilike', $value);
                $qb->orderBy('ninusi_cd');
                break;
            case 'hinmei_nm': 
                $qb = DB::table('m_soko_hinmei')
                    ->select('m_soko_hinmei.*', DB::raw('m_meisyo.meisyo_nm as tani_nm'), DB::raw('m_soko_hinmei.bara_tani as tani_cd'))
                    ->leftJoin('m_bumon', 'm_bumon.bumon_cd', 'm_soko_hinmei.bumon_cd')
                    ->leftJoin('m_meisyo', function($join) {
                        $join->on('m_meisyo.meisyo_cd', '=', 'm_soko_hinmei.bara_tani');
                        $join->where('m_meisyo.meisyo_kbn', '=', configParam('MEISYO_KBN_TANI'));
                    });
                $qb->where(function($query) use ($value) {
                    $query->where('m_soko_hinmei.hinmei_nm', 'ilike', $value);
                    $query->orWhere('m_soko_hinmei.kana', 'ilike', $value);
                });
                break;
            case 'soko_cd':
            case 'soko_cd_to':
                $qb = DB::table('m_soko')->select('m_soko.*', DB::raw('m_soko.soko_cd as soko_cd_to'), DB::raw('m_soko.soko_nm as soko_nm_to'))->where('soko_cd', 'ilike', $value)->orderBy('soko_cd');
                break;
            case 'soko_nm':
            case 'soko_cd_nm':
                $qb = DB::table('m_soko')->select('m_soko.*', DB::raw('m_soko.soko_cd as soko_cd_to'), DB::raw('m_soko.soko_nm as soko_nm_to'))->where('soko_nm', 'ilike', $value)->orWhere('kana', 'ilike', $value)->orderBy('soko_cd');
            default:
                # code...
                break;
        }
       
        if(!empty($request->otherWhere)) {
            
            foreach ($request->otherWhere as $key => $value) {
                if($key == 'hed_bumon_cd') {
                    $key = 'm_soko_hinmei.bumon_cd';
                    if($field == 'soko_cd' || $field == 'soko_nm' || $field == 'soko_cd_to') {
                        $key = 'bumon_cd';
                    }
                }
                if($value) {
                    $qb->where($key, 'ilike', $value);
                }
            }
            
        }
        $data = $qb->get();
        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    public function indexZaikoNyusyukoMeisai(Request $request) 
    {
        $hinmei = DB::table('m_soko_hinmei')
                ->select(
                    'm_soko_hinmei.*',
                    DB::raw('m_meisyo.meisyo_nm as case_cd_nm')
                )
                ->leftJoin('m_meisyo', function($join) {
                    $join->on('m_meisyo.meisyo_cd', 'm_soko_hinmei.bara_tani');
                    $join->where('m_meisyo.meisyo_kbn', configParam('MEISYO_KBN_TANI'));
                })
                ->where('hinmei_cd', $request->hinmei_cd)
                ->where('ninusi_cd', $request->ninusi_cd)
                ->first();
        $setting = require(app_path('Helpers/Grid/config/nyusyuko/nyuryoku/nyuryoku-zaiko-nyusyuko-meisai.php'));
        return view('nyusyuko.nyuryoku.zaiko-nyusyuko-meisai.index', ['setting' => $setting, 'hinmei' => $hinmei]);
    }

    public function listZaikoNyusyukoMeisai(Request $request) {
        $list = DB::table('t_zaiko')
                ->select(
                    't_zaiko.*',
                    'm_soko.soko_nm',
                    DB::raw('t_zaiko.su as hikiate_kano_su'),
                    DB::raw('concat(m_soko_hinmei.hinmei_cd, \'　\', m_soko_hinmei.hinmei_nm) as hinmei_nm'),
                    DB::raw('LEAST('. (!empty($request->su) ? $request->su : '0') .', t_zaiko.su) as hikiate_su'),
                    'm_soko.soko_cd',
                    'm_soko.soko_nm'
                )
                ->leftJoin('m_soko_hinmei', function($join) use ($request) {
                    $join->on('m_soko_hinmei.hinmei_cd', 't_zaiko.hinmei_cd');
                    $join->on('m_soko_hinmei.ninusi_cd', 't_zaiko.ninusi_cd');
                    // $join->where('m_soko_hinmei.ninusi_cd', '=', $request->ninusi_cd);
                })
                ->leftJoin('m_soko', function($join) use ($request) {
                    $join->on('m_soko.soko_cd', 't_zaiko.soko_cd');
                    $join->on('m_soko.bumon_cd', 't_zaiko.bumon_cd');
                    // $join->where('m_soko.bumon_cd', '=', $request->bumon_cd);
                })
                // ->leftJoin('t_zaiko_nyusyuko_meisai', 't_zaiko_nyusyuko_meisai.zaiko_seq_no', 't_zaiko.seq_no')
                ->where('t_zaiko.hinmei_cd', $request->hinmei_cd)
                ->where('t_zaiko.bumon_cd', $request->bumon_cd)
                ->where('t_zaiko.ninusi_cd', $request->ninusi_cd);
        if(!empty($request->radio_su) && $request->radio_su == 'tujyo_hyouji') {
            $list = $list->where('t_zaiko.su', '>', 0);
        }
        $listData = [
            'rows'=> $list,
            'total' => $list->count()
        ];
        $data['total'] = $listData['total'];
        $data['rows'] = $listData['rows']->get();
        return response()->json($data);
    }

    public function validateNyusyukoHeadFormSearch(FormSearchNyusyukoHeadRequest $request) {
        return response()->json([
            'status' => 200,
            'data' => $request->all()
        ]);
    }

    public function calculatorRoundKintax(Request $request)  {
        $data = [];
        if($request->menzei_kbn == 1) {
            $data = [
                'seikyu_kin_tax' => null,
                'yosya_kin_tax' => null
            ];
        } else {
            $menzeiKbn = $request->menzei_kbn ?? 0;
            if($menzeiKbn == 0) {
                if($request->filled('yousya_cd')) {
                    $yousya = DB::table('m_yousya')->whereRaw(
                                    'yousya_cd = (
                                        SELECT COALESCE(siharai_cd, yousya_cd) 
                                        FROM m_yousya
                                        WHERE yousya_cd = ?
                                    )', [$request->yousya_cd])->first();
                    if(!empty($yousya) && $yousya->zei_keisan_kbn == 3) {
                        $yousya = DB::table('m_yousya')->where('yousya_cd', $request->yousya_cd)->first();
                        $data['yosya_kin_tax'] = roundFromKbnTani(($request->yosya_tyukei_kin * configParam('TAX_RATE')), intval($yousya->zei_hasu_kbn), intval($yousya->zei_hasu_tani));
                    }
                }
                    

                if($request->filled('ninusi_cd')) {
                    $ninusi = DB::table('m_ninusi')->whereRaw(
                                    'ninusi_cd = (
                                        SELECT COALESCE(seikyu_cd, ninusi_cd) 
                                        FROM m_ninusi
                                        WHERE ninusi_cd = ?
                                    )', [$request->ninusi_cd])->first();
                    if(!empty($ninusi) && $ninusi->zei_keisan_kbn == 3) {
                        $ninusi = DB::table('m_ninusi')->where('ninusi_cd', $request->ninusi_cd)->first();
                        $data['seikyu_kin_tax'] = roundFromKbnTani(($request->unchin_kin + $request->tyukei_kin + $request->tesuryo_kin + $request->nieki_kin + $request->syuka_kin) * configParam('TAX_RATE'), intval($ninusi->zei_hasu_kbn), intval($ninusi->zei_hasu_tani));
                    }
                }
            }
            
        }
        if($request->ajax()) {
            return response()->json([
                'status' => Response::HTTP_OK,
                'data' => $data
            ]);
        }
        return $data;
    }

    public function getNinusi(Request $request) {
        $data = DB::table('m_ninusi')->where('ninusi_cd', $request->ninusi_cd)->first();
        return response()->json([
            'status' => Response::HTTP_OK,
            'data' => $data
        ]);
    }

    public function exportPdf(Request $request) {
        $outDir = storage_path('app/download');
        $filename = date('YmdHis') . 'nyusyuko_nyuryoku.xlsx';

        $nyusyukoDenNo = $request->nyusyuko_den_no;
        $nyusyukoHead = DB::table('t_nyusyuko_head')->select('t_nyusyuko_head.*', 'm_ninusi.ninusi1_nm', 'm_ninusi.ninusi2_nm')->where('nyusyuko_den_no', $nyusyukoDenNo)->leftJoin('m_ninusi', 'm_ninusi.ninusi_cd', 't_nyusyuko_head.ninusi_cd')->first();
        $savePath = $this->__createExcel($request, $nyusyukoHead, $outDir, $filename);

        $pdfPath = $outDir . DIRECTORY_SEPARATOR . str_replace('.xlsx', '.pdf', $filename);

        // return response()->download(
        //     $savePath,
        //     date('YmdHis') . '.xlsx'
        // )->deleteFileAfterSend(true);

        cnvXlsToPdf($savePath, $outDir);
        if (File::exists($savePath)) {
            File::delete($savePath);
        }

        if($nyusyukoHead->nyusyuko_kbn == 1) {
            $fileName = '入庫伝票.pdf';
        } else {
            $fileName = '納品伝票.pdf';
        }
        return response()->download($pdfPath, $fileName)->deleteFileAfterSend(true);
    }

    private function __createExcel($request, $nyusyukoHead, $outDir, $filename) {
        if(in_array($nyusyukoHead->nyusyuko_kbn, [1,2])) {
            if($nyusyukoHead->nyusyuko_kbn == 1) {
                $exporter = new XlsNyusyukoNyuryokuIn();
                $config = require(app_path('Helpers/Excel/config/nyusyuko_nyuryoku_in.php'));
                $templatePath = app_path('Helpers/Excel/template/nyusyuko_nyuryoku_in.xlsx');
            } elseif($nyusyukoHead->nyusyuko_kbn == 2) {
                $exporter = new XlsNyusyukoNyuryokuOut();
                $config = require(app_path('Helpers/Excel/config/nyusyuko_nyuryoku_out.php'));
                if(!empty($nyusyukoHead->ninusi2_nm)) {
                    $config['base']['header']['others'][] = [
                        'col' => 'G', 'row' => 13, 'value' => function($list) {
                            return $list ? $list->first()->ninusi1_nm : '';
                        }
                    ];
                    $config['base']['header']['others'][] = [
                        'col' => 'G', 'row' => 14, 'value' => function($list) {
                            return $list ? $list->first()->ninusi2_nm : '';
                        }
                    ];
                } else {
                    $config['base']['header']['others'][] = [
                        'col' => 'G', 'row' => 13, 'value' => function($list) {
                            return $list ? $list->first()->ninusi1_nm : '';
                        }
                    ];
                    $config['base']['header']['mergeCells'][] = ['col' => 'G', 'row' => 13, 'w' => 14, 'h' => 2];
                }
                $templatePath = app_path('Helpers/Excel/template/nyusyuko_nyuryoku_out.xlsx');
            }
            $repo = $this->nyuryokuRepository;
            $rows = $repo->getListWithTotalCount($request, $nyusyukoHead)['rows'];
            $data = $rows->get();
            

            if (!File::exists($outDir)) {
                File::makeDirectory($outDir, 0755, true, true);
            }
            $savePath = $outDir . DIRECTORY_SEPARATOR . $filename;
            
            $exporter->export(
                $templatePath,//template
                $config,
                $data,
                $savePath
            );

            return $savePath;
        }
    }
}
