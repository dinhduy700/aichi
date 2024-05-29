<?php

namespace App\Http\Controllers\HanyouKensaku;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\XlsExportMst;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class HanyouKensakuController extends Controller
{
    protected $dataRepository;
    protected $configExcel;
    protected $fileName;
    protected $templatePath;
    protected $exporter = null;

    public function index()
    {
        return view('hanyouKensaku.index');
    }

    public function getSettingMode(Request $request) {
        $mode = $request->mode;
        if($request->filled('mode')) {
            switch ($mode) {
                case 'yosya_geppo':
                    $setting = require(app_path('Helpers/Grid/config/hanyou_kensaku/yosya_geppo.php'));
                    $urlExcel = route('hanyou_kensaku.yosya_geppo.export_excel');
                    $urlData = route('hanyou_kensaku.yosya_geppo.data_list');
                    break;
                case 'suitocho_nyuryoku_list':
                    $setting = require(app_path('Helpers/Grid/config/hanyou_kensaku/suitocho_nyuryoku_list.php'));
                    $urlExcel = route('hanyou_kensaku.suitocho_nyuryoku_list.export_excel');
                    $urlData = route('hanyou_kensaku.suitocho_nyuryoku_list.data_list');
                    break;
                case 'nichibetsu_uriage_kingaku':
                    $setting = require(app_path('Helpers/Grid/config/hanyou_kensaku/nichibetsu_uriage_kingaku.php'));
                    $urlExcel = route('hanyou_kensaku.nichibetsu_uriage_kingaku.export_excel');
                    $urlData = route('hanyou_kensaku.nichibetsu_uriage_kingaku.data_list');
                    break;
                case 'mihikiate_nyukin_denpyo':
                    $setting = require(app_path('Helpers/Grid/config/hanyou_kensaku/mihikiate_nyukin_denpyo.php'));
                    $urlExcel = route('hanyou_kensaku.mihikiate_nyukin_denpyo.export_excel');
                    $urlData = route('hanyou_kensaku.mihikiate_nyukin_denpyo.data_list');
                    break;
                case 'mikakutei_unchin_list':
                    $setting = require(app_path('Helpers/Grid/config/hanyou_kensaku/mikakutei_unchin_list.php'));
                    $urlExcel = route('hanyou_kensaku.mikakutei_unchin_list.export_excel');
                    $urlData = route('hanyou_kensaku.mikakutei_unchin_list.data_list');
                    break;
                case 'genkin_kbn_checklist':
                    $setting = require(app_path('Helpers/Grid/config/hanyou_kensaku/genkin_kbn_checklist.php'));
                    $urlExcel = route('hanyou_kensaku.genkin_kbn_checklist.export_excel');
                    $urlData = route('hanyou_kensaku.genkin_kbn_checklist.data_list');
                    break;
                case 'genkin_kaishu_checklist':
                    $setting = require(app_path('Helpers/Grid/config/hanyou_kensaku/genkin_kaishu_checklist.php'));
                    $urlExcel = route('hanyou_kensaku.genkin_kaishu_checklist.export_excel');
                    $urlData = route('hanyou_kensaku.genkin_kaishu_checklist.data_list');
                    break;
                case 'idoharigami':
                    $setting = require(app_path('Helpers/Grid/config/hanyou_kensaku/idoharigami.php'));
                    $urlExcel = route('hanyou_kensaku.idoharigami.export_excel');
                    $urlData = route('hanyou_kensaku.idoharigami.data_list');
                    break;
                case 'nohin_meisai':
                    $setting = require(app_path('Helpers/Grid/config/hanyou_kensaku/nohin_meisai.php'));
                    $urlExcel = route('hanyou_kensaku.nohin_meisai.export_excel');
                    $urlData = route('hanyou_kensaku.nohin_meisai.data_list');
                    break;
                case 'keiri_soft_renkei':
                    $setting = require(app_path('Helpers/Grid/config/hanyou_kensaku/keiri_soft_renkei.php'));
                    $urlExcel = route('hanyou_kensaku.keiri_soft_renkei.export_excel');
                    $urlData = route('hanyou_kensaku.keiri_soft_renkei.data_list');
                    $urlCsv = route('hanyou_kensaku.keiri_soft_renkei.export_csv');
                    break;
                case 'ninusi_list':
                    $setting = require(app_path('Helpers/Grid/config/hanyou_kensaku/ninusi_list.php'));
                    $urlExcel = route('hanyou_kensaku.ninusi_list.export_excel');
                    $urlData = route('hanyou_kensaku.ninusi_list.data_list');
                    break;
                case 'seikyu_meisai':
                    $setting = require(app_path('Helpers/Grid/config/hanyou_kensaku/seikyu_meisai.php'));
                    $urlExcel = route('hanyou_kensaku.seikyu_meisai.export_excel');
                    $urlData = route('hanyou_kensaku.seikyu_meisai.data_list');
                    break;
                case 'seikyuzan_kakunin':
                    $setting = require(app_path('Helpers/Grid/config/hanyou_kensaku/seikyuzan_kakunin.php'));
                    $urlExcel = route('hanyou_kensaku.seikyuzan_kakunin.export_excel');
                    $urlData = route('hanyou_kensaku.seikyuzan_kakunin.data_list');
                    break;
                case 'yugidai_ninusicd_search':
                    $setting = require(app_path('Helpers/Grid/config/hanyou_kensaku/yugidai_ninusicd_search.php'));
                    $urlExcel = '';
                    $urlData = route('hanyou_kensaku.yugidai_ninusicd_search.data_list');
                    break;
                case 'unten_geppo':
                    $setting = require(app_path('Helpers/Grid/config/hanyou_kensaku/unten_geppo.php'));
                    $urlExcel = route('hanyou_kensaku.unten_geppo.export_excel');
                    $urlData = route('hanyou_kensaku.unten_geppo.data_list');
                    break;
                case 'ryoshusho_honten':
                    $setting = require(app_path('Helpers/Grid/config/hanyou_kensaku/ryoshusho.php'));
                    $urlExcel = route('hanyou_kensaku.ryoshusho.export_excel', ['mode' => 'honten']);
                    $urlData = route('hanyou_kensaku.ryoshusho.data_list', ['mode' => 'honten']);
                    break;
                case 'ryoshusho_hokuriku':
                    $setting = require(app_path('Helpers/Grid/config/hanyou_kensaku/ryoshusho.php'));
                    $urlExcel = route('hanyou_kensaku.ryoshusho.export_excel', ['mode' => 'hokuriku']);
                    $urlData = route('hanyou_kensaku.ryoshusho.data_list', ['mode' => 'hokuriku']);
                    break;
                case 'ryoshusho_kanto':
                    $setting = require(app_path('Helpers/Grid/config/hanyou_kensaku/ryoshusho.php'));
                    $urlExcel = route('hanyou_kensaku.ryoshusho.export_excel', ['mode' => 'kanto']);
                    $urlData = route('hanyou_kensaku.ryoshusho.data_list', ['mode' => 'kanto']);
                    break;
                default:
                    $setting = [];
                    $urlExcel = '';
                    $urlData = '';
                    break;
            }
        }
        return response()->json([
            'status' => 200,
            'data' => [
                'setting' => $setting,
                'url_excel' => $urlExcel,
                'url_data' => $urlData,
                'url_csv' => $urlCsv ?? ''
            ]
        ]);
    }

    public function dataList(Request $request)
    {
        $page = $request->page ?? 1;
        $perPage = config('params.PAGE_SIZE');

        $listData = $this->dataRepository->getListWithTotalCount($request);

        $data['total'] = $listData['total'];
        $data['rows'] = $listData['rows']
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();
        return response()->json($data);
    }

    public function exportExcelDataTable(Request $request)
    {
        $exporter = $this->exporter ?? new XlsExportMst();
        $listData = $this->dataRepository->getListWithTotalCount($request);
        $data = $listData['rows']->get();
        $directoryPath = storage_path('app/download');

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true, true);
        }

        $savePath = $directoryPath . '/'.$this->fileName;
        $exporter->export($this->templatePath, $this->configExcel, $data, $savePath);
        return Response::download($savePath)->deleteFileAfterSend(true);
    }
}
