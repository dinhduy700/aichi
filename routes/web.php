<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\Jyutyu\JyutyuExportController;
use App\Http\Controllers\Uriage\UriageEntryController;
use App\Http\Controllers\Uriage\UriageExportController;
use App\Http\Controllers\Uriage\NouhinsyoController;
use App\Http\Controllers\Sagyo\SagyoExportController;
use App\Http\Controllers\Nyukin\NyukinController;
use App\Http\Controllers\Nyukin\NyukinExportController;
use App\Http\Controllers\Nyusyuko\NyusyukoExportController;
use App\Http\Controllers\Order\OrderEntryController;
use App\Http\Controllers\Nyusyuko\Nyuryoku\NyuryokuController;
use App\Http\Controllers\Seikyu\SeikyuShimebiSijiController;
use App\Http\Controllers\Seikyu\SeikyuExportController;
use App\Http\Controllers\Seikyu\SeikyuListController;
use App\Http\Controllers\Seikyu\SeikyuMikakuteiController;
use App\Http\Controllers\Seikyu\SeikyuKakuteiController;
use App\Http\Controllers\Picking\SoryoExportController;
use App\Http\Controllers\Picking\PickingGurisutoExportController;
use App\Http\Controllers\Hokanryo\NiyakuRyoController;
use App\Http\Controllers\HanyouKensaku\HanyouKensakuController;
use App\Http\Controllers\MyMenu\MyMenuController;
use App\Http\Controllers\UketsukeHaraicho\UketsukeHaraichoExportController;
use App\Http\Controllers\HanyouKensaku\Kinou\YosyaGeppoController;
use App\Http\Controllers\HanyouKensaku\Kinou\SuitochoNyuryokuListController;
use App\Http\Controllers\HanyouKensaku\Kinou\NichibetsuUriageKingakuController;
use App\Http\Controllers\HanyouKensaku\Kinou\MihikiateNyukinDenpyoController;
use App\Http\Controllers\HanyouKensaku\Kinou\MikakuteiUnchinListController;
use App\Http\Controllers\HanyouKensaku\Kinou\NohinMeisaiController;
use App\Http\Controllers\HanyouKensaku\Kinou\GenkinKaishuChecklistController;
use App\Http\Controllers\HanyouKensaku\Kinou\GenkinKbnChecklistController;
use App\Http\Controllers\HanyouKensaku\Kinou\YugidaiNinusicdSearchController;
use App\Http\Controllers\HanyouKensaku\Kinou\NinusiListController;
use App\Http\Controllers\HanyouKensaku\Kinou\UntenGeppoController;
use App\Http\Controllers\HanyouKensaku\Kinou\SeikyuzanKakuninController;

use App\Http\Controllers\HanyouKensaku\Kinou\KeiriSoftRenkeiController;
use App\Http\Controllers\HanyouKensaku\Kinou\SeikyuMeisaiController;
use App\Http\Controllers\HanyouKensaku\Kinou\IdoharigamiController;
use App\Http\Controllers\HanyouKensaku\Kinou\RyoshushoController;
use App\Http\Controllers\ZaikoShoukai\ZaikoShoukaiListController;
use App\Http\Controllers\ShouhinUkebarai\ShouhinUkebaraiExportController;
use App\Http\Controllers\Tanaorosi\TanaorosiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', function () {
        $menus = include app_path('Helpers/Menu.php');
        return view('menu', compact('menus'));
    })->name('menu');

    Route::group(['prefix' => 'my-menu', 'as' => 'my_menu.'], function () {
        Route::get('/', [MyMenuController::class, 'index'])->name('index');
        Route::post('/save', [MyMenuController::class, 'saveMenus'])->name('save');
        Route::post('/delete', [MyMenuController::class, 'deleteMenu'])->name('delete');
    });


    Route::any('master/suggestion', [GeneralController::class, 'searchSuggestion'])->name('master-suggestion');

    Route::group(['prefix' => 'jyutyu', 'as' => 'jyutyu.'], function() {
        // 02.受注入力
        Route::group(['prefix' => 'order-entry', 'as' => 'order_entry.'], function() {
            Route::match(['get', 'post'], '/', [OrderEntryController::class, 'index'])->name('index');

            Route::get('data-list', [OrderEntryController::class, 'dataList'])->name('data_list');

            Route::post('validate-row', [OrderEntryController::class, 'valdateRow'])->name('validate_row');

            Route::post('/export-excel', [OrderEntryController::class, 'exportExcelDataTable'])->name('export_excel');

            Route::post('/search-Suggestion', [OrderEntryController::class, 'searchSuggestion'])->name('search_suggestion');

            Route::post('update-datatable', [OrderEntryController::class, 'updateDataTable'])->name('update_datatable');

            Route::post('/update-init-copy', [OrderEntryController::class, 'updateInitCopy'])->name('update_init_copy');

            Route::post('/update-init-search', [OrderEntryController::class, 'updateInitSearch'])->name('update_init_search');

            Route::post('/update-init-column', [OrderEntryController::class, 'updateInitColumn'])->name('update_init_column');

            Route::post('/validate-uriage-form-search', [UriageEntryController::class, 'valdateFormSearchUriage'])->name('validate_form_search_uriage');
        });


        // 03.受注リスト
        Route::group(['prefix' => 'export', 'as' => 'exp.'], function () {
            Route::any('filters', [JyutyuExportController::class, 'filterForm'])->name('filterForm');
            Route::post('filters/validate', [JyutyuExportController::class, 'filterValidate'])->name('filterValidate');
            Route::post('excel', [JyutyuExportController::class, 'excel'])->name('xls');
            Route::post('pdf', [JyutyuExportController::class, 'pdf'])->name('pdf');
            Route::post('csv', [JyutyuExportController::class, 'csv'])->name('csv');
        });
    });

    Route::group(['prefix' => 'uriage', 'as' => 'uriage.'], function() {
        Route::group(['prefix' => 'uriage-entry', 'as' => 'uriage_entry.'], function() {
            Route::match(['get', 'post'], '/', [UriageEntryController::class, 'index'])->name('index');

            Route::get('data-list', [UriageEntryController::class, 'dataList'])->name('data_list');

            Route::post('validate-row', [UriageEntryController::class, 'valdateRow'])->name('validate_row');

            Route::post('/export-excel', [UriageEntryController::class, 'exportExcelDataTable'])->name('export_excel');

            Route::post('/search-Suggestion', [UriageEntryController::class, 'searchSuggestion'])->name('search_suggestion');

            Route::post('update-datatable', [UriageEntryController::class, 'updateDataTable'])->name('update_datatable');

            Route::post('/update-init-copy', [UriageEntryController::class, 'updateInitCopy'])->name('update_init_copy');

            Route::post('/update-init-search', [UriageEntryController::class, 'updateInitSearch'])->name('update_init_search');

            Route::post('/update-init-column', [UriageEntryController::class, 'updateInitColumn'])->name('update_init_column');

            Route::post('/validate-uriage-form-search', [UriageEntryController::class, 'valdateFormSearchUriage'])->name('validate_form_search_uriage');

            Route::post('/calculator-round-kin-tax', [UriageEntryController::class, 'calculatorRoundKintax'])->name('calculator_round_kin_tax');

            Route::post('/get-other-column', [UriageEntryController::class, 'getOtherColumn'])->name('other_column');
        });

        // 06.売上一覧表
        Route::group(['prefix' => 'export', 'as' => 'exp.'], function () {
            Route::any('filters', [UriageExportController::class, 'filterForm'])->name('filterForm');
            Route::post('filters/validate', [UriageExportController::class, 'filterValidate'])->name('filterValidate');
            Route::post('excel', [UriageExportController::class, 'excel'])->name('xls');
            Route::post('pdf', [UriageExportController::class, 'pdf'])->name('pdf');
            Route::post('csv', [UriageExportController::class, 'csv'])->name('csv');
        });

        // 07.納品書・受領書・出庫伝票・出庫伝票控え
        Route::group(['prefix' => 'nouhinsyo', 'as' => 'nouhinsyo.'], function () {
            Route::any('filters', [NouhinsyoController::class, 'filterForm'])->name('filterForm');
            Route::post('filters/validate', [NouhinsyoController::class, 'filterValidate'])->name('filterValidate');
            Route::post('excel', [NouhinsyoController::class, 'excel'])->name('xls');
            Route::post('pdf', [NouhinsyoController::class, 'pdf'])->name('pdf');
        });
    });

    Route::group(['prefix' => 'order', 'as' => 'order.'], function() {
        Route::group(['prefix' => 'order-entry', 'as' => 'order_entry.'], function() {
            Route::any('dispatch-list', [OrderEntryController::class, 'dispatchList'])->name('dispatch_list');
            Route::any('dispatch-suggestion/{type}', [OrderEntryController::class, 'dispatchSuggestion'])->name('dispatch_suggestion');
            Route::any('dispatch-suggestion/{type}/{key}', [OrderEntryController::class, 'dispatchSuggestion'])->name('dispatch_suggestion_key');
            Route::post('update-init-column-dispatch', [OrderEntryController::class, 'updateInitColumnDispatch'])->name('update_init_column_dispatch');
            Route::post('/update-init-search-dispatch', [OrderEntryController::class, 'updateInitSearchDispatch'])->name('update_init_search_dispatch');
            Route::post('update-datatable-dispatch', [OrderEntryController::class, 'updateDataTableDispatch'])->name('update_datatable_dispatch');
            Route::any('/dispatch', [OrderEntryController::class, 'dispatch'])->name('dispatch');
        });
    });

    Route::group(['prefix' => 'sagyo', 'as' => 'sagyo.'], function() {
        // 04.作業指示書
        Route::group(['prefix' => 'export', 'as' => 'exp.'], function () {
            Route::any('filters', [SagyoExportController::class, 'filterForm'])->name('filterForm');
            Route::post('filters/validate', [SagyoExportController::class, 'filterValidate'])->name('filterValidate');
            Route::post('excel', [SagyoExportController::class, 'excel'])->name('xls');
            Route::post('pdf', [SagyoExportController::class, 'pdf'])->name('pdf');
            Route::post('csv', [SagyoExportController::class, 'csv'])->name('csv');
            Route::post('handle-m-user-pg', [SagyoExportController::class, 'handleMUserPg'])->name('handleMUserPg');
            Route::post('init-data-modal-m-user-pg', [SagyoExportController::class, 'initDataModalMUserPg'])->name('initDataModalMUserPg');
        });
    });

    Route::group(['prefix' => 'nyukin', 'as' => 'nyukin.'], function () {
        // 13.入金入力
        Route::match(['get', 'post'], '/', [NyukinController::class, 'index'])->name('index');
        Route::get('data-list', [NyukinController::class, 'dataList'])->name('data_list');

        Route::match(['get', 'post'], '/edit/{nyukinNo}', [NyukinController::class, 'edit'])->name('edit');

        Route::any('create', [NyukinController::class, 'create'])->name('create');

        Route::post('store', [NyukinController::class, 'store'])->name('store');

        Route::post('update/{nyukinNo}', [NyukinController::class, 'update'])->name('update');

        Route::delete('delete/{nyukinNo}', [NyukinController::class, 'destroy'])->name('destroy');

        Route::post('auto-fill-seikyu-sime-dt', [NyukinController::class, 'autoFillSeikyuSimeDt'])->name('auto_fill_seikyu_sime_dt');

        Route::post('get-list-nyukin-seikyu', [NyukinController::class, 'getListNyukinSeiKyu'])->name('get_list_nyukin_seikyu');
        Route::post('validate-nyukin-form-search', [NyukinController::class, 'validateFormSearch'])->name('validate_from_search');

        // 14.入金一覧
        Route::group(['prefix' => 'export', 'as' => 'exp.'], function () {
            Route::any('filters', [NyukinExportController::class, 'filterForm'])->name('filterForm');
            Route::post('filters/validate', [NyukinExportController::class, 'filterValidate'])->name('filterValidate');
            Route::post('excel', [NyukinExportController::class, 'excel'])->name('excel');
            Route::post('pdf', [NyukinExportController::class, 'pdf'])->name('pdf');
            Route::post('csv', [NyukinExportController::class, 'csv'])->name('csv');
        });
    });

    Route::group(['prefix' => 'nyusyuko', 'as' => 'nyusyuko.'], function () {
        // 15.入出庫入力
        Route::group(['prefix' => 'nyuryoku', 'as' => 'nyuryoku.'], function() {
            Route::match(['get', 'post'], '/', [NyuryokuController::class, 'index'])->name('index');

            Route::post('/create', [NyuryokuController::class, 'create'])->name('create');
            Route::post('/store', [NyuryokuController::class, 'store'])->name('store');

            Route::match(['get', 'post'], '/edit/{id}', [NyuryokuController::class, 'edit'])->name('edit');
            Route::post('/update/{id}', [NyuryokuController::class, 'update'])->name('update');

            Route::delete('delete/{id}', [NyuryokuController::class, 'destroy'])->name('destroy');
            Route::get('data-list', [NyuryokuController::class, 'dataList'])->name('data_list');
            Route::post('update-datatable', [NyuryokuController::class, 'updateDataTable'])->name('update_datatable');

            Route::post('validate-form-nyusyuki-head', [NyuryokuController::class, 'validateFormSearchNyusyukiHead'])->name('valiedate_form_search_nyusyuko_head');

            // validate nyusyuko_meisai
            Route::post('validate-row-nyusyuko-meisai', [NyuryokuController::class, 'validateRowNyusyukoMeisai'])->name('validate_row_nyusyuko_meisai');

            Route::match(['get', 'post'], '/nyusyuko-head', [NyuryokuController::class, 'indexNyusyukoHead'])->name('index_nyusyuko_head');
            Route::get('data-list-nyusyuko-head', [NyuryokuController::class, 'dataListNyusyukoHead'])->name('data_list_nyusyuko_head');

            Route::post('validate-nyusyuko-head-form-search', [NyuryokuController::class, 'validateNyusyukoHeadFormSearch'])->name('validate_nyusyuko_head_form_search');

            Route::match(['get', 'post'], '/zaiko-nyusyuko-meisai', [NyuryokuController::class, 'indexZaikoNyusyukoMeisai'])->name('index_zaiko_nyusyuko_meisai');

            Route::get('data-list-zaiko-nyusyuko-meisai', [NyuryokuController::class, 'listZaikoNyusyukoMeisai'])->name('data_list_zaiko_nyusyuko_meisai');

            Route::post('get-nyusyuko-head', [NyuryokuController::class, 'getNyusyukoHead'])->name('get_nyusyuko_head');
            Route::post('calculator-round-kin-tax', [NyuryokuController::class, 'calculatorRoundKintax'])->name('calculator_round_kin_tax');

            Route::post('get-ninusi', [NyuryokuController::class, 'getNinusi'])->name('get_ninusi');
            // suggestion multiple field
            Route::post('suggestion-multiple', [NyuryokuController::class, 'suggestionMultiple'])->name('suggestion_multiple');

            Route::post('export-pdf', [NyuryokuController::class, 'exportPdf'])->name('export_pdf');

        });
        // 16.入出庫日報
        Route::group(['prefix' => 'nipou', 'as' => 'nipou.'], function () {
            Route::any('filters', [NyusyukoExportController::class, 'nyusyukoNipouFilterForm'])->name('nipouFilterForm');
            Route::post('filters/validate', [NyusyukoExportController::class, 'nyusyukoNipouFilterValidate'])->name('nipouFilterValidate');
            Route::post('excel', [NyusyukoExportController::class, 'nyusyukoNipouExcel'])->name('nipouXls');
            Route::post('pdf', [NyusyukoExportController::class, 'nyusyukoNipouPdf'])->name('nipouPdf');
            Route::post('csv', [NyusyukoExportController::class, 'nyusyukoNipouCsv'])->name('nipouCsv');
        });
        // 17.在庫報告書
        Route::group(['prefix' => 'zaiko-houkoku-syo', 'as' => 'exp.'], function () {
            Route::any('filters', [NyusyukoExportController::class, 'zaikoHoukokuSyoFilterForm'])->name('zaikoFilterForm');
            Route::post('filters/validate', [NyusyukoExportController::class, 'zaikoHoukokuSyoFilterValidate'])->name('zaikoFilterValidate');
            Route::post('excel', [NyusyukoExportController::class, 'zaikoHoukokuSyoExcel'])->name('zaikoXls');
            Route::post('csv', [NyusyukoExportController::class, 'zaikoHoukokuSyoCsv'])->name('zaikoCsv');
            Route::post('pdf', [NyusyukoExportController::class, 'zaikoHoukokuSyoPdf'])->name('zaikoPdf');
        });

        // 18.在庫一覧表
        Route::group(['prefix' => 'zaikoList', 'as' => 'zaikoList.'], function () {
            Route::any('filters', [NyusyukoExportController::class, 'zaikoListFilterForm'])->name('zaikoListFilterForm');
            Route::post('filters/validate', [NyusyukoExportController::class, 'zaikoListFilterValidate'])->name('zaikoListFilterValidate');
            Route::post('excel', [NyusyukoExportController::class, 'zaikoListExcel'])->name('zaikoListXls');
            Route::post('csv', [NyusyukoExportController::class, 'zaikoListCsv'])->name('zaikoListCsv');
            Route::post('pdf', [NyusyukoExportController::class, 'zaikoListPdf'])->name('zaikoListPdf');
        });
    });

    Route::group(['prefix' => 'seikyu', 'as' => 'seikyu.'], function () {
        // 08.請求締日選択
        Route::group(['prefix' => 'seikyu-shimebi', 'as' => 'seikyu_shimebi.'], function() {
            Route::match(['get', 'post'], '/', [SeikyuShimebiSijiController::class, 'index'])->name('index');
            Route::post('/store', [SeikyuShimebiSijiController::class, 'store'])->name('store');
            Route::post('/handle-seikyu-zaiko/{seikyuSimeDt}', [SeikyuShimebiSijiController::class, 'handleSeikyuZaiko'])->name('handle_seikyu_zaiko');
            Route::delete('delete/{seikyuSimeDt}', [SeikyuShimebiSijiController::class, 'destroy'])->name('destroy');
            Route::get('data-list', [SeikyuShimebiSijiController::class, 'dataList'])->name('data_list');
            Route::post('handle-m-user-pg', [SeikyuShimebiSijiController::class, 'handleMUserPg'])->name('handleMUserPg');
            Route::post('validate', [SeikyuShimebiSijiController::class, 'validateSearchForm'])->name('validateSearchForm');
        });

        // 09.請求書
        Route::group(['prefix' => 'seikyu-sho', 'as' => 'seikyu_sho.'], function() {
            Route::group(['prefix' => 'export', 'as' => 'exp.'], function () {
                Route::any('index', [SeikyuExportController::class, 'index'])->name('index');
                Route::get('data-list', [SeikyuExportController::class, 'dataList'])->name('data_list');
                Route::any('filters', [SeikyuExportController::class, 'filterForm'])->name('filterForm');
                Route::post('filters/validate', [SeikyuExportController::class, 'filterValidate'])->name('filterValidate');
                Route::post('excel', [SeikyuExportController::class, 'excel'])->name('xls');
                Route::post('csv', [SeikyuExportController::class, 'csv'])->name('csv');
                Route::post('pdf', [SeikyuExportController::class, 'pdf'])->name('pdf');
                Route::get('preview-pdf', [SeikyuExportController::class, 'previewPdf'])->name('previewPdf');
                Route::post('update-t-seikyu', [SeikyuExportController::class, 'updateTSeikyu'])->name('updateTSeikyu');
            });
        });

        // 10.請求未確定一覧
        Route::group(['prefix' => 'mi-kakutei', 'as' => 'mikakutei.'], function() {
            Route::any('/', [SeikyuMiKakuteiController::class, 'index'])->name('index');
            Route::get('data-list', [SeikyuMiKakuteiController::class, 'dataList'])->name('data_list');
            Route::post('filters', [SeikyuMiKakuteiController::class, 'filterForm'])->name('filterForm');
            Route::post('filters/validate', [SeikyuMiKakuteiController::class, 'filterValidate'])->name('filterValidate');
            Route::post('excel', [SeikyuMikakuteiController::class, 'excel'])->name('xls');
            Route::post('pdf', [SeikyuMikakuteiController::class, 'pdf'])->name('pdf');
            Route::post('csv', [SeikyuMiKakuteiController::class, 'csv'])->name('csv');
        });

        // 11.請求一覧
        Route::group(['prefix' => 'seikyu-list', 'as' => 'list.'], function() {
            Route::any('/', [SeikyuListController::class, 'index'])->name('index');
            Route::get('data-list', [SeikyuListController::class, 'dataList'])->name('data_list');
            Route::post('filters', [SeikyuListController::class, 'filterForm'])->name('filterForm');
            Route::post('filters/validate', [SeikyuListController::class, 'filterValidate'])->name('filterValidate');
            Route::post('excel', [SeikyuListController::class, 'excel'])->name('xls');
            Route::post('pdf', [SeikyuListController::class, 'pdf'])->name('pdf');
            Route::post('csv', [SeikyuListController::class, 'csv'])->name('csv');
        });

        // 12.請求確定処理
        Route::group(['prefix' => 'kakutei', 'as' => 'kakutei.'], function() {
            Route::any('/', [SeikyuKakuteiController::class, 'index'])->name('index');
            Route::get('data-list', [SeikyuKakuteiController::class, 'dataList'])->name('data_list');
            Route::post('set', [SeikyuKakuteiController::class, 'setFlag'])->name('set');
        });
    });

    Route::group(['prefix' => 'picking', 'as' => 'picking.'], function () {
        // 19.総量ピッキングリスト
        Route::group(['prefix' => 'soryo', 'as' => 'soryo.'], function() {
            Route::group(['prefix' => 'export', 'as' => 'exp.'], function () {
                Route::any('filters', [SoryoExportController::class, 'filterForm'])->name('filterForm');
                Route::post('filters/validate', [SoryoExportController::class, 'filterValidate'])->name('filterValidate');
                Route::post('excel', [SoryoExportController::class, 'excel'])->name('xls');
                Route::post('csv', [SoryoExportController::class, 'csv'])->name('csv');
                Route::post('pdf', [SoryoExportController::class, 'pdf'])->name('pdf');
            });
        });

        // 20.ピッキングリスト
        Route::group(['prefix' => 'picking-gurisuto', 'as' => 'picking_gurisuto.'], function() {
            Route::group(['prefix' => 'export', 'as' => 'exp.'], function () {
                Route::any('filters', [PickingGurisutoExportController::class, 'filterForm'])->name('filterForm');
                Route::post('filters/validate', [PickingGurisutoExportController::class, 'filterValidate'])->name('filterValidate');
                Route::post('excel', [PickingGurisutoExportController::class, 'excel'])->name('xls');
                Route::post('csv', [PickingGurisutoExportController::class, 'csv'])->name('csv');
                Route::post('pdf', [PickingGurisutoExportController::class, 'pdf'])->name('pdf');
            });
        });
    });


    Route::group(['prefix' => 'hokanryo', 'as' => 'hokanryo.'], function () {
        // 23. 荷役料・荷役料請求計算書
        Route::group(['prefix' => 'niyakuryo', 'as' => 'niyakuryo.'], function () {
            Route::any('filters', [NiyakuRyoController::class, 'filterForm'])->name('filterForm');
            Route::post('filters/validate', [NiyakuRyoController::class, 'filterValidate'])->name('filterValidate');
            Route::post('excel', [NiyakuRyoController::class, 'excel'])->name('xls');
            Route::post('csv', [NiyakuRyoController::class, 'csv'])->name('csv');
            Route::post('pdf', [NiyakuRyoController::class, 'pdf'])->name('pdf');
        });
    });


    Route::group(['prefix' => 'uketsuke_haraicho', 'as' => 'uketsuke_haraicho.'], function () {
        Route::group(['prefix' => 'export', 'as' => 'exp.'], function () {
            Route::any('filters', [UketsukeHaraichoExportController::class, 'filterForm'])->name('filterForm');
            Route::post('filters/validate', [UketsukeHaraichoExportController::class, 'filterValidate'])->name('filterValidate');
            Route::post('excel', [UketsukeHaraichoExportController::class, 'uketsukeHaraichoExcel'])->name('excel');
            Route::post('pdf', [UketsukeHaraichoExportController::class, 'uketsukeHaraichoPdf'])->name('pdf');
            Route::post('csv', [UketsukeHaraichoExportController::class, 'uketsukeHaraichoCsv'])->name('csv');
        });
    });

    Route::group(['prefix' => 'shouhin_ukebarai', 'as' => 'shouhin_ukebarai.'], function () {
        Route::group(['prefix' => 'export', 'as' => 'exp.'], function () {
            Route::any('filters', [ShouhinUkebaraiExportController::class, 'filterForm'])->name('filterForm');
            Route::post('filters/validate', [ShouhinUkebaraiExportController::class, 'filterValidate'])->name('filterValidate');
            Route::post('excel', [ShouhinUkebaraiExportController::class, 'shouhinUkebaraiExcel'])->name('excel');
            Route::post('pdf', [ShouhinUkebaraiExportController::class, 'shouhinUkebaraiPdf'])->name('pdf');
            Route::post('csv', [ShouhinUkebaraiExportController::class, 'shouhinUkebaraiCsv'])->name('csv');
        });
    });

    // 25. 荷役料・荷役料請求計算書
    Route::group(['prefix' => 'tanaorosi', 'as' => 'tanaorosi.'], function () {
        Route::group(['prefix' => 'export', 'as' => 'exp.'], function () {
            Route::any('filters', [TanaorosiController::class, 'filterForm'])->name('filterForm');
            Route::post('filters/validate', [TanaorosiController::class, 'filterValidate'])->name('filterValidate');
            Route::post('excel', [TanaorosiController::class, 'excel'])->name('xls');
            Route::post('pdf', [TanaorosiController::class, 'pdf'])->name('pdf');
            Route::post('csv', [TanaorosiController::class, 'csv'])->name('csv');
        });
    });

    Route::group(['prefix' => 'zaiko_shoukai', 'as' => 'zaiko_shoukai.'], function () {
        Route::any('/', [ZaikoShoukaiListController::class, 'index'])->name('index');
        Route::get('data-list', [ZaikoShoukaiListController::class, 'dataList'])->name('data_list');
        Route::post('/validate-zaiko-shoukai-form-search', [ZaikoShoukaiListController::class, 'valdateFormSearchZaikoShoukai'])->name('validate_form_search_zaiko_shoukai');
        Route::group(['prefix' => 'ukebarai_shoukai', 'as' => 'ukebarai_shoukai.'], function() {
            Route::any('/', [ZaikoShoukaiListController::class, 'indexUkebaraiShoukai'])->name('index');
            Route::get('data-list', [ZaikoShoukaiListController::class, 'dataListUkebaraiShoukai'])->name('data_list');
            Route::post('/validate-ukebarai-shoukai-form-search', [ZaikoShoukaiListController::class, 'valdateFormSearchUkebaraiShoukai'])->name('validate_form_search_ukebarai_shoukai');
        });
    });

    // 99.汎用検索
    Route::group(['prefix' => 'hanyou-kensaku', 'as' => 'hanyou_kensaku.'], function() {
        Route::match(['get', 'post'], '/', [HanyouKensakuController::class, 'index'])->name('index');

        Route::post('get-setting-mode', [HanyouKensakuController::class, 'getSettingMode'])->name('get_setting_mode');


        Route::group(['prefix' => 'yosya-geppo', 'as' => 'yosya_geppo.'], function() {
            Route::group(['prefix' => 'export', 'as' => 'exp.'], function() {
            });

            Route::match(['get', 'post'], '/', [YosyaGeppoController::class, 'dataList'])->name('data_list');
            Route::post('/export-excel', [YosyaGeppoController::class, 'exportExcelDataTable'])->name('export_excel');
        });

        Route::group(['prefix' => 'suitocho-nyuryoku-list', 'as' => 'suitocho_nyuryoku_list.'], function() {
            Route::match(['get', 'post'], '/', [SuitochoNyuryokuListController::class, 'dataList'])->name('data_list');
            Route::post('/export-excel', [SuitochoNyuryokuListController::class, 'exportExcelDataTable'])->name('export_excel');
        });

        Route::group(['prefix' => 'nichibetsu-uriage-kingaku', 'as' => 'nichibetsu_uriage_kingaku.'], function() {
            Route::match(['get', 'post'], '/', [NichibetsuUriageKingakuController::class, 'dataList'])->name('data_list');
            Route::post('/export-excel', [NichibetsuUriageKingakuController::class, 'exportExcelDataTable'])->name('export_excel');
        });

        // 99.汎用検索(未引当の入金伝票)
        Route::group(['prefix' => 'mihikiate-nyukin-denpyo', 'as' => 'mihikiate_nyukin_denpyo.'], function() {
            Route::match(['get', 'post'], '/', [MihikiateNyukinDenpyoController::class, 'dataList'])->name('data_list');
            Route::post('/export-excel', [MihikiateNyukinDenpyoController::class, 'exportExcelDataTable'])->name('export_excel');
        });

        // 99.汎用検索(未確定運賃リスト)
        Route::group(['prefix' => 'mikakutei-unchin-list', 'as' => 'mikakutei_unchin_list.'], function() {
            Route::match(['get', 'post'], '/', [MikakuteiUnchinListController::class, 'dataList'])->name('data_list');
            Route::post('/export-excel', [MikakuteiUnchinListController::class, 'exportExcelDataTable'])->name('export_excel');
        });

        Route::group(['prefix' => 'genkin-kaishu-checklist', 'as' => 'genkin_kaishu_checklist.'], function() {
            Route::match(['get', 'post'], '/', [GenkinKaishuChecklistController::class, 'dataList'])->name('data_list');
            Route::post('/export-excel', [GenkinKaishuChecklistController::class, 'exportExcelDataTable'])->name('export_excel');
        });

        Route::group(['prefix' => 'genkin-kbn-checklist', 'as' => 'genkin_kbn_checklist.'], function() {
            Route::match(['get', 'post'], '/', [GenkinKbnChecklistController::class, 'dataList'])->name('data_list');
            Route::post('/export-excel', [GenkinKbnChecklistController::class, 'exportExcelDataTable'])->name('export_excel');
        });

        // 99.汎用検索(納品明細)
        Route::group(['prefix' => 'nohin-meisai', 'as' => 'nohin_meisai.'], function() {
            Route::match(['get', 'post'], '/', [NohinMeisaiController::class, 'dataList'])->name('data_list');
            Route::post('/export-excel', [NohinMeisaiController::class, 'exportExcelDataTable'])->name('export_excel');
        });

        Route::group(['prefix' => 'yugidai-ninusicd-search', 'as' => 'yugidai_ninusicd_search.'], function() {
            Route::match(['get', 'post'], '/', [YugidaiNinusicdSearchController::class, 'dataList'])->name('data_list');
        });

        //99.汎用検索(荷主一覧)
        Route::group(['prefix' => 'ninusi-list', 'as' => 'ninusi_list.'], function() {
            Route::match(['get', 'post'], '/', [NinusiListController::class, 'dataList'])->name('data_list');
            Route::post('/export-excel', [NinusiListController::class, 'exportExcelDataTable'])->name('export_excel');
        });
        //99.汎用検索(運転月報)
        Route::group(['prefix' => 'unten-geppo', 'as' => 'unten_geppo.'], function() {
            Route::match(['get', 'post'], '/', [UntenGeppoController::class, 'dataList'])->name('data_list');
            Route::post('/export-excel', [UntenGeppoController::class, 'exportExcelDataTable'])->name('export_excel');
        });

        //99.汎用検索(請求残高確認)
        Route::group(['prefix' => 'seikyuzan-kakunin', 'as' => 'seikyuzan_kakunin.'], function() {
            Route::match(['get', 'post'], '/', [SeikyuzanKakuninController::class, 'dataList'])->name('data_list');
            Route::post('/export-excel', [SeikyuzanKakuninController::class, 'exportExcelDataTable'])->name('export_excel');
        });


        //99.汎用検索(経理ソフト連携)
        Route::group(['prefix' => 'keiri-soft-renkei', 'as' => 'keiri_soft_renkei.'], function() {
            Route::match(['get', 'post'], '/', [KeiriSoftRenkeiController::class, 'dataList'])->name('data_list');
            Route::post('/export-excel', [KeiriSoftRenkeiController::class, 'exportExcelDataTable'])->name('export_excel');
            Route::post('/export-csv', [KeiriSoftRenkeiController::class, 'exportCsv'])->name('export_csv');
        });
        //99.汎用検索(請求明細)
        Route::group(['prefix' => 'seikyu-meisai', 'as' => 'seikyu_meisai.'], function() {
            Route::match(['get', 'post'], '/', [SeikyuMeisaiController::class, 'dataList'])->name('data_list');
            Route::post('/export-excel', [SeikyuMeisaiController::class, 'exportExcelDataTable'])->name('export_excel');
        });

        //99.汎用検索(移動張り紙)
        Route::group(['prefix' => 'idoharigami', 'as' => 'idoharigami.'], function() {
            Route::match(['get', 'post'], '/', [IdoharigamiController::class, 'dataList'])->name('data_list');
            Route::post('/export-excel', [IdoharigamiController::class, 'exportExcelDataTable'])->name('export_excel');
        });

        //99.汎用検索(領収書)
        Route::group(['prefix' => 'ryoshusho', 'as' => 'ryoshusho.'], function() {
            $mode = ['honten', 'hokuriku', 'kanto'];
            Route::match(['get', 'post'], '/{mode}', [RyoshushoController::class, 'dataList'])
                ->name('data_list')->whereIn('mode', $mode);
            Route::any('/{mode}/export-excel', [RyoshushoController::class, 'exportExcelDataTable'])
                ->name('export_excel')->whereIn('mode', $mode);
        });
    });

});

require('web-master.php');

Route::get('/logout', [LoginController::class, 'logout']);
Auth::routes(['register' => false, 'reset' => false]);

