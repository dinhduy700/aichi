@extends('layouts.master')
@section('css')
<style>
  #ui-datepicker-div
  {
    z-index: 1051 !important;
  }
  .label-search-uriage
  {
    width: 70px; text-align: left;
  }
</style>
@endsection
@section('page-content')
<div>
  <form id="formUriage" class="form-custom">
    <div class="card">
      <div class="card-body" style="padding: 10px">
        <div style="display: flex; align-self: center; flex-wrap: wrap; justify-content: space-between;  grid-gap: 10px;">
          <div style="display: flex; align-self: center; flex-wrap: wrap; grid-column-gap: 15px">
            <div class="">
              <div class="group-s-input" style="display: flex;  grid-gap: 10px; flex-wrap: nowrap;">
                <label class="col-form-label label-search-uriage" >部門</label>
                <div>
                  <div class="group-s-head" style="flex-wrap: nowrap; display: flex; flex: 1; position: relative; ">
                    <input type="text" class="form-control size-M" name="hed_bumon_cd" style="width: 100px; border-bottom-right-radius: 0; border-top-right-radius: 0" onkeyup="suggestionForm(this, 'bumon_cd', ['bumon_cd', 'kana', 'bumon_nm'], {'bumon_cd': 'hed_bumon_cd', 'bumon_nm': 'hed_bumon_nm'}, $(this).parent())" style="" autocomplete="off">

                    <input class="form-control size-L" name="hed_bumon_nm" style="border-bottom-left-radius: 0; border-top-left-radius: 0" onkeyup="suggestionForm(this, 'bumon_nm', ['bumon_cd', 'kana', 'bumon_nm'], {'bumon_cd': 'hed_bumon_cd', 'bumon_nm': 'hed_bumon_nm'}, $(this).parent())" autocomplete="off">
                    <ul class="suggestion"></ul>
                  </div>
                  <div><span class="error-message-row"></span></div>
                </div>
              </div>

            </div>

            <div class="">
              <div class="group-s-input" style="display: flex; grid-gap: 10px">
                <label class="col-form-label label-search-uriage">入力担当</label>
                <div>
                  <div class="" style="flex-wrap: nowrap; display: flex; flex: 1; position: relative;">
                    <input type="text" class="form-control size-M" name="hed_jyomuin_cd" style="border-bottom-right-radius: 0; border-top-right-radius: 0" onkeyup="suggestionForm(this, 'jyomuin_cd', ['jyomuin_cd', 'kana', 'jyomuin_nm'], {jyomuin_cd: 'hed_jyomuin_cd', jyomuin_nm: 'hed_jyomuin_nm'}, $(this).parent())" autocomplete="off">
                    <input class="form-control size-L" name="hed_jyomuin_nm" style="width: 100%; border-bottom-left-radius: 0; border-top-left-radius: 0" onkeyup="suggestionForm(this, 'jyomuin_nm', ['jyomuin_cd', 'kana', 'jyomuin_nm'], {jyomuin_cd: 'hed_jyomuin_cd', jyomuin_nm: 'hed_jyomuin_nm'}, $(this).parent())" autocomplete="off">
                    <ul class="suggestion"></ul>
                  </div>
                  <div><span class="error-message-row"></span></div>
                </div>
              </div>
            </div>

            <div class="">
              <div class="" style="margin-bottom: 0;display: flex;grid-gap: 10px;">
                <label class="col-form-label label-search-uriage">運送日</label>
                <div class="group-s-input" style="display: block;">
                  <div class="form-inline" style="flex: 1; display: flex">
                    <input type="text" class="form-control size-L-uni text-center" name="hed_unso_dt_from" onchange="autoFillDate(this)" value="{{ date('Y/m/d') }}">
                    <span class="px-2"> ～ </span>
                    <input type="text" class="form-control size-L-uni text-center" name="hed_unso_dt_to" onchange="autoFillDate(this)" value="{{ date('Y/m/d') }}">
                  </div>
                  <div><span class="error-message-row"></span></div>
                </div>
              </div>
            </div>
          </div>
          <div class="" style="flex: 1;">
            <div class="" style="display: flex;align-items:  center;justify-content: flex-end; flex-wrap: nowrap;">
              <div class="" style="white-space: nowrap;">
                <button class="btn btn-clear min-wid-110" type="button" onclick="openPopupInitColumn()">表示列選択</button>
                <button class="btn btn-clear min-wid-110" type="button" onclick="openPopupInitSearch()">検索条件表示</button>
                <button class="btn btn-clear min-wid-110" type="button" onclick="clearForm(this)">条件クリア</button>
                <button class="btn btn-search min-wid-110" type="button" onclick="searchList(this)">検索</button>
              </div>
            </div>
          </div>
        </div>



        <!-- MODAL POPUP SEARCH -->
        <div class="modal fade bd-example-modal-lg" id="popupSearchModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div style="padding: 30px">

                @include('uriage.uriage-entry.popup-search', ['dataUnchinMikakutei' => $dataUnchinMikakutei, 'dataGenkin' => $dataGenkin])

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="applyInitSearch()">保存</button>
                <button type="button" class="btn btn-secondary" onclick="notAppyInitSearch()">閉じる</button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </form>
</div>
<div class="modal fade" id="popupColumnModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
  <div class="modal-dialog" style="max-width: 65%; width: 800px;">
    <div class="modal-content">
      <div>

        @include('uriage.uriage-entry.popup-column')

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="applyInitColumn()">保存</button>
        <button type="button" class="btn btn-secondary" onclick="notAppyInitColumn()">閉じる</button>
      </div>
    </div>
  </div>
</div>
<div class="mt-2">
  <div class="card">
    <div class="card-body" style="padding: 10px;">
      <div class="form-custom ">
        <div class="table-pagi-top">
          <table id="table" class="hansontable editable" data-sticky-columns="['id']">
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('js')

<script>

  var valueInitSearchPoppup =  @if(!empty($dataInitSearchPopup)) @json($dataInitSearchPopup, true) @else null @endif;
  var valueInitColumnPoppup = @if(!empty($dataInitColumnPopup)) @json($dataInitColumnPopup, true) @else null @endif;
  var listButtonToolBar = '<div class="columns columns-right btn-group float-right"><button type="button" onclick="clearGrid(this)" class="btn btn-clear">並び順＆検索条件初期化</button></div><div class="columns columns-right btn-group float-right"><div class="form-check form-check-flat form-check-primary"><label class="form-check-label text-nowrap" style="padding: 0 20px;"><input id="checkboxMultiSort" type="checkbox" value="1"  onclick="eventMultiSort(this)" class="form-check-input">並び順をAND条件<i class="input-helper"></i></label></div></div>';
  var useAddFormFooter = true;
  var useCopyButton = false;
  var urlUpdateDataRecord = false;
  var urlSearchSuggestion = false;
  var urlExportExcelDataTable = '{{ route('uriage.uriage_entry.export_excel') }}';
  urlExportExcelDataTable = '';
  var pageNumber = {{ request() -> get('page') ?? 1 }};
  var columns = @json($setting);
  if(valueInitColumnPoppup && columns) {
    columns.forEach(function(row) {
      if(valueInitColumnPoppup != 1) {
        if(row.field == 'checkbox' ||  valueInitColumnPoppup.includes(row.field)) {
          row.visible = true;
        } else {
          row.visible = false;
        }
      } else {
        if(row.field == 'checkbox') {
          row.visible = true;
        } else {
          row.visible = false;
        }
      }
    });
  }
  var searchDatas = @json(request() -> query());
  var dataSuggestion = {};

  // $("input[name='hed_bumon_cd']").val('-1');
  $('#table').customTable({
     // Data source URL
    urlData: '{!! route('uriage.uriage_entry.data_list', request()->query()) !!}',
    // Show columns button
    showColumns: false,
    // Column configurations
    columns: columns,
    // Custom toolbar buttons
    listButtonToolBar: listButtonToolBar,
    // Initial page number
    pageNumber: pageNumber,
    // URL for inserting data record
    urlInsertDataRecord: '',
    // URL for updating data record
    urlUpdateDataRecord: '',
    // Search form element
    formSearch: $('#formUriage'),
    // URL for copying record
    urlCopyRecord: '',
    // URL for search suggestion
    urlSearchSuggestion: '{{route('uriage.uriage_entry.search_suggestion')}}',
    // URL for exporting data table to Excel
    urlExportExcelDataTable: urlExportExcelDataTable,
    // Number of items per page
    pageSize: {{ config()->get('params.PAGE_SIZE') }},
    textButtonExportExcel: '{{ trans('app.labels.btn-xls-export') }}',
    urlValidateRows: '{{ route('uriage.uriage_entry.validate_row') }}',
    // Option to insert new rows at the end
    insertLastRow: true,
     // URL for updating data table
    urlUpdateDataTable: '{{route('uriage.uriage_entry.update_datatable')}}',
    // Option to copy data to the left
    isCopyLeft: true,
    // URL for updating initial data copy
    urlUpdateInitDatacopy: '{{ route('uriage.uriage_entry.update_init_copy') }}',
    // Initial data for copying
    initCopy: @json($dataInit),
    // Option to enable row deletion
    isDelete: true,
    // Field(s) to make rows read-only
    readonlyRowField: ['sime_kakutei_kbn'], // If more than 2, pass as an array
    readonlyRowWhere: '!=0',
    defaultSearchForm: false,
    isShow: false, // if is true will be show list when init
    usingPaginateTop: true
  });
  // $("input[name='hed_bumon_cd']").val('');

  function clearGrid(e) {
    refreshInitSearchPopup();
    pageNumber = 1;
    copyButtonAdded = false;
    isAppendListButtonToolBar = false;
    exportButtonAdded = false;
    updateDataTableButtonAdded = false;
    copyButonLeftAdded = false;
    deleteButtonAdded = false;
    dataMultiSort = {};
    var settings = $('#table').data('customTableSettings');
    settings.defaultSearchForm = true;
    // $('#table').bootstrapTable('getOptions').sortable = true;
     $('#table').bootstrapTable('refreshOptions', {
      sortName: '',
      sortOrder: '',
      pageNumber: 1,
      sortable: true
    });
    // $('#table').bootstrapTable('refresh', {
    //   sortable: true
    // });
  }

  function eventMultiSort(e) {
    copyButtonAdded = false;
    isAppendListButtonToolBar = false;
    exportButtonAdded = false;
    updateDataTableButtonAdded = false;
    copyButonLeftAdded = false;
    deleteButtonAdded = false;
    pageNumber = 1;
    if($(e).is(':checked')) {
      $('#table').bootstrapTable('refreshOptions', {
        sortName: '',
        sortOrder: '',
        pageNumber: 1,
        sortable: false,
        useSortMulti: true,
        query: {
          pageNumber: 1
        }
      });
      
    } else {
      dataMultiSort = {};
      $('#table').off('click', 'th div.th-inner.sortable', onClickTh);
      $('#table').bootstrapTable('refreshOptions', {
        sortable: true,
        pageNumber: 1,
        query: {
          pageNumber: 1
        }
      });
    }
  }
  $(document).ajaxSend(function () {
  });

  $(document).ajaxComplete(function () {
  });

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

  function searchList(e) {
    var data = $('#formUriage').serialize();
    $.ajax({
      url: '{{route('uriage.uriage_entry.validate_form_search_uriage')}}',
      method: 'POST',
      data: data,
      success: function(res) {
        $.fn.customTable.searchList();
        $('#formUriage .error-message-row').removeClass('active').html('');
        $('#formUriage .error-input').removeClass('error-input');
      },
      error: function(error) {
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
    });
    

    // nếu đã có table rùi thì ko cần phía dưới cũng được
    // var settings = $('#table').data('customTableSettings');
    // settings.isShow = true;
    // $.fn.customTable.refreshCustom();
  }
  function clearForm(e) {
    $('#formUriage').find('input:not(:checkbox), select').val('');
    $('#formUriage').find('input[type="checkbox"]').prop('checked', false);
    $('.group-s-input').removeClass('active');
    $('.error-message-row').removeClass('active');
    $('.error-input').removeClass('error-input');
    // refreshInitSearchPopup();
  }

  function developing() {
    Swal.fire({
      title: 'DEVELOPING...',
      icon: 'warning',
    })
  }


  function applyInitSearch() {
    var data = $('#formUriage').serialize();
    $.ajax({
      url: '{{ route('uriage.uriage_entry.update_init_search') }}',
      data : data,
      method: 'POST',
      success: function(res) {
        $('#formUriage').find('input').filter(':not(:checkbox)').each(function() {
          var value = $(this).val();
          $(this).attr('data-old', value);
        }); 
        if(res.status == 200) {
          valueInitSearchPoppup = res.data;
          $('.error-message-row').removeClass('active').html('');
          $('.error-input').removeClass('error-input');
        }
        $('#popupSearchModal').modal('hide');
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

  function notAppyInitSearch() {
    $('#formUriage').find('input').filter(':not(:checkbox)').each(function() {
      var old = $(this).attr('data-old');
      $(this).val(old);
    });
    $('#popupSearchModal .error-message-row').removeClass('active').html('');
    $('#popupSearchModal .error-input').removeClass('error-input');
    $('#popupSearchModal').modal('hide');
  }

  function openPopupInitSearch()
  {
    $('#formUriage').find('input').filter(':not(:checkbox)').each(function() {
      var value = $(this).val();
      $(this).attr('data-old', value);
    });
    $('#popupSearchModal').modal('show');
  }

  function openPopupInitColumn() {
    refreshInitColumnPopup();
    $('#popupColumnModal').modal('show');
  }

  function notAppyInitColumn() {
    $('#popupColumnModal').modal('hide');
  }

  function applyInitColumn() {
    var data = $('#initColumn').serializeArray();
    var settings = $('#table').data('customTableSettings');
    $.ajax({
      url: '{{ route('uriage.uriage_entry.update_init_column') }}',
      data : data,
      method: 'POST',
      success: function(res) {
        if(res.status == 200) {
          valueInitColumnPoppup = res.data;
          if(settings.isShow == true) {
            var allColumns = $('#table').bootstrapTable('getOptions').columns[0];
            $.each(allColumns, function(index, column) {
              if (valueInitColumnPoppup != 1 && (valueInitColumnPoppup.includes(column.field)) || column.field == 'checkbox') {
                column.visible = true;
              } else {
              
                column.visible = false;
              }
            });
            copyButtonAdded = false;
            isAppendListButtonToolBar = false;
            exportButtonAdded = false;
            updateDataTableButtonAdded = false;
            copyButonLeftAdded = false;
            deleteButtonAdded = false;
            $('#table').bootstrapTable('refreshOptions', {columns: allColumns});
          } else {
            if(valueInitColumnPoppup && columns) {
              columns.forEach(function(row) {
                if(valueInitColumnPoppup != 1) {
                  if(row.field == 'checkbox' ||  valueInitColumnPoppup.includes(row.field)) {
                    row.visible = true;
                  } else {
                    row.visible = false;
                  }
                } else {
                  if(row.field == 'checkbox') {
                    row.visible = true;
                  } else {
                    row.visible = false;
                  }
                }
              });
            }
          }
        } else {
          Swal.fire({
            title: res.message,
            icon: 'error',
            customClass: {
              popup: 'custom-modal-size'
            }
          })
        }
        $('#popupColumnModal').modal('hide');
      }
    })
  }

  function refreshInitColumnPopup() {
    $('#initColumn input[type="checkbox"]').prop('checked', false);
    // 
    if(valueInitColumnPoppup) {
      if (typeof valueInitColumnPoppup === 'object' && valueInitColumnPoppup !== null) {
        Object.keys(valueInitColumnPoppup).forEach(function (key) {
          if(valueInitColumnPoppup[key]) {
            $('#initColumn').find('input[name="'+valueInitColumnPoppup[key]+'"]').prop('checked', true).trigger('change');
          }
        });

      }
    }
    if(valueInitColumnPoppup == null) {
      $('#initColumn input[type="checkbox"]').prop('checked', true);
    }
  }

  $('#popupSearchModal input[type="checkbox"]:not(.form-search)').click(function(){
    if($(this).is(':checked')) {
      $(this).parents('.row-s').find('.group-s-input').addClass('active');
    } else {
      $(this).parents('.row-s').find('input, select').val('');
      $(this).parents('.row-s').find('.error-message-row').html('').removeClass('active');
      $(this).parents('.row-s').find('.error-input').removeClass('error-input');
      $(this).parents('.row-s').find('input[type="checkbox"]').prop('checked', false);
      $(this).parents('.row-s').find('.group-s-input').removeClass('active');
    }
  })

  function refreshInitSearchPopup() {
    $('#formUriage input:not(:checkbox)[name!="hed_unso_dt_to"][name!="hed_unso_dt_from"], #formUriage select').val('');
    if(valueInitSearchPoppup) {
      if (typeof valueInitSearchPoppup === 'object' && valueInitSearchPoppup !== null) {
        Object.keys(valueInitSearchPoppup).forEach(function (key) {
          if(valueInitSearchPoppup[key]) {
            if (key.includes('_dt')) {
              var value = valueInitSearchPoppup[key].replace(/-/g, '/');
            } else {
              var value = valueInitSearchPoppup[key];

            }
            if(key == 'genkin_cd' || key == 'unchin_mikakutei_kbn') {
              if(value) {
                value = JSON.parse(value);
              }
            }
            if (Array.isArray(value)) {
              for (var j = 0; j < value.length; j++) {
                $('#formUriage').find('[name="'+key+'[]"][value="'+value[j]+'"]').prop('checked', true);
                $('#formUriage').find('[name="'+key+'[]"][value="'+value[j]+'"]').parents('.row-s').find('input[name^="chk["]').prop('checked', true);
                $('#formUriage').find('[name="'+key+'[]"][value="'+value[j]+'"]').parents('.row-s').find('.group-s-input').addClass('active');
              }
            } else {
              $('#formUriage').find('[name="'+key+'"]').val(value);
              if(value) {
                $('#formUriage').find('[name="'+key+'"]').parents('.row-s').find('input[name^="chk["]').prop('checked', true);
                $('#formUriage').find('[name="'+key+'"]').parents('.row-s').find('.group-s-input').addClass('active');
              }
            }
          }
        });
      }
    }
  }

  function initColumnCheckHidden(e, list) {
    if(list && Array.isArray(list)) {
      for(let i = 0 ; i < list.length; i++) {
        if($(e).is(':checked')) {
          $('#popupColumnModal').find('input[name="'+list[i]+'"]').val(1);
        } else {
          $('#popupColumnModal').find('input[name="'+list[i]+'"]').val('');
        }
      }
    }
  }

  // FORMAT INPUT GRID 
  function formatterYosyuTyukeiKin(value, index, row, field) {
    return '<input onkeypress="onlyNumber(event)" type="text" data-length="11" class="form-control text-right" name="yosya_tyukei_kin" value="'+numberFormat(value|| '', -1)+'" onblur="setCellFocusStatus($(this), false), formatNumberOnBlur(this)" onfocus="setCellFocusStatus($(this), true), removeFormatOnFocus(this), calculatorRoundKintax(this, \'yosya_kin_tax\')" onchange="calculatorRoundKintax(this, \'yosya_kin_tax\')"><div class="error error-yosya_tyukei_kin"><span class="text-danger"></span></div>';
  }
  function formatterYosyaKinTax(value, index, row, field) {
    return '<input type="text" readonly name="yosya_kin_tax" value="'+numberFormat(value || '', -1)+'" class="form-control text-right"><div class="error error-yosya_kin_tax"><span class="text-danger"></span>';
  }


  function calculatorRoundKintax(e, type) {
    var _this = $(e);
    var currentData = $('#table').bootstrapTable('getData')[$(e).parents('tr').data('index')];
    data = Object.assign({}, currentData);
    data.type = type;
    data.ninusi_cd = _this.parents('tr').find('input[name="ninusi_cd"]').val();
    data.yousya_cd = _this.parents('tr').find('input[name="yousya_cd"]').val();
    if(type == 'yosya_kin_tax') {
      data.yosya_tyukei_kin = _this.parents('tr').find('input[name="yosya_tyukei_kin"]').val() || 0;
    }
    $.ajax({
      url: '{{route('uriage.uriage_entry.calculator_round_kin_tax')}}',
      data: data,
      method: 'POST',
      success: function(res) {
        if(type == 'yosya_kin_tax') {
          _this.parents('tr').find('input[name="yosya_kin_tax"]').val(numberFormat(res.data || '', -1));
        }
      }
    })
  }

  function formatterFooter(column, index) {
    if(column.field == 'yosya_tyukei_kin') {
      return '<td><input type="text" name="yosya_tyukei_kin" class="form-control text-right" onkeypress="onlyNumber(event)" onblur="setCellFocusStatus($(this), false), formatNumberOnBlur(this)" onfocus="setCellFocusStatus($(this), true), removeFormatOnFocus(this)" onchange="calculatorRoundKintax(this, \'yosya_kin_tax\')" /><div class="error error-yosya_tyukei_kin"><span class="text-danger"></span></div></td>';
    }
    if(column.field == 'yosya_kin_tax') {
      return '<td><input type="text" class="form-control text-right" name="yosya_kin_tax" readonly /></td>';
    }
    return '<td></td>';
  }
  var flagNinusi = false;
  function onchangeNinusi(e) {
    var ninusiCd = $(e).parents('tr').find('[name="ninusi_cd"]').val();
    var _tr = $(e).parents('tr');
    
    if(ninusiCd && flagNinusi == false) {
      flagNinusi = true;
      $.ajax({
        url: '{{route('uriage.uriage_entry.other_column')}}',
        data: {
          field_from: 'ninusi_cd',
          value_from: ninusiCd,
          field_to: ['hachaku_cd']
        },
        method: 'POST',
        success: function(res) {
          if(res.data && res.data.hachaku_cd && res.data.hachaku_cd.length == 1) {
            _tr.find('[name="hachaku_cd"]').val(res.data.hachaku_cd[0].hachaku_cd).addClass('hasChangeValue').trigger('change');
            _tr.find('[name="hachaku_nm"]').val(res.data.hachaku_cd[0].hachaku_nm).addClass('hasChangeValue');
          }
        },
        complete: function() {
          flagNinusi = false;
        }
      })
    }
  }

  $(document).ready(function() {

    refreshInitSearchPopup();
    refreshInitColumnPopup();
    $('.popup-datetime').datepicker({
      dateFormat: 'yy/mm/dd',
      onSelect: function(selectedDate, instance) {
        var name = $(this).attr('name');
        if(name.includes('_from')) {
          // $("#endDate").datepicker("option", "minDate", selectedDate);
          $('input[name="'+ name.replace('_from', '_to') +'"]').datepicker("option", "minDate", selectedDate);
        }
      }
    });

    // $('#table').bootstrapTable('hideColumn', 'bumon_cd');

    // Add event listener for "Check All" checkbox
    $('#checkAllColumn').on('change', function() {
      var isChecked = $(this).prop('checked');
      // Set the state of all checkboxes based on the state of "Check All" checkbox
      $('#columnInitModalBody input[type="checkbox"]').prop('checked', isChecked);
      if(isChecked !== true) {
        $('#columnInitModalBody input[type="hidden"]').val('');
      } else {
        $('#columnInitModalBody input[type="hidden"]').val(1);
      }
    });

    // Add event listener for individual checkboxes
    $('#columnInitModalBody input[type="checkbox"]').on('change', function() {
      // Update the state of "Check All" based on the state of individual checkboxes
      var allChecked = $('#columnInitModalBody input[type="checkbox"]:not(#checkAllColumn)').length === $('#columnInitModalBody input[type="checkbox"]:not(#checkAllColumn):checked').length;
      $('#checkAllColumn').prop('checked', allChecked);
    });
  })
</script>
@endsection