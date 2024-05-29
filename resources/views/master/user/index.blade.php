@extends('layouts.master')
@section('css')
@endsection
@section('page-content')
  <form method="" id="formUser">
    <div class="card form-custom list-master-search-area">
      <div class="card-body">
        <div class="row">
          <div class="col-12 col-md-7">
            <div class="row">
              <div class="col-md-8">
                <div class="row">
                  <div class="col-md-6">
                    <div class="row">
                      <label class="col-12 col-md-4 col-form-label text-nowrap py-0">ユーザID</label>
                      <div class="col-12 col-md-8">
                        <input type="text" name="user_cd" class="form-control size-M" value="{{ $request->user_cd }}"
                               onkeydown="return event.key != 'Enter';">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="row">
                      <label class="col-12 col-md-5 col-form-label text-nowrap py-0">権限グループ</label>
                      <div class="col-12 col-md-7">
                        <select class="form-control size-M" name="group">
                          <option value=""></option>
                          @foreach (config()->get('params.options.m_user.group') as $key => $value)
                            <option value="{{ $key }}" @selected($key == old('group', $request->group))>{{ $value }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4 d-flex">
                <div class="form-check form-check-flat form-check-primary my-0 align-self-center">
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
          <div class="col-md-5">
            <div class="d-flex" style="align-items: center; justify-content: space-between;">
              <div class="text-right">
                <button class="btn btn-clear min-wid-110" type="button"
                  onclick="clearForm(this)">{{ trans('app.labels.btn-clear') }}</button>
                <button class="btn btn-search min-wid-110" type="button"
                  onclick="searchList(this)">{{ trans('app.labels.btn-search') }}</button>
                <a href="#" data-href="{{ route('master.user.create') }}" class="btn btn-insertNew"
                  onclick="redirectForm(this, false, 'UserIndex', $('#table'))">{{ trans('app.labels.btn-insertNew') }}</a>
              </div>
              <div class="d-flex">
                <label class="col-form-label">&nbsp;</label>
                <div class="text-right"
                  style="display: flex; flex-wrap: wrap; align-items: center;justify-content: flex-end;grid-gap: 10px; flex: 1">
                  <button class="btn btn-success min-wid-110" type="button"
                    onclick="addExportExcelDataTableOutSide()">{{ trans('app.labels.btn-xls-export') }}</button>
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
@endsection

@section('js')
  <script>
    var listButtonToolBar = '';
    var useAddFormFooter = true;
    var useCopyButton = false;
    var urlUpdateDataRecord = false;
    var urlSearchSuggestion = false;
    var urlExportExcelDataTable = '{{ route('master.user.export_excel') }}';

    var pageNumber = {{ request()->get('page') ?? 1 }};
    var columns = @json($setting);
    var searchDatas = @json(request()->query());

    var dataSuggestion = {};

    var columnsKeyBy = @json(\Illuminate\Support\Arr::keyBy($setting, 'field'), JSON_PRETTY_PRINT);

    $('#table').customTable({
      urlData: '{!! route('master.user.data_list', request()->query()) !!}',
      showColumns: false,
      columns: columns,
      listButtonToolBar: listButtonToolBar,
      pageNumber: pageNumber,
      urlInsertDataRecord: '',
      urlUpdateDataRecord: '',
      formSearch: $('#formUser'),
      urlCopyRecord: '',
      urlSearchSuggestion: '',
      urlExportExcelDataTable: urlExportExcelDataTable,
      pageSize: {{ config()->get('params.PAGE_SIZE') }},
      textButtonExportExcel: '{{ trans('app.labels.btn-xls-export') }}',
      isShow: @if (!empty($request->isShowCustomTable))
        true
      @else
        false
      @endif ,
      defaultParams: {
        kyumin_flg: 0
      },
      isShowBtnExcel: false,
    });

    function formatUserCd(value, row, index) {
      var url = '{{ route('master.user.edit', ['userCd' => ':userCd']) }}';
      url = url.replace(':userCd', row.user_cd);
      return '<a href="#" data-href="' + url +
        '" class="text-decoration" onclick="redirectForm(this, false, \'UserIndex\', $(\'#table\'))">' + value + '</a>'
    }

    function formatUserKbn(value, row, index, name) {
      return columnsKeyBy[name]['options'][value];
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
