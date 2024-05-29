<?php 
namespace App\Http\Repositories\Seikyu;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;

use App\Models\TSeikyu;
use App\Models\TUriage;
use App\Models\TNyukin;
use App\Models\TSeikyuSimebiSiji;

class SeikyuShimebiSijiRepository 
{
    public function getListWithTotalCount($request)
    {
        $list = TSeikyuSimebiSiji::query()
                ->filter($request)
                ->orderBy('t_seikyu_simebi_siji.seikyu_sime_dt');
       
        $total = $list->count();
        
        return [
            'total' => $total,
            'rows' => $list,
        ];
    }

    public function getListSeikyuJoinSeikyuSimebi($seikyuSimeDts = [])
    {
        $qb = TSeikyuSimebiSiji::select([
                                    't_seikyu.seikyu_sime_dt',
                                    't_seikyu.seikyu_hako_flg',
                                    't_seikyu.seikyu_kakutei_flg',
                                ])
                                ->join('t_seikyu', 't_seikyu_simebi_siji.seikyu_sime_dt', '=', 't_seikyu.seikyu_sime_dt');

        if (!empty($seikyuSimeDts)) {
           $qb->whereIn('t_seikyu.seikyu_sime_dt', $seikyuSimeDts);
        }

        return $qb;
    }

    public function listUriageJoinNinusi($bumons, $seikyuSimeDt)
    {   
        $qb = new TUriage();// $qb = new Builder();
        $table = 't_uriage';

        $qb = $qb->select([
            DB::raw('COALESCE(m_ninusi.seikyu_cd, m_ninusi.ninusi_cd) as ninusi_cd'),
            "N2.kin_hasu_kbn",
            "N2.kin_hasu_tani",
            "N2.zei_keisan_kbn",
            "N2.zei_hasu_kbn",
            "N2.zei_hasu_tani",
            DB::raw("sum({$table}.unchin_kin) as sum__unchin_kin"), // 基本運賃合計金額
            DB::raw("sum({$table}.tyukei_kin) as sum__tyukei_kin"), // 中継料合計金額
            DB::raw("sum({$table}.tukoryo_kin) as hikazei_kin"), // 通行料合計金額
            DB::raw("sum({$table}.syuka_kin) as sum__syuka_kin"), // 集荷料合計金額
            DB::raw("sum({$table}.tesuryo_kin) as sum__tesuryo_kin"), // 手数料計金額
            DB::raw("sum({$table}.nieki_kin) as sum__nieki_kin"), // 荷役料合計金額
            DB::raw("(COALESCE(sum({$table}.unchin_kin), 0) + COALESCE(sum({$table}.tyukei_kin), 0) 
                     + COALESCE(sum({$table}.syuka_kin), 0) + COALESCE(sum({$table}.tesuryo_kin), 0)) 
                     as kazei_unchin_kin"), // kazei_unchin_kin
        ]);
        
        $qb = $this->joinMultipleNinusi($qb, $bumons, $seikyuSimeDt);

        $qb->groupBy([
            DB::raw('COALESCE(m_ninusi.seikyu_cd, m_ninusi.ninusi_cd)'),
            "N2.kin_hasu_kbn",
            "N2.kin_hasu_tani",
            "N2.zei_keisan_kbn",
            "N2.zei_hasu_kbn",
            "N2.zei_hasu_tani",
        ]);
       
        return $qb;
    }

    public function listUriageJoinNinusiNoGroupBy($bumons, $seikyuSimeDt)
    {
        $qb = TUriage::query();

        $this->joinMultipleNinusi($qb, $bumons, $seikyuSimeDt);
       
        return $qb;
    }

    private function joinMultipleNinusi($qb, $bumons, $seikyuSimeDt)
    {
        $qb->joinMNinusi('inner');

        $qb->join("m_ninusi as N2", DB::raw('COALESCE(m_ninusi.seikyu_cd, m_ninusi.ninusi_cd)'), "=", "N2.ninusi_cd");

        $qb = $this->whereListUriageJoinNinusi($qb, $bumons, $seikyuSimeDt);

        return $qb;
    }

    public function listUriageJoinNinusiZeiKeisanKbn3($bumons, $seikyuSimeDt)
    {
        $tUriage =  new TUriage();// $qb = new Builder();
        $qb = $tUriage->joinMNinusi('inner');
        
        $qb->join("m_ninusi as N2", DB::raw('COALESCE(m_ninusi.seikyu_cd, m_ninusi.ninusi_cd)'), "=", "N2.ninusi_cd");

        $qb = $this->whereListUriageJoinNinusi($qb, $bumons, $seikyuSimeDt);

        $qb->where('m_ninusi.zei_keisan_kbn', 3);

        return $qb;
    }

    public function listNyukin($seikyuSimeDt)
    {
        $qb = TNyukin::select([
                            DB::raw('COALESCE(m_ninusi.seikyu_cd, m_ninusi.ninusi_cd) as ninusi_cd'),
                            DB::raw('sum(genkin_kin) as sum__genkin_kin'),
                            DB::raw('sum(furikomi_kin) as sum__furikomi_kin'),
                            DB::raw('sum(furikomi_tesuryo_kin) as sum__furikomi_tesuryo_kin'),
                            DB::raw('sum(tegata_kin) as sum__tegata_kin'),
                            DB::raw('sum(sousai_kin) as sum__sousai_kin'),
                            DB::raw('sum(nebiki_kin) as sum__nebiki_kin'),
                            DB::raw('sum(sonota_nyu_kin) as sum__sonota_nyu_kin'),

                            DB::raw('COALESCE(sum(genkin_kin), 0) + COALESCE(sum(furikomi_kin), 0) 
                                    + COALESCE(sum(furikomi_tesuryo_kin), 0) + COALESCE(sum(tegata_kin), 0) 
                                    + COALESCE(sum(sonota_nyu_kin), 0) 
                                    as nyukin_kin'),
                        ])
                        ->join('m_ninusi', 'm_ninusi.ninusi_cd', '=', 't_nyukin.ninusi_cd')
                        ->where(function ($query) {
                            $query->where('t_nyukin.sime_kakutei_kbn', '<>', '1')
                                  ->orWhereNull('t_nyukin.sime_kakutei_kbn');
                        })
                        ->where('t_nyukin.seikyu_sime_dt', '=', $seikyuSimeDt)
                        ->groupBy(DB::raw('COALESCE(m_ninusi.seikyu_cd, m_ninusi.ninusi_cd)'));
            
        return $qb;
    }

    public function whereListUriageJoinNinusi($qb, $bumons, $seikyuSimeDt)
    {
        $qb->whereIn('t_uriage.bumon_cd', $bumons);
        $qb->where('m_ninusi.seikyu_mu_kbn', '<>', 1);
        $qb->where('t_uriage.unchin_mikakutei_kbn', '=', 0);
        $qb->where('t_uriage.seikyu_sime_dt', '=', $seikyuSimeDt);

        return $qb;
    }

    public function listNyukinNo($collection, $seikyuSimeDt)
    {
        $ninusiCds  = $collection->keys()->toArray();

        $qbNyukin = DB::table('t_nyukin')
                        ->whereIn('ninusi_cd', $ninusiCds)
                        ->where('seikyu_sime_dt', $seikyuSimeDt);

        $res = $qbNyukin->get()->pluck('nyukin_no')->toArray();

        return $res;
    }

    public function listUriageDenNo($collection, $seikyuSimeDt)
    {
        $ninusiCds = $collection->keys()->toArray();

        $qbUriage = DB::table('t_uriage')
                        ->whereIn('ninusi_cd', $ninusiCds)
                        ->where('seikyu_sime_dt', $seikyuSimeDt);

        $res = $qbUriage->get()->pluck('uriage_den_no')->toArray();

        return $res;
    }
    
    public function getListNinusiBySimebi($day)
    {
        $qb = DB::table('m_ninusi')
                ->orWhere('simebi1', $day)
                ->orWhere('simebi2', $day)
                ->orWhere('simebi3', $day)
                ->whereIn('ninusi_cd', function ($query) {
                    $query->select([
                                DB::raw('COALESCE(seikyu_cd, ninusi_cd) AS seikyu_cd')
                            ])
                          ->from('m_ninusi');
                })
                ->orderBy('ninusi_cd');
                ;
        
        return $qb;
    }

    public function getSumSuEachKi($ninusiCd, $dtKi1From, $dtKi1To, $dtKi2From, $dtKi2To, $dtKi3From, $dtKi3To)
    {
        $qb = DB::table('t_nyusyuko_head')
                ->select([
                    't_nyusyuko_head.bumon_cd',
                    't_nyusyuko_meisai.hinmei_cd',
                    DB::raw('COALESCE(m_ninusi.seikyu_cd, m_ninusi.ninusi_cd) as ninusi_cd'),

                    DB::raw("(SELECT SUM(su) AS sum_su_ki1__kbn_1
                            FROM t_nyusyuko_head AS head_1
                            INNER JOIN t_nyusyuko_meisai AS meisai_1 ON head_1.nyusyuko_den_no = meisai_1.nyusyuko_den_no
                            INNER JOIN m_ninusi ON head_1.ninusi_cd = m_ninusi.ninusi_cd 
                            WHERE COALESCE(m_ninusi.seikyu_cd, m_ninusi.ninusi_cd) = {$ninusiCd}
                            AND head_1.bumon_cd = t_nyusyuko_head.bumon_cd
                            AND meisai_1.hinmei_cd = t_nyusyuko_meisai.hinmei_cd
                            AND head_1.nyusyuko_kbn = '1'
                            AND head_1.denpyo_dt >= '{$dtKi1From}'
                            AND head_1.denpyo_dt <= '{$dtKi1To}')"),

                    DB::raw("(SELECT SUM(su) AS sum_su_ki1__kbn_2
                            FROM t_nyusyuko_head AS head_2
                            INNER JOIN t_nyusyuko_meisai AS meisai_2 ON head_2.nyusyuko_den_no = meisai_2.nyusyuko_den_no
                            INNER JOIN m_ninusi ON head_2.ninusi_cd = m_ninusi.ninusi_cd 
                            WHERE COALESCE(m_ninusi.seikyu_cd, m_ninusi.ninusi_cd) = {$ninusiCd}
                            AND head_2.bumon_cd = t_nyusyuko_head.bumon_cd
                            AND meisai_2.hinmei_cd = t_nyusyuko_meisai.hinmei_cd
                            AND head_2.nyusyuko_kbn = '2'
                            AND head_2.denpyo_dt >= '{$dtKi1From}'
                            AND head_2.denpyo_dt <= '{$dtKi1To}')"),

                    DB::raw("(SELECT SUM(su) AS sum_su_ki2__kbn_1
                            FROM t_nyusyuko_head AS head_3
                            INNER JOIN t_nyusyuko_meisai AS meisai_3 ON head_3.nyusyuko_den_no = meisai_3.nyusyuko_den_no
                            INNER JOIN m_ninusi ON head_3.ninusi_cd = m_ninusi.ninusi_cd 
                            WHERE COALESCE(m_ninusi.seikyu_cd, m_ninusi.ninusi_cd) = {$ninusiCd}
                            AND head_3.bumon_cd = t_nyusyuko_head.bumon_cd
                            AND meisai_3.hinmei_cd = t_nyusyuko_meisai.hinmei_cd
                            AND head_3.nyusyuko_kbn = '1'
                            AND head_3.denpyo_dt >= '{$dtKi2From}'
                            AND head_3.denpyo_dt <= '{$dtKi2To}')"),

                    DB::raw("(SELECT SUM(su) AS sum_su_ki2__kbn_2
                            FROM t_nyusyuko_head AS head_4
                            INNER JOIN t_nyusyuko_meisai AS meisai_4 ON head_4.nyusyuko_den_no = meisai_4.nyusyuko_den_no
                            INNER JOIN m_ninusi ON head_4.ninusi_cd = m_ninusi.ninusi_cd 
                            WHERE COALESCE(m_ninusi.seikyu_cd, m_ninusi.ninusi_cd) = {$ninusiCd}
                            AND head_4.bumon_cd = t_nyusyuko_head.bumon_cd
                            AND meisai_4.hinmei_cd = t_nyusyuko_meisai.hinmei_cd
                            AND head_4.nyusyuko_kbn = '2'
                            AND head_4.denpyo_dt >= '{$dtKi2From}'
                            AND head_4.denpyo_dt <= '{$dtKi2To}')"),

                    DB::raw("(SELECT SUM(su) AS sum_su_ki3__kbn_1
                            FROM t_nyusyuko_head AS head_5
                            INNER JOIN t_nyusyuko_meisai AS meisai_5 ON head_5.nyusyuko_den_no = meisai_5.nyusyuko_den_no
                            INNER JOIN m_ninusi ON head_5.ninusi_cd = m_ninusi.ninusi_cd 
                            WHERE COALESCE(m_ninusi.seikyu_cd, m_ninusi.ninusi_cd) = {$ninusiCd}
                            AND head_5.bumon_cd = t_nyusyuko_head.bumon_cd
                            AND meisai_5.hinmei_cd = t_nyusyuko_meisai.hinmei_cd
                            AND head_5.nyusyuko_kbn = '1'
                            AND head_5.denpyo_dt >= '{$dtKi3From}'
                            AND head_5.denpyo_dt <= '{$dtKi3To}')"),

                    DB::raw("(SELECT SUM(su) AS sum_su_ki3__kbn_2
                            FROM t_nyusyuko_head AS head_6
                            INNER JOIN t_nyusyuko_meisai AS meisai_6 ON head_6.nyusyuko_den_no = meisai_6.nyusyuko_den_no
                            INNER JOIN m_ninusi ON head_6.ninusi_cd = m_ninusi.ninusi_cd 
                            WHERE COALESCE(m_ninusi.seikyu_cd, m_ninusi.ninusi_cd) = {$ninusiCd}
                            AND head_6.bumon_cd = t_nyusyuko_head.bumon_cd
                            AND meisai_6.hinmei_cd = t_nyusyuko_meisai.hinmei_cd
                            AND head_6.nyusyuko_kbn = '2'
                            AND head_6.denpyo_dt >= '{$dtKi3From}'
                            AND head_6.denpyo_dt <= '{$dtKi3To}')"),
                ])
                   
                ->join('t_nyusyuko_meisai', 't_nyusyuko_head.nyusyuko_den_no', '=', 't_nyusyuko_meisai.nyusyuko_den_no')
                ->join('m_ninusi', 't_nyusyuko_head.ninusi_cd', '=', 'm_ninusi.ninusi_cd')

                ->whereIn('t_nyusyuko_head.nyusyuko_kbn', array_keys(config('params.NYUSYUKO_KBN')))
                ->groupBy([
                    't_nyusyuko_head.bumon_cd',
                    't_nyusyuko_meisai.hinmei_cd',
                    DB::raw('COALESCE(m_ninusi.seikyu_cd, m_ninusi.ninusi_cd)'),
                ])
                ->havingRaw('COALESCE(m_ninusi.seikyu_cd, m_ninusi.ninusi_cd) = ?', [$ninusiCd])
                ;
        
        return $qb;
    }

    public function getSumSuInsZaikoKijyun($ninusiCds, $seikyuSimeDt)
    {
        $qb = DB::table('t_nyusyuko_head')
            ->select([
                't_nyusyuko_head.bumon_cd',
                't_nyusyuko_head.ninusi_cd',
                't_nyusyuko_meisai.hinmei_cd',
                't_nyusyuko_meisai.location',
                DB::raw("FLOOR(SUM(su) / m_soko_hinmei.irisu) as case_su"),
                DB::raw("(SUM(su) % m_soko_hinmei.irisu) as hasu_su"),
                DB::raw("(SELECT SUM(su) AS sum_su__kbn_1
                            FROM t_nyusyuko_head AS head_1
                            INNER JOIN t_nyusyuko_meisai AS meisai_1 ON head_1.nyusyuko_den_no = meisai_1.nyusyuko_den_no
                            WHERE head_1.ninusi_cd = t_nyusyuko_head.ninusi_cd
                            AND head_1.bumon_cd = t_nyusyuko_head.bumon_cd
                            AND meisai_1.hinmei_cd = t_nyusyuko_meisai.hinmei_cd
                            AND (meisai_1.location = t_nyusyuko_meisai.location OR (meisai_1.location IS NULL AND t_nyusyuko_meisai.location IS NULL))
                            AND head_1.nyusyuko_kbn = '1'
                            AND head_1.denpyo_dt <= '{$seikyuSimeDt}')"),

                DB::raw("(SELECT SUM(su) AS sum_su__kbn_2
                            FROM t_nyusyuko_head AS head_2
                            INNER JOIN t_nyusyuko_meisai AS meisai_2 ON head_2.nyusyuko_den_no = meisai_2.nyusyuko_den_no
                            WHERE head_2.ninusi_cd = t_nyusyuko_head.ninusi_cd
                            AND head_2.bumon_cd = t_nyusyuko_head.bumon_cd
                            AND meisai_2.hinmei_cd = t_nyusyuko_meisai.hinmei_cd
                            AND (meisai_2.location = t_nyusyuko_meisai.location OR (meisai_2.location IS NULL AND t_nyusyuko_meisai.location IS NULL))
                            AND head_2.nyusyuko_kbn = '2'
                            AND head_2.denpyo_dt <= '{$seikyuSimeDt}')"),

                DB::raw("(SELECT SUM(su) AS sum_su__kbn_4
                            FROM t_nyusyuko_head AS head_4
                            INNER JOIN t_nyusyuko_meisai AS meisai_4 ON head_4.nyusyuko_den_no = meisai_4.nyusyuko_den_no
                            WHERE head_4.ninusi_cd = t_nyusyuko_head.ninusi_cd
                            AND head_4.bumon_cd = t_nyusyuko_head.bumon_cd
                            AND meisai_4.hinmei_cd = t_nyusyuko_meisai.hinmei_cd
                            AND (meisai_4.location = t_nyusyuko_meisai.location OR (meisai_4.location IS NULL AND t_nyusyuko_meisai.location IS NULL))
                            AND head_4.nyusyuko_kbn = '4'
                            AND head_4.denpyo_dt <= '{$seikyuSimeDt}')"),

                DB::raw("(SELECT SUM(su) AS sum_su__kbn_5
                            FROM t_nyusyuko_head AS head_5
                            INNER JOIN t_nyusyuko_meisai AS meisai_5 ON head_5.nyusyuko_den_no = meisai_5.nyusyuko_den_no
                            WHERE head_5.ninusi_cd = t_nyusyuko_head.ninusi_cd
                            AND head_5.bumon_cd = t_nyusyuko_head.bumon_cd
                            AND meisai_5.hinmei_cd = t_nyusyuko_meisai.hinmei_cd
                            AND (meisai_5.location = t_nyusyuko_meisai.location OR (meisai_5.location IS NULL AND t_nyusyuko_meisai.location IS NULL))
                            AND head_5.nyusyuko_kbn = '5'
                            AND head_5.denpyo_dt <= '{$seikyuSimeDt}')"),
            ])
            ->join('t_nyusyuko_meisai', 't_nyusyuko_head.nyusyuko_den_no', '=', 't_nyusyuko_meisai.nyusyuko_den_no')
            ->join('m_ninusi', 't_nyusyuko_head.ninusi_cd', '=', 'm_ninusi.ninusi_cd')
            ->leftJoin('m_soko_hinmei', function($j) {
                $j->on('t_nyusyuko_head.ninusi_cd', 'm_soko_hinmei.ninusi_cd');
                $j->on('t_nyusyuko_meisai.hinmei_cd', 'm_soko_hinmei.hinmei_cd');
            })
            ->where('t_nyusyuko_head.denpyo_dt', '<=', $seikyuSimeDt)
            ->whereIn(DB::raw("COALESCE(m_ninusi.seikyu_cd, m_ninusi.ninusi_cd)"), $ninusiCds)
            ->whereIn('t_nyusyuko_head.nyusyuko_kbn', array_keys(config('params.NYUSYUKO_KBN_SUPPORT')))
            ->groupBy([
                't_nyusyuko_head.bumon_cd',
                't_nyusyuko_head.ninusi_cd',
                't_nyusyuko_meisai.hinmei_cd',
                't_nyusyuko_meisai.location',
                'm_soko_hinmei.irisu',
            ]);
        
        return $qb;
    }

    public function getSumZaikoAllSu($ninusiCd, $dateOfPreMonth)
    {
        $qb = DB::table('t_zaiko_kijyun')
                ->select([
                    'kijyun_dt',
                    't_zaiko_kijyun.bumon_cd',
                    DB::raw("COALESCE(m_ninusi.seikyu_cd, m_ninusi.ninusi_cd) as ninusi_cd"),
                    't_zaiko_kijyun.hinmei_cd',
                    DB::raw('sum(zaiko_all_su) as sum__zaiko_all_su')
                ])
                ->join('m_ninusi', 't_zaiko_kijyun.ninusi_cd', '=', 'm_ninusi.ninusi_cd')
              
                ->where('kijyun_dt', '=', $dateOfPreMonth)
                ->whereRaw("COALESCE(m_ninusi.seikyu_cd, m_ninusi.ninusi_cd) = '{$ninusiCd}'")
                ->groupBy([
                    'kijyun_dt',
                    't_zaiko_kijyun.bumon_cd',
                    't_zaiko_kijyun.hinmei_cd',
                    DB::raw("COALESCE(m_ninusi.seikyu_cd, m_ninusi.ninusi_cd)"),
                ])
                ->orderBy('kijyun_dt');

        return $qb;
    }

    public function getKonkaiTorihikiKin($ninuciCd, $seikyuSimeDt)
    {
        $result = DB::table('t_seikyu')
                    ->where('seikyu_sime_dt', '<', $seikyuSimeDt)
                    ->where('ninusi_cd', '=', $ninuciCd)
                    ->orderBy('seikyu_sime_dt', 'DESC')
                    ->value('konkai_torihiki_kin');
                
        return $result;
    }

    public function getListSeikyu($ninuciCd, $seikyuSimeDt)
    {
        $res = DB::table('t_seikyu')
                ->where('ninusi_cd', $ninuciCd)
                ->where('seikyu_sime_dt', $seikyuSimeDt);

        return $res;
    }

    public function insertTSeikyu($values)
    {
        DB::table('t_seikyu')->insert($values);
    }
    
    public function emptySeikyuNo($table, $seikyuNo)
    {
        DB::table($table)
            ->where('seikyu_no', $seikyuNo)
            ->update([
                'seikyu_no'    => null,
                'upd_user_cd'  => Auth::id(),
                'upd_dt'       => now(),
            ]
        );
    }

    public function updateTSeikyu($where, $values)
    {
        DB::table('t_seikyu')->where($where)->update($values);
    }

    public function updateTUriage($where, $listUriageDenNo, $values)
    {
        DB::table('t_uriage')
                ->where($where)
                ->where('unchin_mikakutei_kbn', '=', 0)
                ->whereIn('uriage_den_no', $listUriageDenNo)
                ->update($values);
    }

    public function store($request) 
    {
        DB::beginTransaction();
        try {
            $res = TSeikyuSimebiSiji::create($request->all()); 
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => $res
            ];
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            DB::rollback();
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => ''
            ];
        }
    }

    public function delete($seikyuSimeDt) 
    {
        DB::beginTransaction();
        try {
            TSeikyu::where([
                ['seikyu_sime_dt', '=', $seikyuSimeDt]
            ])->delete();

            TSeikyuSimebiSiji::where([
                ['seikyu_sime_dt', '=', $seikyuSimeDt]
            ])->delete();
            
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'data' => null
            ];
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            DB::rollback();
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => ''
            ];
        }
    }
}