<?php

namespace App\Http\Controllers\Nyusyuko;

use App\Helpers\CsvExport;
use App\Helpers\Excel\XlsNyusyukoNipou;
use App\Helpers\Excel\XlsZaikoHoukokuSyo;
use App\Helpers\Excel\XlsZaikoList;
use App\Helpers\Formatter;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Nyusyuko\NyusyukoNipouRepository;
use App\Http\Repositories\Nyusyuko\ZaikoListRepository;
use App\Http\Repositories\Nyusyuko\ZaikoHoukokuSyoRepository;
use App\Http\Requests\NyusyukoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class NyusyukoExportController extends Controller
{
    const ZAIKO_HOUKOKU_SYO_EXPORT_PG_NM = 'zaiko_houkoku_syo_export';
    const ZAIKO_HOUKOKU_SYO_EXPORT_FUNCTON_INIT_SEARCH = 'init_checkbox';
    protected $zaikoHoukokuSyoRepository;
    protected $nyusyukoNipouRepository;
    protected $zaikoListRepository;

    public function __construct(
        ZaikoHoukokuSyoRepository $zaikoHoukokuSyoRepository,
        NyusyukoNipouRepository $nyusyukoNipouRepository,
        ZaikoListRepository $zaikoListRepository
    )
    {
        $this->zaikoHoukokuSyoRepository = $zaikoHoukokuSyoRepository;
        $this->nyusyukoNipouRepository = $nyusyukoNipouRepository;
        $this->zaikoListRepository = $zaikoListRepository;
    }

    // 16.入出庫日報
    public function nyusyukoNipouFilterForm()
    {
        $optionOpts = $this->nyusyukoNipouRepository->getOptionOpts();
        $initValues = $this->nyusyukoNipouRepository->handleInitValues('get');
        return view('nyusyuko.nyusyuko-nipou-filter-form', compact('optionOpts', 'initValues'));
    }

    public function nyusyukoNipouFilterValidate(NyusyukoRequest $request)
    {
        $this->nyusyukoNipouRepository->handleInitValues('set', $request);
        return responseSendForward($this->nyusyukoNipouRepository->applyRequestToBuilder($request));
    }

    public function  nyusyukoNipouExcel(Request $request)
    {
        $outDir = storage_path('app/download');
        $fileNm = date('YmdHis') . '_t_nyusyuko_nipou.xlsx';
        return response()->download(
            $this->nyusyukoNipouExportExcel($request, $outDir, $fileNm),
            getPageTitle('nyusyuko.nipou.nipouFilterForm') . '_' . date('YmdHis') . '.xlsx'
        )->deleteFileAfterSend(true);
    }

    public function nyusyukoNipouPdf(Request $request)
    {
        $outDir = storage_path('app/download');
        $fileNm = date('YmdHis') . '_t_nyusyuko_nipou.xlsx';
        $xlsPath = $this->nyusyukoNipouExportExcel($request, $outDir, $fileNm);
        cnvXlsToPdf($xlsPath, $outDir);
        File::delete($xlsPath);
        $pdfPath = $outDir . DIRECTORY_SEPARATOR .  str_replace('.xlsx', '.pdf', $fileNm);
        $fileName = getPageTitle('nyusyuko.nipou.nipouFilterForm') . '_' . date('YmdHis') . '.pdf';
        return response()->file($pdfPath, ['Content-Disposition' => 'filename="' . $fileName . '"'])
            ->deleteFileAfterSend(true);
    }

    public function  nyusyukoNipouCsv(Request $request)
    {
        $repo = $this->nyusyukoNipouRepository;
        $savePath = storage_path(date('YmdHis') . '.csv');
        $exp = new CsvExport();
        $exp->fp = fopen($savePath, 'w');
        $mapping = require(app_path('Helpers/Csv/config/t_nyusyuko_nipou.php'));
        $qb = $repo->applyRequestToBuilder($request);;
        $qb->orderBy('t_nyusyuko_head.bumon_cd');
        $qb->orderBy('t_nyusyuko_head.ninusi_cd');
        $qb->orderBy('t_nyusyuko_meisai.hinmei_cd');
        $exp->qb = $qb;

        $options = [
            'kijyun_dt' => data_get($request->all(), 'exp.kijyun_dt', null)
        ];

        $exportHeader = in_array(
            $repo::EXP_PRINT_CSV_HEADER,
            data_get($request->all(), 'exp.option', [])
        );
        if ($exportHeader) $exp->fputcsv($mapping);//export header

        $exp->exportData(function ($row) use ($options, $mapping) {
            $expRow = [];

            foreach (array_keys($mapping) as $k) {
                $expRow[$k] = data_get($row, $k, '');
            }
            $expRow['kijyun_dt'] = data_get($options, 'kijyun_dt');
            return $expRow;
        });
        $exp->fclose();

        return response()->download(
            $savePath,
            getPageTitle('nyusyuko.nipou.nipouFilterForm') . '_' . date('YmdHis') . '.csv',
            ['Content-Type: text/csv']
        )->deleteFileAfterSend(true);
    }

    private function nyusyukoNipouExportExcel(Request $request, $outDir, $filename)
    {
        $exporter = new XlsNyusyukoNipou();
        $qb = $this->nyusyukoNipouRepository->applyRequestToBuilder($request);
        $data = $qb->get();
        //dd($data);

        $config = require(app_path('Helpers/Excel/config/t_nyusyuko_nipou.php'));

        $config['base']['header']['others']['kijyun_dt']['value'] = data_get($request->all(), 'exp.kijyun_dt');

        // Check if the directory exists, if not, create it
        if (!File::exists($outDir)) {
            File::makeDirectory($outDir, 0755, true, true);
        }
        $savePath = $outDir . DIRECTORY_SEPARATOR . $filename;

        $exporter->export(
            app_path('Helpers/Excel/template/t_nyusyuko__nipou.xlsx'),//template
            $config,
            $data,
            $savePath
        );

        return $savePath;
    }

    // 17.在庫報告書
    public function zaikoHoukokuSyoFilterForm()
    {
        $optionOpts = $this->zaikoHoukokuSyoRepository->getOptionOpts();
        $initSearch = $init = DB::table('m_user_pg_function')
            ->where('user_cd', \Auth::id())
            ->where('pg_nm', self::ZAIKO_HOUKOKU_SYO_EXPORT_PG_NM)
            ->where('function', self::ZAIKO_HOUKOKU_SYO_EXPORT_FUNCTON_INIT_SEARCH)
            ->first();
        $defaultKijyunDt = DB::table('t_zaiko_kijyun')->max('kijyun_dt');
        return view('nyusyuko.zaiko-houkoku-syo-filter-form', compact('optionOpts', 'initSearch', 'defaultKijyunDt'));
    }

    public function  zaikoHoukokuSyoFilterValidate(NyusyukoRequest $request)
    {
        $init = DB::table('m_user_pg_function')
            ->where('user_cd', \Auth::id())
            ->where('pg_nm', self::ZAIKO_HOUKOKU_SYO_EXPORT_PG_NM)
            ->where('function', self::ZAIKO_HOUKOKU_SYO_EXPORT_FUNCTON_INIT_SEARCH)
            ->first();
        $all  = $request->all();


        $data = [];
        for ($i = 1; $i <= 100; $i++) {
            $data['choice'.$i.'_nm'] = null;
            $data['choice'.$i.'_bool'] = null;
            $data['choice'.$i.'_dt'] = null;
            $data['choice'.$i.'_char'] = null;
            $data['choice'.$i.'_num'] = null;
        }

        if($request->filled('exp') && $request->filled('exp.option')) {
            foreach ($request->get('exp')['option'] as $key => $value) {
                if($value == 1) {
                    $data['choice1_nm'] = 'CSV見出し出力';
                    $data['choice1_char'] = 1;
                }
                if($value == 2) {
                    $data['choice2_nm'] = 'ロット別に出力';
                    $data['choice2_char'] = 2;
                }
            }
        }

        if(!empty($init)) {
            DB::table('m_user_pg_function')
                ->where('user_cd', \Auth::id())
                ->where('pg_nm', self::ZAIKO_HOUKOKU_SYO_EXPORT_PG_NM)
                ->where('function', self::ZAIKO_HOUKOKU_SYO_EXPORT_FUNCTON_INIT_SEARCH)
                ->update($data);
        } else {
            $data['user_cd'] = \Auth::id();
            $data['pg_nm'] = self::ZAIKO_HOUKOKU_SYO_EXPORT_PG_NM;
            $data['function'] = self ::ZAIKO_HOUKOKU_SYO_EXPORT_FUNCTON_INIT_SEARCH;
            DB::table('m_user_pg_function')
                ->insert($data);
        }
        return responseSendForward($this->zaikoHoukokuSyoRepository->applyRequestToBuilder($request));
    }

    public function zaikoHoukokuSyoExcel(Request $request)
    {
        $outDir = storage_path('app/download');
        $filename =  date('YmdHis') . 'zaiko_houkoku_syo.xlsx';
        $savePath = $this->__createZaikoHoukokuSyoExcel($request, $outDir, $filename);
        return response()->download($savePath, '在庫報告書_'.date('YmdHis').'.xlsx')
            ->deleteFileAfterSend(true);
    }

    public function  zaikoHoukokuSyoCsv(Request $request)
    {
        $repo = $this->zaikoHoukokuSyoRepository;
        $savePath = storage_path(date('YmdHis') . '.csv');
        $exp = new CsvExport();
        $exp->fp = fopen($savePath, 'w');
        $mapping = require(app_path('Helpers/Csv/config/zaiko_houkoku_syo.php'));

        $exp->qb = $repo->applyRequestToBuilderCsv($request)
            ->orderBy('ninusi_cd')
            ->orderBy('bumon_cd')
            ->orderBy('hinmei_cd');

        if (!in_array($repo::EXP_PRINT_BY_LOT, $request->input('exp.option', []))) {
            $mapByReq = [];
            foreach ($mapping as $k => $v) {
                if (in_array($k, ['lot1', 'lot2', 'lot3'])) {
                    $mapByReq["blank_{$k}"] = '';
                } else $mapByReq[$k] = $v;
            }
            $mapping = $mapByReq;
        }

        if($request->filled('exp.option') && in_array('1', $request->input('exp.option') ) ) {
            $exp->fputcsv($mapping);
        }

        $exp->exportData(function ($row) use ($mapping, $request) {
            $expRow = [];
            foreach (array_keys($mapping) as $k) {
                switch ($k) {
                    case '会社名': $expRow[$k] = '有限会社　愛知高速運輸'; break;
                    case '基準日':
                        $expRow[$k] = !empty($request->exp['kijyun_dt'])
                            ? Formatter::date($request->exp['kijyun_dt'])
                            : '';
                        break;
                    case '自社印字区分': $expRow[$k] = '0'; break;
                    default:
                        $expRow[$k] = data_get($row, $k, '');
                }
            }
            return $expRow;
        });
        $exp->fclose();
        return response()->download($savePath, getPageTitle('nyusyuko.exp.zaikoFilterForm').'.csv', ['Content-Type: text/csv'])
            ->deleteFileAfterSend(true);
    }

    public function zaikoHoukokuSyoPdf(Request $request) {

        $outDir = storage_path('app/download');
        $filename =  date('YmdHis') . 'zaiko_houkoku_syo.xlsx';
        $savePath = $this->__createZaikoHoukokuSyoExcel($request, $outDir, $filename);

        $pdfPath = $outDir . DIRECTORY_SEPARATOR .  str_replace('.xlsx', '.pdf', $filename);

        cnvXlsToPdf($savePath, $outDir);

        $response = response()->file($pdfPath, ['Content-Disposition' => 'filename="' . str_replace('.xlsx', '.pdf', $filename) . '"']);

        if (File::exists($savePath)) {
            File::delete($savePath);
        }
        return $response->deleteFileAfterSend();
    }

    private function __createZaikoHoukokuSyoExcel($request, $outDir, $filename)
    {
        $repo = $this->zaikoHoukokuSyoRepository;
        $exporter = new XlsZaikoHoukokuSyo();
        $qb = $repo->applyRequestToBuilder($request);
        $data = $qb->orderBy('ninusi_cd')->get();
        // dd($data);
        $config = require(app_path('Helpers/Excel/config/zaiko_houkoku_syo.php'));

        if(!$request->filled('exp.option') || !in_array($repo::EXP_PRINT_BY_LOT, $request->input('exp.option')) )
        {
            $fnc = $config['hide_lot'];
            $fnc($config);
        }
        // Specify the directory path
        $directoryPath = $outDir;
        // Check if the directory exists, if not, create it
        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true, true);
        }
        $savePath = $directoryPath . DIRECTORY_SEPARATOR . $filename;

        $exporter->export(
            app_path('Helpers/Excel/template/zaiko_houkoku_syo.xlsx'),//template
            $config,
            $data,
            $savePath
        );
        return $savePath;
    }


    // 18.在庫一覧表
    public function zaikoListFilterForm(Request $request)
    {
        $optionOpts = $this->zaikoListRepository->getOptionOpts();
        $optionHinmes = $this->zaikoListRepository->getOptionHinmei();
        $initValue = $request->except('_token');
        if ($request->filled('search_kensaku_zyoken')) {
            switch ($request['search_kensaku_zyoken']) {
                case '1':
                    $initValue['search_kensaku_zyoken'] = 'start';
                    break;
                case '2':
                    $initValue['search_kensaku_zyoken'] = 'contain';
                    break;
            }
        }
        return view('nyusyuko.zaikoList-filter-form', compact('optionOpts', 'optionHinmes', 'initValue'));
    }

    public function zaikoListFilterValidate(NyusyukoRequest $request)
    {
        return responseSendForward($this->zaikoListRepository->applyRequestToBuilder($request));
    }

    public function zaikoListCsv(Request $request)
    {
        $repo = $this->zaikoListRepository;
        $savePath = storage_path(date('YmdHis') . '.csv');
        $exp = new CsvExport();
        $exp->fp = fopen($savePath, 'w');
        $mapping = require(app_path('Helpers/Csv/config/t_nyusyuko_zaiko_list.php'));

        if (!in_array($repo::EXP_PRINT_BY_LOT, data_get($request->all(), 'exp.option', []))) {
            $mapByReq = [];
            foreach ($mapping as $k => $v) {
                if (in_array($k, ['lot1', 'lot2', 'lot3'])) {
                    $mapByReq["blank_{$k}"] = '';
                } else $mapByReq[$k] = $v;
            }
            $mapping = $mapByReq;
        }
        if (!in_array($repo::EXP_PRINT_WITH_AMOUNT, data_get($request->all(), 'exp.option', []))) {
            $mapByReq = [];
            foreach ($mapping as $k => $v) {
                if ($k == 'kingaku') {
                    $mapByReq['blank_kingaku'] = '';
                } else $mapByReq[$k] = $v;
            }
            $mapping = $mapByReq;
        }

        $exp->qb = $repo->applyRequestToBuilder($request)
            ->orderBy('t_zaiko.bumon_cd')
            ->orderBy('t_zaiko.soko_cd')
            ->orderBy('t_zaiko.ninusi_cd')
            ->orderBy('t_zaiko.hinmei_cd')
        ;
        $options = [
            'kijyun_dt' => data_get($request->all(), 'exp.kijyun_dt', null)
        ];

        if ($request->filled('exp.option') && in_array($repo::EXP_PRINT_CSV_HEADER, $request->input('exp.option'))) {
            $exp->fputcsv($mapping);
        }

        $exp->exportData(function ($row) use ($options, $mapping, $request) {
            $expRow = [];
            foreach (array_keys($mapping) as $k) {
                switch ($k) {
                    case 'kijyun_dt': $expRow[$k] = Formatter::date(data_get($options, 'kijyun_dt')); break;
                    default: $expRow[$k] = data_get($row, $k, '');
                }
            }
            return $expRow;
        });
        $exp->fclose();
        return response()->download($savePath, getPageTitle('nyusyuko.zaikoList.zaikoListFilterForm') . '_' . date('YmdHis') . '.csv', ['Content-Type: text/cvs'])
            ->deleteFileAfterSend(true);
    }

    public function zaikoListExcel(Request $request)
    {
        $outDir = storage_path('app/download');
        $filename = date('YmdHis') . '_zaikoList.xlsx';
        $savePath = $this->zaikoListExportExcel($request, $outDir, $filename);
        return response()->download($savePath, getPageTitle('nyusyuko.zaikoList.zaikoListFilterForm')  . '_' . date('YmdHis') . '.xlsx')
            ->deleteFileAfterSend(true);
    }

    public function zaikoListPdf(Request $request)
    {
        $outDir = storage_path('app/download');
        $filename = date('YmdHis') . '_zaikoList.xlsx';
        $savePath = $this->zaikoListExportExcel($request, $outDir, $filename);
        $pdfPath = $outDir . DIRECTORY_SEPARATOR . str_replace('.xlsx', '.pdf', $filename);
        cnvXlsToPdf($savePath, $outDir);
        if (File::exists($savePath)) {
            File::delete($savePath);
        }
        $fileName = getPageTitle('nyusyuko.zaikoList.zaikoListFilterForm') . '_' . date('YmdHis') . '.pdf';
        return response()->file($pdfPath, ['Content-Disposition' => 'filename="' . $fileName . '"'])->deleteFileAfterSend(true);

    }

    private function zaikoListExportExcel(Request $request, $outDir, $filename)
    {
        $exporter = new XlsZaikoList();
        $repo = $this->zaikoListRepository;
        $qb = $repo->applyRequestToBuilder($request);
        $qb->orderBy('t_zaiko.bumon_cd')
            ->orderBy('t_zaiko.soko_cd')
            ->orderBy('t_zaiko.ninusi_cd')
            ->orderBy('t_zaiko.hinmei_cd');
        $data = $qb->get();

        $config = require(app_path('Helpers/Excel/config/t_nyusyuko_zaiko_list.php'));

        $config['base']['header']['others']['kijyun_dt']['value'] = data_get($request->all(), 'exp.kijyun_dt');
        if (!in_array($repo::EXP_PRINT_BY_LOT, data_get($request->all(), 'exp.option', []))) {
            $fnc = $config['hide_lot'];
            $fnc($config);
        }
        if (!in_array($repo::EXP_PRINT_WITH_AMOUNT, data_get($request->all(), 'exp.option', []))) {
            $fnc = $config['hide_kingaku'];
            $fnc($config);
        }

        // Check if the directory exists, if not, create it
        if (!File::exists($outDir)) {
            File::makeDirectory($outDir, 0755, true, true);
        }
        $savePath = $outDir . DIRECTORY_SEPARATOR . $filename;

        $exporter->export(
            app_path('Helpers/Excel/template/t_nyusyuko__zaiko_list.xlsx'),//template
            $config,
            $data,
            $savePath
        );

        return $savePath;
    }
}
