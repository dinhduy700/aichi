<?php


namespace App\Helpers\Excel;


use App\Helpers\XlsExportPageSummary;

class XlsSeikyuList extends XlsExportPageSummary
{
    protected function dataGroupBy($data)
    {
        return $data->groupBy(function ($item, $key) {
            return implode('-', [
                $item->seikyu_sime_dt,
            ]);
        });
    }
}
