@extends('layouts.master')
@section('css')
  <style>
    .error-message-row {
      display: inherit;
    }
  </style>
@endsection
@section('page-content')
  <form id="formSearchUkebaraiShoukai" class="form-custom">
    <div class="card">
      <div class="card-body">
        <div class="form-group row">
          <div class="col-12 col-md-5">
            <div class="row">
              <div class="col-md-2 col-form-label">
                <label class="mb-0">部門名</label>
              </div>
              <div class="col-md-10 p-0">
                <input class="form-control col-md-6" name="search_bumon_nm" value="{{ $dataInit['bumon_nm'] }}" disabled
                       onkeyup="suggestionForm(this, 'bumon_nm', ['bumon_cd', 'kana', 'bumon_nm'], {'bumon_nm': 'search_bumon_nm'}, $(this).parent())">
                <ul class="suggestion"></ul>
                <span class="error-message-row" id="search_bumon_nm"></span>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-7">
            <div class="row">
              <div class="col-md-6 col-form-label text-right">
                <label class="mb-0">日付範囲</label>
              </div>
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-5 p-0">
                    <input type="text" class="form-control" name="search_kisan_dt_from"
                           value="{{ $dataInit['kisan_dt_from'] }}"
                           onchange="autoFillDate(this)">
                    <span class="error-message-row" id="search_kisan_dt_from"></span>
                  </div>
                  <div class="col-md-2 col-form-label text-center">
                    <label class="mb-0">～</label>
                  </div>
                  <div class="col-md-5 p-0">
                    <input type="text" class="form-control" name="search_kisan_dt_to"
                           value="{{ $dataInit['kisan_dt_to'] }}"
                           onchange="autoFillDate(this)">
                    <span class="error-message-row" id="search_kisan_dt_to"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-12 col-md-5">
            <div class="row">
              <div class="col-md-2 col-form-label">
                <label class="mb-0">荷主名</label>
              </div>
              <div class="col-md-10 p-0">
                <input class="form-control col-md-6" name="search_ninusi_ryaku_nm"
                       value="{{ $dataInit['ninusi_ryaku_nm'] }}"
                       onkeyup="suggestionForm(this, 'ninusi_nm', ['ninusi_cd', 'kana', 'ninusi_ryaku_nm'], {'ninusi_ryaku_nm': 'search_ninusi_ryaku_nm'}, $(this).parent())"
                       disabled>
                <ul class="suggestion"></ul>
                <span class="error-message-row" id="search_ninusi_ryaku_nm"></span>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-12 col-md-5">
            <div class="row">
              <div class="col-md-2 col-form-label">
                <label class="mb-0">商品名</label>
              </div>
              <div class="col-md-10 p-0">
                <input type="text" class="form-control" name="search_soko_hinmei_nm"
                       value="{{ $dataInit['hinmei_nm'] }}"
                       onkeyup="suggestionForm(this, 'soko_hinmei_cd', ['hinmei_cd', 'kana', 'hinmei_nm'], {'hinmei_nm': 'search_soko_hinmei_nm'}, $(this).parent())"
                       disabled>
                <ul class="suggestion"></ul>
                <span class="error-message-row" id="search_soko_hinmei_nm"></span>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-12 col-md-5">
            <div class="row">
              <div class="col-md-2">
              </div>
              <div class="col-md-10">
                <div class="row">
                  <input type="text" class="form-control col-md-6" name="search_soko_hinmei_kikaku"
                         value="{{ $dataInit['kikaku'] }}" disabled>
                  <div class="col-md-2 col-form-label text-right">
                    <label class="mb-0">入数</label>
                  </div>
                  <input type="text" class="form-control col-md-4" name="search_soko_hinmei_irisu"
                         value="{{ $dataInit['irisu'] }}" disabled>
                  
                  <input type="text" class="form-control col-md-4" name="search_ninusi_cd" hidden="hidden"
                         value="{{ $ninusiCd }}">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-10"></div>
              <div class="col-md-2 col-form-label text-right p-0">
                <button class="btn btn-search min-wid-110" type="button"
                        onclick="searchList(this)">{{ trans('app.labels.btn-search') }}</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
  <div class="mt-2" id="content-list" style="display: none">
    <div class="card">
      <div class="card-body" style="padding: 10px;">
        <div class="form-custom ">
          <div class="table-pagi-top">
            <table id="table" class="hansontable" data-sticky-columns="['id']">
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('js')
  <script>
    var urlData = '{!! route('zaiko_shoukai.ukebarai_shoukai.data_list', request()->query()) !!}';
    var pageNumber = {{ request() -> get('page') ?? 1 }};
    var searchDatas = @json(request() -> query());
    var urlSearchSuggestion = '{{ route('master-suggestion') }}';
    var pageSize = {{ config()->get('params.PAGE_SIZE') }};
    var formSearch = $('#formSearchUkebaraiShoukai');
    var configNyusyukoKbn = @json($configNyusyukoKbn);
    function createCustomTable(columnsTable) {
      $('#table').customTable({
        urlData: urlData,
        columns: columnsTable,
        pageNumber: pageNumber,
        formSearch: formSearch,
        urlSearchSuggestion: urlSearchSuggestion,
        pageSize: pageSize,
      });
    }
    
    function searchList(e) {
      var data = $('#formSearchUkebaraiShoukai').serialize();
      $.ajax({
        url: '{{route('zaiko_shoukai.ukebarai_shoukai.validate_form_search_ukebarai_shoukai')}}',
        method: 'POST',
        data: data,
        success: function (res) {
          $("#content-list").css('display', 'block');
          if ($('.bootstrap-table').length > 0) {
            $.fn.customTable.destroy();
          }
          createCustomTable(res.setting)
          $.fn.customTable.searchList();
          $('#formSearchUkebaraiShoukai .error-message-row').html('');
          $('#formSearchUkebaraiShoukai .error-input').removeClass('error-input');
        },
        error: function (error) {
          if (error.status == 422) {
            $('.error-message-row').html('');
            $('.error-input').removeClass('error-input');
            var errors = error.responseJSON.errors;
            $.each(errors, function (key, value) {
              $('input[name="' + key + '"]').addClass('error-input');
              $('span[id="' + key + '"]').html(value);
            });
          }
        }
      });
    }
    
    function formatNyusyukoKbn(value, row, index, name) {
      return configNyusyukoKbn[row.nyusyuko_kbn]
    }
    
    $(function() {
      searchList()
    });
  </script>
@endsection