<?php


namespace App\Helpers;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

class CsvExport
{
    protected $config = [
        'csv_export_encoding' => 'SJIS',
        'csv_export_separator' => ',',
    ];

    protected $closed = false;

    /**
     * @var $qb Builder
     */
    public $qb = null;

    public $listGroup = null;

    public $fp = null;
    public $convertEncodingCallBack = null;

    public function __construct($config = null)
    {
        if (!is_null($config)) {
            $this->config = $config;
        }
    }

    public function getConvertEncodingCallback()
    {
        $config = $this->config;

        return function ($value) use ($config) {
            if ($toEncoding = data_get($config, 'csv_export_encoding', null)) {
                return mb_convert_encoding($value, $toEncoding, 'UTF-8');
            }
            return $value;
        };
    }

    public function fopen()
    {
        if (is_null($this->fp) || $this->closed) {
            $this->fp = fopen('php://output', 'w');
        }
    }

    public function fputcsv($row)
    {
        if (is_null($this->convertEncodingCallBack)) {
            $this->convertEncodingCallBack = $this->getConvertEncodingCallback();
        }

        fputcsv(
            $this->fp,
            array_map($this->convertEncodingCallBack, $row),
            $this->config['csv_export_separator']
        );
    }

    public function fclose()
    {
        if (!$this->closed) {
            fclose($this->fp);
            $this->closed = true;
        }
    }

//    public function exportHeader($header)
//    {
//        $this->fopen();
//        $this->fputcsv($header);
//        $this->fclose();
//    }

    public function exportData(\Closure $closure = null)
    {
        if (is_null($this->qb)) {
            throw new \LogicException('query builder not set.');
        }

        $this->fopen();

        $count = 100;
        $this->qb->chunk($count, function (Collection $rows) use ($closure) {
            foreach ($rows as $row) {
                $row = $row instanceof Model ? $row->toArray() : (array)$row;
                if (!is_null($closure)) {
                    $row = $closure($row);
                }
                $this->fputcsv($row);
            }
        });

        $this->fclose();
    }

    public function exportDataCollection(\Closure $closure = null, \Closure $closureGroup = null)
    {
        if (is_null($this->listGroup)) {
            throw new \LogicException('query builder not set.');
        }
        $this->fopen();
        $this->listGroup->each(function ($rows) use ($closure, $closureGroup) {
            $rowGroup = $closureGroup($rows);
            $this->fputcsv($rowGroup);
            foreach ($rows as $key => $row) {
                if (!is_null($closure)) {
                    $row = $closure($rows, $key, $row);
                }
                if (!empty($row)) {
                    $this->fputcsv($row);
                }
            }
        });

        $this->fclose();
    }
}
