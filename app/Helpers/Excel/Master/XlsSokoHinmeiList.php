<?php


namespace App\Helpers\Excel\Master;

use App\Helpers\XlsExportMstMultiRowBlock;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class XlsSokoHinmeiList extends XlsExportMstMultiRowBlock
{
    protected $orgPageBlock = [];

    public function addDataToExcel($objPHPExcel, $config, $data)
    {
        $row    = 1;
        $base   = $config['base'];
        $sheet  = $objPHPExcel->getActiveSheet();
       
        $this->setCellValue($sheet, 
                $base['others'][0]['col'] . $base['others'][0]['row'], 
                $base['others'][0]['value'],
                'datetime');
        
        foreach ($data as $k => $page) {

            $orgBlock = $base['block'];

            $toBlock = [
                'col' => $orgBlock['start']['col'],
                'row' => $row + $orgBlock['end']['row']
            ];
            $this->cloneBlock($objPHPExcel, $orgBlock, $toBlock);

            $objPHPExcel = $this->setData($objPHPExcel, $config, [$page], $row + ($orgBlock['end']['row'] - $orgBlock['start']['row'] + 1));

            $row += $orgBlock['end']['row'] - $orgBlock['start']['row'] + 1;
        }

        $sheet->removeRow($orgBlock['start']['row'], $orgBlock['end']['row'] - $orgBlock['start']['row'] + 1);

        return $objPHPExcel;
    }
}