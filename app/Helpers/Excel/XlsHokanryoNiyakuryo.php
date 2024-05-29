<?php


namespace App\Helpers\Excel;


use App\Helpers\XlsExportBlockSummary;

class XlsHokanryoNiyakuryo extends XlsExportBlockSummary
{
    protected function addDataToExcel($objPHPExcel, $config, $data)
    {
        $base = $config['base'];
        $groups = $data->groupBy(function ($item, $key) {
            return implode('-', [
                $item->ninusi_cd,
                $item->seikyu_sime_dt,
            ]);
        });

        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
        $templateMergeCells = $sheet->getMergeCells();
        $confHeader = $base['header'];

        // block
        $blockH = $base['block']['end']['row'] - $base['block']['start']['row'] + 1;
        $orgPageBlock = $base['template']['page'];

        $row = 1 + $base['template']['height'];

        $renderedCnt = 0;
        foreach ($groups as $k => $groubByDt) {
            $row = $this->addPage($objPHPExcel, $sheet, $base, $row, $groubByDt, $renderedCnt);
            $first=true;
            $groupByHinmoku = $groubByDt->groupBy('hinmei_cd');
            foreach ($groupByHinmoku as $byHinmoku) {
                foreach ($byHinmoku as $item) {
                    if (!$first) {
                        $row = $this->addPage($objPHPExcel, $sheet, $base, $row, $groubByDt, $renderedCnt);
                    }

                    $this->renderBlock($objPHPExcel, $config, $data, $row, $item);
                    $row += $blockH;
                    $renderedCnt++;
                    $first = false;
                }
            }

            // render summary-gokei
            $row = $this->addPage($objPHPExcel, $sheet, $base, $row, $groubByDt, $renderedCnt);
            $this->renderSummary($objPHPExcel, $sheet, $base, 'gokei', $row, $groubByDt);
            $row += $blockH;
            $renderedCnt++;

            // to next page
            $div = $renderedCnt % $orgPageBlock['size'];
            if ($div > 1) {
                $row += ($blockH * ($orgPageBlock['size'] - $div));
                $renderedCnt = ceil($renderedCnt / $orgPageBlock['size']) * $orgPageBlock['size'];
            }
        }
        foreach ($templateMergeCells as $cells) {
            $sheet->unmergeCells($cells);
        }
        $sheet->removeRow(1, $base['template']['height']);
        return $objPHPExcel;
    }
}
