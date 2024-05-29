<?php


namespace App\Helpers\Csv;

use App\Helpers\CsvExport;
use App\Http\Repositories\Seikyu\SeikyuRepository;

class SeikyuCsvExport extends CsvExport
{
    public function exportData(\Closure $closure = null)
    {
        $this->fopen();

        $req        = request()->all();
        $repo       = new SeikyuRepository();
        
        $displayNyukin = in_array(
            $repo::EXP_PRINT_OTHER_DISPLAY_NYUKIN,
            data_get($req, 'exp.print_other', [])
        );

        //ROW HEADER
        if ($repo->csvHeaderRow($req)->count() == 0) {
            \Log::info(print_r('csvHeaderRow is empty', TRUE) );
            $this->fclose();
            return;
        }

        $groupedHeader      = $repo->csvHeaderRow($req)->get()->groupBy('ninusi_cd');
        $arrHeader          = $this->prepareHeaderRow($groupedHeader);
        // ROWS DETAIL t_uriage
        if ($repo->csvDetailTUriage($req)->count() == 0) {
            \Log::info(print_r('csvDetailTUriage is empty', TRUE) );
            $this->fclose();
            return;
        }

        $arrDetailUriage = $repo->csvDetailTUriage($req)->get()->groupBy('ninusi_cd');

        if ($displayNyukin) {
            // ROWS DETAIL t_nyukin
            if ($repo->csvDetailTNyukin($req)->count() == 0) {
                \Log::info(print_r('csvDetailTNyukin is empty', TRUE) );
                $this->fclose();
                return;
            }
            $groupedTNyukin = $repo->csvDetailTNyukin($req)->get()->groupBy('ninusi_cd');
        }

        // ROW TOTAL 
        if ($repo->csvTotalRow($req)->count() == 0) {
            \Log::info(print_r('csvDetailTNyukin is empty', TRUE) );
            $this->fclose();
            return;
        }
        $groupedTotal     = $repo->csvTotalRow($req)->get()->groupBy('ninusi_cd');
        $arrTotal         = $this->prepareTotalRow($groupedTotal);

        $tmp    = [];
        $output = [];
       
        foreach ($arrHeader as $ninusiCd => $item) {
            $tmp[$ninusiCd][] = $item;
           
            if (isset($arrDetailUriage[$ninusiCd])) {
                $output[$ninusiCd] = array_merge($tmp[$ninusiCd], data_get($arrDetailUriage, $ninusiCd)->toArray());
            }

            if ($displayNyukin) {
                if ($groupedTNyukin->has($ninusiCd)) {
                    $arrDetaiTNyukin = $this->prepareDetailNyukin($groupedTNyukin, $ninusiCd)->toArray();
                    $output[$ninusiCd] = array_merge($output[$ninusiCd], $arrDetaiTNyukin);
                }
            }

            if (isset($arrTotal[$ninusiCd])) {
                array_push($output[$ninusiCd], $arrTotal[$ninusiCd]);
            }
        }
        
        $mapping    = require(app_path('Helpers/Csv/config/t_uriage_seikyu.php'));
       
        foreach ($output as $ninusiCd => $rows) {
            foreach ($rows as $i => $row) {
                $expRow = [];
                if ($i == 0) {
                    foreach (array_keys($mapping['header']) as $k) {
                        $expRow[$k] = data_get($row, $k, '');
                    }
                } else {
                    foreach (array_keys($mapping['detail']) as $k) {
                        $expRow[$k] = data_get($row, $k, '');
                    }
                }
    
                $this->fputcsv($expRow);
            }
        }

        $this->fclose();
    }

    private function prepareHeaderRow($grouped)
    {
        foreach ($grouped as $ninusiCd => $items) {
            $first                      = $items->first();
            $sumUnchinKin               = $items->sum('unchin_kin');
            $sumKazeiUnchinKin          = $items->sum('kazei_unchin_kin');
            $sumTyukeiKin               = $items->sum('tyukei_kin');
            $sumTukoryoKin              = $items->sum('tukoryo_kin');
            $sumSyukaKin                = $items->sum('syuka_kin');
            $sumTesuryoKin              = $items->sum('tesuryo_kin');
            $sumNiekiKin                = $items->sum('nieki_kin');
            $sumGenkinKin               = $items->sum('genkin_kin');
            $sumFurikomiKin             = $items->sum('furikomi_kin');
            $sumpTegataKin              = $items->sum('tegata_kin');
            $sumSousaiKin               = $items->sum('sousai_kin');
            $sumNebikiKin               = $items->sum('nebiki_kin');
            $sumFurikomiTesuryoKin      = $items->sum('furikomi_tesuryo_kin');
            $sumSonotaNyuKin            = $items->sum('sonota_nyu_kin');
            $sumZeiKin                  = $items->sum('zei_kin');
            $sumHikazeiUnchinKin        = $items->sum('hikazei_unchin_kin');
            $sumKazeiTyukeiKin          = $items->sum('kazei_tyukei_kin');
            $sumKazeiTukouryouKin       = $items->sum('kazei_tukouryou_kin');
            $sumKazeiNiyakuryoKin       = $items->sum('kazei_niyakuryo_kin');
            $sumHikazeiTyukeiKin        = $items->sum('hikazei_tyukei_kin');
            $sumHikazeiTukouryoKin      = $items->sum('hikazei_tukouryo_kin');
            $sumHikazeiNiyakuryoKin     = $items->sum('hikazei_niyakuryo_kin');

            $arrHeader[$ninusiCd] = [
                'seikyu_shimebi'        => data_get($first, 'seikyu_shimebi'),
                'seikyu_gp_1'           => data_get($first, 'ninusi_cd', ''),
                'seikyu_saki_kodo'      => data_get($first, 'ninusi_cd', ''),
                'ninushi_kodo'          => data_get($first, 'ninusi_cd', ''),
                'yubin_no'              => data_get($first, 'yubin_no', ''),
                'jyusyo1_nm'            => data_get($first, 'jyusyo1_nm', ''),
                'jyusyo2_nm'            => data_get($first, 'jyusyo2_nm', ''),
                'ninusi1_nm'            => data_get($first, 'ninusi1_nm', ''),
                'ninusi2_nm'            => data_get($first, 'ninusi2_nm', ''),
                'genkin_kin'            => $sumGenkinKin,
                'furikomi_kin'          => $sumFurikomiKin,
                'tegata_kin'            => $sumpTegataKin,
                'sousai_kin'            => $sumSousaiKin,
                'nebiki_kin'            => $sumNebikiKin,
                'furikomi_tesuryo_kin'  => $sumFurikomiTesuryoKin,
                'sonota_nyu_kin'        => $sumSonotaNyuKin,
                'zei_keisan_kbn'        => data_get($first, 'zei_keisan_kbn', ''),
                'deta_kbn'              => 0,
                'kazei_unchin_kin'      => $sumKazeiUnchinKin,
                'zei_kin'               => $sumZeiKin,
                'hikazei_unchin_kin'    => $sumHikazeiUnchinKin,
                'futai_ryokin_kazei'    => $sumKazeiTyukeiKin + $sumKazeiTukouryouKin + $sumKazeiNiyakuryoKin,
                'futai_ryokin_hikazei'  => $sumHikazeiTyukeiKin + $sumHikazeiTukouryoKin + $sumHikazeiNiyakuryoKin,
                'gaishamei'             => '有限会社　愛知高速運輸',
                'jisha_yubenbango'      => '475-0032',
                'jisha_jusho'           => '愛知県半田市潮干町２番地の３',
                'jisha_tenwabango'      => '0569-20-1601',
                'jisha_fakkusu'         => '0569-20-1602',
                'jobu'                  => '登録番号T1180002089071  課税運賃・消費税は１０％です',
                'kabu'                  => 'お振込口座：愛知銀行　港支店　普通2035736',
                'hakko'                 => 'システム日付',
                                                
            ];
        }

        return $arrHeader;
    }

    private function prepareTotalRow($grouped)
    {
        foreach ($grouped as $ninusiCd => $items) {
            $first              = $items->first();
            $sumUnchinKin       = $items->sum('unchin_kin');
            $sumTyukeiKin       = $items->sum('tyukei_kin');
            $sumTukoryoKin      = $items->sum('tukoryo_kin');
            $sumSyukaKin        = $items->sum('syuka_kin');
            $sumTesuryoKin      = $items->sum('tesuryo_kin');
            $sumNiekiKin        = $items->sum('nieki_kin');
            $sumSeikyuKinTax    = $items->sum('seikyu_kin_tax');
           
            $arrTotal[$ninusiCd] = [
                'seikyu_shimebi'        => data_get($first, 'seikyu_shimebi'),
                'seikyu_gp_1'           => data_get($first, 'ninusi_cd', ''),
                'seikyu_saki_kodo'      => data_get($first, 'ninusi_cd', ''),
                'ninushi_kodo'          => 0,
                'deta_kbn'              => 3,
                'unchin_kin'            => $sumUnchinKin,
                'tyukei_kin'            => $sumTyukeiKin,
                'tukoryo_kin'           => $sumTukoryoKin,
                'tesuryo_kin'           => $sumTesuryoKin,
                'nieki_kin'             => $sumNiekiKin,
                'syuka_kin'             => $sumSyukaKin,
                'seikyu_kin_tax'        => $sumSeikyuKinTax,
            ];
        }

        return $arrTotal;
    }

    private function prepareDetailNyukin($collectTNyukin, $ninusiCd)
    {   
        $filteredNyukin = data_get($collectTNyukin, $ninusiCd);

        $colsKin        = ['genkin_kin', 'furikomi_kin', 
                            'furikomi_tesuryo_kin', 'tegata_kin', 
                            'sousai_kin', 'nebiki_kin', 'sonota_nyu_kin'];
        $arrTmp         = [];

        foreach ($filteredNyukin as $index => $v) {
            for ($i=0; $i < count($colsKin); $i++) { 
                $tegata = null;
                data_set($arrTmp[$index][$i], 'seikyu_shimebi', data_get($v, 'seikyu_shimebi'));
                data_set($arrTmp[$index][$i], 'seikyu_gp_1', data_get($v, 'seikyu_gp_1'));
                data_set($arrTmp[$index][$i], 'seikyu_saki_kodo', data_get($v, 'seikyu_saki_kodo'));
                data_set($arrTmp[$index][$i], 'ninushi_kodo', data_get($v, 'ninushi_kodo'));
                data_set($arrTmp[$index][$i], 'deta_kbn', data_get($v, 'deta_kbn'));
                data_set($arrTmp[$index][$i], 'dt', data_get($v, 'dt'));
                data_set($arrTmp[$index][$i], 'meisai_kbn', data_get($v, 'meisai_kbn'));
                data_set($arrTmp[$index][$i], 'gyo_nyukin', data_get($v, 'gyo_nyukin'));
                data_set($arrTmp[$index][$i], 'nyukin_no', data_get($v, 'nyukin_no'));
                data_set($arrTmp[$index][$i], 'biko', data_get($v, 'biko'));
                data_set($arrTmp[$index][$i], 'sime_kakutei_kbn', data_get($v, 'sime_kakutei_kbn'));
                data_set($arrTmp[$index][$i], 'nyukin_kbn_mei', data_get($this->getConfg(), $i . '.nyukin_kbn_mei'));
                data_set($arrTmp[$index][$i], 'nyukin_gaku', data_get($v, data_get($this->getConfg(), $i . '.nyukin_gaku')));

                if (data_get($this->getConfg(), $i . '.nyukin_gaku') == 'tegata_kin') {
                    $tegata = data_get($v, 'tegata_kijitu_kin');
                }

                data_set($arrTmp[$index][$i], 'tegata', $tegata);
            }
        }
        
        $collection = collect($arrTmp);
         
        $result = $collection->collapse();

        return $result;
      
    }

    private function getConfg()
    {
        return [
            0 => [
                'nyukin_kbn_mei' => '現金',
                'nyukin_gaku' => 'genkin_kin',
            ],
            1 => [
                'nyukin_kbn_mei' => '振込',
                'nyukin_gaku' => 'furikomi_kin',
            ],
            2 => [
                'nyukin_kbn_mei' => '振込手数料',
                'nyukin_gaku' => 'furikomi_tesuryo_kin',
            ],
            3 => [
                'nyukin_kbn_mei' => '手形',
                'nyukin_gaku' => 'tegata_kin',
            ],
            4 => [
                'nyukin_kbn_mei' => '相殺',
                'nyukin_gaku' => 'sousai_kin',
            ],
            5 => [
                'nyukin_kbn_mei' => '値引',
                'nyukin_gaku' => 'nebiki_kin',
            ],
            6 => [
                'nyukin_kbn_mei' => 'その他入金',
                'nyukin_gaku' => 'sonota_nyu_kin',
            ],
        ];
    }
}
