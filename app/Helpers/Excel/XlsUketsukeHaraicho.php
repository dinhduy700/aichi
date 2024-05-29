<?php

namespace App\Helpers\Excel;

use App\Helpers\XlsExportMstMultiRowBlock;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class XlsUketsukeHaraicho extends XlsExportMstMultiRowBlock
{
    protected function addDataToExcel($objPHPExcel, $config, $groupList)
    {
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();

        $base = $config['base'];
        $headerH = $base['header']['end']['row'] - $base['header']['start']['row'] + 1;
        $orgPageBlock = $base['template']['page'];
        $rowSummary = 0;
        $confHeader = $base['header'];
        $row = 1 + $base['template']['height'];
        if($groupList->isEmpty()) {
            $this->clonePage($objPHPExcel,$sheet, $orgPageBlock, $confHeader, $row);
            $this->fillHeader($sheet, $base, null, $row);
            $sheet->removeRow(1, $base['template']['height']);
            return $objPHPExcel;
        }
        $size = $orgPageBlock['size'];


        $this->pageNo = 0;
        foreach($groupList as $list) {
            $chunks = $list->chunk($size);
            foreach ($chunks as $key => $chunk) {
                $lastPageData = $chunk;
                $cnt = count($chunk);
                $this->clonePage($objPHPExcel, $sheet, $orgPageBlock, $confHeader, $row);
                $this->fillHeader($sheet, $base, $chunk, $row);
                $this->setData($objPHPExcel, $config, $chunk, $row);
                $rowSummary = $row + $headerH +
                    count(collect($chunk)->filter(function ($item) {
                        return $item->nyusyuko_den_no !== null;
                    })) * count($config['block']);
                $row += $base['template']['height'];

                if($key + 1 == count($chunks) && $cnt == $orgPageBlock['size']) {
                    $this->clonePage($objPHPExcel,$sheet, $orgPageBlock, $confHeader, $row);
                    $this->fillHeader($sheet, $base, $lastPageData, $row);
                    $rowSummary = $row + $headerH;
                }
            }

            foreach ($base['summary']['total'] as $setting) {
                if (isset($setting['mergeCells'])) {
                    $c = $setting['col'];
                    $r = $rowSummary + $setting['row'] - 1;
                    $range = $c . $r . ':'
                        . Coordinate::stringFromColumnIndex(
                            Coordinate::columnIndexFromString($c) + ($setting['mergeCells']['w'] - 1)
                        )
                        . ($r + ($setting['mergeCells']['h'] - 1));
                    $sheet->mergeCells($range, $sheet::MERGE_CELL_CONTENT_EMPTY);
                    if (isset($setting['mergeCells']['style'])) {
                        $style = $sheet->getStyle($range);
                        $style->getAlignment()->setHorizontal($setting['mergeCells']['style']);
                    }
                }
                $v = $this->getValueOther($setting, $list);
                $sheet = $this->setCellValue(
                    $sheet, $setting['col'] . ($rowSummary + $setting['row'] - 1), $v,
                    data_get($setting, 'type', null)
                );
            }
        }
        $sheet->removeRow(1, $base['template']['height']);
        return $objPHPExcel;
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

    protected function setData($objPHPExcel, $config, $data, $startAt = 1, $sheetActive = 0)
    {
        try {
            $sheet = $objPHPExcel->setActiveSheetIndex($sheetActive);

            $orgBlock = $config['base']['block'];
            $cntRow = $orgBlock['end']['row'] - $orgBlock['start']['row'] + 1;

            $startAt--;
            $i=0;
            foreach ($data as $key => $item) {
                if (empty(data_get($item, 'nyusyuko_den_no'))) {
                    continue;
                }
                $row = $orgBlock['start']['row'] + ($i * $cntRow);
                if ($i || ($startAt > $orgBlock['start']['row'])) {
                    $toBlock = [
                        'col' => $orgBlock['start']['col'],
                        'row' => $row + $startAt
                    ];
                    $this->copyBlockDataStyle($objPHPExcel, $orgBlock, $toBlock);
                }
                $arrReCalSu = $data->filter(function ($item, $index) use ($key) {
                    return $index <= $key;
                });
                $sumIn = $arrReCalSu->sum('in_su');
                $sumOut = $arrReCalSu->sum('out_su');
                $zaikoSu = $item->zaiko_su + $sumIn - $sumOut;
                $item->zaiko_case_su = floor($zaikoSu / $item->irisu);
                $item->zaiko_hasu = $zaikoSu % $item->irisu;
                $item->zaiko_su = $zaikoSu;
                $item->zaiko_jyuryo = $zaikoSu * $item->bara_tani_juryo;
                for ($ii = 0; $ii < $cntRow; $ii++) {
                    foreach ($config['block'][$ii] as $c => $setting) {
                        $v = $this->getValue($setting, $item);
                        $dataType = $setting['type'] ?? null;

                        $r = $row + $ii + $startAt;
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

                $i++;
            }
            return $objPHPExcel;
        } catch (\Exception $exception) {
            $this->logErr($exception);
        }
        return null;
    }
}