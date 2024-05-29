<?php


namespace App\Helpers\Excel;


use App\Helpers\XlsExportPageSummary;

class XlsNyusyukoNipou extends XlsExportPageSummary
{
    protected function dataGroupBy($data)
    {
        return $data->groupBy(function ($item, $key) {
            return implode('-', [
                $item->bumon_cd,
                $item->ninusi_cd,
            ]);
        });
    }

    protected function addDataToExcel($objPHPExcel, $config, $data)
    {
        $this->orgPageBlock = $this->getFormatBlock($objPHPExcel, $config['base']['template']['page']);
        return parent::addDataToExcel($objPHPExcel, $config, $data);
    }
}
