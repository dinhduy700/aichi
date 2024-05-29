<?php


namespace App\Helpers\Excel;


use App\Helpers\XlsExportPageSummary;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class XlsRyoshusho extends XlsExportPageSummary
{
    protected $config = null;
    protected $orgSummaryBlock = null;
    protected $orgFirstBlock = null;
    protected $orgMiddleBlock = null;

    protected function dataGroupBy($data)
    {
        return $data->groupBy(function ($item, $key) {
            return implode('-', [
                $item->haitatu_dt,
                $item->hachaku_cd,
                $item->seikyu_cd,
            ]);
        });
    }

    protected function renderSummary($base, &$objPHPExcel, &$sheet, &$row, $groupData, $lastPageData, $pageStartAt)
    {
        $orgPageBlock = $base['template']['page'];
        $pageH = $orgPageBlock['end']['row'] - $orgPageBlock['start']['row'] + 1;
        $rowSummary = $pageStartAt + $pageH;

        $orgBlock = $base['template']['summary'];
        $toBlock = [
            'col' => $orgBlock['start']['col'],
            'row' => $rowSummary
        ];
        if (isset($orgBlock['mergeCells'])) {
            foreach ($orgBlock['mergeCells'] as $setting) {
                $this->mergeCells($sheet, $rowSummary, $setting);
            }
        }

        $this->cloneBlockFormat($objPHPExcel, $orgBlock, $toBlock, $this->orgSummaryBlock);

        foreach ($base['summary'] as $setting) {
            $r = ($rowSummary + $setting['row'] - 1);
            $v = $this->getValueOther($setting, $groupData);
            $sheet = $this->setCellValue(
                $sheet, $setting['col'] . $r, $v,
                data_get($setting, 'type', null)
            );
        }

        $row += $orgBlock['end']['row'] - $orgBlock['start']['row'] + 1;
    }

    protected function addDataToExcel($objPHPExcel, $config, $data)
    {
        $base = $config['base'];
        $this->config = $config;
        $this->orgPageBlock = $this->getFormatBlock($objPHPExcel, $base['template']['page']);
        $this->orgFirstBlock = $this->getFormatBlock($objPHPExcel, $base['block']);
        $this->orgMiddleBlock = $this->getFormatBlock($objPHPExcel, $base['middle-block']);
        $this->orgSummaryBlock = $this->getFormatBlock($objPHPExcel, $base['template']['summary']);
        return parent::addDataToExcel($objPHPExcel, $config, $data);
    }

    protected function setData($objPHPExcel, $config, $data, $startAt = 1, $sheetActive = 0)
    {
        try {
            $sheet = $objPHPExcel->getActiveSheet();//dd($config);
            $orgBlock = $config['base']['block'];
            $cntRow = $orgBlock['end']['row'] - $orgBlock['start']['row'] + 1;

            $startAt--;

            $i=0;
            foreach ($data as $item) {
                $row = $orgBlock['start']['row'] + ($i * $cntRow);
                $toBlock = [
                    'col' => $orgBlock['start']['col'],
                    'row' => $row + $startAt
                ];
                $this->cloneBlockFormat($objPHPExcel,
                    $i ? $this->config['base']['middle-block'] : $orgBlock,
                    $toBlock,
                    $i ? $this->orgMiddleBlock : $this->orgFirstBlock
                );

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
}
