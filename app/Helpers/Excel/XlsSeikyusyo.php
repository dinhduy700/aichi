<?php


namespace App\Helpers\Excel;

use App\Helpers\XlsExportMstMultiRowBlock;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class XlsSeikyusyo extends XlsExportMstMultiRowBlock
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
        $dataGroupBy    = $data->groupBy('ninusi_cd');
        $perPage        = $orgPageBlock['size'];


        for ($i=1; $i <= $config['midasisitei']; $i++) {
            foreach ($dataGroupBy as $key => $items) {
                $chunks = $items->chunk($perPage);

                $this->pageNo = 0;
                $arrTmp       = [];
                foreach ($chunks as $key => $chunk) {
                    $baseTmp = $base;

                    // set blank header page > 1
                    if ($key > 0) {
                        $arrTmp  = [];
                        $baseTmp = $base;

                        data_set($baseTmp, 'header.others.konkai_torihiki_kin__top.value', ' ');
                        data_set($baseTmp, 'header.others.zenkai_seikyu_kin.value', ' ');
                        data_set($baseTmp, 'header.others.nyukin_kin.value', ' ');
                        data_set($baseTmp, 'header.others.sousai_kin.value', ' ');
                        data_set($baseTmp, 'header.others.kjrikosi_kin.value', ' ');
                        data_set($baseTmp, 'header.others.kazei_unchin_kin.value', ' ');
                        data_set($baseTmp, 'header.others.zei_kin.value', ' ');
                        data_set($baseTmp, 'header.others.hikazei_kin.value', ' ');
                        data_set($baseTmp, 'header.others.konkai_torihiki_kin.value', ' ');
                    }

                    // set unso_dt
                    foreach ($chunk as $k => $item) {
                        if (in_array($item->unso_dt, $arrTmp)) {
                            $item->unso_dt = null;
                        } else {
                            $arrTmp[] = $item->unso_dt;
                        }
                    }

                    if ($i > 1) {
                        $baseTmp['header']['others'][] = ['col' => 'Z', 'row' => '3', 'value' => function() use($base) {return $base['template']['title'];}];
                    }

                    $this->clonePage($objPHPExcel, $sheet, $orgPageBlock, $confHeader, $row);
                    $this->fillHeader($sheet, $baseTmp, $chunk, $row, $chunks);
                    $this->setData($objPHPExcel, $config, $chunk, $row);
                    $rowSummary = $row + $headerH + count($chunk) * count($config['block']) ;

                    // summary
                    $orgBlock = $base['template']['summary']['total'];
                    $toBlock = [
                        'col' => $orgBlock['start']['col'],
                        'row' => $rowSummary
                    ];

                    $this->cloneBlock($objPHPExcel, $orgBlock, $toBlock);

                    foreach ($base['summary']['total'] as $setting) {
                        if (isset($setting['mergeCells'])) {
                            $c = $setting['col'];
                            $r = $rowSummary + $setting['row'] - 1;
                            $range = $c . $r . ':'
                                . Coordinate::stringFromColumnIndex(
                                    Coordinate::columnIndexFromString($c) + ($setting['mergeCells']['w'] - 1)
                                )
                                . ($r + ($setting['mergeCells']['h'] - 1));
                            $sheet->mergeCells($range, $sheet::MERGE_CELL_CONTENT_EMPTY);
                        }

                        $v = $this->getValueOther($setting, $chunk);
                        $sheet = $this->setCellValue(
                            $sheet, $setting['col'] . ($rowSummary + $setting['row'] - 1), $v,
                            data_get($setting, 'type', null)
                        );
                    }

                    $row += $base['template']['height'];
                }
            }
        }

        $this->renderPageNoOverTotal($sheet);

        $sheet->removeRow(1, $base['template']['height']);

        return $objPHPExcel;
    }


    private function fillHeader(&$sheet, $base, $pageData, $row, $pagesData)
    {
        // set height cho row
        if (isset($base['header']['others'])) {
            foreach ($base['header']['others'] as $setting) {
                if (isset($setting['height'])) {
                    $sheet->getRowDimension($row + $setting['row'] - 1)->setRowHeight($setting['height']);
                }

                $cell = $setting['col'] . ($row + $setting['row'] - 1);
                $v = $this->getValueOther($setting, $pageData, $pagesData, $cell);

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

    public function getValueOther($setting, $page = null, $pages = null, $cell = null)
    {
        if(isset($setting['constVal'])) {
            return $this->getConstValue($setting['constVal'], $cell);
        }
        if(isset($setting['value'])) {
            if ($setting['value'] instanceof \Closure) {
                return $setting['value']($page, $pages);
            } else {
                return $setting['value'];
            }
        }
        return '';
    }
}
