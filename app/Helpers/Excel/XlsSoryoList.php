<?php


namespace App\Helpers\Excel;

use App\Helpers\XlsExportMstMultiRowBlock;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class XlsSoryoList extends XlsExportMstMultiRowBlock
{
    public function addDataToExcel($objPHPExcel, $config, $data)
    {
        $base           = $config['base'];
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet          = $objPHPExcel->getActiveSheet();
        $headerH        = $base['header']['end']['row'] - $base['header']['start']['row'] + 1;
        $orgPageBlock   = $base['template']['page'];
        $rowSummary     = 0;
        $confHeader     = $base['header'];
        $row            = 1 + $base['template']['height'];

        $dataGroupBy = $data->groupBy(function ($item, $key){
            $colsGroupBy = [
                $item->bumon_cd,
                $item->kisan_dt,
            ];
          
            return implode('__', $colsGroupBy);
        });

        $perPage  = $orgPageBlock['size'];

        foreach ($dataGroupBy as $key => $items) {

            $items          = $items->sortBy('location');

            $chunks         = $items->chunk($perPage);
            $this->pageNo   = 0;
            $this->pageNoCells = [];
            
            foreach ($chunks as $key => $chunk) {
                $prev       = [];
                foreach ($chunk as $k => $item) {
                    if ( isset($prev[data_get($item, 'location')])) {
                        if (in_array(data_get($item, 'ninusi1_nm'), $prev[data_get($item, 'location')])) {
                            data_set($item, 'ninusi1_nm', null);
                        } else {
                            $prev[data_get($item, 'location')][] = data_get($item, 'ninusi1_nm');
                        }

                        data_set($item, 'location', null);
                    } else {
                        $prev[data_get($item, 'location')][] = data_get($item, 'ninusi1_nm');
                    }
                }

                $this->clonePage($objPHPExcel, $sheet, $orgPageBlock, $confHeader, $row);
                $this->fillHeader($sheet, $base, $chunk, $row);
                $this->setData($objPHPExcel, $config, $chunk, $row);

                $row += $base['template']['height'];
            }
            $this->renderPageNoOverTotal($sheet);
        }
        

        $sheet->removeRow(1, $base['template']['height']);

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

        // clone page-template
        $toBlock = [
            'col' => $orgPageBlock['start']['col'],
            'row' => $row
        ];
        $this->cloneBlock($objPHPExcel, $orgPageBlock, $toBlock);
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