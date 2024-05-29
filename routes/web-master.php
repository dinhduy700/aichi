<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Master\MeisyoController;
use App\Http\Controllers\Master\NinusiController;
use App\Http\Controllers\Master\HachakuController;
use App\Http\Controllers\Master\JyomuinController;
use App\Http\Controllers\Master\BumonController;
use App\Http\Controllers\Master\HinmokuController;
use App\Http\Controllers\Master\HinmeiController;
use App\Http\Controllers\Master\SyaryoController;
use App\Http\Controllers\Master\YousyaController;
use App\Http\Controllers\Master\BikoController;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Master\SokoController;
use App\Http\Controllers\Master\SokoHinmeiController;

Route::group(['middleware' => ['auth']], function () {
    Route::group(['prefix' => 'master', 'as' => 'master.'], function () {
        Route::group(['prefix' => 'bumon', 'as' => 'bumon.'], function () {
            Route::match(['get', 'post'], '/', [BumonController::class, 'index'])->name('index');
            Route::any('create', [BumonController::class, 'create'])->name('create');
            Route::post('/store', [BumonController::class, 'store'])->name('store');
            Route::match(['get', 'post'], '/edit/{bumonCd}', [BumonController::class, 'edit'])->name('edit');
            Route::post('/update/{bumonCd}', [BumonController::class, 'update'])->name('update');
            Route::delete('delete/{bumonCd}', [BumonController::class, 'destroy'])->name('destroy');
            Route::get('data-list', [BumonController::class, 'dataList'])->name('data_list');
            Route::post('/export-excel', [BumonController::class, 'exportExcelDataTable'])->name('export_excel');
        });

        Route::group(['prefix' => 'meisyo', 'as' => 'meisyo.'], function () {
            Route::match(['get', 'post'], '/', [MeisyoController::class, 'index'])->name('index');
            Route::any('create', [MeisyoController::class, 'create'])->name('create');
            Route::post('/store', [MeisyoController::class, 'store'])->name('store');
            Route::match(['get', 'post'], '/edit/{meisyoKbn}/{meisyoCd}', [MeisyoController::class, 'edit'])->name('edit');
            Route::post('/update/{meisyoKbn}/{meisyoCd}', [MeisyoController::class, 'update'])->name('update');
            Route::delete('delete/{meisyoKbn}/{meisyoCd}', [MeisyoController::class, 'destroy'])->name('destroy');
            Route::get('data-list', [MeisyoController::class, 'dataList'])->name('data_list');
            Route::post('/export-excel', [MeisyoController::class, 'exportExcelDataTable'])->name('export_excel');
        });

        Route::group(['prefix' => 'ninusi', 'as' => 'ninusi.'], function () {
            Route::match(['get', 'post'], '/', [NinusiController::class, 'index'])->name('index');
            Route::any('create', [NinusiController::class, 'create'])->name('create');
            Route::post('/store', [NinusiController::class, 'store'])->name('store');
            Route::match(['get', 'post'], '/edit/{ninusiCd}', [NinusiController::class, 'edit'])->name('edit');
            Route::post('/update/{ninusiCd}', [NinusiController::class, 'update'])->name('update');
            Route::delete('delete/{ninusiCd}', [NinusiController::class, 'destroy'])->name('destroy');
            Route::get('data-list', [NinusiController::class, 'dataList'])->name('data_list');
            Route::post('/export-excel', [NinusiController::class, 'exportExcelDataTable'])->name('export_excel');
        });

        Route::group(['prefix' => 'hachaku', 'as' => 'hachaku.'], function () {
            Route::match(['get', 'post'], '/', [HachakuController::class, 'index'])->name('index');
            Route::any('create', [HachakuController::class, 'create'])->name('create');
            Route::post('/store', [HachakuController::class, 'store'])->name('store');
            Route::match(['get', 'post'], '/edit/{hachakuCd}', [HachakuController::class, 'edit'])->name('edit');
            Route::post('/update/{hachakuCd}', [HachakuController::class, 'update'])->name('update');
            Route::delete('delete/{hachakuCd}', [HachakuController::class, 'destroy'])->name('destroy');
            Route::get('data-list', [HachakuController::class, 'dataList'])->name('data_list');
            Route::post('/export-excel', [HachakuController::class, 'exportExcelDataTable'])->name('export_excel');
        });

        Route::group(['prefix' => 'jyomuin', 'as' => 'jyomuin.'], function () {
            Route::match(['get', 'post'], '/', [JyomuinController::class, 'index'])->name('index');
            Route::any('create', [JyomuinController::class, 'create'])->name('create');
            Route::post('/store', [JyomuinController::class, 'store'])->name('store');
            Route::match(['get', 'post'], '/edit/{jyomuinCd}', [JyomuinController::class, 'edit'])->name('edit');
            Route::post('/update/{jyomuinCd}', [JyomuinController::class, 'update'])->name('update');
            Route::delete('delete/{jyomuinCd}', [JyomuinController::class, 'destroy'])->name('destroy');
            Route::get('data-list', [JyomuinController::class, 'dataList'])->name('data_list');
            Route::post('/export-excel', [JyomuinController::class, 'exportExcelDataTable'])->name('export_excel');
        });

        Route::group(['prefix' => 'hinmoku', 'as' => 'hinmoku.'], function () {
            Route::match(['get', 'post'], '/', [HinmokuController::class, 'index'])->name('index');

            Route::any('create', [HinmokuController::class, 'create'])->name('create');

            Route::post('/store', [HinmokuController::class, 'store'])->name('store');

            Route::match(['get', 'post'], '/edit/{hinmokuCd}', [HinmokuController::class, 'edit'])->name('edit');

            Route::post('/update/{hinmokuCd}', [HinmokuController::class, 'update'])->name('update');

            Route::delete('delete/{hinmokuCd}', [HinmokuController::class, 'destroy'])->name('destroy');

            Route::get('data-list', [HinmokuController::class, 'dataList'])->name('data_list');

            Route::post('/export-excel', [HinmokuController::class, 'exportExcelDataTable'])->name('export_excel');
		});

        Route::group(['prefix' => 'hinmei', 'as' => 'hinmei.'], function () {
            Route::match(['get', 'post'], '/', [HinmeiController::class, 'index'])->name('index');

            Route::any('create', [HinmeiController::class, 'create'])->name('create');

            Route::post('/store', [HinmeiController::class, 'store'])->name('store');

            Route::match(['get', 'post'], '/edit/{hinmeiCd}', [HinmeiController::class, 'edit'])->name('edit');

            Route::post('/update/{hinmeiCd}', [HinmeiController::class, 'update'])->name('update');

            Route::delete('delete/{hinmeiCd}', [HinmeiController::class, 'destroy'])->name('destroy');

            Route::get('data-list', [HinmeiController::class, 'dataList'])->name('data_list');

            Route::post('/export-excel', [HinmeiController::class, 'exportExcelDataTable'])->name('export_excel');
		});

        Route::group(['prefix' => 'syaryo', 'as' => 'syaryo.'], function () {
            Route::match(['get', 'post'], '/', [SyaryoController::class, 'index'])->name('index');

            Route::any('create', [SyaryoController::class, 'create'])->name('create');

            Route::post('/store', [SyaryoController::class, 'store'])->name('store');

            Route::match(['get', 'post'], '/edit/{syaryoCd}', [SyaryoController::class, 'edit'])->name('edit');

            Route::post('/update/{syaryoCd}', [SyaryoController::class, 'update'])->name('update');

            Route::delete('delete/{syaryoCd}', [SyaryoController::class, 'destroy'])->name('destroy');

            Route::get('data-list', [SyaryoController::class, 'dataList'])->name('data_list');

            Route::post('/export-excel', [SyaryoController::class, 'exportExcelDataTable'])->name('export_excel');
        });

        Route::group(['prefix' => 'yousya', 'as' => 'yousya.'], function() {
            Route::match(['get', 'post'], '/', [YousyaController::class, 'index'])->name('index');
            Route::any('create', [YousyaController::class, 'create'])->name('create');
            Route::post('/store', [YousyaController::class, 'store'])->name('store');
            Route::match(['get', 'post'], '/edit/{yousyaCd}', [YousyaController::class, 'edit'])->name('edit');
            Route::post('/update/{yousyaCd}', [YousyaController::class, 'update'])->name('update');
            Route::delete('delete/{yousyaCd}', [YousyaController::class, 'destroy'])->name('destroy');
            Route::get('data-list', [YousyaController::class, 'dataList'])->name('data_list');
            Route::post('/export-excel', [YousyaController::class, 'exportExcelDataTable'])->name('export_excel');
        });

        Route::group(['prefix' => 'biko', 'as' => 'biko.'], function() {
            Route::match(['get', 'post'], '/', [BikoController::class, 'index'])->name('index');
            Route::any('create', [BikoController::class, 'create'])->name('create');
            Route::post('/store', [BikoController::class, 'store'])->name('store');
            Route::match(['get', 'post'], '/edit/{bikoCd}', [BikoController::class, 'edit'])->name('edit');
            Route::post('/update/{bikoCd}', [BikoController::class, 'update'])->name('update');
            Route::delete('delete/{bikoCd}', [BikoController::class, 'destroy'])->name('destroy');
            Route::get('data-list', [BikoController::class, 'dataList'])->name('data_list');
            Route::post('/export-excel', [BikoController::class, 'exportExcelDataTable'])->name('export_excel');
        });

        Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
            Route::match(['get', 'post'], '/', [UserController::class, 'index'])->name('index');
            Route::any('create', [UserController::class, 'create'])->name('create');
            Route::post('/store', [UserController::class, 'store'])->name('store');
            Route::match(['get', 'post'], '/edit/{userCd}', [UserController::class, 'edit'])->name('edit');
            Route::post('/update/{userCd}', [UserController::class, 'update'])->name('update');
            Route::delete('delete/{userCd}', [UserController::class, 'destroy'])->name('destroy');
            Route::get('data-list', [UserController::class, 'dataList'])->name('data_list');
            Route::post('/export-excel', [UserController::class, 'exportExcelDataTable'])->name('export_excel');
        });

        Route::group(['prefix' => 'soko', 'as' => 'soko.'], function () {
            Route::match(['get', 'post'], '/', [SokoController::class, 'index'])->name('index');

            Route::any('create', [SokoController::class, 'create'])->name('create');

            Route::post('/store', [SokoController::class, 'store'])->name('store');

            Route::match(['get', 'post'], '/edit/{sokoCd}/{bumonCd}', [SokoController::class, 'edit'])->name('edit');

            Route::post('/update/{sokoCd}/{bumonCd}', [SokoController::class, 'update'])->name('update');

            Route::delete('delete/{sokoCd}/{bumonCd}', [SokoController::class, 'destroy'])->name('destroy');

            Route::get('data-list', [SokoController::class, 'dataList'])->name('data_list');

            Route::post('/export-excel', [SokoController::class, 'exportExcelDataTable'])->name('export_excel');
        });

        Route::group(['prefix' => 'soko-hinmei', 'as' => 'soko_hinmei.'], function() {
            Route::match(['get', 'post'], '/', [SokoHinmeiController::class, 'index'])->name('index');
            Route::any('create', [SokoHinmeiController::class, 'create'])->name('create');
            Route::post('/store', [SokoHinmeiController::class, 'store'])->name('store');
            Route::match(['get', 'post'], '/edit/{ninusiCd}/{hinmeiCd}', [SokoHinmeiController::class, 'edit'])->name('edit');
            Route::match(['get', 'post'], '/copy/{ninusiCd}/{hinmeiCd}', [SokoHinmeiController::class, 'copy'])->name('copy');
            Route::post('/update/{ninusiCd}/{hinmeiCd}', [SokoHinmeiController::class, 'update'])->name('update');
            Route::post('/copy-data/{ninusiCd}/{hinmeiCd}', [SokoHinmeiController::class, 'postCopy'])->name('post_copy');
            Route::delete('delete/{ninusiCd}/{hinmeiCd}', [SokoHinmeiController::class, 'destroy'])->name('destroy');
            Route::get('data-list', [SokoHinmeiController::class, 'dataList'])->name('data_list');
            Route::post('/export-excel', [SokoHinmeiController::class, 'exportExcelDataTable'])->name('export_excel');
        });
    });
});
