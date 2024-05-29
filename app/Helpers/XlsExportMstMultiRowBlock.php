<?php


namespace App\Helpers;


use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class XlsExportMstMultiRowBlock extends XlsExportMst
{
    protected $addDataFuncName = null;
    protected $saveStyles = null;

    public function setAddDataFuncName($fnc)
    {
        $this->addDataFuncName = $fnc;
    }

    protected function addDataToExcel($objPHPExcel, $config, $data)
    {
        $method = $this->addDataFuncName;
        if ($method && method_exists($this, $method)) {
            return $this->$method($objPHPExcel, $config, $data);
        }

        try {
            $sheet = $objPHPExcel->setActiveSheetIndex(0);

            $orgBlock = $config['base']['block'];
            $cntRow = $orgBlock['end']['row'] - $orgBlock['start']['row'] + 1;

            foreach ($data as $i => $item) {
                $row = $orgBlock['start']['row'] + ($i * $cntRow);
                if ($i) {
                    $toBlock = [
                        'col' => $orgBlock['start']['col'],
                        'row' => $row
                    ];
                    $this->copyBlockDataStyle($objPHPExcel, $orgBlock, $toBlock);
                }

                for ($ii = 0; $ii < $cntRow; $ii++) {
                    foreach ($config['block'][$ii] as $c => $setting) {

                        $attr = $setting['field'];
                        $v = data_get($item, $attr, '');
                        $dataType = $setting['type'] ?? null;
                        $pCell = $c . ($row + $ii);

                        if (isset($setting['mergeCells'])) {
                            $sheet->mergeCells(
                                $c . ($row + $ii) . ':'
                                . Coordinate::stringFromColumnIndex(
                                    Coordinate::columnIndexFromString($c) + ($setting['mergeCells']['w'] - 1)
                                )
                                . ($row + $ii + ($setting['mergeCells']['h'] - 1))

                                , $sheet::MERGE_CELL_CONTENT_EMPTY);
                        }

                        $sheet = $this->setCellValue($sheet, $pCell, $v, $dataType);
                    }
                }
            }

            if (isset($config['base']['others'])) {
                foreach ($config['base']['others'] as $setting) {
                    $v = $this->getValueOther($setting);
                    $sheet = $this->setCellValue(
                        $sheet, $setting['col'] . $setting['row'], $v,
                        data_get($setting, 'type', null)
                    );
                }
            }

            return $objPHPExcel;
        } catch (\Exception $exception) {
            $this->logErr($exception);
        }
        return null;
    }

    protected function setData($objPHPExcel, $config, $data, $startAt = 1, $sheetActive = 0)
    {
        try {
            $sheet = $objPHPExcel->setActiveSheetIndex($sheetActive);

            $orgBlock = $config['base']['block'];
            $cntRow = $orgBlock['end']['row'] - $orgBlock['start']['row'] + 1;

            $startAt--;

            $i=0;
            foreach ($data as $item) {
                $row = $orgBlock['start']['row'] + ($i * $cntRow);
                if ($i || ($startAt > $orgBlock['start']['row'])) {
                    $toBlock = [
                        'col' => $orgBlock['start']['col'],
                        'row' => $row + $startAt
                    ];
                    $this->copyBlockDataStyle($objPHPExcel, $orgBlock, $toBlock);
                }

                for ($ii = 0; $ii < $cntRow; $ii++) {
                    foreach ($config['block'][$ii] as $c => $setting) {
                        $v = $this->getValue($setting, $item);
                        $dataType = $setting['type'] ?? null;

                        $r = $row + $ii + $startAt;
                        $pCell = $c . $r;

                        if (isset($setting['mergeCells'])) {
                            $sheet->mergeCells(
                                $c . $r . ':'
                                . Coordinate::stringFromColumnIndex(
                                    Coordinate::columnIndexFromString($c) + ($setting['mergeCells']['w'] - 1)
                                )
                                . ($r + ($setting['mergeCells']['h'] - 1))

                                , $sheet::MERGE_CELL_CONTENT_EMPTY);
                        }

                        $sheet = $this->setCellValue($sheet, $pCell, $v, $dataType);
                    }
                }

                $i++;
            }
            return $objPHPExcel;
        } catch (\Exception $exception) {
            $this->logErr($exception);
        }
        return null;
    }

    protected function copyBlockDataStyle(&$objPHPExcel, $orgBlock, $toBlock)
    {
//        $orgBlock = [
//            'start' => ['col' => 'A', 'row' => 4],
//            'end' => ['col' => 'H', 'row' => 4],
//        ];
        $cntRow = $orgBlock['end']['row'] - $orgBlock['start']['row'] + 1;

        $orgStartColIndex = Coordinate::columnIndexFromString($orgBlock['start']['col']);
        $orgEndColIndex = Coordinate::columnIndexFromString($orgBlock['end']['col']);
        $toStartColIndex = Coordinate::columnIndexFromString($toBlock['col']);

        for ($i = 0; $i < $cntRow; $i++) {
            $j = 0;
            for ($colIndex = $orgStartColIndex; $colIndex <= $orgEndColIndex; $colIndex++, $j++) {
                $orgCol = Coordinate::stringFromColumnIndex($colIndex);
                $toCol = Coordinate::stringFromColumnIndex($toStartColIndex + $j);

                $orgCell = $orgCol . ($orgBlock['start']['row'] + $i);
                $toCell = $toCol . ($toBlock['row'] + $i);

                $objPHPExcel->getActiveSheet()->duplicateStyle(
                    $objPHPExcel->getActiveSheet()->getStyle($orgCell),
                    $toCell . ':' . $toCell
                );
            }
        }
    }

    public function cloneBlock(&$objPHPExcel, $fromBlock, $toBlock)
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

        for ($i = 0; $i < $cntRow; $i++) {
            for ($colIndex = $orgStartColIndex, $j = 0; $colIndex <= $orgEndColIndex; $colIndex++, $j++) {
                $orgCol = Coordinate::stringFromColumnIndex($colIndex);

                $toColIndex = $toStartColIndex + $j;
                $toRow = $toBlock['row'] + $i;
                $toCol = Coordinate::stringFromColumnIndex($toColIndex);

                $orgCell = $orgCol . ($fromBlock['start']['row'] + $i);
                $toCell = $toCol . $toRow;

                $sheet = $objPHPExcel->getActiveSheet();

                $binder = $sheet->getCell($orgCell)->getValue();
                $styleArray = $sheet->getStyle($orgCell)->exportArray();
                $sheet->setCellValueByColumnAndRow($toColIndex, $toRow, $binder);
                $sheet->getStyle($toCell)->applyFromArray($styleArray);
            }
        }


    }

    public function addDataToExcelGroupBy($objPHPExcel, $config, $pages)
    {
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

                $this->cloneBlock($objPHPExcel, $base['header'], ['col' => 'A', 'row' => $row]);
            }
            if (isset($base['header']['others'])) {
                foreach ($base['header']['others'] as $setting) {
                    $cell = $setting['col'] . ($row + $setting['row'] - 1);
                    $v = $this->getValueOther($setting, $page, $cell);
                    $sheet = $this->setCellValue(
                        $sheet, $cell, $v,
                        data_get($setting, 'type', null)
                    );
                }
            }

            $objPHPExcel = $this->setData($objPHPExcel, $config, $page, $row);

            $row = $row
                + ($base['header']['end']['row'] - $base['header']['start']['row'] + 1)
                + ($base['block']['end']['row'] - $base['block']['start']['row'] + 1) * count($page);
        }
        $this->renderPageNoOverTotal($sheet);
        return $objPHPExcel;
    }

    public function getFormatBlock($objPHPExcel, $fromBlock)
    {
        $format = ['binder' => [], 'styleArray' => []];
        $sheet = $objPHPExcel->getActiveSheet();

        $cntRow = $fromBlock['end']['row'] - $fromBlock['start']['row'] + 1;

        $orgStartColIndex = Coordinate::columnIndexFromString($fromBlock['start']['col']);
        $orgEndColIndex = Coordinate::columnIndexFromString($fromBlock['end']['col']);

        for ($i = 0; $i < $cntRow; $i++) {
            for ($colIndex = $orgStartColIndex, $j = 0; $colIndex <= $orgEndColIndex; $colIndex++, $j++) {
                $orgCol = Coordinate::stringFromColumnIndex($colIndex);
                $orgCell = $orgCol . ($fromBlock['start']['row'] + $i);
                $format['binder'][$orgCell] = $sheet->getCell($orgCell)->getValue();
                $format['styleArray'][$orgCell] = $sheet->getStyle($orgCell)->exportArray();
            }
        }
        return $format;
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
