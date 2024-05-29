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
<form method="" id="formBiko" class="form-custom">
<div class="card list-master-search-area">
  <div class="card-body">
    <div class="row">
      <div class="col-12 col-md-7">
        <div class="row" id="biko_cd__biko_nm">
          <div class="col-md-5">
            <div class="row">
              <label class="col-12 col-md-4 col-form-label text-nowrap ">{{ trans('attributes.m_biko.biko_cd') }}</label>
              <div class="col-12 col-md-8">
                <input type="text" name="biko_cd" class="form-control size-M" value="{{ $request->biko_cd }}"
                      onkeyup="suggestionForm(this, 'biko_cd', ['biko_cd', 'biko_nm', 'kana'], {biko_cd: 'biko_cd', biko_nm: 'biko_nm'}, $('#biko_cd__biko_nm') )"
                       autocomplete="off"
                >
                <ul class="suggestion modify-position-suggest"></ul>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-5">
            <div class="row">
              <label class="col-12 col-md-4 col-form-label text-nowrap ">{{ trans('attributes.m_biko.biko_nm') }}</label>
              <div class="col-12 col-md-8">
                <input type="text" name="biko_nm" class="form-control size-2L" value="{{ $request->biko_nm }}"
                    onkeyup="suggestionForm(this, 'biko_nm', ['biko_cd', 'biko_nm', 'kana'], {biko_cd: 'biko_cd', biko_nm: 'biko_nm'}, $('#biko_cd__biko_nm') )"
                       autocomplete="off"
                >
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
            <a href="#" data-href="{{route('master.biko.create')}}" class="btn btn-insertNew" onclick="redirectForm(this, false, 'BikoIndex', $('#table'))">{{ trans('app.labels.btn-insertNew') }}</a>
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
  var urlExportExcelDataTable = '{{ route('master.biko.export_excel') }}';
  var isShowBtnExcel = false;

  var pageNumber = {{ request() -> get('page') ?? 1 }};
  var columns = @json($setting);
  var searchDatas = @json(request() -> query());
  var columnsKeyBy = @json(\Illuminate\Support\Arr::keyBy($setting, 'field'), JSON_PRETTY_PRINT);

  var dataSuggestion = {};

  $('#table').customTable({
    urlData: '{!! route('master.biko.data_list', request()->query()) !!}',
    showColumns: false,
    columns: columns,
    listButtonToolBar: listButtonToolBar,
    pageNumber: pageNumber,
    urlInsertDataRecord: '',
    urlUpdateDataRecord: '',
    formSearch: $('#formBiko'),
    urlCopyRecord: '',
    urlSearchSuggestion: '{!! route('master-suggestion') !!}',
    urlExportExcelDataTable: urlExportExcelDataTable,
    pageSize: {{ config()->get('params.PAGE_SIZE') }},
    textButtonExportExcel: '{{ trans('app.labels.btn-xls-export') }}',
    isShow: @if (!empty($request->isShowCustomTable)) true @else false @endif,
    defaultParams: {kyumin_flg: 0},
    isShowBtnExcel: false,
  });

  function formatBikoCd(value, row, index) {
    var url = '{{ route('master.biko.edit', ['bikoCd' => ':bikoCd']) }}';
    url = url.replace(':bikoCd', encodeURIComponent(row.biko_cd));
    return '<a href="#" data-href="' + url + '" class="text-decoration" onclick="redirectForm(this, false, \'BikoIndex\', $(\'#table\'))">' + value + '</a>'
  }

  function formatSyubetuKbn(value, row, index)
  {
    return columnsKeyBy['syubetu_kbn']['options'][value];
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
