@extends('layouts.master')
@section('css')
<style>
  #ui-datepicker-div {
    z-index: 1051 !important;
  }
  .label-search-uriage {
    width: 70px;
    text-align: left;
  }
  .flex-g {
    display: flex;
    grid-gap: 10px;
    flex-wrap: nowrap;
  }
  .fixed-table-body
  {
    max-height: calc(100vh - var(--header-height) - 1rem - 250px) !important;
    min-height: 300px;
  }
  .table-pagi-top .bootstrap-table .fixed-table-pagination > .pagination, .table-pagi-top .bootstrap-table .fixed-table-pagination > .pagination-detail
  {
    margin: 0;
  }
</style>
@endsection
@section('page-content')
<div>
  <form id="formUriage" class="form-custom">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <div class="flex-g">
              <label class="col-form-label label-search-uriage">部門</label>
              <div class="group-s-input" style="display: block;">
                <div class="" style="flex-wrap: nowrap; display: flex; flex: 1; position: relative; ">
                  <input type="text" class="form-control size-M" name="hed_bumon_cd"
                    style="width: 100px; border-bottom-right-radius: 0; border-top-right-radius: 0"
                    onkeyup="suggestionForm(this, 'bumon_cd', ['bumon_cd', 'kana', 'bumon_nm'], {'bumon_cd': 'hed_bumon_cd', 'bumon_nm': 'hed_bumon_nm'}, $(this).parent())"
                    style="">

                  <input class="form-control size-L" name="hed_bumon_nm"
                    style="border-bottom-left-radius: 0; border-top-left-radius: 0"
                    onkeyup="suggestionForm(this, 'bumon_nm', ['bumon_cd', 'kana', 'bumon_nm'], {'bumon_cd': 'hed_bumon_cd', 'bumon_nm': 'hed_bumon_nm'}, $(this).parent())">
                  <ul class="suggestion"></ul>
                </div>
                <div><span class="error-message-row"></span></div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="flex-g">
              <label class="col-form-label label-search-uriage">入力担当</label>
              <div class="group-s-input" style="display: block;">
                <div style="position: relative;">
                  <input type="text" class="form-control size-M" name="hed_add_tanto_cd" onkeyup="suggestionForm(this, 'jyomuin_cd', ['jyomuin_cd', 'kana', 'jyomuin_nm'], {'jyomuin_cd': 'hed_add_tanto_cd', 'jyomuin_nm': 'hed_add_tanto_nm'}, $(this).parent())">
                  <ul class="suggestion"></ul>  
                </div>
                <div><span class="error-message-row"></span></div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="flex-g">
              <label class="col-form-label label-search-uriage">集荷日</label>
              <div >
                <div class="col-sm form-inline">
                  <div class="group-s-input" style="display: block;">
                    <div class="group-flex">
                      <input type="text" class="form-control size-L-uni input1" name="hed_syuka_dt_from" onchange="autoFillDate(this)"> 
                    </div>
                    <div class="error-message-row"></div>
                  </div>
                  <span class="px-2"> ～ </span>
                  <div class="group-s-input" style="display: block;">
                    <div class="group-flex">
                      <input type="text" class="form-control size-L-uni input1" name="hed_syuka_dt_to" onchange="autoFillDate(this)">
                    </div>
                    <div class="error-message-row"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="flex-g">
              <label class="col-form-label label-search-uriage">受注担当</label>
              <div class="group-s-input" style="display: block;">
                <div class="" style="flex-wrap: nowrap; display: flex; flex: 1; position: relative; ">
                  <input type="text" class="form-control size-M" name="hed_jyomuin_cd"
                    style="width: 100px; border-bottom-right-radius: 0; border-top-right-radius: 0"
                    onkeyup="suggestionForm(this, 'jyomuin_cd', ['jyomuin_cd', 'kana', 'jyomuin_nm'], {'jyomuin_cd': 'hed_jyomuin_cd', 'jyomuin_nm': 'hed_jyomuin_nm'}, $(this).parent())"
                    style="">

                  <input class="form-control size-L" name="hed_jyomuin_nm"
                    style="border-bottom-left-radius: 0; border-top-left-radius: 0"
                    onkeyup="suggestionForm(this, 'jyomuin_nm', ['jyomuin_cd', 'kana', 'jyomuin_nm'], {'jyomuin_cd': 'hed_jyomuin_cd', 'jyomuin_nm': 'hed_jyomuin_nm'}, $(this).parent())">
                  <ul class="suggestion"></ul>
                </div>
                <div><span class="error-message-row"></span></div>
              </div>
            </div>
          </div>
          <div class="col-md-4 align-self-center">
            <div class="flex-g">
              <div class="form-check form-check-primary" style="margin: 0">
                <label class="form-check-label">
                  <input type="radio" class="form-check-input" name="radio_hed_syaban" value="all" checked>
                  全て
                  <i class="input-helper"></i>
                </label>
              </div>
              <div class="form-check form-check-primary" style="margin: 0">
                <label class="form-check-label">
                  <input type="radio" class="form-check-input" name="radio_hed_syaban" value="mihaisya">
                  未配車
                  <i class="input-helper"></i>
                </label>
              </div>
              <div class="form-check form-check-primary" style="margin: 0">
                <label class="form-check-label">
                  <input type="radio" class="form-check-input" name="radio_hed_syaban" value="haisyazumi">
                  配車済
                  <i class="input-helper"></i>
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="flex-g">
              <label class="col-form-label label-search-uriage">配達日</label>
              <div>
                <div class="col-sm form-inline">
                  <div class="group-s-input" style="display: block;">
                    <div class="group-flex">
                      <input type="text" class="form-control size-L-uni input1" name="hed_haitatu_dt_from" onchange="autoFillDate(this)">
                    </div>
                    <div class="error-message-row"></div>
                  </div>
                  <span class="px-2"> ～ </span>
                  <div class="group-s-input" style="display: block;">
                    <div class="group-flex">
                      <input type="text" class="form-control size-L-uni input1" name="hed_haitatu_dt_to" onchange="autoFillDate(this)">
                    </div>
                    <div class="error-message-row"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="flex-g">
              <label class="col-form-label label-search-uriage">受注区分</label>
              <div class="group-s-input" style="display: block;">
                <div class="" style="flex-wrap: nowrap; display: flex; flex: 1; position: relative; ">
                  <input type="text" class="form-control size-M" name="hed_jyutyu_kbn"
                    style="width: 100px; border-bottom-right-radius: 0; border-top-right-radius: 0"
                    onkeyup="suggestionForm(this, 'jyutyu_kbn', ['jyutyu_kbn', 'kana', 'jyutyu_nm'], {'jyutyu_kbn': 'hed_jyutyu_kbn', 'jyutyu_nm': 'hed_jyutyu_nm'}, $(this).parent())"
                    style="">

                  <input class="form-control size-L" name="hed_jyutyu_nm"
                    style="border-bottom-left-radius: 0; border-top-left-radius: 0"
                    onkeyup="suggestionForm(this, 'bumon_nm', ['hed_jyutyu_kbn', 'kana', 'hed_jyutyu_nm'], {'jyutyu_kbn': 'hed_jyutyu_kbn', 'jyutyu_nm': 'hed_jyutyu_nm'}, $(this).parent())">
                  <ul class="suggestion"></ul>
                </div>
                <div><span class="error-message-row"></span></div>
              </div>
            </div>
          </div>
          <div class="col-md-3 align-self-center">
           <!--  <button class="min-wid-110 btn btn-secondary">入力モード</button> -->
          </div>
          <div class="col-md-5 align-self-center">
            <div class="row mt-2">
              <div class="col-12" style="display: flex;align-items:  center;justify-content: flex-end;">
                <div class="mr-3">
                  <button class="btn btn-clear min-wid-110" type="button" onclick="openPopupInitColumn()">表示列選択</button>
                  <button class="btn btn-clear min-wid-110" type="button"
                    onclick="openPopupInitSearch()">検索条件表示</button>
                </div>
                <div>
                  <button class="btn btn-clear min-wid-110" type="button" onclick="clearForm(this)">条件クリア</button>
                  <button class="btn btn-search min-wid-110" type="button" onclick="searchList(this)">検索</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- MODAL POPUP SEARCH -->
        <div class="modal fade bd-example-modal-lg" id="popupSearchModal" tabindex="-1" role="dialog"
          aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div style="padding: 30px">

                @include('order.order-entry.popup-search')

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
<div class="modal fade" id="popupColumnModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" style="max-width: 65%; width: 800px">
    <div class="modal-content">
      <div>

        @include('order.order-entry.popup-column')

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="applyInitColumn()">保存</button>
        <button type="button" class="btn btn-secondary" onclick="notAppyInitColumn()">閉じる</button>
      </div>
    </div>
  </div>
</div>
<div class="mt-3">
  <div class="card">
    <div class="card-body">
      <div class="form-custom table-pagi-top">
        <table id="table" class="hansontable editable" data-sticky-columns="['id']">
        </table>
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
  var urlExportExcelDataTable = '';
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
    urlData: '{!! route('jyutyu.order_entry.data_list', request()->query()) !!}',
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
    urlValidateRows: '{{ route('jyutyu.order_entry.validate_row') }}',
    // Option to insert new rows at the end
    insertLastRow: true,
     // URL for updating data table
    urlUpdateDataTable: '{{route('jyutyu.order_entry.update_datatable')}}',
    // Option to copy data to the left
    isCopyLeft: true,
    // URL for updating initial data copy
    urlUpdateInitDatacopy: '{{ route('jyutyu.order_entry.update_init_copy') }}',
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
      url: '{{route('jyutyu.order_entry.validate_form_search_uriage')}}',
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
  }

  function clearForm(e) {
    $('#formUriage').find('input:not(:checkbox, :radio), select').val('');
    $('#formUriage').find('input[type="checkbox"], input[type="radio"]').prop('checked', false);
    $('#formUriage').find('input[type="radio"][value="all"]').prop('checked', true);
    $('.error-message-row').removeClass('active');
    $('.error-input').removeClass('error-input');
    $('.group-s-input').removeClass('active');
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
      url: '{{ route('jyutyu.order_entry.update_init_search') }}',
      data : data,
      method: 'POST',
      success: function(res) {
        $('#formUriage').find('input').filter(':not(:checkbox)').each(function() {
          var value = $(this).val();
          $(this).attr('data-old', value);
        }); 
        if(res.status == 200) {
          valueInitSearchPoppup = res.data;
        }
        $('.error-message-row').removeClass('active').html('');
        $('.error-input').removeClass('error-input');
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
      url: '{{ route('jyutyu.order_entry.update_init_column') }}',
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
    $('#formUriage input:not(:checkbox, :radio), #formUriage select').val('');
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
              if($('#formUriage').find('[name="'+key+'"]').is(':radio')) {
                $('#formUriage').find('[name="'+key+'"][value="'+value+'"]').prop('checked', true);
              } else {
                $('#formUriage').find('[name="'+key+'"]').val(value);
              }
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
  
  function onchangeNinusi(e) {
    var ninusiCd = $(e).parents('tr').find('[name="ninusi_cd"]').val();
    var _tr = $(e).parents('tr');
    if(ninusiCd) {
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
        }
      })
    }
  }

  $(document).ready(function() {

    refreshInitSearchPopup();
    refreshInitColumnPopup();
    // clearForm();
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