@extends('layouts.master')
@section('css')
@endsection
@section('page-content')
  <form method="" id="formsoko">
    <div class="card form-custom list-master-search-area">
      <div class="card-body">
        <div class="row">
          <div class="col-12 col-md-7 align-self-center">
            <div class="row">
              <div class="col-md-5 align-self-center" id="bumon">
                <div class="row">
                  <label class="col-md-4 col-form-label text-nowrap py-0 align-self-center">部門コード/部門名称</label>
                  <div class="col-md-8 p-0 d-flex">
                    <input type="text" name="bumon_cd" maxlength="255" class="form-control size-L"
                    onkeyup="suggestionForm(this, 'bumon_cd', ['bumon_cd', 'bumon_nm', 'kana'], {bumon_cd: 'bumon_cd', bumon_nm: 'bumon_nm'}, $('#bumon'))"
                    autocomplete="off" value="{{ $request->bumon_cd }}" style="width: 40% !important">
                    <input type="text" name="bumon_nm" maxlength="255" class="form-control size-L"
                           onkeyup="suggestionForm(this, 'bumon_nm', ['bumon_cd', 'bumon_nm', 'kana'], {bumon_cd: 'bumon_cd', bumon_nm: 'bumon_nm'}, $('#bumon'))"
                           autocomplete="off" style="width: 60% !important">
                    <ul class="suggestion" style="width: calc(100% - 2rem);"></ul>
                  </div>
                </div>
              </div>
              <div class="col-md-5 align-self-center" id="soko">
                <div class="row">
                  <label class="col-md-4 col-form-label text-nowrap py-0 align-self-center">倉庫コード/倉庫名称</label>
                  <div class="col-md-8 p-0 d-flex">
                    <input type="text" name="soko_cd" maxlength="255" class="form-control size-L"
                    onkeyup="suggestionForm(this, 'soko_cd', ['soko_cd', 'soko_nm'], {soko_cd: 'soko_cd', soko_nm: 'soko_nm'}, $('#soko'))"
                    autocomplete="off" value="{{ $request->soko_cd }}" style="width: 40% !important">
                    <input type="text" name="soko_nm" maxlength="255" class="form-control size-L"
                         onkeyup="suggestionForm(this, 'soko_nm', ['soko_cd', 'soko_nm'], {soko_cd: 'soko_cd', soko_nm: 'soko_nm'}, $('#soko'))"
                         autocomplete="off" style="width: 60% !important">
                    <ul class="suggestion" style="width: calc(100% - 2rem);"></ul>
                  </div>
                </div>
              </div>
              <div class="col-md-2 d-flex justify-content-end align-items-center">
                <div class="form-check form-check-flat form-check-primary my-0 align-self-center">
                  <label class="form-check-label text-nowrap">
                    <input type="checkbox" maxlength="1" class="form-check-input" name="kyumin_flg" value="0"
                      @checked($request->kyumin_flg === '0' || ($request->isMethod('get') && !$request->has('kyumin_flg')))>
                    休眠非表示
                    <i class="input-helper"></i>
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-5 align-self-center">
            <div class="row">
              <div class="col-12 col-md-9 d-flex">
                  <label class="col-form-label">&nbsp;</label>
                  <div class="text-left"
                    style="display: flex; flex-wrap: wrap; align-items: center;justify-content: flex-start;grid-gap: 10px; flex: 1">
                    <button class="btn btn-clear min-wid-110" type="button"
                      onclick="clearForm(this)">{{ trans('app.labels.btn-clear') }}</button>
                    <button class="btn btn-search min-wid-110" type="button"
                      onclick="searchList(this)">{{ trans('app.labels.btn-search') }}</button>
                    <div class="columns columns-right btn-group"><a href="#"
                      data-href="{{ route('master.soko.create') }}" class="btn btn-insertNew"
                      onclick="redirectForm(this, false, 'sokoIndex', $('#table'))">{{ trans('app.labels.btn-insertNew') }}</a>
                    </div>
                  </div>
              </div>
              <div class="col-12 col-md-3">
                <div class="row d-flex justify-content-center align-items-center">
                  <button class="btn btn-success btn-xls-export min-wid-110" type="button" onclick="addExportExcelDataTableOutSide()">EXCEL出力
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
  <div class="mt-3" id="content-list"
    style="@if (!empty($request->isShowCustomTable)) {{ 'display: block' }} @else {{ 'display:none' }} @endif">
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
    var listButtonToolBar = ''
    var useAddFormFooter = true;
    var useCopyButton = false;
    var urlUpdateDataRecord = false;
    var urlExportExcelDataTable = '{{ route('master.soko.export_excel') }}';

    var pageNumber = {{ request()->get('page') ?? 1 }};
    var columns = @json($setting);
    var searchDatas = @json(request()->query());

    var dataSuggestion = {};
    var columnsKeyBy = @json(\Illuminate\Support\Arr::keyBy($setting, 'field'), JSON_PRETTY_PRINT);

    $('#table').customTable({
      urlData: '{!! route('master.soko.data_list', request()->query()) !!}',
      showColumns: false,
      columns: columns,
      listButtonToolBar: listButtonToolBar,
      pageNumber: pageNumber,
      urlInsertDataRecord: '',
      urlUpdateDataRecord: '',
      formSearch: $('#formsoko'),
      urlCopyRecord: '',
      urlSearchSuggestion: "{{ route('master-suggestion') }}",
      urlExportExcelDataTable: urlExportExcelDataTable,
      isShowBtnExcel: false,
      pageSize: {{ config()->get('params.PAGE_SIZE') }},
      textButtonExportExcel: '{{ trans('app.labels.btn-xls-export') }}',
      isShow: @if (!empty($request->isShowCustomTable)) true @else false @endif,
      defaultParams: {
        kyumin_flg: 0
      }
    });

    function formatSokoCd(value, row, index) {
      var url = '{{ route('master.soko.edit', ['sokoCd' => ':sokoCd', 'bumonCd' => ':bumonCd']) }}';
      url = url.replace(':sokoCd', encodeURIComponent(row.soko_cd));
      url = url.replace(':bumonCd', encodeURIComponent(row.bumon_cd));
      return '<a href="#" data-href="' + url +
        '" class="text-decoration" onclick="redirectForm(this, false, \'sokoIndex\', $(\'#table\'))">' + value + '</a>'
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

    function exportExcel() {
      var params = $('#table').customTable.getQueryParams();
      console.log(params);
    }
  </script>
@endsection
