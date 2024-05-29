@extends('layouts.master')
@section('css')
@endsection
@section('page-content')
  <form method="" id="formSyaryo" class="form-custom">
    <div class="card list-master-search-area">
      <div class="card-body">
        <div class="row">
          <div class="col-12 col-md-7">
            <div class="row">
              <div class="col-md-4">
                <div class="row">
                  <label class="col-12 col-md-5 col-form-label text-nowrap ">車両コード</label>
                  <div class="col-12 col-md-7">
                    <input type="text" name="syaryo_cd" class="form-control size-M" value="{{ $request->syaryo_cd }}"
                           onkeydown="return event.key != 'Enter';">
                  </div>
                </div>
              </div>
              <div class="col-md-6 row" style="align-self: center;">
                <div class="form-check form-check-flat form-check-primary my-0">
                  <label class="form-check-label text-nowrap">
                    <input type="checkbox" class="form-check-input" name="kyumin_flg" value="0"
                      @checked($request->kyumin_flg === '0' || ($request->isMethod('get') && !$request->has('kyumin_flg')))>
                    休眠非表示
                    <i class="input-helper"></i>
                  </label>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-md-5">
            <div class="d-flex" style="justify-content: space-between; align-items: center;">
              <div class="d-flex">
                <div class="text-right"
                     style="display: flex; flex-wrap: wrap; align-items: center;justify-content: flex-end;grid-gap: 10px; flex: 1">
                  <button class="btn btn-clear min-wid-110" type="button"
                          onclick="clearForm(this)">{{ trans('app.labels.btn-clear') }}</button>
                  <button class="btn btn-search min-wid-110" type="button"
                          onclick="searchList(this)">{{ trans('app.labels.btn-search') }}</button>
                  <a href="#" data-href="{{ route('master.syaryo.create') }}" class="btn btn-insertNew"
                     onclick="redirectForm(this, false, 'SyaryoIndex', $('#table'))">{{ trans('app.labels.btn-insertNew') }}</a>
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

  <div class="mt-3" id="content-list"
       style="@if (!empty($request->isShowCustomTable)) {{  'display: block' }} @else {{'display:none'}} @endif">
    <div class="card">
      <div class="card-body">
        <div>
          <table id="table" class="hansontable" data-sticky-columns="['id']">
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('js')
  <script>
    var listButtonToolBar = '';
    var useAddFormFooter = true;
    var useCopyButton = false;
    var urlUpdateDataRecord = false;
    var urlSearchSuggestion = false;
    var urlExportExcelDataTable = '{{ route('master.syaryo.export_excel') }}';

    var pageNumber = {{ request()->get('page') ?? 1 }};
    var columns = @json($setting);
    var searchDatas = @json(request()->query());

    var dataSuggestion = {};

    var columnsKeyBy = @json(\Illuminate\Support\Arr::keyBy($setting, 'field'), JSON_PRETTY_PRINT);

    $('#table').customTable({
      urlData: '{!! route('master.syaryo.data_list', request()->query()) !!}',
      showColumns: false,
      columns: columns,
      listButtonToolBar: listButtonToolBar,
      pageNumber: pageNumber,
      urlInsertDataRecord: '',
      urlUpdateDataRecord: '',
      formSearch: $('#formSyaryo'),
      urlCopyRecord: '',
      urlSearchSuggestion: '',
      urlExportExcelDataTable: urlExportExcelDataTable,
      pageSize: {{ config()->get('params.PAGE_SIZE') }},
      textButtonExportExcel: '{{ trans('app.labels.btn-xls-export') }}',
      isShow: @if (!empty($request->isShowCustomTable)) true @else false @endif,
      defaultParams: {kyumin_flg: 0},
	    isShowBtnExcel: false,
    });

    function formatSyaryoCd(value, row, index) {
      var url = '{{ route('master.syaryo.edit', ['syaryoCd' => ':syaryoCd']) }}';
      url = url.replace(':syaryoCd', row.syaryo_cd);
      return '<a href="#" data-href="' + url +
        '" class="text-decoration" onclick="redirectForm(this, false, \'SyaryoIndex\', $(\'#table\'))">' + value + '</a>'
    }

    function formatSyaryo(value, row, index) {
      return value;
    }

    function formatSyaryoKbn(value, row, index, name) {
      return columnsKeyBy[name]['options'][value];
    }

    function formatDate(value, row, index) {
      if (value) {
        let date = new Date(value);
        return $.datepicker.formatDate("yy/mm/dd", date);
      }
      return value;
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
