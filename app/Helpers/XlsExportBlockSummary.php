<?php


namespace App\Helpers;


use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class XlsExportBlockSummary extends XlsExportMstMultiRowBlock
{
    protected function fillHeader(&$sheet, $base, $pageData, $row)
    {
        if (isset($base['header']['others'])) {
            foreach ($base['header']['others'] as $setting) {
                $v = $this->getValueOther($setting, $pageData);
                $sheet = $this->setCellValue(
                    $sheet, $setting['col'] . ($row + $setting['row'] - 1), $v,
                    data_get($setting, 'type', null)
                );
            }
        }
    }

    protected function clonePage(&$objPHPExcel, &$sheet, $orgPageBlock, $confHeader, $row)
    {
        $this->pageNo++;
        $sheet->setBreak('A' . ($row - 1), Worksheet::BREAK_ROW);

        // clone page-template
        $toBlock = [
            'col' => $orgPageBlock['start']['col'],
            'row' => $row
        ];
        for ($r = $orgPageBlock['start']['row']; $r<=$orgPageBlock['end']['row']; $r++) {
            $sheet->getRowDimension($row + $r - 1)->setRowHeight($sheet->getRowDimension($r)->getRowHeight());
        }

        if (isset($confHeader['mergeCells'])) {
            foreach ($confHeader['mergeCells'] as $setting) {
                $c = $setting['col'];
                $r = $row + $setting['row'] - 1;
                $range = $c . $r . ':'
                    . Coordinate::stringFromColumnIndex(
                        Coordinate::columnIndexFromString($c) + ($setting['w'] - 1)
                    )
                    . ($r + ($setting['h'] - 1));
                $sheet->mergeCells($range, $sheet::MERGE_CELL_CONTENT_EMPTY);
            }
        }

        if (isset($this->orgPageBlock)) {
            $this->cloneBlockFormat($objPHPExcel, $orgPageBlock, $toBlock, $this->orgPageBlock);
        } else {
            $this->cloneBlock($objPHPExcel, $orgPageBlock, $toBlock);
        }
    }

    protected function addPage(&$objPHPExcel, &$sheet, $base, $row, $pageData, $renderCnt)
    {
        $orgPageBlock = $base['template']['page'];
        $rowSummary = $row;

        if ($renderCnt % $orgPageBlock['size'] == 0) {
            $confHeader = $base['header'];
            $headerH = $confHeader['end']['row'] - $confHeader['start']['row'] + 1;

            $blockH = $base['block']['end']['row'] - $base['block']['start']['row'] + 1;
            $gridH = ($orgPageBlock['end']['row'] - $orgPageBlock['start']['row'] + 1) - $headerH;
            $row += ($gridH - ($blockH * $orgPageBlock['size']));

            $this->clonePage($objPHPExcel, $sheet, $orgPageBlock, $confHeader, $row);
            //fill header-data
            $this->fillHeader($sheet, $base, $pageData, $row);
            $rowSummary = $row + $headerH;
        }
        return $rowSummary;
    }

    protected function renderSummary(&$objPHPExcel, &$sheet, $base, $key, $rowSummary, $page)
    {
        $orgBlock = $base['template']['summary'][$key];
        $toBlock = [
            'col' => $orgBlock['start']['col'],
            'row' => $rowSummary
        ];
        $this->cloneBlock($objPHPExcel, $orgBlock, $toBlock);
        foreach ($base['summary'][$key] as $setting) {
            if (isset($setting['mergeCells'])) {
                $c = $setting['col'];
                $r = $rowSummary + $setting['row'] - 1;
                $range = $c . $r . ':'
                    . Coordinate::stringFromColumnIndex(
                        Coordinate::columnIndexFromString($c) + ($setting['mergeCells']['w'] - 1)
                    )
                    . ($r + ($setting['mergeCells']['h'] - 1));
                $sheet->mergeCells($range, $sheet::MERGE_CELL_CONTENT_EMPTY);
            }

            $v = $this->getValueOther($setting, $page);
            $sheet = $this->setCellValue(
                $sheet, $setting['col'] . ($rowSummary + $setting['row'] - 1), $v,
                data_get($setting, 'type', null)
            );
        }

    }

    protected function renderBlock(&$objPHPExcel, $config, $data, $row, $item)
    {
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $orgBlock = $config['base']['block'];
        $cntRow = $orgBlock['end']['row'] - $orgBlock['start']['row'] + 1;
        $toBlock = [
            'col' => $orgBlock['start']['col'],
            'row' => $row
        ];
        $this->copyBlockDataStyle($objPHPExcel, $orgBlock, $toBlock);

        for ($ii = 0; $ii < $cntRow; $ii++) {
            foreach ($config['block'][$ii] as $c => $setting) {
                $v = $this->getValue($setting, $item);
                $dataType = $setting['type'] ?? null;
                $r = $row + $ii;
                $pCell = $c . $r;
                if (isset($setting['mergeCells'])) {
                    $sheet->mergeCells(
                        $c . $r . ':'
                        . Coordinate::stringFromColumnIndex(
                            Coordinate::columnIndexFromString($c) + ($setting['mergeCells']['w'] - 1)
                        )
                        . ($r + ($setting['mergeCells']['h'] - 1))

                        , $sheet::MERGE_CELL_CONTENT_EMPTY);
                }
                $sheet = $this->setCellValue($sheet, $pCell, $v, $dataType);
            }
        }
    }
}
