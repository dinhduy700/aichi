@extends('layouts.master')
@section('css')
@endsection
@section('page-content')
<form method="" id="formMeisyo" class="form-custom">
<div class="card list-master-search-area">
  <div class="card-body">
      <div class="row">
        <div class="col-12 col-md-7">
          <div class="row">
            <div class="col-md-3">
              <div class="row">
                <label class="col-12 col-md-4 col-form-label text-nowrap">名称区分</label>
                <div class="col-12 col-md-8">
                  <select class="form-control size-2L" name="meisyo_kbn">
                    <option value=""></option>
                    @foreach(config()->get('params.options.m_meisyo.meisyo_kbn') as $key => $value)
                    <option value="{{ $key }}"  @selected($key == old('meisyo_kbn', $request->meisyo_kbn)) >{{ $value }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="row">
                <label class="col-md-4 col-form-label text-nowrap py-0 align-self-center">ヨミガナ</label>
                <div class="col-md-8">
                  <input type="text" name="meisyo_kana" maxlength="255" class="form-control size-L"
                         onkeyup="suggestionForm(this, 'meisyo_kana', ['meisyo_kbn', 'kana', 'meisyo_cd'], {meisyo_kbn: 'meisyo_kbn', meisyo_cd: 'meisyo_cd', kana: 'meisyo_kana'}, $('#formHinmei'))"
                         autocomplete="off" value="{{ $request->meisyo_kana }}">
                  <ul class="suggestion mx-3"></ul>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="row">
                <label class="col-12 col-md-4 col-form-label text-nowrap">名称コード</label>
                <div class="col-12 col-md-8">
                  <input type="text" name="meisyo_cd" class="form-control size-M" value="{{ $request->meisyo_cd }}">
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

        <div class="col-12 col-md-5">
          <div class="d-flex" style="justify-content: space-between; align-items: center;">
            <div class="d-flex">
              <label class="col-form-label">&nbsp;</label>
              <div class="text-right" style="display: flex; flex-wrap: wrap; align-items: center;justify-content: flex-end;grid-gap: 10px; flex: 1">
                <button class="btn btn-clear min-wid-110" type="button" onclick="clearForm(this)">{{ trans('app.labels.btn-clear') }}</button>
                <button class="btn btn-search min-wid-110" type="button" onclick="searchList(this)">{{ trans('app.labels.btn-search') }}</button>
                <a href="#" data-href="{{route('master.meisyo.create')}}"
                     class="btn btn-insertNew"
                     onclick="redirectForm(this, false, 'MeisyoIndex', $('#table'))">{{ trans('app.labels.btn-insertNew') }}</a>
              </div>
            </div>
            <div class="d-flex" style="align-items: center;">
              <label class="col-form-label">&nbsp;</label>
              <div>
                <button class="btn btn-success min-wid-110" type="button" onclick="addExportExcelDataTableOutSide()">{{ trans('app.labels.btn-xls-export') }}</button>
              </div>
            </div>
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
  var urlExportExcelDataTable = '{{ route('master.meisyo.export_excel') }}';

  var pageNumber = {{ request() -> get('page') ?? 1 }};
  var columns = @json($setting);
  var searchDatas = @json(request() -> query());

  var dataSuggestion = {};

  var columnsKeyBy = @json(\Illuminate\Support\Arr::keyBy($setting, 'field'), JSON_PRETTY_PRINT);

  $('#table').customTable({
      urlData: '{!! route('master.meisyo.data_list', request()->query()) !!}',
      showColumns: false,
      columns: columns,
      listButtonToolBar: listButtonToolBar,
      pageNumber: pageNumber,
      urlInsertDataRecord: '',
      urlUpdateDataRecord: '',
      formSearch: $('#formMeisyo'),
      urlCopyRecord: '',
      urlSearchSuggestion: '',
      urlExportExcelDataTable: urlExportExcelDataTable,
      pageSize: {{ config()->get('params.PAGE_SIZE') }},
      textButtonExportExcel: '{{ trans('app.labels.btn-xls-export') }}',
      isShow: @if (!empty($request->isShowCustomTable)) true @else false @endif,
      defaultParams: {kyumin_flg: 0},
      isShowBtnExcel: false,
      sortName:''
  });

  function formatMeisyoCd(value, row, index) {
    var url = '{{ route('master.meisyo.edit', ['meisyoCd' => ':meisyoCd', 'meisyoKbn' => ':meisyoKbn']) }}';
    url = url.replace(':meisyoCd', encodeURIComponent(row.meisyo_cd));
    url = url.replace(':meisyoKbn', encodeURIComponent(row.meisyo_kbn));
    return '<a href="#" data-href="' + url + '" class="text-decoration" onclick="redirectForm(this, false, \'MeisyoIndex\', $(\'#table\'))">' + value + '</a>'
  }

  function formatMeisyoKbn(value, row, index) {
    return value;
  }

  function formatKyuminFlg(value, row, index) {
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
