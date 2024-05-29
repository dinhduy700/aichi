@extends('layouts.master')
@section('css')
  <style>
    .bootstrap-table .fixed-table-pagination > .pagination, .bootstrap-table .fixed-table-pagination > .pagination-detail {
      margin-top: 0;
    }
  </style>
@endsection
@section('page-content')
  @php
    $errorClass = ["error_message", "mb-0"];
  @endphp
  <x-success-summary/><x-error-summary/>
  <div method="" id="formSeikyu" class="form-custom" onsubmit="">
    <div class="card list-master-search-area">
      <div class="card-body">
        <div class="form-group row">
          <div class="col">
            <button class="btn btn-primary min-wid-110" type="button" onclick="clkZikkou(this)"
                    data-href="{{ route('seikyu.kakutei.set') }}">実行</button>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-lg-7">
            <div class="row">
              <div class="col-12 col-md-6">
                <div class="row">
                  <label class="col-12 col-md-2 col-form-label text-nowrap">締日</label>
                  <div class="col-12 col-md-10 group-input">
                    <input type="text" class="form-control size-L datepicker" name="seikyu_sime_dt">
                    <div @class($errorClass)><span class=" text-danger" id="error-seikyu_sime_dt"></span></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-4">
            <div class="d-flex" style="justify-content: space-between; align-items: center;">
              <div class="d-flex">
                <label class="col-form-label">&nbsp;</label>
                <div class="text-right" style="display: flex; flex-wrap: wrap; align-items: center;justify-content: flex-end;grid-gap: 10px; flex: 1">
                  <button class="btn btn-search min-wid-110" type="button" onclick="searchList(this)">{{ trans('app.labels.btn-search') }}</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="mt-3" id="content-list" style="@if (!empty($request->isShowCustomTable)) {{  'display: block' }} @else {{'display:none'}} @endif">
    <div class="card">
      <div class="card-body">
        <table id="table" class="hansontable" data-sticky-columns="['pk']" data-id-field="pk"
               data-select-item-name="selected[]"
        >
        </table>
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
    var urlExportExcelDataTable = '';

    var pageNumber = {{ request() -> get('page') ?? 1 }};
    var columns = @json($setting);
    var searchDatas = @json(request() -> query());

    var dataSuggestion = {};

    var columnsKeyBy = @json(\Illuminate\Support\Arr::keyBy($setting, 'field'), JSON_PRETTY_PRINT);
    var disabledRows = [];

    $('#table').customTable({
      urlData: '{!! route('seikyu.kakutei.data_list', request()->query()) !!}',
      showColumns: false,
      columns: columns,
      formSearch: $('#formSeikyu'),
      urlSearchSuggestion: '{!! route('master-suggestion') !!}',
      isShow: @if (!empty($request->isShowCustomTable)) true @else false @endif,
      sortName:'ninusi_cd',
    });

    function formatHakoFlg(value, row, index){
      if (row['seikyu_kakutei_flg']==1 || row['seikyu_hako_flg']!=1) disabledRows.push(index);
      return columnsKeyBy['seikyu_hako_flg']['options'][value];
    }
    function formatKakuteiFlg(value, row, index){
      return columnsKeyBy['seikyu_kakutei_flg']['options'][value];
    }

    function searchList(e) {
      cleanLayout();

      $('#content-list').css('display', 'block');
      $.fn.customTable.searchList();
    }

    function clearForm(e) {
      $(e).parents('form').find('select, input[type=text]').val('');
      $(e).parents('form').find('input[type="checkbox"]').prop('checked', true);
    }

    function cleanLayout() {
      $('.success-summary').empty().hide();
      $('.error-summary').empty().hide();
      $('.group-input').removeClass('error');
      $('.error_message span').html('');
    }

    function clkZikkou(e){
      cleanLayout();

      if($( "#table input[name='selected[]']:checked").length == 0) {
        alert(@JSON(__('messages.E0022')));
        return;
      }

      $.ajax({
        url: $(e).data('href'),
        method: 'POST',
        data: $.param($('#table input:checked')),
        success: function(res) {
          searchList();
          $('.success-summary').html(res.message).show();
        },
        error: function(jqXHR, textStatus, errorThrown) {
          $('.error-summary').html(jqXHR.responseJSON.message).show();
        },
        // complete: function() {
        //
        // }
      });
    }

    $('#table').on('load-error.bs.table', function (e, status, jqXHR) {
      if(jqXHR.status == 422) {
        var errors = jqXHR.responseJSON.errors;
        var form = $('#formSeikyu');
        $.each(errors, function(key, value) {
          form.find('#error-'+key).parents('.group-input').addClass('error');
          form.find('#error-'+key).html(value);
        });
      }
    });

    $('#table').on('load-success.bs.table', function(e, status, jqXHR) {
      $.each(disabledRows, function(i, v) {
        $('table#table tr[data-index=' + v + '] input[type=checkbox]').prop('disabled', true);
      });
      disabledRows = [];
    });

    $(function() {
      $('.datepicker').change(function() {
        autoFillDate(this);
      });
    });

  </script>
@endsection
