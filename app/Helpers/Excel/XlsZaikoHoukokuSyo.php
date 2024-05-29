<?php


namespace App\Helpers\Excel;


use App\Helpers\XlsExportBlockSummary;

class XlsZaikoHoukokuSyo extends XlsExportBlockSummary
{
    protected $orgPageBlock = null;

    protected function addDataToExcel($objPHPExcel, $config, $data)
    {
        $base = $config['base'];
        $groups = $data->groupBy(function ($item, $key) {
            return implode('-', [
                $item->ninusi_cd,
            ]);
        });

        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
        $templateMergeCells = $sheet->getMergeCells();

        // block
        $blockH = $base['block']['end']['row'] - $base['block']['start']['row'] + 1;
        $orgPageBlock = $base['template']['page'];

        $this->orgPageBlock = $this->getFormatBlock($objPHPExcel, $base['template']['page']);

        $row = 1 + $base['template']['height'];

        $renderedCnt = 0;
        foreach ($groups as $k => $byNinusi) {
            $first = true;
            $renderedCnt = 0;
            $row = $this->addPage($objPHPExcel, $sheet, $base, $row, $byNinusi, $renderedCnt);

            foreach ($byNinusi as $item) {
                if (!$first) {
                    $row = $this->addPage($objPHPExcel, $sheet, $base, $row, $byNinusi, $renderedCnt);
                }

                $this->renderBlock($objPHPExcel, $config, $data, $row, $item);
                $row += $blockH;
                $renderedCnt++;
                $first = false;
            }

            // render ninusi
            $row = $this->addPage($objPHPExcel, $sheet, $base, $row, $byNinusi, $renderedCnt);
            $this->renderSummary($objPHPExcel, $sheet, $base, 'total', $row, $byNinusi);
            $row += $blockH;
            $renderedCnt++;

            // to next page
            $div = $renderedCnt % $orgPageBlock['size'];
            if ($div >= 1) {
                $row += ($blockH * ($orgPageBlock['size'] - $div));
            }
        }
        foreach ($templateMergeCells as $cells) {
            $sheet->unmergeCells($cells);
        }
        $sheet->removeRow(1, $base['template']['height']);
        return $objPHPExcel;
    }
}
