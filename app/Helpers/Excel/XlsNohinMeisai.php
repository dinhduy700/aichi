<?php


namespace App\Helpers\Excel;


use App\Helpers\XlsExportPageSummary;

class XlsNohinMeisai extends XlsExportPageSummary
{

    protected $orgSummaryBlock = null;

    protected function dataGroupBy($data)
    {
        return $data->groupBy(function ($item, $key) {
            // 配達日・着地CD・請求先CD
            return implode('-', [
                $item->haitatu_dt,
                $item->hachaku_cd,
                $item->seikyu_cd,
            ]);
        });
    }

    protected function addDataToExcel($objPHPExcel, $config, $data)
    {
        $base = $config['base'];

        $this->orgPageBlock = $this->getFormatBlock($objPHPExcel, $base['template']['page']);
        $this->orgSummaryBlock = $this->getFormatBlock($objPHPExcel, $base['template']['summary']);

        $data = $this->dataGroupBy($data);

        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
        $templateMergeCells = $sheet->getMergeCells();

        $row = 1 + $base['template']['height'];

        $pageStartAt = $row;
        $headerH = $base['header']['end']['row'] - $base['header']['start']['row'] + 1;
        $blockH = $base['block']['end']['row'] - $base['block']['start']['row'] + 1;

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

                // re-style rows
                $orgBlock = $base['middle-block'];
                for ($i=1; $i<=$page->count(); $i++) {
                    $r = $pageStartAt + $headerH + $blockH*($i -1);
                    $toBlock = ['col' => $base['block']['start']['col'], 'row' => $r];
                    $this->copyBlockDataStyle(
                        $objPHPExcel,
                        $orgBlock,
                        $toBlock
                    );
                    $sheet->getRowDimension($r)->setRowHeight($sheet->getRowDimension($orgBlock['start']['row'])->getRowHeight());
                }
            }

            $this->renderSummary($base, $objPHPExcel, $sheet, $row, $group, $page, $pageStartAt);
        }

        foreach ($templateMergeCells as $cells) {
            $sheet->unmergeCells($cells);
        }
        $sheet->removeRow(1, $base['template']['height']);
        return $objPHPExcel;
    }

    protected function renderSummary($base, &$objPHPExcel, &$sheet, &$row, $groupData, $lastPageData, $pageStartAt)
    {
        $orgPageBlock = $base['template']['page'];

        $headerH = $base['header']['end']['row'] - $base['header']['start']['row'] + 1;
        $blockH = $base['block']['end']['row'] - $base['block']['start']['row'] + 1;
        $rowSummary = $pageStartAt + $headerH + ($blockH * $orgPageBlock['size']);

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
        $this->cloneBlockFormat($objPHPExcel, $orgBlock, $toBlock, $this->orgSummaryBlock);

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
}
