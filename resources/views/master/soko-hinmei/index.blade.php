@extends('layouts.master')
@section('css')
  <style>
    .modify-position-suggest {
      margin-left: 0px;
      min-width: 320px;
      margin-top: 2px;
    }

    .btn-in-list {
      display: inline;
      padding: 4px 10px !important;
    }
  </style>
@endsection
@section('page-content')
<form method="" id="formSokoHinmei" class="form-custom">
<div class="card list-master-search-area">
  <div class="card-body">
    <div class="row">
      <div class="col-12 col-md-7">
        <div class="row">
          <div class="col-md-6">
            <div class="row" id="ninusi_cd__ninusi_nm">
              <label class="col-12 col-md-2 col-form-label text-nowrap ">荷主CD/名</label>&nbsp;&nbsp;
              <div class="col-12 col-md-10 row">
                <div class="col-md-4 p-0">
                    <input type="text" name="ninusi_cd" class="form-control" value="{{ $request->ninusi_cd }}"
                          onkeyup="suggestionForm(this, 'ninusi_cd', ['ninusi_cd', 'kana', 'ninusi_nm'], {ninusi_cd: 'ninusi_cd', ninusi_nm: 'ninusi_nm'}, $('#ninusi_cd__ninusi_nm') )"
                          autocomplete="off"
                    >
                    <ul class="suggestion modify-position-suggest"></ul>
                </div>
                <div class="col-md-8" style="padding-left: 4px">
                  <input type="text" name="ninusi_nm" class="form-control" value="{{ $request->ninusi_nm }}"
                    onkeyup="suggestionForm(this, 'ninusi_nm', ['ninusi_cd', 'kana', 'ninusi_nm'], {ninusi_cd: 'ninusi_cd', ninusi_nm: 'ninusi_nm'}, $('#ninusi_cd__ninusi_nm') )"
                    autocomplete="off"
                  >
                  <ul class="suggestion modify-position-suggest"></ul>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="row" id="hinmei_cd__hinmei_nm">
              <label class="col-12 col-md-2 col-form-label text-nowrap ">品名CD/名</label>&nbsp;&nbsp;
              <div class="col-12 col-md-10 row">
                <div class="col-md-4 p-0">
                  <input type="text" name="hinmei_cd" class="form-control" value="{{ $request->hinmei_cd }}"
                    onkeyup="suggestionForm(this, 'hinmei_cd', ['hinmei_cd', 'kana', 'hinmei_nm'], {hinmei_cd: 'hinmei_cd', hinmei_nm: 'hinmei_nm'}, $('#hinmei_cd__hinmei_nm') )"
                    autocomplete="off"
                  >
                    <ul class="suggestion modify-position-suggest"></ul>
                </div>

                <div class="col-md-8" style="padding-left: 4px">
                  <input type="text" name="hinmei_nm" class="form-control" value="{{ $request->hinmei_nm }}"
                    onkeyup="suggestionForm(this, 'hinmei_nm', ['hinmei_cd', 'kana', 'hinmei_nm'], {hinmei_cd: 'hinmei_cd', hinmei_nm: 'hinmei_nm'}, $('#hinmei_cd__hinmei_nm') )"
                    autocomplete="off"
                  >
                  <ul class="suggestion modify-position-suggest"></ul>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

      <div class="col-12 col-md-5" style="display: flex; justify-content: space-between;">
        <div class="form-check form-check-flat form-check-primary">
          <label class="form-check-label text-nowrap">
            <input type="checkbox" class="form-check-input" name="kyumin_flg" value="0"
                   @checked($request->kyumin_flg === '0' || ($request->isMethod('get') && !$request->has('kyumin_flg'))) >
            休眠非表示
            <i class="input-helper"></i>
          </label>
        </div>

        <div class="d-flex">
          <label class="col-form-label">&nbsp;</label>
          <div class="text-right" style="display: flex; flex-wrap: wrap; align-items: center;justify-content: flex-end;grid-gap: 10px; flex: 1">
            <button class="btn btn-clear min-wid-110" type="button" onclick="clearForm(this)">{{ trans('app.labels.btn-clear') }}</button>
            <button class="btn btn-search min-wid-110" type="button" onclick="searchList(this)">{{ trans('app.labels.btn-search') }}</button>
            <a href="#" data-href="{{route('master.soko_hinmei.create')}}" class="btn btn-insertNew" onclick="redirectForm(this, false, 'SokoHinmeiIndex', $('#table'))">{{ trans('app.labels.btn-insertNew') }}</a>
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
  var urlSearchSuggestion = false;
  var urlExportExcelDataTable = '{{ route('master.soko_hinmei.export_excel') }}';

  var pageNumber = {{ request() -> get('page') ?? 1 }};
  var columns = @json($setting);
  var searchDatas = @json(request() -> query());
  var columnsKeyBy = @json(\Illuminate\Support\Arr::keyBy($setting, 'field'), JSON_PRETTY_PRINT);

  var dataSuggestion = {};

  $('#table').customTable({
    urlData: '{!! route('master.soko_hinmei.data_list', request()->query()) !!}',
    showColumns: false,
    columns: columns,
    listButtonToolBar: listButtonToolBar,
    pageNumber: pageNumber,
    urlInsertDataRecord: '',
    urlUpdateDataRecord: '',
    formSearch: $('#formSokoHinmei'),
    urlCopyRecord: '',
    urlSearchSuggestion: '{!! route('master-suggestion') !!}',
    urlExportExcelDataTable: urlExportExcelDataTable,
    pageSize: {{ config()->get('params.PAGE_SIZE') }},
    textButtonExportExcel: '{{ trans('app.labels.btn-xls-export') }}',
    isShow: @if (!empty($request->isShowCustomTable)) true @else false @endif,
    defaultParams: {kyumin_flg: 0},
    isShowBtnExcel: false,
  });

  function displayBtnCopy(value, row, index)
  {
    var url = '{{ route('master.soko_hinmei.copy', ['ninusiCd' => ':ninusiCd', 'hinmeiCd' => ':hinmeiCd']) }}';
    url = url.replace(':ninusiCd', encodeURIComponent(row.ninusi_cd));
    url = url.replace(':hinmeiCd', encodeURIComponent(row.hinmei_cd));
    value = '{{ trans('app.labels.btn-copy') }}';
    return '<a href="#" data-href="' + url + '" class="btn btn-warning text-white rounded btn-in-list" onclick="redirectForm(this, false, \'SokoHinmeiIndex\', $(\'#table\'))">' + value + '</a>'
  }

  function displayBtnEdit(value, row, index) {
    var url = '{{ route('master.soko_hinmei.edit', ['ninusiCd' => ':ninusiCd', 'hinmeiCd' => ':hinmeiCd']) }}';
    url = url.replace(':ninusiCd', encodeURIComponent(row.ninusi_cd));
    url = url.replace(':hinmeiCd', encodeURIComponent(row.hinmei_cd));
    value = '{{ trans('app.labels.btn-detail') }}';
    return '<a href="#" data-href="' + url + '" class="btn btn-secondary text-white rounded btn-in-list" onclick="redirectForm(this, false, \'SokoHinmeiIndex\', $(\'#table\'))">' + value + '</a>'
  }

  function formatOndo(value, row, index)
  {
    return columnsKeyBy['ondo']['options'][value];
  }

  function formatZaikoKbn(value, row, index)
  {
    return columnsKeyBy['zaiko_kbn']['options'][value];
  }

  function formatKeisanKb(value, row, index)
  {
    return columnsKeyBy['keisan_kb']['options'][value];
  }

  function formatSeikyuKeta(value, row, index)
  {
    return columnsKeyBy['seikyu_keta']['options'][value];
  }

  function formatKyuminFlg(value, row, index)
  {
    return columnsKeyBy['kyumin_flg']['options'][value];
  }

  function searchList(e) {
    $('#content-list').css('display', 'block');
    $.fn.customTable.searchList();
  }

  function clearForm(e) {
    $(e).parents('form').find('select, input[type=text]').val('');
    $(e).parents('form').find('input[type="checkbox"]').prop('checked', true);
  }



</script>
@endsection
