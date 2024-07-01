<?php

namespace App\Helpers\Pdf;

use setasign\Fpdi\Tcpdf\Fpdi;

use App\Helpers\Formatter;

class PdfSeikyusyo {

    public $pageNo = 0;

    public function export($data, $listZaikoHokanryoByNinusiCd)
    {
        $pdf            = new Fpdi();
        $dataGroupBy    = $data->groupBy('ninusi_cd');
        
        $perPage        = 24;
        $fontFamily     = 'ms_mincho';

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
       
        $pdf->SetFont($fontFamily, '', 9);
       
        // set the source file
        $sourceFile = app_path('Helpers/Pdf/Template/t_uriage_seikyu.pdf');
       
        $pdf->setSourceFile($sourceFile);
        $midasisitei = data_get(request()->all(), 'exp.midasisitei');

        $optMidasisitei = $midasisitei == 3 ? 2 : 1;

        for ($i=1; $i <= $optMidasisitei; $i++) {
            foreach ($dataGroupBy as $key => $items) {
                $ninusiCd = null;
                if (isset($items[0]['ninusi_cd'])) {
                    $ninusiCd = $items[0]['ninusi_cd'];
                }
               
                if ($listZaikoHokanryoByNinusiCd->has($ninusiCd) &&
                    data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.seki_su') != null
                    && data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.seki_su') != null
                    && data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.hokan_kin') != null
                    && data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.nyuko_su') != null
                    && data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.nyuko_kin') != null
                    && data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.syuko_su') != null
                    && data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.syuko_kin') != null
                ) {
                    $perPage = 21;
                }

                $pages = $items->chunk($perPage);
                $this->pageNo = 0;
                
                foreach ($pages as $key => $page) {
                    $pdf->AddPage();
                    $tplId = $pdf->importPage(1);
                    $pdf->useTemplate($tplId);

                    // header
                    $pdf->SetFont($fontFamily, '', 9);

                    if ($i > 1 || $midasisitei == 2) {
                        $pdf->SetFont('yugothib', 'B', 12);
                        $pdf->SetXY(160, 15);
                        $pdf->Cell(1, 20, '（控）');
                    }
                    $pdf->SetFont($fontFamily, '', 9);
                    $this->fillHeader(count($pages), $page, $pdf);
                    
                    $this->setData($page, $pdf, $listZaikoHokanryoByNinusiCd);
                }
            }
        }

        // Output the PDF as a string
        $outDir         = storage_path('app/download');
        $newFilename    = '請求書_'.  date('YmdHis') . '.pdf';

        $pdf->Output($outDir . DIRECTORY_SEPARATOR . $newFilename, "F");

        return $newFilename;
    }

    public function fillHeader($totalPage, $page, $pdf) 
    {
        $this->pageNo++;

        $first              = $page->first();
        $fontFamily         = 'ms_mincho';
        $yubinNo            = '〒' . data_get($first, 'yubin_no');
        $jyusyo1Nm          = data_get($first, 'jyusyo1_nm');
        $jyusyo2Nm          = data_get($first, 'jyusyo2_nm');
        $ninusi1Nm          = data_get($first, 'ninusi1_nm');
        $hakkouDt           = !empty(request('exp.hakkou_dt')) ? Formatter::date(request('exp.hakkou_dt')) : '';
        $konkaiTorihikiKin  = numberFormat(data_get($first, 'konkai_torihiki_kin') ?? 0);
        $konkaiTorihikiKinTop = '￥' . ($konkaiTorihikiKin) . '-';
        $zenkaiSeikyuKin    = request('exp.seikyussk') != 2 ? numberFormat(data_get($first, 'zenkai_seikyu_kin') ?? 0) : '';
        $genkinKin          = data_get($first, 'genkin_kin') ?? 0;
        $furikomiKin        = data_get($first, 'furikomi_kin') ?? 0;
        $furikomiTesuryoKin = data_get($first, 'furikomi_tesuryo_kin') ?? 0;
        $tegataKin          = data_get($first, 'tegata_kin') ?? 0;
        $sonotaNyuKin       = data_get($first, 'sonota_nyu_kin') ?? 0;
        $nyukinKin          = request('exp.seikyussk') != 2 ? numberFormat($genkinKin + $furikomiKin + $furikomiTesuryoKin + $tegataKin + $sonotaNyuKin) : ' ';
        $sousaiKin          = data_get($first, '0.sousai_kin') ?? 0;
        $nebikiKin          = data_get($first, '0.nebiki_kin') ?? 0;
        $resSousaiKin       = request('exp.seikyussk') != 2 ? numberFormat($sousaiKin + $nebikiKin) : ' ';
        $kjrikosiKin        = request('exp.seikyussk') != 2 ? numberFormat(data_get($first, 'kjrikosi_kin') ?? 0) : ' ';
        $kazeiUnchinKin     = data_get($first, 'kazei_unchin_kin') ?? 0;
        $kazeiTyukeiKin     = data_get($first, 'kazei_tyukei_kin') ?? 0;
        $kazeiTukouryouKin  = data_get($first, 'kazei_tukouryou_kin') ?? 0;
        $kazeiNiyakuryoKin  = data_get($first, 'kazei_niyakuryo_kin') ?? 0;
        $resKazeiUnchinKin  = numberFormat($kazeiUnchinKin + $kazeiTyukeiKin + $kazeiTukouryouKin + $kazeiNiyakuryoKin);
        $zeiKin             = numberFormat(data_get($first, 'zei_kin') ?? 0);
        $hikazeiUnchinKin   = data_get($first, 'hikazei_unchin_kin') ?? 0;
        $hikazeiTyukeiKin   = data_get($first, 'hikazei_tyukei_kin') ?? 0;
        $hikazeiTukouryoKin = data_get($first, 'hikazei_tukouryo_kin') ?? 0;
        $hikazeiNiyakuryoKin = data_get($first, 'hikazei_niyakuryo_kin') ?? 0;
        $hikazeiKin         = numberFormat($hikazeiUnchinKin + $hikazeiTyukeiKin + $hikazeiTukouryoKin + $hikazeiNiyakuryoKin);
        $overPage           = $this->pageNo . '/' . $totalPage .'頁';
        $seikyuSimeDt       = \Illuminate\Support\Carbon::parse(data_get($first, 'seikyu_sime_dt'));
        $seikyuSimeDt       = $seikyuSimeDt->format('Y年m月d日') . '締 ';

        if ($this->pageNo > 1) {
            $konkaiTorihikiKin    = '';
            $konkaiTorihikiKinTop = '';
            $zenkaiSeikyuKin      = '';
            $nyukinKin            = '';
            $resSousaiKin         = '';
            $kjrikosiKin          = '';
            $resKazeiUnchinKin    = '';
            $zeiKin               = '';
            $hikazeiKin           = '';
        }

        $pdf->SetXY(22, 7);
        $pdf->Cell(1, 20, $yubinNo);

        $pdf->SetXY(22, 12);
        $pdf->Cell(1, 20, $jyusyo1Nm);

        $pdf->SetXY(22, 17);
        $pdf->Cell(1, 20, $jyusyo2Nm);

        $pdf->SetXY(22, 22);
        $pdf->Cell(1, 20, $ninusi1Nm);

        $pdf->SetXY(165, 7);
        $pdf->Cell(1, 20, $hakkouDt);

        $pdf->SetXY(187, 7);
        $pdf->Cell(1, 20, $overPage);

        $pdf->SetXY(15, 60);
        $pdf->Cell(1, 20, $seikyuSimeDt);
                        
        //今回請求額 - top
        $pdf->SetFont($fontFamily, '', 15);
        $pdf->SetXY(117, 30);
        $pdf->MultiCell(60, 5, $konkaiTorihikiKinTop, 0, 'R', false, 1, null, null, true, 0, false, true, 0, 'M', 'B');
        
        $pdf->SetFont($fontFamily, '', 9);

        //前回御請求額
        $pdf->SetXY(48, 67);
        // $pdf->SetXY(41, 71);
        $pdf->MultiCell(19, 6,  ($zenkaiSeikyuKin) . ' ', 0, 'R', false, 1, null, null, true, 0, false, true, 0, 'M', 'B');

        //御入金額
        $pdf->SetXY(66, 67);
        $pdf->MultiCell(18, 6,  ($nyukinKin), 0, 'R', false, 1, null, null, true, 0, false, true, 0, 'M', 'B');

        //相殺・値引額
        $pdf->SetXY(84, 67);
        $pdf->MultiCell(18, 6,  ($resSousaiKin), 0, 'R', false, 1, null, null, true, 0, false, true, 0, 'M', 'B');

        //繰越額
        $pdf->SetXY(102, 67);
        $pdf->MultiCell(18, 6,  ($kjrikosiKin), 0, 'R', false, 1, null, null, true, 0, false, true, 0, 'M', 'B');

        //課税運賃
        $pdf->SetXY(122, 67);
        $pdf->MultiCell(18, 6,  ($resKazeiUnchinKin), 0, 'R', false, 1, null, null, true, 0, false, true, 0, 'M', 'B');

        // 消費税額
        $pdf->SetXY(140, 67);
        $pdf->MultiCell(18, 6,  ($zeiKin), 0, 'R', false, 1, null, null, true, 0, false, true, 0, 'M', 'B');

        // 非課税額
        $pdf->SetXY(158, 67);
        $pdf->MultiCell(18, 6,  ($hikazeiKin), 0, 'R', false, 1, null, null, true, 0, false, true, 0, 'M', 'B');

        // 今回取引額
        $pdf->SetXY(176, 67);
        $pdf->MultiCell(18, 6,  ($konkaiTorihikiKin), 0, 'R', false, 1, null, null, true, 0, false, true, 0, 'M', 'B');

        return $pdf;
    }

    public function setData($page, $pdf, $listZaikoHokanryoByNinusiCd)
    {
        $fontFamily     = 'ms_mincho';
        $arrTmp         = [];
        $sumKin         = 0;

        $pdf->SetY(82);
        
        $y = 7.70;
        $pdf->SetFont($fontFamily, '', 7);
        $ninusiCd = data_get($page->first(), 'ninusi_cd') ?? '';

        if (!empty(data_get($page->first(), 't_uriage__ninusi_cd') )) {
            $ninusiCd = null;

            foreach ($page as $index => $record) {
                $ninusiCd       = data_get($record, 'ninusi_cd');
                $unsoDt         = Formatter::datetime(data_get($record, 'unso_dt'), 'm/d');
                $syaban         = data_get($record, 'syaban');
                $syasyu         = data_get($record, 'syasyu');
                $hatutiNm       = data_get($record, 'hatuti_nm');
                $hachakuNm      = data_get($record, 'hachaku_nm');
                $hinmeiNm       = data_get($record, 'hinmei_nm');
                $su             = numberFormat(data_get($record, 'su'));
                $taniNm         = data_get($record, 'tani_nm');
                $kihonUnchin    = numberFormat(data_get($record, 'kihon_unchin'));
                $tukoryoKin     = numberFormat(data_get($record, 'tukoryo_kin'));
                $unchinGokei    = numberFormat(data_get($record, 'unchin_gokei'));
    
                if (in_array(data_get($record, 'unso_dt'), $arrTmp)) {
                    $unsoDt = null;
                } else {
                    $arrTmp[] = data_get($record, 'unso_dt');
                }
    
                // detail
                
                // row 1
                $pdf->SetX(15);
                
                $pdf->MultiCell(10, $y,  $unsoDt, 0, 'C', false, 0, null, null, true, 0, false, true, 0, 'T', 'B');
                $pdf->MultiCell(23, $y/2,  $syaban, 0, 'L', false, 0, null, null, true, 0, false, true, 0, 'T', 'B');
                $pdf->MultiCell(32, $y/2,  $hatutiNm, 0, 'L', false, 0, null, null, true, 0, false, true, 0, 'T', 'B');
                $pdf->MultiCell(51, $y,  $hinmeiNm, 0, 'L', false, 0, null, null, true, 0, false, true, 0, 'T', 'B');
                $pdf->MultiCell(13, $y,  $su, 0, 'R', false, 0, null, null, true, 0, false, true, 0, 'T', 'B');
                $pdf->MultiCell(10, $y, $taniNm, 0, 'L', false, 0, null, null, true, 0, false, true, 0, 'T', 'B');
               
                // row 2
                $pdf->setY( $pdf->getY() + $y/2);
                $pdf->setX($pdf->getX() + 15);
    
                $pdf->MultiCell(23, $y/2, $syasyu , 0, 'L', false, 0, null, null, true, 0, false, true, 0, 'T', 'B');
                $pdf->MultiCell(32, $y/2, $hachakuNm, 0, 'L', false, 0, null, null, true, 0, false, true, 0, 'T', 'B');
                $pdf->MultiCell(51, $y/2,  '', 0, 'L', false, 0, null, null, true, 0, false, true, 0, 'T', 'B');
                $pdf->MultiCell(13, $y/2,  '', 0, 'R', false, 0, null, null, true, 0, false, true, 0, 'T', 'B');
                $pdf->MultiCell(10, $y/2, '', 0, 'L', false, 0, null, null, true, 0, false, true, 0, 'T', 'B');
                $pdf->MultiCell(14, $y/2, $kihonUnchin, 0, 'C', false, 0, null, null, true, 0, false, true, 0, 'C', 'B');
                $pdf->MultiCell(14, $y/2, $tukoryoKin, 0, 'C', false, 0, null, null, true, 0, false, true, 0, 'C', 'B');
                $pdf->MultiCell(14, $y/2, $unchinGokei, 0, 'C', false, 1, null, null, true, 0, false, true, 0, 'C', 'B');
            }
        }

        if ($listZaikoHokanryoByNinusiCd->has($ninusiCd) &&
            data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.seki_su') != null
            && data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.seki_su') != null
            && data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.hokan_kin') != null
            && data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.nyuko_su') != null
            && data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.nyuko_kin') != null
            && data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.syuko_su') != null
            && data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.syuko_kin') != null
        ) {
            $sekiSu     = data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.seki_su');
            $hokanKin   = data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.hokan_kin');
            $nyukoSu    = data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.nyuko_su');
            $nyukoKin   = data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.nyuko_kin');
            $syukoSu    = data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.syuko_su');
            $syukoKin   = data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.syuko_kin');
    
            $pdf->setX(80);
            $pdf->MultiCell(51, $y, '保管料', 0, 'L', false, 0, null, null, true, 0, false, true, 0, 'T', 'B');
            $pdf->MultiCell(22.5, $y, numberFormat($sekiSu) ?? '', 0, 'C', false, 0, null, null, true, 0, false, true, 0, 'T', 'B');
            $pdf->MultiCell(14, $y-0.5, numberFormat($hokanKin) ?? '', 0, 'C', false, 0, null, null, true, 0, false, true, 0, 'B', 'B');
            $pdf->MultiCell(14, $y-0.5, '', 0, 'C', false, 0, null, null, true, 0, false, true, 0, 'B', 'B');
            $pdf->MultiCell(14, $y-0.5, numberFormat($hokanKin) ?? '', 0, 'C', false, 0, null, null, true, 0, false, true, 0, 'B', 'B');

            $pdf->MultiCell(1, $y, '', 0, 'C', false, 1, null, null, true, 0, false, true, 0, 'B', 'B');
    
            $pdf->setX(80);
            $pdf->MultiCell(51, $y, '入庫料', 0, 'L', false, 0, null, null, true, 0, false, true, 0, 'T', 'B');
            $pdf->MultiCell(22.5, $y-0.5, numberFormat($nyukoSu) ?? '', 0, 'C', false, 0, null, null, true, 0, false, true, 0, 'T', 'B');
            $pdf->MultiCell(14, $y-0.5, numberFormat($nyukoKin) ?? '', 0, 'C', false, 0, null, null, true, 0, false, true, 0, 'B', 'B');
            $pdf->MultiCell(14, $y-0.5, '', 0, 'C', false, 0, null, null, true, 0, false, true, 0, 'B', 'B');
            $pdf->MultiCell(14, $y-0.5, numberFormat($nyukoKin) ?? '', 0, 'C', false, 0, null, null, true, 0, false, true, 0, 'B', 'B');

            $pdf->MultiCell(1, $y, '', 0, 'C', false, 1, null, null, true, 0, false, true, 0, 'B', 'B');

            $pdf->setX(80);
            $pdf->MultiCell(51, $y, '出庫料', 0, 'L', false, 0, null, null, true, 0, false, true, 0, 'T', 'B');
            $pdf->MultiCell(22.5, $y-0.5, numberFormat($syukoSu) ?? '', 0, 'C', false, 0, null, null, true, 0, false, true, 0, 'T', 'B');
            $pdf->MultiCell(14, $y-0.5, numberFormat($syukoKin) ?? '', 0, 'C', false, 0, null, null, true, 0, false, true, 0, 'B', 'B');
            $pdf->MultiCell(14, $y-0.5, '', 0, 'C', false, 0, null, null, true, 0, false, true, 0, 'B', 'B');
            $pdf->MultiCell(14, $y-0.5, numberFormat($syukoKin) ?? '', 0, 'C', false, 0, null, null, true, 0, false, true, 0, 'B', 'B');
            $pdf->MultiCell(1, $y, '', 0, 'C', false, 1, null, null, true, 0, false, true, 0, 'B', 'B');

            $sumKin = $hokanKin + $nyukoKin + $syukoKin;
        }
      
        // total
        $pdf->setX(73);
        $pdf->MultiCell(48, $y-0.5, '【　合　計　】', 0, 'C', false, 0, null, null, true, 0, false, true, 0, 'B', 'B');
      
        $pdf->MultiCell(32, $y, '', 0, 'C', false, 0, null, null, true, 0, false, true, 0, 'B', 'B');
        $pdf->MultiCell(14, $y-0.5, numberFormat($page->sum('kihon_unchin') + $sumKin), 0, 'C', false, 0, null, null, true, 0, false, true, 0, 'B', 'B');
        $pdf->MultiCell(14, $y, '', 0, 'C', false, 0, null, null, true, 0, false, true, 0, 'B', 'B');
        $pdf->MultiCell(14, $y-0.5, numberFormat($page->sum('unchin_gokei') + $sumKin), 0, 'C', false, 1, null, null, true, 0, false, true, 0, 'B', 'B');

        return $pdf;
    }
} 