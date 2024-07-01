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
            $firstGroup = $list->first();
            $zaikoSu = !empty($firstGroup) ? $firstGroup->zaiko_su : 0;
            $rowForward = $row + $headerH;
            $list->prepend(['【　繰　越　】']);
            $list->push(['【　合　計　】']);
            $chunks = $list->chunk($size);
            foreach ($chunks as $key => $chunk) {
                $chunk = $chunk->filter(function ($item) {
                    return is_object($item);
                });
                $this->clonePage($objPHPExcel, $sheet, $orgPageBlock, $confHeader, $row);
                $this->fillHeader($sheet, $base, $chunk, $row);
                if (count($chunks) == 1) {
                    $this->setDataExcel($objPHPExcel, $config, $chunk, $row, 0, $zaikoSu);
                    $rowSummary = $row + $headerH + 2 +
                        count(collect($chunk)->filter(function ($item) {
                            return $item->nyusyuko_den_no !== null;
                        })) * count($config['block']);
                } else {
                    if ($key == 0 || $key + 1 == count($chunks)) {
                        $isLastPage = $key + 1 == count($chunks);
                        $this->setDataExcel($objPHPExcel, $config, $chunk, $row, 0, $zaikoSu, $isLastPage);
                    } else {
                        $this->setDataExcel($objPHPExcel, $config, $chunk, $row, 0, $zaikoSu, true);
                    }
                    $rowSummary = $row + $headerH +
                        count(collect($chunk)->filter(function ($item) {
                            return $item->nyusyuko_den_no !== null;
                        })) * count($config['block']);
                }

                $row += $base['template']['height'];
            }
            $this->renderSummary($sheet, $base, 'forward', $rowForward, $list);
            $this->renderSummary($sheet, $base, 'summary', $rowSummary, $list);
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

    protected function setDataExcel($objPHPExcel, $config, $data, $startAt = 1, $sheetActive = 0, &$zaikoSu, $isResetRow = false)
    {
        try {
            $sheet = $objPHPExcel->setActiveSheetIndex($sheetActive);

            $orgBlock = $config['base']['block'];
            $cntRow = $orgBlock['end']['row'] - $orgBlock['start']['row'] + 1;

            if ($isResetRow) {
                $orgBlock['start']['row'] = 16;
                $orgBlock['end']['row'] = 17;
                $cntRow = $orgBlock['end']['row'] - $orgBlock['start']['row'] + 1;
            }
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
                $zaikoSu += $item->in_su - $item->out_su;
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

    private function renderSummary(&$sheet, $base, $key, $rowSetValue, $list)
    {
        $list = $list->filter(function ($item) {
            return is_object($item);
        });
        foreach ($base[$key]['total'] as $setting) {
            if (isset($setting['mergeCells'])) {
                $c = $setting['col'];
                $r = $rowSetValue + $setting['row'] - 1;
                $range = $c . $r . ':'
                    . Coordinate::stringFromColumnIndex(
                        Coordinate::columnIndexFromString($c) + ($setting['mergeCells']['w'] - 1)
                    )
                    . ($r + ($setting['mergeCells']['h'] - 1));
                $sheet->mergeCells($range, $sheet::MERGE_CELL_CONTENT_EMPTY);
            }
            if (isset($setting['mergeCells']['style'])) {
                $style = $sheet->getStyle($range);
                $style->getAlignment()->setHorizontal($setting['mergeCells']['style']);
            }
            $v = $this->getValueOther($setting, $list);
            $sheet = $this->setCellValue(
                $sheet, $setting['col'] . ($rowSetValue + $setting['row'] - 1), $v,
                data_get($setting, 'type', null)
            );
        }

    }
}