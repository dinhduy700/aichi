@extends('layouts.master')
@section('css')
@endsection
@section('page-content')
  <form method="" id="formNinusi" class="form-custom">
    <div class="card list-master-search-area">
      <div class="card-body">
        <div class="row">
          <div class="col-12 col-md-7">
            <div class="row" id="input_search">
              <div class="col-md-5">
                <div class="row">
                  <label class="col-12 col-md-4 col-form-label text-nowrap ">荷主コード</label>
                  <div class="col-12 col-md-8">
                    <input type="text" name="ninusi_cd" id="ninusi_cd" class="form-control size-M"
                           onkeyup="suggestionForm(this, 'ninusi_cd', ['ninusi_cd', 'kana','ninusi_ryaku_nm'], {'ninusi_cd':'ninusi_cd','ninusi_ryaku_nm':'ninusi_ryaku_nm'}, $('#input_search'))"
                           autocomplete="off" value="{{ $request->ninusi_cd }}">
                    <ul class="suggestion mx-3" style="width: calc(100% - 2rem);"></ul>
                  </div>
                </div>
              </div>
              <div class="col-md-5">
                <div class="row">
                  <label class="col-12 col-md-4 col-form-label text-nowrap ">荷主名</label>
                  <div class="col-12 col-md-8">
                    <input type="text" name="ninusi_ryaku_nm" id="ninusi_ryaku_nm" class="form-control size-2L"
                           onkeyup="suggestionForm(this, 'ninusi_nm', ['ninusi_cd', 'kana','ninusi_ryaku_nm'], {'ninusi_cd':'ninusi_cd','ninusi_ryaku_nm':'ninusi_ryaku_nm'}, $('#input_search'))"
                           autocomplete="off" value="{{ $request->ninusi_ryaku_nm }}">
                    <ul class="suggestion mx-3" style="width: calc(100% - 2rem);"></ul>
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
                <a href="#" data-href="{{route('master.ninusi.create')}}" class="btn btn-insertNew" onclick="redirectForm(this, false, 'NinusiIndex', $('#table'))">{{ trans('app.labels.btn-insertNew') }}</a>
              </div>
            </div>

            <div style="display: flex; align-items: center;">
              <a href="#" class="btn btn-success min-wid-110" onclick="addExportExcelDataTableOutSide(this)">{{ trans('app.labels.btn-xls-export') }}</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-3 @if (empty($request->isShowCustomTable)) d-none @endif" id="content-list">
      <div class="card grid">
        <div class="card-body">
          <div>
            <table id="table" class="hansontable" data-sticky-columns="['id']">
            </table>
          </div>
        </div>
      </div>
    </div>
  </form>
@endsection

@section('js')
  <script>
    var listButtonToolBar = '';
    var useAddFormFooter = true;
    var useCopyButton = false;
    var urlUpdateDataRecord = false;
    var urlExportExcelDataTable = '{{ route('master.ninusi.export_excel') }}';

    var pageNumber = {{ request()->get('page') ?? 1 }};
    var columns = @json($setting);
    var searchDatas = @json(request()->query());
    var dataSuggestion = {};
    var columnsKeyBy = @json(\Illuminate\Support\Arr::keyBy($setting, 'field'), JSON_PRETTY_PRINT);

    $('#table').customTable({
      urlData: '{!! route('master.ninusi.data_list', request()->query()) !!}',
      showColumns: false,
      columns: columns,
      listButtonToolBar: listButtonToolBar,
      pageNumber: pageNumber,
      urlInsertDataRecord: '',
      urlUpdateDataRecord: '',
      formSearch: $('#formNinusi'),
      urlCopyRecord: '',
      urlSearchSuggestion: "{{ route('master-suggestion') }}",
      urlExportExcelDataTable: urlExportExcelDataTable,
      pageSize: {{ config()->get('params.PAGE_SIZE') }},
      textButtonExportExcel: '{{ trans('app.labels.btn-xls-export') }}',
      isShow: @if (!empty($request->isShowCustomTable)) true @else false @endif,
      defaultParams: {kyumin_flg: 0},
	    isShowBtnExcel: false,
    });

    function formatNinusi(value, row, index) {
      return value;
    }

    function formatNinusiKbn(value, row, index, name) {
      return columnsKeyBy[name]['options'][value];
    }

    function formatNinusiCd(value, row, index) {
      var url = '{{ route('master.ninusi.edit', ['ninusiCd' => ':ninusiCd']) }}';
      url = url.replace(':ninusiCd', row.ninusi_cd);
      return '<a href="#" data-href="' + url +
        '" class="text-decoration" onclick="redirectForm(this, false, \'NinusiIndex\', $(\'#table\'))">' + value + '</a>'
    }

    function clearForm(e) {
      $(e).parents('form').find('select, input').val('');
      $(e).parents('form').find('input[type="checkbox"]').prop('checked', true);
    }

    function searchList(e) {
      $('#content-list').removeClass('d-none');
      $.fn.customTable.searchList();
    }
  </script>
@endsection
