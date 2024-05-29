<?php
namespace App\Helpers\Excel;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use App\Helpers\XlsExportMstMultiRowBlock;

class XlsIdoharigami extends XlsExportMstMultiRowBlock
{
    protected function addDataToExcel($objPHPExcel, $config, $data)
    {
        $base = $config['base'];
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
        $confHeader = $base['header'];
        $sheetIndex = 0;
        foreach($data as $key => $uriage) {
            $row = 1;
            $sheetIndex ++;
            $tempSheet = $objPHPExcel->getSheet(0)->copy();
            $tempSheet->setTitle('移動張紙_'.$sheetIndex);
            $objPHPExcel->addSheet($tempSheet);
            $sheet = $objPHPExcel->setActiveSheetIndex($sheetIndex);
            $this->fillHeader($sheet, $base, $uriage, $row);
        }
        if(!empty($data)) {
            $objPHPExcel->removeSheetByIndex(0);
            $objPHPExcel->setActiveSheetIndex(0);
        }
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
}
