@extends('layouts.master')
@section('css')
  <style>
    .border-right {
      border-top-right-radius: 0;
      border-bottom-right-radius: 0;
    }

    .border-left {
      border-top-left-radius: 0;
      border-bottom-left-radius: 0;
    }

    .error-message-row {
      display: inherit;
    }
  
  </style>
@endsection
@section('page-content')
  @php
    $currentDate = date("Y/m/d");
  @endphp
  <div class="form-group row">
    <div class="col">
      <button class="btn btn-primary min-wid-110 btn-xls-export" type="button"
              onclick="submitToUrl(this, '{{ route('nyusyuko.zaikoList.zaikoListFilterForm') }}')">印刷
      </button>
    </div>
  </div>
  <form id="formSearchZaikoShoukai" class="form-custom">
    <div class="card">
      <div class="card-body">
        <div class="form-group row">
          <div class="col-12 col-md-5">
            <div class="row">
              <div class="col-md-2 col-form-label">
                <label class="mb-0">部門</label>
              </div>
              <div class="col-md-10">
                <div class="row">
                  <input type="text" class="form-control border-right col-md-5" name="search_bumon_cd"
                         onkeyup="suggestionForm(this, 'bumon_cd', ['bumon_cd', 'kana', 'bumon_nm'], {'bumon_cd': 'search_bumon_cd', 'bumon_nm': 'search_bumon_nm'}, $(this).parent())">
                  <input class="form-control border-left col-md-7" name="search_bumon_nm"
                         onkeyup="suggestionForm(this, 'bumon_nm', ['bumon_cd', 'kana', 'bumon_nm'], {'bumon_cd': 'search_bumon_cd', 'bumon_nm': 'search_bumon_nm'}, $(this).parent())">
                  <ul class="suggestion"></ul>
                </div>
                <div class="row">
                  <span class="error-message-row col-md-5 p-0" id="search_bumon_cd"></span>
                  <span class="error-message-row col-md-7 p-0" id="search_bumon_nm"></span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-7">
            <div class="row">
              <div class="col-md-2 col-form-label text-right">
                <label class="mb-0">照会日付</label>
              </div>
              <div class="col-md-2 p-0">
                <input type="text" class="form-control" name="search_kisan_dt"
                       onchange="autoFillDate(this)" value="{{$currentDate}}">
                <span class="error-message-row" id="search_kisan_dt"></span>
              </div>
              <div class="col-md-1 col-form-label">
                <label class="mb-0">現在</label>
              </div>
              <div class="col-md-1 col-form-label text-right">
                <label class="mb-0">荷主</label>
              </div>
              <div class="col-md-6">
                <div class="row">
                  <input type="text" class="form-control col-md-4 border-right " name="search_ninusi_cd"
                         onkeyup="suggestionForm(this, 'ninusi_cd', ['ninusi_cd', 'kana', 'ninusi_ryaku_nm'], {'ninusi_cd': 'search_ninusi_cd', 'ninusi_ryaku_nm': 'search_ninusi_ryaku_nm'}, $(this).parent())">
                  <input class="form-control col-md-8 border-left" name="search_ninusi_ryaku_nm"
                         onkeyup="suggestionForm(this, 'ninusi_nm', ['ninusi_cd', 'kana', 'ninusi_ryaku_nm'], {'ninusi_cd': 'search_ninusi_cd', 'ninusi_ryaku_nm': 'search_ninusi_ryaku_nm'}, $(this).parent())">
                  <ul class="suggestion"></ul>
                </div>
                <div class="row">
                  <span class="error-message-row col-md-4 pl-0" id="search_ninusi_cd"></span>
                  <span class="error-message-row col-md-8 p-0" id="search_ninusi_ryaku_nm"></span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-12 col-md-5">
            <div class="row">
              <div class="col-md-2 col-form-label">
                <label class="mb-0">商品</label>
              </div>
              <div class="col-md-10">
                <div class="row">
                  <div class="col-md-5 p-0">
                    <input type="text" class="form-control" name="search_soko_hinmei_cd_from"
                           onkeyup="suggestionForm(this, 'soko_hinmei_cd', ['hinmei_cd', 'kana', 'hinmei_nm'], {'hinmei_cd': 'search_soko_hinmei_cd_from'}, $(this).parent())">
                    <ul class="suggestion"></ul>
                    <span class="error-message-row" id="search_soko_hinmei_cd_from"></span>
                  </div>
                  <div class="col-md-2 col-form-label text-center">
                    <label class="mb-0">～</label>
                  </div>
                  <div class="col-md-5 p-0">
                    <input type="text" class="form-control" name="search_soko_hinmei_cd_to"
                           onkeyup="suggestionForm(this, 'soko_hinmei_cd', ['hinmei_cd', 'kana', 'hinmei_nm'], {'hinmei_cd': 'search_soko_hinmei_cd_to'}, $(this).parent())">
                    <ul class="suggestion"></ul>
                    <span class="error-message-row" id="search_soko_hinmei_cd_to"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-7">
            <div class="row">
              <div class="col-md-2 col-form-label text-right">
                <label class="mb-0">商品名</label>
              </div>
              <div class="col-md-6 p-0">
                <input type="text" class="form-control" name="search_soko_hinmei_nm"
                       onkeyup="suggestionForm(this, 'soko_hinmei_nm', ['hinmei_cd', 'kana', 'hinmei_nm'], {'hinmei_nm': 'search_soko_hinmei_nm'}, $(this).parent())">
                <ul class="suggestion"></ul>
                <span class="error-message-row" id="search_soko_hinmei_nm"></span>
              </div>
              <div class="col-md-2 col-form-label text-right">
                <label class="mb-0">検索条件</label>
              </div>
              <div class="col-md-2 p-0">
                <select class="form-control" name="search_kensaku_zyoken">
                  @foreach($searchDrds as $key => $value)
                    <option value="{{$key}}"> {{ $value['text'] }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-12 col-md-5">
            <div class="row">
              <div class="col-md-2 col-form-label">
                <label class="mb-0">倉庫</label>
              </div>
              <div class="col-md-10">
                <div class="row">
                  <div class="col-md-5 p-0">
                    <input type="text" class="form-control" name="search_soko_cd_from"
                           onkeyup="suggestionForm(this, 'soko_cd', ['soko_cd', 'kana', 'soko_nm'], {'soko_cd': 'search_soko_cd_from'}, $(this).parent())">
                    <ul class="suggestion"></ul>
                    <span class="error-message-row" id="search_soko_cd_from"></span>
                  </div>
                  <div class="col-md-2 col-form-label text-center">
                    <label class="mb-0">～</label>
                  </div>
                  <div class="col-md-5 p-0">
                    <input type="text" class="form-control" name="search_soko_cd_to"
                           onkeyup="suggestionForm(this, 'soko_cd', ['soko_cd', 'kana', 'soko_nm'], {'soko_cd': 'search_soko_cd_to'}, $(this).parent())">
                    <ul class="suggestion"></ul>
                    <span class="error-message-row" id="search_soko_cd_to"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-7">
            <div class="row">
              <div class="col-md-2 col-form-label text-right">
                <label class="mb-0">表示選択</label>
              </div>
              <div class="col-md-8 form-inline flex-nowrap p-0">
                @foreach($searchOpts as $index => $opt)
                  <div class="form-check col-auto justify-content-start align-self-center">
                    <label class="form-check-label mb-0">
                      <input type="checkbox" class="form-check-input" name="option[{{$index}}]" value="{{ $index }}">
                      {{ $opt['text'] }}
                    </label>
                  </div>
                @endforeach
              </div>
              <div class="col-md-2 col-form-label text-right">
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
    var urlData = '{!! route('zaiko_shoukai.data_list', request()->query()) !!}';
    var pageNumber = {{ request() -> get('page') ?? 1 }};
    var searchDatas = @json(request() -> query());
    var urlSearchSuggestion = '{{ route('master-suggestion') }}';
    var pageSize = {{ config()->get('params.PAGE_SIZE') }};
    var formSearch = $('#formSearchZaikoShoukai');
    createCustomTable([]);

    function searchList(e) {
      var data = $('#formSearchZaikoShoukai').serialize();
      $.ajax({
        url: '{{route('zaiko_shoukai.validate_form_search_zaiko_shoukai')}}',
        method: 'POST',
        data: data,
        success: function (res) {
          $("#content-list").css('display', 'block');
          if ($('.bootstrap-table').length > 0) {
            $.fn.customTable.destroy();
          }
          if (res.isShowLot) {
            createCustomTable(res.customSetting);
          } else {
            createCustomTable(res.customSetting);
          }
          $.fn.customTable.searchList();
          $('#formSearchZaikoShoukai .error-message-row').html('');
          $('#formSearchZaikoShoukai .error-input').removeClass('error-input');
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

    function displayBtnListUkebaraiShoukai(value, row, index) {
      var url = '{{ route('zaiko_shoukai.ukebarai_shoukai.index', ['bumonCd' => '_bumonCd', 'ninusiCd' => '_ninusiCd', 'hinmeiCd' => '_hinmeiCd', 'kisanDt' => '_kisanDt']) }}';
      url = url.replace('_bumonCd', encodeURIComponent(row.bumon_cd));
      url = url.replace('_ninusiCd', encodeURIComponent(row.ninusi_cd));
      url = url.replace('_hinmeiCd', encodeURIComponent(row.hinmei_cd));
      url = url.replace('_kisanDt', encodeURIComponent($("[name='search_kisan_dt']").val()));
      value = '受払照会';
      return '<a href="#" data-href="' + url + '" class="btn btn-primary text-white rounded btn-in-list" onclick="redirectForm(this, false, \'index\', $(\'table\'))">' + value + '</a>'
    }

    function formatZaikoCaseSu(value, row, index, name) {
      return Math.floor(row.zaiko_su / row.irisu);
    }

    function formatZaikoHaSu(value, row, index, name) {
      return row.zaiko_su % row.irisu;
    }

    function formatZaikoJuryo(value, row, index, name) {
      let result = row.zaiko_su * row.bara_tani_juryo;
      return result.toFixed(3)
    }

    function submitToUrl(e, url) {
      var form = $('<form>', {
        'action': url,
        'method': 'POST',
        'target': '_blank'
      });
      var $formSearch = $('#formSearchZaikoShoukai');
      if ($formSearch) {
        $formSearch.find('input, select').each(function () {
          form.append($('<input>', {
            'type': 'hidden',
            'name': $(this).attr('name'),
            'value': $(this).val()
          }));
        });
      }
      form.append($('<input>', {
        'type': 'hidden',
        'name': '_token',
        'value': $('meta[name="csrf-token"]').attr('content')
      }));
      form.appendTo('body').submit().remove();
    }
  </script>
@endsection