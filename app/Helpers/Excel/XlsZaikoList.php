<?php

namespace App\Helpers\Excel;

use App\Helpers\XlsExportBlockSummary;


class XlsZaikoList extends XlsExportBlockSummary
{
    protected function addDataToExcel($objPHPExcel, $config, $data)
    {
        $base = $config['base'];
        $groups = $data->groupBy(function ($item, $key) {
            return implode('-', [
                $item->soko_cd,
            ]);
        });

        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
        $templateMergeCells = $sheet->getMergeCells();

        // block
        $blockH = $base['block']['end']['row'] - $base['block']['start']['row'] + 1;
        $orgPageBlock = $base['template']['page'];

        $row = 1 + $base['template']['height'];

        $renderedCnt = 0;
        foreach ($groups as $k => $groubBySoko) {
            $first=true;
            $renderedCnt = 0;
            $row = $this->addPage($objPHPExcel, $sheet, $base, $row, $groubBySoko, $renderedCnt);
            $prevNinusi = null;
            $groupByNinusi = $groubBySoko->groupBy('ninusi_cd');
            foreach ($groupByNinusi as $byNinusi) {
                foreach ($byNinusi as $item) {
                    if (!$first) {
                        $row = $this->addPage($objPHPExcel, $sheet, $base, $row, $groubBySoko, $renderedCnt);
                    }
                    $this->renderBlock($objPHPExcel, $config, $data, $row, $item);
                    $row += $blockH;
                    $renderedCnt += 1;
                    $first = false;
                }

                // render ninusi
                $this->renderSummary($objPHPExcel, $sheet, $base, 'by_ninusi', $row, $byNinusi);
                $row += $blockH;
                $renderedCnt++;
            }

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
