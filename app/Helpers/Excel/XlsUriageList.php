<?php


namespace App\Helpers\Excel;


use App\Helpers\XlsExportMstMultiRowBlock;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class XlsUriageList extends XlsExportMstMultiRowBlock
{
    protected $orgPageBlock = [];

    public function addDataToExcel($objPHPExcel, $config, $pages)
    {
        $this->orgPageBlock = $this->getFormatBlock($objPHPExcel, $config['base']['header']);
        $row = 1;
        $base = $config['base'];
        $sheet = $objPHPExcel->getActiveSheet();
        $this->pageNo = 0;
        $headerRowHeights = [];
        for ($i = $base['header']['start']['row']; $i <= $base['header']['end']['row']; $i++) {
            $headerRowHeights[$i] = $sheet->getRowDimension($i)->getRowHeight();
        }
        foreach ($pages as $bumon_cd => $page) {
            $this->pageNo++;
            $this->cloneHeader($objPHPExcel, $sheet, $base, $headerRowHeights, $row, $page);

            $objPHPExcel = $this->setData($objPHPExcel, $config, $page, $row);

            $row = $row
                + ($base['header']['end']['row'] - $base['header']['start']['row'] + 1)
                + ($base['block']['end']['row'] - $base['block']['start']['row'] + 1) * count($page);
        }
        $this->renderPageNoOverTotal($sheet);
        return $objPHPExcel;
    }

    public function cloneHeader(&$objPHPExcel, &$sheet, $base, $headerRowHeights, $row, $pageData)
    {
        if ($row > 1) {
            $sheet->setBreak('A' . ($row - 1), Worksheet::BREAK_ROW);

            for ($i = $base['header']['start']['row']; $i <= $base['header']['end']['row']; $i++) {
                $r = $row + $i - 1;
                $sheet->getRowDimension($r)->setRowHeight($headerRowHeights[$i]);
            }

            if (isset($base['header']['mergeCells'])) {
                foreach ($base['header']['mergeCells'] as $setting) {
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

            $this->cloneBlockFormat($objPHPExcel, $base['header'], ['col' => 'A', 'row' => $row], $this->orgPageBlock);
        }
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
}
