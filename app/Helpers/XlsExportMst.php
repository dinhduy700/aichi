<?php


namespace App\Helpers;


use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class XlsExportMst
{
//        $exp = new MstXlsExport();
//        $config = [
//            'base' => [
//                'block' => [
//                    'start' => ['col' => 'A', 'row' => 4],
//                    'end' => ['col' => 'H', 'row' => 4],
//                ],
//                'others' => [
//                    ['col' => 'F', 'row' => 1, 'constVal' => $exp::VAL_CURRENT_TIME],
//                    ['col' => 'A', 'row' => 1, 'value' => \Illuminate\Support\Carbon::now(), 'type' => $exp::DATA_DATETIME],
//                    ['col' => 'B', 'row' => 1, 'value' => '000', 'type' => $exp::DATA_STRING],
//                ]
//            ],
//            'block' => [
//                'A' => ['field' => 'meisyo_kbn'],
//                'B' => ['field' => 'meisyo_cd'],
//                'C' => ['field' => 'kana'],
//                'D' => ['field' => 'meisyo_nm'],
//                'E' => ['field' => 'jyuryo_kansan'],
//                'F' => ['field' => 'sekisai_kbn'],
//                'G' => ['field' => 'kyumin_flg'],
//                'H' => ['field' => 'add_dt', 'type' => $exp::DATA_DATETIME],
//            ],
//        ],

    const VAL_CURRENT_TIME = 'current_time';
    const VAL_PAGE_NO = 'page_no';
    const VAL_PAGE_NO_OVER_TOTAL_PAGE = 'page_no_over_total_page';
    const VAL_PAGE_NO_OVER_TOTAL_PAGE_ON_TOTAL_GROUP = 'page_no_over_total_page_on_total_group';

    const DATA_DATETIME = 'datetime';
    const DATA_STRING = 'string';
    const DATA_CLOSURE = 'closure';

    protected $pageNo = 0;
    protected $totalGroup = 1;
    protected $pageNoCells = [];
    public $pageNoFormat = "%d頁";//sprintf
    public $pageNoOverTotalFormat = "%d/%d頁";//sprintf

    protected function getObjPHPExcel($templatePath)
    {
        $objReader = IOFactory::createReader('Xlsx');
        return $objReader->load($templatePath);
    }

    // Block data 1 row height
    protected function addDataToExcel($objPHPExcel, $config, $data)
    {
        try {
            $sheet = $objPHPExcel->setActiveSheetIndex(0);
            $rowStart = data_get($config, 'base.block.start.row', 1);

            $this->copyRowDataStyle(
                $objPHPExcel,
                data_get($config, 'base.block.start.col'),  //$startCol
                data_get($config, 'base.block.end.col'),    //$endCol
                $rowStart,                                  //$orgRow
                ($rowStart + 1),                            //$startRow
                ($rowStart + count($data) - 1)              //$endRow
            );

            foreach ($data as $i => $item) {
                foreach ($config['block'] as $c => $setting) {
                    $attr = $setting['field'];

                    $v = isset($setting['value'])
                        ? ($setting['value'] instanceof \Closure ? $setting['value']($item) : $setting['value'])
                        : data_get($item, $attr, '');

                    $dataType = $setting['type'] ?? null;
                    $pCell = $c . $rowStart;
                    $sheet = $this->setCellValue($sheet, $pCell, $v, $dataType);
                }
                $rowStart++;
            }

            if (isset($config['base']['others'])) {
                foreach ($config['base']['others'] as $setting) {
                    $cell = $setting['col'] . $setting['row'];
                    $v = $this->getValueOther($setting, null, $cell);
                    $sheet = $this->setCellValue(
                        $sheet, $cell, $v,
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

    public function getConstValue($const, $cell = null)
    {
        switch ($const) {
            case self::VAL_CURRENT_TIME:
                return Date::PHPToExcel(Carbon::now());
            case self::VAL_PAGE_NO:
                return sprintf($this->pageNoFormat, $this->pageNo);
            case self::VAL_PAGE_NO_OVER_TOTAL_PAGE:
                $this->pageNoCells[$cell] = $this->pageNo;
            case self::VAL_PAGE_NO_OVER_TOTAL_PAGE_ON_TOTAL_GROUP:
                return sprintf($this->pageNoOverTotalFormat, $this->pageNo, $this->totalGroup);
            default:
                return '';
        }
        return '';
    }

    protected function setCellValue($sheet, $cell, $value, $dataType = null)
    {
        if (!isset($value) || $value === null || $value === '') return $sheet;
        switch ($dataType) {
            case self::DATA_DATETIME:
                $sheet->setCellValue($cell, Date::PHPToExcel($value));
                break;
            case self::DATA_STRING:
                $sheet->setCellValueExplicit($cell, $value, DataType::TYPE_STRING);
                break;
            default:
                $sheet->setCellValue($cell, $value);
        }
        return $sheet;
    }

    protected function copyRowDataStyle(Spreadsheet $objPHPExcel, $startCol, $endCol, $orgRow, $startRow, $endRow)
    {
        try {
            $startColIndex = Coordinate::columnIndexFromString($startCol);
            $endColIndex = Coordinate::columnIndexFromString($endCol);
        } catch (Exception $e) {
            $this->logErr($e);
            return;
        }

        for ($colIndex = $startColIndex; $colIndex <= $endColIndex; $colIndex++) {
            $col = Coordinate::stringFromColumnIndex($colIndex);
            $objPHPExcel->getActiveSheet()->duplicateStyle(
                $objPHPExcel->getActiveSheet()->getStyle($col.$orgRow),
                $col.$startRow.':'.$col.$endRow
            );
        }
    }

    protected function logErr(\Exception $e)
    {
        Log::error(
            __CLASS__.$e->getMessage()
            ."\n".$e->getTraceAsString()
        );
    }

    public function save($objPHPExcel, $savePath)
    {
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save($savePath);
    }

    public function export($templatePath, $config, $data, $savePath, $customExpFunc = null)
    {
        $objPHPExcel = $this->getObjPHPExcel($templatePath);
        $objPHPExcel = $this->addDataToExcel($objPHPExcel, $config, $data);



        if ($customExpFunc instanceof \Closure) {
            $customExpFunc($objPHPExcel);
        }

        $this->save($objPHPExcel, $savePath);
    }

    public function renderPageNoOverTotal(&$sheet)
    {
        $numPages = count($this->pageNoCells);
        //dd($this->pageNoCells);
        foreach ($this->pageNoCells as $cell => $value) {
            $sheet->setCellValue(
                $cell,
                sprintf($this->pageNoOverTotalFormat, $value, $numPages)
            );
        }
    }

    public function getValueOther($setting, $data = null, $cell = null)
    {
        if(isset($setting['constVal'])) return $this->getConstValue($setting['constVal'], $cell);
        if(isset($setting['value'])) {
            if ($setting['value'] instanceof \Closure) {
                return $setting['value']($data);
            } else {
                return $setting['value'];
            }
        }
        return '';
    }

    public function getValue($setting, $item)
    {
        $v = isset($setting['value'])
            ? ($setting['value'] instanceof \Closure ? $setting['value']($item) : $setting['value'])
            : data_get($item, $setting['field'], '');

        return $v;
    }
}
