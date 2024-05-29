<?php


namespace App\Helpers\Excel;

use App\Helpers\XlsExportMstMultiRowBlock;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class XlsSagyoList extends XlsExportMstMultiRowBlock
{
    protected $orgPageBlock = [];

    public function addDataToExcel($objPHPExcel, $config, $dataGrouped)
    {
        $method = $this->addDataFuncName;
        if ($method && method_exists($this, $method)) {
            return $this->$method($objPHPExcel, $config, $dataGrouped);
        }
        
        $base           = $config['base'];
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet          = $objPHPExcel->getActiveSheet();
        $headerH        = $base['header']['end']['row'] - $base['header']['start']['row'] + 1;
        $orgPageBlock   = $base['template']['page'];
        $rowSummary     = 0;
        $confHeader     = $base['header'];
        $row            = 1 + $base['template']['height'];
        
        $perPage  = $orgPageBlock['size'];
        $sh = 0;
        $this->orgPageBlock = $this->getFormatBlock($objPHPExcel, $config['base']['template']['page']);

        foreach ($dataGrouped as $key => $items) {
            $row = 1 + $base['template']['height'];
            $sh++;

            $tempSheet = $objPHPExcel->getSheet(0)->copy();
            $tempSheet->setTitle($key);
            $objPHPExcel->addSheet($tempSheet);

            $sheet = $objPHPExcel->setActiveSheetIndex($sh);

            $chunks = $items->chunk($perPage);
            $this->pageNo   = 0;
           
            foreach ($chunks as $key => $chunk) {
                $this->clonePage($objPHPExcel, $sheet, $orgPageBlock, $confHeader, $row);
                $this->fillHeader($sheet, $base, $chunk, $row);
                $this->setData($objPHPExcel, $config, $chunk, $row, $sh);

                $row += $base['template']['height'];
            }

            $sheet->removeRow(1, $base['template']['height']);
        }

        $objPHPExcel->removeSheetByIndex(0);
        
        $objPHPExcel->setActiveSheetIndex(0);

        return $objPHPExcel;
    }

    public function addDataToExcelGroupByPdf($objPHPExcel, $config, $dataGrouped)
    {
        $base           = $config['base'];
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet          = $objPHPExcel->getActiveSheet();
        $headerH        = $base['header']['end']['row'] - $base['header']['start']['row'] + 1;
        $orgPageBlock   = $base['template']['page'];
        $rowSummary     = 0;
        $confHeader     = $base['header'];
        $row            = 1 + $base['template']['height'];
        
        $perPage  = $orgPageBlock['size'];
        $sh = 0;
        
        $this->orgPageBlock = $this->getFormatBlock($objPHPExcel, $config['base']['template']['page']);

        foreach ($dataGrouped as $key => $items) {
            $collectItems = collect($items);

            $chunks = $collectItems->chunk($perPage);
            $this->pageNo   = 0;
            foreach ($chunks as $key => $chunk) {

                $this->clonePage($objPHPExcel, $sheet, $orgPageBlock, $confHeader, $row);

                $this->fillHeader($sheet, $base, $chunk, $row);

                $this->setData($objPHPExcel, $config, $chunk, $row, $sh);

                $row += $base['template']['height'];
            }

        }
        
        $sheet->removeRow(1, $base['template']['height']);
        
        $objPHPExcel->setActiveSheetIndex(0);

        return $objPHPExcel;
    }

    private function fillHeader(&$sheet, $base, $pageData, $row)
    {
        if (isset($base['header']['others'])) {
            foreach ($base['header']['others'] as $setting) {
                if (isset($setting['height'])) {
                    $sheet->getRowDimension($row + $setting['row'] - 1)->setRowHeight($setting['height']);
                }

                $cell = $setting['col'] . ($row + $setting['row'] - 1);
                $v = $this->getValueOther($setting, $pageData, $cell);

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

        if (isset($orgPageBlock['rowHeight'])) {
            for ($r = $orgPageBlock['start']['row']; $r <= $orgPageBlock['end']['row']; $r++) {
                $sheet->getRowDimension($row + $r - 1)->setRowHeight($orgPageBlock['rowHeight']);
            }
        } else {
            for ($r = $orgPageBlock['start']['row']; $r <= $orgPageBlock['end']['row']; $r++) {
                $sheet->getRowDimension($row + $r - 1)->setRowHeight($sheet->getRowDimension($r)->getRowHeight());
            }
        }

        // clone page-template
        $toBlock = [
            'col' => $orgPageBlock['start']['col'],
            'row' => $row
        ];
        
        $this->cloneBlockFormat($objPHPExcel, $orgPageBlock, $toBlock, $this->orgPageBlock);
    }

    public function getValueOther($setting, $page = null, $cell = null)
    {
        if(isset($setting['constVal'])) {
            return $this->getConstValue($setting['constVal'], $cell);
        }
        if(isset($setting['value'])) {
            if ($setting['value'] instanceof \Closure) {
                return $setting['value']($page);
            } else {
                return $setting['value'];
            }
        }
        return '';
    }
}