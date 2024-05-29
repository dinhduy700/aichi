@extends('layouts.master')
@section('css')
  <style>
    .modify-position-suggest {
      margin-left: 15px;
      width: 92%;
      margin-top: 2px;
    }
  </style>
@endsection
@section('page-content')
<form method="" id="formYousya" class="form-custom">
<div class="card list-master-search-area">
  <div class="card-body">
    <div class="row">
      <div class="col-12 col-md-7">
        <div class="row" id="yousya_cd__yousya_ryaku_nm">
          <div class="col-md-5">
            <div class="row">
              <label class="col-12 col-md-4 col-form-label text-nowrap ">{{ trans('attributes.m_yousya.yousya_cd') }}</label>
              <div class="col-12 col-md-8">
                  <input type="text" name="yousya_cd" class="form-control size-M" value="{{ $request->yousya_cd }}"
                         onkeyup="suggestionForm(this, 'yousya_cd', ['yousya_cd', 'yousya_ryaku_nm', 'kana'], {yousya_cd: 'yousya_cd', yousya_ryaku_nm: 'yousya_ryaku_nm'}, $('#yousya_cd__yousya_ryaku_nm') )"
                         autocomplete="off" >
                  <ul class="suggestion modify-position-suggest"></ul>
              </div>
            </div>
          </div>
          <div class="col-md-5">
            <div class="row">
              <label class="col-12 col-md-4 col-form-label text-nowrap ">{{ trans('attributes.m_yousya.yousya_ryaku_nm') }}</label>
              <div class="col-12 col-md-8">
                <input type="text" name="yousya_ryaku_nm" class="form-control size-2L" value="{{ $request->yousya_ryaku_nm }}"
                       onkeyup="suggestionForm(this, 'yousya_ryaku_nm', ['yousya_cd', 'yousya_ryaku_nm', 'kana'], {yousya_cd: 'yousya_cd', yousya_ryaku_nm: 'yousya_ryaku_nm'}, $('#yousya_cd__yousya_ryaku_nm') )"
                       autocomplete="off" >
                <ul class="suggestion modify-position-suggest"></ul>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-2" style="align-self: center;">
            <div class="row" >
              <div class="form-check form-check-flat form-check-primary" style="margin-top: 0px; margin-bottom: 0;">
                <label class="form-check-label text-nowrap">
                  <input type="checkbox" class="form-check-input" name="kyumin_flg" value="0"
                         @checked($request->kyumin_flg === '0' || ($request->isMethod('get') && !$request->has('kyumin_flg'))) >
                  休眠非表示
                  <i class="input-helper"></i>
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-5" style="display: flex; justify-content: space-between;">
        <div class="d-flex">
          <label class="col-form-label">&nbsp;</label>
          <div class="text-right" style="display: flex; flex-wrap: wrap; align-items: center;justify-content: flex-end;grid-gap: 10px; flex: 1">
            <button class="btn btn-clear min-wid-110" type="button" onclick="clearForm(this)">{{ trans('app.labels.btn-clear') }}</button>
            <button class="btn btn-search min-wid-110" type="button" onclick="searchList(this)">{{ trans('app.labels.btn-search') }}</button>
            <a href="#" data-href="{{route('master.yousya.create')}}" class="btn btn-insertNew" onclick="redirectForm(this, false, 'YousyaIndex', $('#table'))">{{ trans('app.labels.btn-insertNew') }}</a>
          </div>
        </div>

        <div style="display: flex; align-items: center;">
          <a href="#" class="btn btn-success min-wid-110" onclick="addExportExcelDataTableOutSide(this)">{{ trans('app.labels.btn-xls-export') }}</a>
        </div>
      </div>
    </div>
  </div>
</div>

</form>

<div class="mt-3" id="content-list" style="@if (!empty($request->isShowCustomTable)) {{  'display: block' }} @else {{'display:none'}} @endif">
  <div class="card">
    <div class="card-body">
      <div>
        <table id="table" class="hansontable" data-sticky-columns="['id']">
        </table>
      </div>
    </div>
  </div>
</div>
<style type="text/css">

</style>
@endsection

@section('js')
<script>
  var listButtonToolBar = '';
  var useAddFormFooter = true;
  var useCopyButton = false;
  var urlUpdateDataRecord = false;
  var urlSearchSuggestion = '';
  var urlExportExcelDataTable = '{{ route('master.yousya.export_excel') }}';
  var isShowBtnExcel = false;

  var columnsKeyBy = @json(\Illuminate\Support\Arr::keyBy($setting, 'field'), JSON_PRETTY_PRINT);

  var pageNumber = {{ request() -> get('page') ?? 1 }};
  var columns = @json($setting);
  var searchDatas = @json(request() -> query());

  var dataSuggestion = {};

  $('#table').customTable({
    urlData: '{!! route('master.yousya.data_list', request()->query()) !!}',
    showColumns: false,
    columns: columns,
    listButtonToolBar: listButtonToolBar,
    pageNumber: pageNumber,
    urlInsertDataRecord: '',
    urlUpdateDataRecord: '',
    formSearch: $('#formYousya'),
    urlCopyRecord: '',
    urlSearchSuggestion: '{!! route('master-suggestion') !!}',
    urlExportExcelDataTable: urlExportExcelDataTable,
    pageSize: {{ config()->get('params.PAGE_SIZE') }},
    textButtonExportExcel: '{{ trans('app.labels.btn-xls-export') }}',
    isShow: @if (!empty($request->isShowCustomTable)) true @else false @endif,
    defaultParams: {kyumin_flg: 0},
    isShowBtnExcel: false,
  });



  function formatYousyaCd(value, row, index)
  {
    var url = '{{ route('master.yousya.edit', ['yousyaCd' => ':yousyaCd']) }}';
    url = url.replace(':yousyaCd', encodeURIComponent(row.yousya_cd));
    return '<a href="#" data-href="' + url + '" class="text-decoration" onclick="redirectForm(this, false, \'YousyaIndex\', $(\'#table\'))">' + value + '</a>'
  }

  function formatKyuminFlg(value, row, index)
  {
    return columnsKeyBy['kyumin_flg']['options'][value];
  }

  function formatSiharaiKbn(value, row, index)
  {
    return columnsKeyBy['siharai_kbn']['options'][value];
  }

  function formatSiharaiUmuKbn(value, row, index)
  {
    return columnsKeyBy['siharai_umu_kbn']['options'][value];
  }

  function formatMikakuteiSeigyoKbn(value, row, index)
  {
    return columnsKeyBy['mikakutei_seigyo_kbn']['options'][value];
  }

  function formatKinHasuKbn(value, row, index)
  {
    return columnsKeyBy['kin_hasu_kbn']['options'][value];
  }

  function formatKinHasuTani(value, row, index)
  {
    return columnsKeyBy['kin_hasu_tani']['options'][value];
  }

  function formatZeiKeisanKbn(value, row, index)
  {
    return columnsKeyBy['zei_keisan_kbn']['options'][value];
  }

  function formatZeiHasuKbn(value, row, index)
  {
    return columnsKeyBy['zei_hasu_kbn']['options'][value];
  }

  function formatZeiHasuTani(value, row, index)
  {
    return columnsKeyBy['zei_hasu_tani']['options'][value];
  }

  function formatSiharaiNyuryokuUmuKbn(value, row, index)
  {
    return columnsKeyBy['siharai_nyuryoku_umu_kbn']['options'][value];
  }

  function formatKensakuKbn(value, row, index)
  {
    return columnsKeyBy['kensaku_kbn']['options'][value];
  }

  function searchList(e)
  {
    $('#content-list').css('display', 'block');
    $.fn.customTable.searchList();
  }

  function clearForm(e)
  {
    $(e).parents('form').find('select, input[type=text]').val('');
    $(e).parents('form').find('input[type="checkbox"]').prop('checked', true);
  }
</script>
@endsection
