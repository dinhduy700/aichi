@extends('layouts.master')
@section('css')
@endsection
@section('page-content')
<form method="" id="formMeisyo" class="form-custom">
<div class="card list-master-search-area">
  <div class="card-body">
      <div class="row">
        <div class="col-12 col-md-9">
          <div class="row">
            <div class="col-12 col-md-4">
              <div class="group-s-input" style="margin-bottom: 0;display: flex;grid-gap: 10px;">
                <label class="col-form-label label-search-uriage">入金日</label>
                <div class="form-inline" style="flex: 1; display: flex; flex-wrap: wrap;">
                  <input type="text" class="form-control size-L-uni text-center" name="hed_nyukin_dt_from" onchange="autoFillDate(this)">
                  <span class="px-2"> ～ </span>
                  <input type="text" class="form-control size-L-uni text-center" name="hed_nyukin_dt_to" onchange="autoFillDate(this)">
                  <div style="width: 100%"><span class="error-message-row"></span></div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-8">
              <div class="group-s-input" style="display: flex;  grid-gap: 10px; flex-wrap: nowrap;">
                <label class="col-form-label label-search-uriage" >荷主</label>
                <div class="" style="flex-wrap: nowrap; display: flex; flex: 1; position: relative; flex-wrap: wrap;">
                  <input type="text" class="form-control size-M" name="ninusi_cd" style="width: 100px; border-bottom-right-radius: 0; border-top-right-radius: 0" onkeyup="suggestionForm(this, 'ninusi_cd', ['ninusi_cd', 'kana', 'ninusi_nm'], {'ninusi_cd': 'ninusi_cd', 'ninusi_nm': 'ninusi_nm'}, $(this).parent())" style="" autocomplete="off">

                  <input class="form-control size-3L"  type="text" name="ninusi_nm" style="border-bottom-left-radius: 0; border-top-left-radius: 0" onkeyup="suggestionForm(this, 'ninusi_nm', ['ninusi_cd', 'kana', 'ninusi_nm'], {'ninusi_cd': 'ninusi_cd', 'ninusi_nm': 'ninusi_nm'}, $(this).parent())" autocomplete="off">
                  <ul class="suggestion"></ul>
                  <div style="width: 100%"><span class="error-message-row"></span></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-3">
          <div class="d-flex" style="justify-content: flex-end; align-items: center;">
            <div class="d-flex">
              <label class="col-form-label">&nbsp;</label>
              <div class="text-right" style="display: flex; flex-wrap: wrap; align-items: center;justify-content: flex-end;grid-gap: 10px; flex: 1">
                <button class="btn btn-clear min-wid-110" type="button" onclick="clearForm(this)">{{ trans('app.labels.btn-clear') }}</button>
                <button class="btn btn-search min-wid-110" type="button" onclick="searchList(this)">{{ trans('app.labels.btn-search') }}</button>
                <a href="#" data-href="{{route('nyukin.create')}}"
                     class="btn btn-insertNew"
                     onclick="redirectForm(this, false, 'NyukinIndex', $('#table'))">{{ trans('app.labels.btn-insertNew') }}</a>
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
  var urlExportExcelDataTable = '';

  var pageNumber = {{ request() -> get('page') ?? 1 }};
  var columns = @json($setting);
  var searchDatas = @json(request() -> query());

  var dataSuggestion = {};

  var columnsKeyBy = @json(\Illuminate\Support\Arr::keyBy($setting, 'field'), JSON_PRETTY_PRINT);

  $('#table').customTable({
      urlData: '{!! route('nyukin.data_list', request()->query()) !!}',
      showColumns: false,
      columns: columns,
      listButtonToolBar: listButtonToolBar,
      pageNumber: pageNumber,
      urlInsertDataRecord: '',
      urlUpdateDataRecord: '',
      formSearch: $('#formMeisyo'),
      urlCopyRecord: '',
      urlSearchSuggestion: '{{route('master-suggestion')}}',
      urlExportExcelDataTable: urlExportExcelDataTable,
      pageSize: {{ config()->get('params.PAGE_SIZE') }},
      textButtonExportExcel: '{{ trans('app.labels.btn-xls-export') }}',
      isShow: @if (!empty($request->isShowCustomTable)) true @else false @endif,
      defaultParams: {kyumin_flg: 0},
      isShowBtnExcel: false,
      sortName:'nyukin_no'
  });

  // function formatMeisyoCd(value, row, index) {
  //   var url = '{{ route('master.meisyo.edit', ['meisyoCd' => ':meisyoCd', 'meisyoKbn' => ':meisyoKbn']) }}';
  //   url = url.replace(':meisyoCd', encodeURIComponent(row.meisyo_cd));
  //   url = url.replace(':meisyoKbn', encodeURIComponent(row.meisyo_kbn));
  //   return '<a href="#" data-href="' + url + '" class="text-decoration" onclick="redirectForm(this, false, \'MeisyoIndex\', $(\'#table\'))">' + value + '</a>'
  // }
  function formatNyukinNo(value, row, index) {
    var url = '{{route('nyukin.edit', ['nyukinNo' => ':nyukinNo'])}}';
    url = url.replace(':nyukinNo', encodeURIComponent(row.nyukin_no));
    return '<a href="#" data-href="' + url + '" class="text-decoration" onclick="redirectForm(this, false, \'NyukinIndex\', $(\'#table\'))">' + value + '</a>'
  } 

  function formatNyukinSum(value, row, index) {
    var total = 0;
    if (!isNaN(parseFloat(row.genkin_kin))) total += parseFloat(row.genkin_kin);
    if (!isNaN(parseFloat(row.furikomi_kin))) total += parseFloat(row.furikomi_kin);
    if (!isNaN(parseFloat(row.furikomi_tesuryo_kin))) total += parseFloat(row.furikomi_tesuryo_kin);
    if (!isNaN(parseFloat(row.tegata_kin))) total += parseFloat(row.tegata_kin);
    if (!isNaN(parseFloat(row.sousai_kin))) total += parseFloat(row.sousai_kin);
    if (!isNaN(parseFloat(row.nebiki_kin))) total += parseFloat(row.nebiki_kin);
    if (!isNaN(parseFloat(row.sonota_nyu_kin))) total += parseFloat(row.sonota_nyu_kin);
    return formatNumber(total);
  }

  function formatNyukinDt(value, row, index) {
    return convertDateFormat(value, 'yyyy/mm/dd');
  }

  function searchList(e) {

    $.ajax({
      url: '{{route('nyukin.validate_from_search')}}',
      method: 'POST',
      data: {
        ninusi_cd: $('input[name="ninusi_cd"]').val(),
        ninusi_nm: $('input[name="ninusi_nm"]').val(),
        hed_nyukin_dt_from: $('input[name="hed_nyukin_dt_from"]').val(),
        hed_nyukin_dt_to: $('input[name="hed_nyukin_dt_to"]').val()
      },
      success: function() {
        $('#content-list').css('display', 'block');
        $.fn.customTable.searchList();
      },
      error: function (error) {
        if (error.status == 422) {
          $('.error-message-row').removeClass('active').html('');
          $('.error-input').removeClass('error-input');
          var errors = error.responseJSON.errors;
          $.each(errors, function (key, value) {
            var row = $('input[name="'+key+'"]').parents('.group-s-input');
            row.find('.error-message-row').addClass('active').html(value);
            $('input[name="'+key+'"]').addClass('error-input');
          });
        }
      }
    })
  }

  function clearForm(e) {
    $('.error-input').removeClass('error-input');
    $('.error-message-row').removeClass('active').html('');
    $(e).parents('form').find('select, input[type=text]').val('');
  }

  $(function() {
    $("#startDate").datepicker({
      dateFormat: 'yy/mm/dd',
      onSelect: function(selectedDate) {
        $("#endDate").datepicker("option", "minDate", selectedDate);
      }
    });

    $("#endDate").datepicker({
      dateFormat: 'yy/mm/dd'
    });
  });
</script>
@endsection
