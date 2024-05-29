<?php


namespace App\Helpers\Excel;


use App\Helpers\Formatter;
use App\Helpers\XlsExportMstMultiRowBlock;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class XlsNouhinsyou extends XlsExportMstMultiRowBlock
{
    const FORM_1 = 'form1';
    const FORM_2 = 'form2';
    const FORM_3 = 'form3';
    const FORM_4 = 'form4';

    protected $activeForm = null;
    protected $orgStampBlock = [];
    protected $orgPageBlock = [];

    function addDataToExcel($objPHPExcel, $config, $data)
    {
        $base = $config['base'];
        foreach([$this::FORM_1, $this::FORM_2, $this::FORM_3, $this::FORM_4] as $form) {
            $this->orgStampBlock[$form] = $base['stamp'][$form]
                ? $this->getFormatBlock($objPHPExcel, $base['stamp'][$form])
                : null;
            $this->orgPageBlock[$form] = $base['template']['page'][$form]
                ? $this->getFormatBlock($objPHPExcel, $base['template']['page'][$form])
                : null;
        }

        $data = $data->groupBy(function ($item, $key) {
            return implode('-', [
                Formatter::datetime($item->haitatu_dt, 'Ymd'),
                $item->okurijyo_no,
                $item->hachaku_cd,
                $item->hatuti_cd,
                $item->ninusi_cd,
                Formatter::datetime($item->syuka_dt, 'Ymd'),
                $item->syaban,
                $item->jyomuin_cd,
            ]);
        });

        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();

        $row = 1 + $base['template']['height'];

        foreach ($data as $k => $group) {
            $pages = $group->chunk($base['template']['size']);
            foreach ($pages as $page) {
                $this->activeForm = $this::FORM_1;
                $this->renderFormX(
                    $objPHPExcel, $sheet, $row,
                    $base['template']['page'][$this::FORM_1],
                    $base['header'][$this::FORM_1],
                    $base['block'][$this::FORM_1],
                    $base['stamp'][$this::FORM_1],
                    $config['block'],
                    $page
                );

                $this->activeForm = $this::FORM_2;
                $this->renderFormX(
                    $objPHPExcel, $sheet, $row,
                    $base['template']['page'][$this::FORM_2],
                    $base['header'][$this::FORM_2],
                    $base['block'][$this::FORM_2],
                    $base['stamp'][$this::FORM_2],
                    $config['block'],
                    $page
                );

                $this->activeForm = $this::FORM_3;
                $this->renderFormX(
                    $objPHPExcel, $sheet, $row,
                    $base['template']['page'][$this::FORM_3],
                    $base['header'][$this::FORM_3],
                    $base['block'][$this::FORM_3],
                    $base['stamp'][$this::FORM_3],
                    $config['block'],
                    $page
                );

                $this->activeForm = $this::FORM_4;
                $this->renderFormX(
                    $objPHPExcel, $sheet, $row,
                    $base['template']['page'][$this::FORM_4],
                    $base['header'][$this::FORM_4],
                    $base['block'][$this::FORM_4],
                    $base['stamp'][$this::FORM_4],
                    $config['block'],
                    $page
                );
            }
        }

        $sheet->removeRow(1, $base['template']['height']);
        return $objPHPExcel;
    }

    private function renderFormX(
        &$objPHPExcel, &$sheet, &$row,
        $confPage, $confHeader, $confBlock, $stampSetting, $blockSetting,
        $page
    )
    {
        if ($confPage === false) return;
        $pageH = $confPage['end']['row'] - $confPage['start']['row'] + 1;

        // block
        $orgPageBlock = $confPage;

        $this->clonePage($objPHPExcel,$sheet, $orgPageBlock, $confHeader, $row);
        if ($stampSetting !== false) $this->cloneStamp($objPHPExcel, $sheet, $stampSetting, $row);

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

    private function clonePage(&$objPHPExcel, &$sheet, $orgPageBlock, $confHeader, $row)
    {
        $this->pageNo++;
        if ($this->pageNo%2) $sheet->setBreak('A' . ($row - 1), Worksheet::BREAK_ROW);

        // clone page-template
        $toBlock = [
            'col' => $orgPageBlock['start']['col'],
            'row' => $row
        ];
        if (isset($orgPageBlock['rowHeight'])) {
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

        $this->cloneBlockFormat($objPHPExcel, $orgPageBlock, $toBlock, $this->orgPageBlock[$this->activeForm]);
    }

    private function fillHeader(&$sheet, $confHeader, $row, $pageData)
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

    private function cloneStamp(&$objPHPExcel, &$sheet, $orgSignBlock, $pageStartAt)
    {
        $toBlock = [
            'col' => $orgSignBlock['toCell']['col'],
            'row' => $pageStartAt + $orgSignBlock['toCell']['row'] - 1,
        ];

        $toColIndex = Coordinate::columnIndexFromString($orgSignBlock['toCell']['col']);
        $orgColIndex = Coordinate::columnIndexFromString($orgSignBlock['start']['col']);

        if (isset($orgSignBlock['mergeCells'])) {
            foreach ($orgSignBlock['mergeCells'] as $setting) {
                $cIdx = $toColIndex + (Coordinate::columnIndexFromString($setting['col']) - $orgColIndex);
                $c = Coordinate::stringFromColumnIndex($cIdx);
                $r = $pageStartAt + $setting['row'] - 1;
                $range = $c . $r . ':'
                    . Coordinate::stringFromColumnIndex(
                        $cIdx + ($setting['w'] - 1)
                    )
                    . ($r + ($setting['h'] - 1));
                $sheet->mergeCells($range, $sheet::MERGE_CELL_CONTENT_EMPTY);
            }
        }
        $this->cloneBlockFormat($objPHPExcel, $orgSignBlock, $toBlock, $this->orgStampBlock[$this->activeForm]);
    }

}
