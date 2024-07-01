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
        dd($dataGroupBy->toArray());
        $perPage        = $orgPageBlock['size'];
        $this->orgPageBlock = $this->getFormatBlock($objPHPExcel, $config['base']['template']['page']);
        $listZaikoHokanryoByNinusiCd = $config['listZaikoHokanryoByNinusiCd'];

        for ($i=1; $i <= $config['midasisitei']; $i++) {
            foreach ($dataGroupBy as $key => $items) {
                $base           = $config['base'];
                $ninusiCd = $dataGroupBy[$key][0]['ninusi_cd'];
                
                $isExistZaikoHokanryo = $this->isExistZaikoHokanryo($listZaikoHokanryoByNinusiCd, $ninusiCd);

                if ($isExistZaikoHokanryo) {
                    $perPage = 21;
                } else {
                    unset($base['summary']['seki_su']);
                    unset($base['summary']['nyuko_su']);
                    unset($base['summary']['syuko_su']);
                }

               
                $chunks = $items->chunk($perPage);

                $this->pageNo = 0;
                $this->totalGroup = count($chunks);
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

                    if ($i > 1 || request()->input('exp.midasisitei') == 2) {
                        $baseTmp['header']['others'][] = ['col' => 'J', 'row' => '2', 'value' => function() use($base) {return $base['template']['title'];}];
                    }

                    $this->clonePage($objPHPExcel, $sheet, $orgPageBlock, $confHeader, $row);

                    $this->fillHeader($sheet, $baseTmp, $chunk, $row, $chunks);

                    if (!empty(data_get($chunk->first(), 't_uriage__ninusi_cd'))) {
                        $this->setData($objPHPExcel, $config, $chunk, $row);
                    } else {
                        $row = $row - 2;
                    }

                    if (!$isExistZaikoHokanryo) {
                        $rowSummary = ($row + $headerH + count($chunk) * count($config['block']));
                    } else {
                        $rowSekiSu = ($row + $headerH + count($chunk) * count($config['block']));
                        $rowNyukoSu = $rowSekiSu + 2;
                        $rowSyukoSu = $rowSekiSu + 4;
                        $rowSummary = $rowSekiSu + 6;
                    
                        // seki_su
                        $orgBlock = $base['template']['summary']['seki_su'];
                        $toBlock = [
                                'col' => $orgBlock['start']['col'],
                                'row' => $rowSekiSu
                            ];
                        $this->cloneBlock($objPHPExcel, $orgBlock, $toBlock, [
                            'J' => [
                                'alignment' => [
                                    'horizontal' => 'center',
                                    'vertical' => 'top'
                                ]
                            ]
                        ]);
                        $this->setDataSummary('seki_su', $sheet, $base, $rowSekiSu, $chunk);

                        //nyuko_su
                        $orgBlock = $base['template']['summary']['nyuko_su'];
                        $toBlock = [
                                'col' => $orgBlock['start']['col'],
                                'row' => $rowNyukoSu
                            ];
                            
                        $this->cloneBlock($objPHPExcel, $orgBlock, $toBlock, [
                            'J' => [
                                'alignment' => [
                                    'horizontal' => 'center',
                                    'vertical' => 'top'
                                ]
                            ]
                        ]);
                        $this->setDataSummary('nyuko_su', $sheet, $base, $rowNyukoSu, $chunk);
                    
                        // syuko_su
                        $orgBlock = $base['template']['summary']['syuko_su'];
                        $toBlock = [
                                'col' => $orgBlock['start']['col'],
                                'row' => $rowSyukoSu
                            ];
                            
                        $this->cloneBlock($objPHPExcel, $orgBlock, $toBlock, [
                            'J' => [
                                'alignment' => [
                                    'horizontal' => 'center',
                                    'vertical' => 'top'
                                ]
                            ]
                        ]);
                        $this->setDataSummary('syuko_su', $sheet, $base, $rowSyukoSu, $chunk);
                    }

                    // summary
                    $orgBlock = $base['template']['summary']['total'];
                    $toBlock = [
                        'col' => $orgBlock['start']['col'],
                        'row' => $rowSummary
                    ];

                    $this->cloneBlock($objPHPExcel, $orgBlock, $toBlock);
                    $this->setDataSummary('total', $sheet, $base, $rowSummary, $chunk);

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
        // $this->cloneBlock($objPHPExcel, $orgPageBlock, $toBlock);
        $this->cloneBlockFormat($objPHPExcel, $orgPageBlock, $toBlock, $this->orgPageBlock);
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

    public function cloneBlock(&$objPHPExcel, $fromBlock, $toBlock, $styleApply = [])
    {
        // copy Value
        $cntRow = $fromBlock['end']['row'] - $fromBlock['start']['row'] + 1;

        $orgStartColIndex = Coordinate::columnIndexFromString($fromBlock['start']['col']);
        $orgEndColIndex = Coordinate::columnIndexFromString($fromBlock['end']['col']);
        $toStartColIndex = Coordinate::columnIndexFromString($toBlock['col']);

        for ($i = 0; $i < $cntRow; $i++) {
            $sheet              = $objPHPExcel->getActiveSheet();
            $toRow              = $toBlock['row'] + $i;
            $headerRowHeight    = $sheet->getRowDimension($i+1)->getRowHeight();
            $sheet->getRowDimension($toRow)->setRowHeight($headerRowHeight);

            for ($colIndex = $orgStartColIndex, $j = 0; $colIndex <= $orgEndColIndex; $colIndex++, $j++) {
                $orgCol = Coordinate::stringFromColumnIndex($colIndex);

                $toColIndex = $toStartColIndex + $j;
                $toRow = $toBlock['row'] + $i;
                $toCol = Coordinate::stringFromColumnIndex($toColIndex);

                $orgCell = $orgCol . ($fromBlock['start']['row'] + $i);
                $toCell = $toCol . $toRow;
                
                $binder = $sheet->getCell($orgCell)->getValue();
                $styleArray = $sheet->getStyle($orgCell)->exportArray();
                $sheet->setCellValueByColumnAndRow($toColIndex, $toRow, $binder);
                
                if(!empty($styleApply)) {
                    if(array_key_exists($toCol, $styleApply)) {
                        $styleArray['alignment']['horizontal'] = $styleApply[$toCol]['alignment']['horizontal'];
                        $styleArray['alignment']['vertical'] = $styleApply[$toCol]['alignment']['vertical'];
                    }
                }
                $sheet->getStyle($toCell)->applyFromArray($styleArray);
            }
        }
    }

    public function setDataSummary($key, &$sheet, $base, $row, $chunk)
    {
        if (isset($base['summary'][$key])) {
            foreach ($base['summary'][$key] as $setting) {
                if (isset($setting['mergeCells'])) {
                    $c = $setting['col'];
                    $r = $row + $setting['row'] - 1;
                    $range = $c . $r . ':'
                        . Coordinate::stringFromColumnIndex(
                            Coordinate::columnIndexFromString($c) + ($setting['mergeCells']['w'] - 1)
                        )
                        . ($r + ($setting['mergeCells']['h'] - 1));
                    $sheet->mergeCells($range, $sheet::MERGE_CELL_CONTENT_EMPTY);
                }
    
                $v = $this->getValueOther($setting, $chunk);
                $sheet = $this->setCellValue(
                    $sheet, $setting['col'] . ($row + $setting['row'] - 1), $v,
                    data_get($setting, 'type', null)
                );
            }
        }
    }

    public function isExistZaikoHokanryo($listZaikoHokanryoByNinusiCd, $ninusiCd)
    {
        if ($listZaikoHokanryoByNinusiCd->has($ninusiCd) &&
               data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.seki_su') != null
            && data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.seki_su') != null
            && data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.hokan_kin') != null
            && data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.nyuko_su') != null
            && data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.nyuko_kin') != null
            && data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.syuko_su') != null
            && data_get($listZaikoHokanryoByNinusiCd, $ninusiCd . '.syuko_kin') != null
        ) {
            return true;
        } else {
            return false;
        }
    }

    public function cloneBlockFormat(&$objPHPExcel, $fromBlock, $toBlock, $blockFormat)
    {
//        //A1:BJ7
//        $fromBlock = [
//            'start' => ['col' => 'A', 'row' => 1],
//            'end' => ['col' => 'BJ', 'row' => 7],
//        ];
//
//        $row = 67;
//        $toBlock = ['col' => 'A', 'row' => $row];

        // copy Value
        $cntRow = $fromBlock['end']['row'] - $fromBlock['start']['row'] + 1;

        $orgStartColIndex = Coordinate::columnIndexFromString($fromBlock['start']['col']);
        $orgEndColIndex = Coordinate::columnIndexFromString($fromBlock['end']['col']);
        $toStartColIndex = Coordinate::columnIndexFromString($toBlock['col']);

        $sheet = $objPHPExcel->getActiveSheet();

        for ($i = 0; $i < $cntRow; $i++) {
            $sheet              = $objPHPExcel->getActiveSheet();
            $toRow              = $toBlock['row'] + $i;
            $headerRowHeight    = $sheet->getRowDimension($i+1)->getRowHeight();
            $sheet->getRowDimension($toRow)->setRowHeight($headerRowHeight);
            for ($colIndex = $orgStartColIndex, $j = 0; $colIndex <= $orgEndColIndex; $colIndex++, $j++) {
                $orgCol = Coordinate::stringFromColumnIndex($colIndex);

                $toColIndex = $toStartColIndex + $j;
                $toRow = $toBlock['row'] + $i;
                $toCol = Coordinate::stringFromColumnIndex($toColIndex);

                $orgCell = $orgCol . ($fromBlock['start']['row'] + $i);
                $toCell = $toCol . $toRow;

                $binder = $blockFormat['binder'][$orgCell] ?? '';
                $styleArray = $blockFormat['styleArray'][$orgCell] ?? [];
                $sheet->setCellValueByColumnAndRow($toColIndex, $toRow, $binder);
                $sheet->getStyle($toCell)->applyFromArray($styleArray);
            }
        }
    }
}
