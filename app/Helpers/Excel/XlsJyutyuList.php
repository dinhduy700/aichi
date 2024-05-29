<?php


namespace App\Helpers\Excel;


use App\Helpers\XlsExportMstMultiRowBlock;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class XlsJyutyuList extends XlsExportMstMultiRowBlock
{
    protected $orgPageBlock = [];

    protected function addDataToExcel($objPHPExcel, $config, $data)
    {
        $base = $config['base'];

        $this->orgPageBlock = $this->getFormatBlock($objPHPExcel, $config['base']['template']['page']);

        $data = $data->groupBy($base['groupBy'][0]);
        foreach ($data as $k => $page) {
            $data[$k] = $page->groupBy($base['groupBy'][1]);
        }

        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();

        $pageH = $base['template']['page']['end']['row'] - $base['template']['page']['start']['row'] + 1;
        $headerH = $base['header']['end']['row'] - $base['header']['start']['row'] + 1;
        $confHeader = $base['header'];
        // block
        $blockH = $base['block']['end']['row'] - $base['block']['start']['row'] + 1;
        $orgPageBlock = $base['template']['page'];
        $rowSummary = 0;

        $row = 1 + $base['template']['height'];
        $lastPageData = collect([]);

        foreach ($data as $dates) {
            $cnt = 0;
            $lastPageData = collect([]);
            foreach ($dates as $bumon) {
                $chunks = $bumon->chunk($orgPageBlock['size']);
                $rowSummary = $row + $headerH;
                $datePageCnt = $chunks->count();
                $cnt = 0;
                foreach ($chunks as $i => $chunk) {
                    $lastPageData = $chunk;
                    $rowSummary = $row + $headerH;
                    $cnt = count($chunk);

                    $this->clonePage($objPHPExcel,$sheet, $orgPageBlock, $confHeader, $row);

                    //fill header-data
                    $this->fillHeader($sheet, $base, $chunk, $row);

                    // render Blocks
                    $objPHPExcel = $this->setData($objPHPExcel, $config, $chunk, $row);

                    $row+=$pageH;

                    if ($datePageCnt-1 == $i) { //is last page
                        if ($cnt <= $orgPageBlock['size'] - 1) {
                            $rowSummary += $blockH * count($chunk);
                        }
                    }
                }

                // sum bumon
                if ($cnt == $orgPageBlock['size']) { // sum bumon to next-page
                    $rowSummary = $row + $headerH;
                    $this->clonePage($objPHPExcel, $sheet, $orgPageBlock, $confHeader, $row);

                    //fill header-data
                    $this->fillHeader($sheet, $base, $lastPageData, $row);
                }

                $orgBlock = $base['template']['summary']['bumon_cd'];
                $toBlock = [
                    'col' => $orgBlock['start']['col'],
                    'row' => $rowSummary
                ];
                $this->cloneBlock($objPHPExcel, $orgBlock, $toBlock);
                foreach ($base['summary']['bumon_cd'] as $setting) {
                    $v = $this->getValueOther($setting, $bumon);
                    $sheet = $this->setCellValue(
                        $sheet, $setting['col'] . ($rowSummary + $setting['row'] - 1), $v,
                        data_get($setting, 'type', null)
                    );
                }
            }

            if ($cnt == $orgPageBlock['size'] - 1) { // sum-date to next-page
                $rowSummary = $row + $headerH;

                $this->clonePage($objPHPExcel,$sheet, $orgPageBlock, $confHeader, $row);

                //fill header-data
                $this->fillHeader($sheet, $base, $lastPageData, $row);
                $row += $pageH;

            } else {
                $rowSummary += $blockH;//summa
            }
            // sum by date
            $orgBlock = $base['template']['summary']['ooo_dt'];
            $toBlock = [
                'col' => $orgBlock['start']['col'],
                'row' => $rowSummary
            ];
            $this->cloneBlock($objPHPExcel, $orgBlock, $toBlock);
            foreach ($base['summary']['ooo_dt'] as $setting) {
                $v = $this->getValueOther($setting, $dates);
                $sheet = $this->setCellValue(
                    $sheet, $setting['col'] . ($rowSummary + $setting['row'] - 1), $v,
                    data_get($setting, 'type', null)
                );
            }
        }

        $this->renderPageNoOverTotal($sheet);

        $sheet->removeRow(1, $base['template']['height']);
        return $objPHPExcel;
    }

    private function fillHeader(&$sheet, $base, $pageData, $row)
    {
        if (isset($base['header']['others'])) {
            foreach ($base['header']['others'] as $setting) {
                $cell = $setting['col'] . ($row + $setting['row'] - 1);
                $v = $this->getValueOther($setting, $pageData, $cell);
                $sheet = $this->setCellValue(
                    $sheet, $cell, $v,
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

        $this->cloneBlockFormat($objPHPExcel, $orgPageBlock, $toBlock, $this->orgPageBlock);
    }
}
