<?php


namespace App\Helpers\Excel;


use App\Helpers\XlsExportMstMultiRowBlock;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class XlsNyukin extends XlsExportMstMultiRowBlock
{
    protected function addDataToExcel($objPHPExcel, $config, $data)
    {
        $base = $config['base'];
        $groupByDate = $data->groupBy($base['groupBy'][0]);

        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
        $templateMergeCells = $sheet->getMergeCells();

        // block
        $blockH = $base['block']['end']['row'] - $base['block']['start']['row'] + 1;
        $orgPageBlock = $base['template']['page'];
        $rowSummary = 0;
        $confHeader = $base['header'];

        $row = 1 + $base['template']['height'];

        if($data->isEmpty()) {
            $this->clonePage($objPHPExcel,$sheet, $orgPageBlock, $confHeader, $row);
            $this->fillHeader($sheet, $base, null, $row);
            foreach ($templateMergeCells as $cells) {
                $sheet->unmergeCells($cells);
            }
            $sheet->removeRow(1, $base['template']['height']);
            return $objPHPExcel;
        }

        $renderCnt = 0;
        foreach ($groupByDate as $group) {
            foreach ($group as $item) {
                $rowSummary = $this->addPage($objPHPExcel,$sheet, $base, $row, $group, $renderCnt);
                $row = $rowSummary;
                $this->renderBlock($objPHPExcel, $config, $data, $row, $item);
                $row += $blockH;
                $renderCnt+=1;
            }
            // shokei
            $rowSummary = $this->addPage($objPHPExcel,$sheet, $base, $row, $group, $renderCnt);
            $this->renderSummary($objPHPExcel, $sheet, $base, 'shokei', $rowSummary, $group);
            $row += $blockH;
            $renderCnt+=1;
        }

        // gokei
        $rowSummary = $this->addPage($objPHPExcel,$sheet, $base, $row, $groupByDate, $renderCnt);
        $this->renderSummary($objPHPExcel, $sheet, $base, 'gokei', $rowSummary, $groupByDate);

        foreach ($templateMergeCells as $cells) {
            $sheet->unmergeCells($cells);
        }
        $sheet->removeRow(1, $base['template']['height']);
        return $objPHPExcel;
    }

    private function fillHeader(&$sheet, $base, $pageData, $row)
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

    private function clonePage(&$objPHPExcel, &$sheet, $orgPageBlock, $confHeader, $row)
    {
        $this->pageNo++;
        $sheet->setBreak('A' . ($row - 1), Worksheet::BREAK_ROW);

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

        // clone page-template
        $toBlock = [
            'col' => $orgPageBlock['start']['col'],
            'row' => $row
        ];
        $this->cloneBlock($objPHPExcel, $orgPageBlock, $toBlock);
    }

    private function addPage(&$objPHPExcel, &$sheet, $base, $row, $pageData, $renderCnt)
    {
        $orgPageBlock = $base['template']['page'];
        $rowSummary = $row;

        if ($renderCnt % $orgPageBlock['size'] == 0) {
            $confHeader = $base['header'];
            $headerH = $confHeader['end']['row'] - $confHeader['start']['row'] + 1;

            $this->clonePage($objPHPExcel, $sheet, $orgPageBlock, $confHeader, $row);
            //fill header-data
            $this->fillHeader($sheet, $base, $pageData, $row);
            $rowSummary = $row + $headerH;
        }
        return $rowSummary;
    }

    private function renderSummary(&$objPHPExcel, &$sheet, $base, $key, $rowSummary, $page)
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

    private function renderBlock(&$objPHPExcel, $config, $data, $row, $item)
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
