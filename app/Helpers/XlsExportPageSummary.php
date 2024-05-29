<?php


namespace App\Helpers;


use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class XlsExportPageSummary extends XlsExportMstMultiRowBlock
{
    protected $orgPageBlock = null;

    abstract protected function dataGroupBy($data);
//    protected function dataGroupBy($data)
//    {
//        return $data->groupBy(function ($item, $key) {
//            return implode('-', [
//                $item->bumon_cd,
//                $item->ninusi_cd,
//            ]);
//        });
//    }

    protected function addDataToExcel($objPHPExcel, $config, $data)
    {
        $base = $config['base'];
        $data = $this->dataGroupBy($data);

        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
        $templateMergeCells = $sheet->getMergeCells();

        $row = 1 + $base['template']['height'];
        $pageStartAt = $row;

        foreach ($data as $k => $group) {
            $pages = $group->chunk($base['template']['page']['size']);
            $page = collect([]);
            foreach ($pages as $page) {
                $pageStartAt = $row;
                $this->renderData(
                    $objPHPExcel, $sheet, $row,
                    $base['template']['page'],
                    $base['header'],
                    $base['block'],
                    $config['block'],
                    $page
                );
            }
            $this->renderSummary($base, $objPHPExcel, $sheet, $row, $group, $page, $pageStartAt);
        }

        foreach ($templateMergeCells as $cells) {
            $sheet->unmergeCells($cells);
        }
        $sheet->removeRow(1, $base['template']['height']);
        return $objPHPExcel;
    }

    public function renderData(
        &$objPHPExcel, &$sheet, &$row,
        $confPage, $confHeader, $confBlock, $blockSetting,
        $page
    )
    {
        $pageH = $confPage['end']['row'] - $confPage['start']['row'] + 1;

        // block
        $orgPageBlock = $confPage;

        $this->clonePage($objPHPExcel,$sheet, $orgPageBlock, $confHeader, $row);

        $this->fillHeader($sheet, $confHeader, $row, $page);

        $config = [
            'base' => [
                'block' => $confBlock
            ],
            'block' => $blockSetting
        ];
        $objPHPExcel = $this->setData($objPHPExcel, $config, $page, $row);

        $row += $pageH;
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

        if (!empty($orgPageBlock['rowHeight'])) {
            for ($r = $orgPageBlock['start']['row']; $r <= $orgPageBlock['end']['row']; $r++) {
                $sheet->getRowDimension($row + $r - 1)->setRowHeight($orgPageBlock['rowHeight']);
            }
        } else {
            for ($r = $orgPageBlock['start']['row']; $r<=$orgPageBlock['end']['row']; $r++) {
                $sheet->getRowDimension($row + $r - 1)->setRowHeight($sheet->getRowDimension($r)->getRowHeight());
            }
        }

        if (isset($confHeader['mergeCells'])) {
            foreach ($confHeader['mergeCells'] as $setting) {
                $this->mergeCells($sheet, $row, $setting);
            }
        }

        if (isset($this->orgPageBlock)) {
            $this->cloneBlockFormat($objPHPExcel, $orgPageBlock, $toBlock, $this->orgPageBlock);
        } else {
            $this->cloneBlock($objPHPExcel, $orgPageBlock, $toBlock);
        }
    }

    protected function fillHeader(&$sheet, $confHeader, $row, $pageData)
    {
        if (isset($confHeader['others'])) {
            foreach ($confHeader['others'] as $setting) {
                $v = $this->getValueOther($setting, $pageData);
                $sheet = $this->setCellValue(
                    $sheet, $setting['col'] . ($row + $setting['row'] - 1), $v,
                    data_get($setting, 'type', null)
                );
            }
        }
    }

    protected function renderSummary($base, &$objPHPExcel, &$sheet, &$row, $groupData, $lastPageData, $pageStartAt)
    {
        $orgPageBlock = $base['template']['page'];
        $cnt = $lastPageData->count();

        $headerH = $base['header']['end']['row'] - $base['header']['start']['row'] + 1;
        if ($cnt == $orgPageBlock['size']) {
            $rowSummary = $pageStartAt + $headerH;
            $this->clonePage($objPHPExcel,$sheet, $orgPageBlock, $base['header'], $row);
            $this->fillHeader($sheet, $base, $lastPageData, $row);
            $row += ($orgPageBlock['end']['row'] - $orgPageBlock['start']['row'] + 1);
        } else {
            $blockH = $base['block']['end']['row'] - $base['block']['start']['row'] + 1;
            $rowSummary = $pageStartAt + $headerH + ($blockH * $cnt);
        }

        $orgBlock = $base['template']['summary'];
        $toBlock = [
            'col' => $orgBlock['start']['col'],
            'row' => $rowSummary
        ];
        if (isset($orgBlock['mergeCells'])) {
            foreach ($orgBlock['mergeCells'] as $setting) {
                $this->mergeCells($sheet, $rowSummary, $setting);
            }
        }
        $this->cloneBlock($objPHPExcel, $orgBlock, $toBlock);
        foreach ($base['summary'] as $setting) {
            $r = ($rowSummary + $setting['row'] - 1);
            if (isset($setting['mergeCells'])) {
                $this->mergeCells($sheet, $r, array_merge($setting, $setting['mergeCells']));
            }
            $v = $this->getValueOther($setting, $groupData);
            $sheet = $this->setCellValue(
                $sheet, $setting['col'] . $r, $v,
                data_get($setting, 'type', null)
            );
        }
    }

    public function mergeCells(&$sheet, $row, $setting)
    {
        $c = $setting['col'];
        $r = $row + $setting['row'] - 1;
        $range = $c . $r . ':'
            . Coordinate::stringFromColumnIndex(
                Coordinate::columnIndexFromString($c) + ($setting['w'] - 1)
            )
            . ($r + ($setting['h'] - 1));
        $sheet->mergeCells($range, $sheet::MERGE_CELL_CONTENT_EMPTY);
    }


//    protected function dataGroupBy($data)
//    {
//        return $data->groupBy(function ($item, $key) {
//            return implode('-', [
//                $item->bumon_cd,
//                $item->ninusi_cd,
//            ]);
//        });
//    }
}
