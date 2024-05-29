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
  .spreadsheet-content {
      overflow-x: auto;
  }
  /* CSS to apply a background color on even rows. */
  #spreadsheet tbody tr:nth-child(even) {
    background-color: #d3e3f5;
  }
  .jexcel_search, .perPageNumber{
      border: 1px solid #CED4DA !important;
      border-radius: 4px;
      font-size: 0.875rem;
      font-weight: 400;
      padding: 5px;
  }
  .cus-pagination {
      margin-bottom: 0px !important;
      margin-top: 0px !important;
  }
  .pagination-info{
      line-height: 34px;
      margin-right: 5px;
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
              <div class="" style="flex-wrap: nowrap; display: flex; flex: 1; position: relative; ">
                <input type="text" class="form-control size-M" name="hed_bumon_cd"
                  style="width: 100px; border-bottom-right-radius: 0; border-top-right-radius: 0"
                  onkeyup="suggestionForm(this, 'bumon_cd', ['bumon_cd', 'kana', 'bumon_nm'], {'bumon_cd': 'hed_bumon_cd', 'bumon_nm': 'hed_bumon_nm'}, $(this).parent())"
                  style="" autocomplete="off">

                <input class="form-control size-L" name="hed_bumon_nm"
                  style="border-bottom-left-radius: 0; border-top-left-radius: 0"
                  onkeyup="suggestionForm(this, 'bumon_nm', ['bumon_cd', 'kana', 'bumon_nm'], {'bumon_cd': 'hed_bumon_cd', 'bumon_nm': 'hed_bumon_nm'}, $(this).parent())"
                  autocomplete="off">
                <ul class="suggestion"></ul>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="flex-g">
              <label class="col-form-label label-search-uriage">入力担当</label>
              <div  style="position: relative;">
                <input type="text" class="form-control size-M" name="hed_add_tanto_cd" onkeyup="suggestionForm(this, 'hed_add_tanto_cd', ['jyomuin_cd', 'kana', 'jyomuin_nm'], {'jyomuin_cd': 'hed_add_tanto_cd', 'jyomuin_nm': 'hed_add_tanto_nm'}, $(this).parent())">
                <ul class="suggestion"></ul>
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
              <div class="" style="flex-wrap: nowrap; display: flex; flex: 1; position: relative; ">
                <input type="text" class="form-control size-M" name="hed_jyomuin_cd"
                  style="width: 100px; border-bottom-right-radius: 0; border-top-right-radius: 0"
                  onkeyup="suggestionForm(this, 'jyomuin_cd', ['jyomuin_cd', 'kana', 'jyomuin_nm'], {'jyomuin_cd': 'hed_jyomuin_cd', 'jyomuin_nm': 'hed_jyomuin_nm'}, $(this).parent())"
                  style="" autocomplete="off">

                <input class="form-control size-L" name="hed_jyomuin_nm"
                  style="border-bottom-left-radius: 0; border-top-left-radius: 0"
                  onkeyup="suggestionForm(this, 'jyomuin_nm', ['jyomuin_cd', 'kana', 'jyomuin_nm'], {'jyomuin_cd': 'hed_jyomuin_cd', 'jyomuin_nm': 'hed_jyomuin_nm'}, $(this).parent())" autocomplete="off">
                <ul class="suggestion"></ul>
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
              <div class="" style="flex-wrap: nowrap; display: flex; flex: 1; position: relative; ">
                <input type="text" class="form-control size-M" name="hed_jyutyu_kbn"
                  style="width: 100px; border-bottom-right-radius: 0; border-top-right-radius: 0"
                  onkeyup="suggestionForm(this, 'jyutyu_kbn', ['jyutyu_kbn', 'kana', 'jyutyu_nm'], {'jyutyu_kbn': 'hed_jyutyu_kbn', 'jyutyu_nm': 'hed_jyutyu_nm'}, $(this).parent())"
                  style="" autocomplete="off">

                <input class="form-control size-L" name="hed_jyutyu_nm"
                  style="border-bottom-left-radius: 0; border-top-left-radius: 0"
                  onkeyup="suggestionForm(this, 'bumon_nm', ['hed_jyutyu_kbn', 'kana', 'hed_jyutyu_nm'], {'jyutyu_kbn': 'hed_jyutyu_kbn', 'jyutyu_nm': 'hed_jyutyu_nm'}, $(this).parent())" autocomplete="off">
                <ul class="suggestion"></ul>
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
                  <button class="btn btn-clear btn-clear-form min-wid-110" type="button">条件クリア</button>
                  <button class="btn btn-search min-wid-110" type="button" >検索</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- MODAL POPUP SEARCH -->
        <div class="modal fade bd-example-modal-lg" id="popupSearchModal" tabindex="-1" role="dialog"
          aria-labelledby="myLargeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div style="padding: 30px">

                @include('order.order-entry.popup-search-dispatch')

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
  <div class="modal-dialog" style="max-width: 65%; width: 800px;">
    <div class="modal-content">
      <div>

        @include('order.order-entry.popup-column-dispatch')

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
    <div class="card-body spreadsheet-content">
        <div class="fixed-table-toolbar" style="display: none;">
            <div class="row">
                <div class="col-md-2">
                    <label>表示:</label>
                        <select class="perPageNumber" name="perPageNumber">
                            <option value="100">100</option>
                            <option value="200">200</option>
                            <option value="300">300</option>
                            <option value="400">400</option>
                        </select>
                    <label>件</label>
                </div>
                <div class="col-md-5">
                    <div class="fixed-table-pagination">
                        <div class="float-left pagination-detail">
                            <span class="pagination-info" pattern="{0}件中{1}～{2}件を表示">{0}件中{1}～{2}件を表示</span>
                        </div>
                        <div class="float-right pagination">
                            <ul class="pagination cus-pagination"></ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="columns columns-right btn-group float-right"><button class="btn btn-update min-wid-110" id="addRow">更新</button></div>
                </div>
            </div>
        </div>
        <div id='spreadsheet'></div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
var valueInitColumnPoppup = @if(!empty($dataInitColumnPopup)) @json($dataInitColumnPopup, true) @else null @endif;
localStorage.setItem("valueInitColumnPoppup", valueInitColumnPoppup);
if (valueInitColumnPoppup === null) {
    $('#initColumn input[type="checkbox"]').prop('checked', true);
    applyInitColumn(false);
}

function refreshInitColumnPopup() {
    $('#initColumn input[type="checkbox"]').prop('checked', false);
    if(valueInitColumnPoppup) {
      if (typeof valueInitColumnPoppup === 'object' && valueInitColumnPoppup !== null) {
        Object.keys(valueInitColumnPoppup).forEach(function (key) {
          if(valueInitColumnPoppup[key]) {
            $('#initColumn').find('input[name="'+valueInitColumnPoppup[key]+'"]').prop('checked', true);
          }
        });
      }
    }
    if(valueInitColumnPoppup == null) {
      $('#initColumn input[type="checkbox"]').prop('checked', true);
    }
}
function openPopupInitColumn() {
    refreshInitColumnPopup();
    $('#popupColumnModal').modal('show');
}

function notAppyInitColumn() {
    $('#popupColumnModal').modal('hide');
}

function applyInitColumn(reloadSearch = true) {
    var data = $('#initColumn').serializeArray();
    $.ajax({
        url: '{{ route('order.order_entry.update_init_column_dispatch') }}',
        data : data,
        method: 'POST',
        beforeSend: function (request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
        },
        success: function(res) {
            if(res.status == 200) {
                valueInitColumnPoppup = res.data;
                localStorage.setItem("valueInitColumnPoppup", res.data);
                if (reloadSearch) {
                    $("button.btn-search").click();
                }
            } else {
                Swal.fire({
                    title: res.message,
                    icon: 'error',
                    customClass: {
                      popup: 'custom-modal-size'
                    }
                });
            }
            $('#popupColumnModal').modal('hide');
        },
        error: function (xhr, status, error) {
          console.log(error);
        }
    });
}

function initColumnCheckHidden(e, list) {
    if (list && Array.isArray(list)) {
        for(let i = 0 ; i < list.length; i++) {
            if($(e).is(':checked')) {
                $('#popupColumnModal').find('input[name="'+list[i]+'"]').val(1);
            } else {
                $('#popupColumnModal').find('input[name="'+list[i]+'"]').val('');
            }
        }
    }
}

function openPopupInitSearch() {
    $('#formUriage').find('input').filter(':not(:checkbox)').each(function() {
        var value = $(this).val();
        $(this).attr('data-old', value);
    });
    $('#popupSearchModal').modal('show');
}
function notAppyInitSearch() {
    $('#formUriage').find('input').filter(':not(:checkbox)').each(function() {
      var old = $(this).data('old');
      $(this).val(old);
    });
    $('#popupSearchModal').modal('hide');
}
function applyInitSearch() {
var data = $('#formUriage').serialize();
    $.ajax({
      url: '{{ route('order.order_entry.update_init_search_dispatch') }}',
      data : data,
      method: 'POST',
      beforeSend: function (request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
        },
      success: function(res) {
        $('#formUriage').find('input').filter(':not(:checkbox)').each(function() {
          var value = $(this).val();
          $(this).attr('data-old', value);
        }); 
        if(res.status == 200) {
          valueInitSearchPoppup = res.data;
          $('.error-message-row').removeClass('active').html('');
          $("#popupSearchModal").find("input.error-input").removeClass('error-input');
        }
        $('#popupSearchModal').modal('hide');
      },
      error: function (error) {
        if (error.status == 422) {

          $('.error-message-row').removeClass('active').html('');
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

// onload.
$(function() {
    // global variables.
    var valueInitSearchPoppup =  @if(!empty($dataInitSearchPopup)) @json($dataInitSearchPopup, true) @else null @endif;
    var sps_settings = @json($setting);
    var _ja_language = {};
    var _jspDataKey = [];
    var _currentPage = 1;
    var _fromPagePGN = -1;
    var _toPagePGN = -1;
    var hideColumns = [];
    var dataMultiSort = [];
    var hScrollBarPos = -1;

    //////////// Object Event ////////////
    // 1. click: search data dispatch.
    $("button.btn-search").click(function() {
        searchDispatch();
    });
    // 2. click: clear form search.
    $("button.btn-clear-form").click(function() {
        clearForm();
    });
    // 3. change cell.
    var cellChanged = function(instance, cell, x, y, value, options) {
        let colInfo = instance.jexcel.getConfig().columns[x];
        switch (colInfo.type) {
            case 'c_date':
                if (value === '') {
                    return;
                }
                if (validDate(value) !== '') {
                    return;
                }
                let formatedDate = validDate(autoFillDate(value));
                instance.jspreadsheet.updateCell(x, y, formatedDate, true);
                break;
            case 'dropdown':
                if (value.indexOf("§") == -1) {
                    return;
                }
                let selectedData = value.split("§");
                if (selectedData.length == 0) {
                    return;
                }
                let posData = getPosSuggestion(x, _jspDataKey, selectedData);
                for (let i = 0; i < posData.pos.length; i++) {
                    instance.jspreadsheet.updateCell(posData.pos[i], parseInt(y), posData.value[i]);
                }
                cell.innerHTML = selectedData[0];
                cell.innerText = selectedData[0];
                break;
            case 'time':
                // TODO: format time type.
                if (value === '') {
                    return;
                }
                let formatedTime = validTime(value);
                if (formatedTime !== '') {
                    return;
                }
                instance.jspreadsheet.updateCell(x, y, formatedTime, true);
                break;
            default:
                break;
        }
    };
    // 4. paste data in selectedCell:range.
    var onbeforepasteEvent = function(instance, data, x, y, properties) {
        if (data.indexOf("\n") != -1) {
            return;
        }
        let selectedCell = jspreadsheet.current.selectedCell;
        let copyData = data.split("\t");

        if (selectedCell == undefined || selectedCell.length < 4) {
            return;
        }
        if (parseInt(selectedCell[2]) - parseInt(selectedCell[0]) + 1 != copyData.length) {
            return;
        }
        if (copyData.length == 0) {
            return;
        }
        let spreadCopyInfo = {
            'fromX' : parseInt(selectedCell[0]),
            'toX' : parseInt(selectedCell[2]),
            'fromY' : parseInt(selectedCell[1]),
            'toY' : parseInt(selectedCell[3]),
            'currentX': parseInt(selectedCell[0]),
            'currentY': parseInt(selectedCell[1]),
            'copyData' : copyData,
            'intervalId' : 0,
            'finished' : false,
        };
        let xCells = Math.abs(spreadCopyInfo.toX - spreadCopyInfo.fromX + 1);
        let yCells = Math.abs(spreadCopyInfo.toY - spreadCopyInfo.fromY + 1);
        let totalCells = xCells * yCells;

        if ( totalCells > 500) {
            // copy with interval > prevent overhead memory.
            loadingStatus();
            spreadCopyInfo.intervalId = setInterval(pasteLargeData, 100);
            localStorage.setItem('pasteLargeData', JSON.stringify(spreadCopyInfo));
            return;
        }
        for (let i = parseInt(selectedCell[0]); i <= parseInt(selectedCell[2]); i++) {
            for (let j = parseInt(selectedCell[1]); j <= parseInt(selectedCell[3]); j++) {
                instance.jspreadsheet.updateCell(i, j, copyData[i-parseInt(selectedCell[0])], true);
            }
        }
    }
    // 5. oncopy data.
    var oncopyEvent = function(instance, cell, data, cut) {
        // do something.
    }
    // 6. jspreadsheet onload event.
    var onLoadEvent = function(instance) {
        loadingStatus(false);

        // after spreadsheet loaded. do something.
        for (let i = 0; i < hideColumns.length; i++) {
            instance.jexcel.hideColumn(i);
        }
        $("div.fixed-table-toolbar").show();

        // mark sort.
        if (Object.keys(dataMultiSort).length > 0) {
            let keySort = Object.keys(dataMultiSort)[0];
            let indexCol = _jspDataKey.indexOf(keySort);
            if (indexCol > -1 && indexCol < $("table.jexcel>thead>tr>td").length) {
                if (dataMultiSort[keySort] === 'ASC') {
                    $("table.jexcel>thead>tr>td[data-x='"+indexCol+"']").addClass("arrow-up");
                }
                if (dataMultiSort[keySort] === 'DESC') {
                    $("table.jexcel>thead>tr>td[data-x='"+indexCol+"']").addClass("arrow-down");
                }
                if (hScrollBarPos > -1) {
                    $("div.jexcel_content").scrollLeft(hScrollBarPos);
                    hScrollBarPos = -1;
                }
            }
        }

        // change font size.
        $("#spreadsheet table td").css("font-size",'11pt');
    }
    // 7. change perPage.
    $("select.perPageNumber").change(function() {
        searchDispatch(false);
    });
    // 8. save data.
    $("button.btn-update").click(function() {
        saveSpreadSheetData();
    });
    // 9.Add event listener for "Check All" checkbox
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
    // 10. search condition event.
    $('#popupSearchModal input[type="checkbox"]:not(.form-search)').click(function(){
        if($(this).is(':checked')) {
          $(this).parents('.row-s').find('.group-s-input').addClass('active');
        } else {
          $(this).parents('.row-s').find('input, select').val('');
          $(this).parents('.row-s').find('input[type="checkbox"]').prop('checked', false);
          $(this).parents('.row-s').find('.group-s-input').removeClass('active');
        }
    });
    // 11. sort event
    var onSortEvent = function(instance, column, direction, newValue) {
        column = parseInt(column);
        let bkDataMultiSort = dataMultiSort;
        if (_jspDataKey.hasOwnProperty(column)) {
            if (bkDataMultiSort.hasOwnProperty(_jspDataKey[column])) {
                bkDataMultiSort[_jspDataKey[column]] = (bkDataMultiSort[_jspDataKey[column]] == 'DESC') ? 'ASC': 'DESC';
            } else {
                bkDataMultiSort[_jspDataKey[column]] = 'DESC';
            }
            dataMultiSort = [];
            dataMultiSort[_jspDataKey[column]] = bkDataMultiSort[_jspDataKey[column]];
        }
        hScrollBarPos = $("div.jexcel_content").scrollLeft();
        searchDispatch(true);
    };

    //////////// Functions ////////////
    // 1.1 get params search.
    function getParamsSearch() {
        var params = [];
        var formSearch = $('#formUriage');
        formSearch.find('select, input').each(function() {
            if ($(this).attr('name')) {
                if ($(this).is(':checkbox') && !$(this).is(':checked')) {
                    // Skip unchecked checkboxes
                    return;
                }
                // Check if the input is a radio and if it is checked
                if ($(this).is(':radio') && !$(this).is(':checked')) {
                  // Skip unchecked radio buttons
                  return;
                }
                params[$(this).attr('name')] = $(this).val();
            }
            // Handle search inputs
            
        });
        params['dataMultiSort'] = Object.assign({}, dataMultiSort);
        if ($("select.perPageNumber :selected").val().length > 0) {
            params['perPage'] = parseInt($("select.perPageNumber :selected").val());
        }
        return params;
    }

    // 1.2 search dispatch mode data.
    function searchDispatch(isPageChange = false) {
        let url = '{!! route('order.order_entry.dispatch_list', request()->query()) !!}';
        let params = Object.assign({}, getParamsSearch());
        if (!isPageChange) {
            _currentPage = 1; // reset to default page.
        }
        params['page'] = _currentPage;
        loadingStatus();
        $.ajax({
            url : url,
            data : params,
            type : 'POST',
            beforeSend: function (request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
            },
            success : function(res) {
                renderSpreadSheetDispatch(res);
            },
            error: function (xhr, status, error) {
              console.log(error);
              loadingStatus(false);
            }
        });
    }

    // 1.3 render data from search result to spreadsheet.
    function renderSpreadSheetDispatch(dispatchData) {
        jspreadsheet.destroy(document.getElementById('spreadsheet'));
        if (!dispatchData.hasOwnProperty("rows") || !dispatchData.hasOwnProperty("total") || dispatchData.total == 0) {
            setPageInfo(dispatchData);
            loadingStatus(false);
            return;
        }

        var data = [];
        var rowsInfo = [];
        var key_data = [];
        _jspDataKey = [];
        for (let i = 0; i < dispatchData.rows.length; i++) {
            let currRow = dispatchData.rows[i];
            let currRowData = [];
            rowsInfo.push({
                id : currRow.uriage_den_no,
            });

            Object.keys(currRow).forEach(function(key) {
                currRowData.push(currRow[key]);
                if (i == 0) {
                    key_data.push(key);
                    _jspDataKey.push(key);
                }
            });
            if (currRowData.length > 0) {
                data.push(currRowData);
            }
        }

        if (localStorage.hasOwnProperty("valueInitColumnPoppup")) {
            let listInitColumnPoppup = localStorage.valueInitColumnPoppup.split(",");
            if (listInitColumnPoppup.indexOf("uriage_den_no") === -1) {
                sps_settings.uriage_den_no.visible = true;
            } else {
                sps_settings.uriage_den_no.visible = false;
            }
        }

        // TODO: setting render columns.
        let columns = [];
        let column = null;
        hideColumns = [];
        for (let i = 0; i < key_data.length; i++) {
            column = {
                type : 'c_text',
                title : key_data[i],
                width : 120,
                align : 'center',
            };
            if (sps_settings.hasOwnProperty(key_data[i])) {
                column.title = sps_settings[key_data[i]].title;
                if (sps_settings[key_data[i]].hasOwnProperty("type")) {
                    column.type = sps_settings[key_data[i]].type;
                    if (column.type == 'numberic') {
                        column['mask'] = '#,##';
                        column['decimal'] = '.';
                        if (sps_settings[key_data[i]].hasOwnProperty("mask")) {
                            column['mask'] = sps_settings[key_data[i]].mask;
                        }
                    }
                    if (column.type == 'calendar') {
                        column['options'] = {
                            format : 'YYYY/MM/DD',
                            months : ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
                            weekdays : ['日曜日','月曜日','火曜日','水曜日','木曜日','金曜日','土曜日'],
                            weekdays_short : ['日', '月', '火', '水', '木', '金', '土'],
                            readonly : 0,
                            update: '更新',
                        };
                    }
                    if (column.type == 'autocomplete') {
                        column.type = 'dropdown';
                        column['autocomplete'] = true;
                        if (sps_settings[key_data[i]].hasOwnProperty("source")) {
                            column['source'] = sps_settings[key_data[i]].source;
                        }
                        if (sps_settings[key_data[i]].hasOwnProperty("remoteSearch")) {
                            column['remoteSearch'] = sps_settings[key_data[i]].remoteSearch;
                            column['lazyLoading'] = !!sps_settings[key_data[i]].remoteSearch;
                            column['url'] = sps_settings[key_data[i]].url;
                        }
                    }
                }
                if (sps_settings[key_data[i]].hasOwnProperty("width")) {
                    column.width = sps_settings[key_data[i]].width;
                }
                if (sps_settings[key_data[i]].hasOwnProperty("visible") && sps_settings[key_data[i]].visible == true) {
                    hideColumns.push(i);
                }
                if (sps_settings[key_data[i]].hasOwnProperty("align")) {
                    column.align = sps_settings[key_data[i]].align;
                }
                if (sps_settings[key_data[i]].hasOwnProperty("editable") && sps_settings[key_data[i]].editable == false) {
                    column['readOnly'] = true;
                }
            }
            columns.push(column);
        }

        let resizeTableHeight = $("div.spreadsheet-content").innerHeight() - 90;
        resizeTableHeight = resizeTableHeight.toString() + "px";

        var spreadTable;
        spreadTable = jspreadsheet(document.getElementById('spreadsheet'), {
            data : data,
            columns : columns,
            rows: rowsInfo,
            text: _ja_language,
            onchange: cellChanged,
            onbeforepaste: onbeforepasteEvent,
            oncopy: oncopyEvent,
            onload: onLoadEvent,
            onsort: onSortEvent,
            freezeRows: 0,
            tableHeight: resizeTableHeight,
            tableWidth: ($("div.card").width() - 50 )+'px',
            tableOverflow: true,
            allowInsertRow: false,
            allowInsertColumn: false,
            allowDeleteRow: false,
            allowDeleteColumn: false,
            // search:true,
        });
        setPageInfo(dispatchData);
    }

    // 1.4 set page info.
    function setPageInfo(resData) {
        if (!resData.hasOwnProperty("rows") || !resData.hasOwnProperty("total")) {
            return;
        }
        if (resData.total == 0) {
            $("span.pagination-info").text("0件");
            $("ul.cus-pagination").hide();
            $("div.fixed-table-toolbar").show();
        } else {
            let pnMaxButton = 5;
            let perPage = parseInt($("select.perPageNumber :selected").val());
            let totalPage = parseInt(resData.total / perPage);
            totalPage += (resData.total%perPage != 0)? 1: 0;
            let fromPage = 1;
            if (_currentPage > pnMaxButton) {
                fromPage = Math.floor(_currentPage / pnMaxButton);
                fromPage += (_currentPage % pnMaxButton != 0) ? 1: 0;
                fromPage = fromPage * pnMaxButton - pnMaxButton + 1;
            }
            let toPage = ((fromPage+pnMaxButton - 1) <= totalPage) ? (fromPage+pnMaxButton - 1): totalPage;
            let ulElements = [];
            ulElements.push('<li class="page-item page-pre" typeButton="previous"><a class="page-link" aria-label="previous page" href="javascript:void(0)">‹</a></li>');
            if (fromPage > pnMaxButton) {
                ulElements.push('<li class="page-item" typeButton="prevRangePage"><a class="page-link" aria-label="to page" href="javascript:void(0)">...</a></li>');
            }
            _fromPagePGN = fromPage;
            _toPagePGN = toPage;
            for (let ip = fromPage; ip <= toPage; ip++) {
                let active = (ip == _currentPage)? 'active': '';
                ulElements.push('<li class="page-item '+active+'" typeButton="number"><a class="page-link" aria-label="to page '+ip+'" href="javascript:void(0)">'+ip+'</a></li>');
            }
            if (toPage < totalPage) {
                ulElements.push('<li class="page-item" typeButton="nextRangePage"><a class="page-link" aria-label="to page" href="javascript:void(0)">...</a></li>');
            }
            ulElements.push('<li class="page-item page-next" typeButton="next"><a class="page-link" aria-label="next page" href="javascript:void(0)">›</a></li>');
            $("ul.cus-pagination").html(ulElements.join(''));
            $("ul.cus-pagination").attr("totalPage", totalPage);
            $("ul.cus-pagination>li").click(function() {
                let typeButton = $(this).attr('typeButton');
                let totalPage = parseInt($(this).parents().attr('totalPage'));
                switch(typeButton) {
                    case 'previous':
                        _currentPage = (_currentPage > 1) ? _currentPage - 1: _currentPage;
                        break;
                    case 'next':
                        _currentPage = (_currentPage < totalPage) ? _currentPage + 1: _currentPage;
                        break;
                    case 'prevRangePage':
                        _currentPage = _fromPagePGN - 1;
                        break;
                    case 'nextRangePage':
                        _currentPage = _toPagePGN + 1;
                        break;
                    default:
                        _currentPage = parseInt($(this).text());
                        break;
                }
                searchDispatch(true);
            });
            // pagination info.
            let info = $("span.pagination-info").attr("pattern");
            info = info.replace('{0}', resData.total);
            let toRecord = ((_currentPage * perPage) <= resData.total) ? _currentPage * perPage: resData.total;
            let fromRecord = _currentPage * perPage - perPage + 1;
            info = info.replace('{1}', fromRecord);
            info = info.replace('{2}', toRecord);
            $("span.pagination-info").text(info);
            $("ul.cus-pagination").show();
        }
    }

    // 2.1 clear search form.
    function clearForm() {
        $('#formUriage').find('input:not(:checkbox, :radio), select').val('');
        $('#formUriage').find('input[type="checkbox"], input[type="radio"]').prop('checked', false);
        $('#formUriage').find('input[type="radio"][value="all"]').prop('checked', true);
        $('.group-s-input').removeClass('active');
    }

    // 3.1 input : mm/dd → convert to yyyy/mm/dd.
    function autoFillDate(inputDate) {
      var mmdd_regex = /^\d{1,2}\/\d{1,2}$/;
      var yyyymmdd_regex = /^\d{4}\/\d{1,2}\/\d{1,2}$/;
      inputDate = inputDate.replaceAll('-', '/');
      if (mmdd_regex.test(inputDate)) {
        var splitMMDD = inputDate.split("/");
        var mm = splitMMDD[0];
        var dd = splitMMDD[1];
        if (mm.length == 1) {
          mm = '0' + mm;
        }
        if (dd.length == 1) {
          dd = '0' + dd;
        }
        var Today = new Date();
        return Today.getFullYear() + "/" + mm + "/" + dd;
      }
      if (yyyymmdd_regex.test(inputDate)) {
          return inputDate;
      }
      return '';
    }

    // 3.2 return validDate or ''
    function validDate(date) {
        try {
            $.datepicker.parseDate("yy/mm/dd", date);
        } catch (e) {
            return '';
        }
        return date;
    }

    // 3.3 return validTime or ''
    function validTime(time) {
        let valid = moment(time, "HH:mm:ss", true).isValid();
        return valid ? time: '';
    }

    // 3.4 get pos enter data suggestion.
    function getPosSuggestion(xPos, colKeys, selectedData) {
        let posData = {
            pos : [],
            value : [],
        };
        for (let i = 0; i < selectedData.length; i++) {
            posData.pos.push(parseInt(xPos) + i);
            posData.value.push(selectedData[i]);
        }
        if (!colKeys.hasOwnProperty(xPos)) {
            return posData;
        }
        // customize suggestion.
        switch(colKeys[xPos]) {
            case 'syaban':
                if (selectedData.length >= 4) {
                    posData.pos = [];
                    posData.value = [];
                    posData.pos.push(xPos);
                    posData.value.push(selectedData[0]);
                    if (selectedData[1] == '0') {
                        pos = colKeys.indexOf("jyomuin_cd");
                    } else if (selectedData[1] == '1') {
                        pos = colKeys.indexOf("yousya_cd");
                    }
                    if (pos >= 0) {
                        posData.pos.push(pos);
                        posData.value.push(selectedData[2]);
                        posData.pos.push(pos+1);
                        posData.value.push(selectedData[3]);
                    }
                }
                break;
        }
        return posData;
    }

    // 4.1 paste large data.
    function pasteLargeData() {
        if (!localStorage.hasOwnProperty("pasteLargeData")) {
            return;
        }
        let spreadCopyInfo = JSON.parse(localStorage.pasteLargeData);
        let count = 0;
        let maxCellPaste = 200; // paste 200 cell every times.
        let spreadRef = $("#spreadsheet")[0].jexcel;
        var jPos = null;
        for (var i = spreadCopyInfo.currentX; i <= spreadCopyInfo.toX; i++) {
            if (jPos == null) {
                jPos = spreadCopyInfo.currentY;
            } else {
                jPos = j > spreadCopyInfo.toY ? spreadCopyInfo.fromY: j;
            }
            for (var j = jPos; j <= spreadCopyInfo.toY; j++) {
                spreadRef.updateCell(i, j, spreadCopyInfo.copyData[i-spreadCopyInfo.fromX], true);
                count++;
                if (count == maxCellPaste) {
                    spreadCopyInfo.currentX = i;
                    spreadCopyInfo.currentY = j;
                    break;
                }
            }
            if (count == maxCellPaste) {
                spreadCopyInfo.currentX = i;
                spreadCopyInfo.currentY = j;
                break;
            }
        }
        localStorage.setItem('pasteLargeData', JSON.stringify(spreadCopyInfo));
        if (i > spreadCopyInfo.toX) {
            clearInterval(spreadCopyInfo.intervalId);
            localStorage.removeItem('pasteLargeData');
            loadingStatus(false);
            return;
        }
    }

    // 8.1 save data.
    function saveSpreadSheetData() {
        if ($("#spreadsheet")[0].jexcel === null || $("#spreadsheet")[0].jexcel === undefined) {
            return;
        }
        let spData = $("#spreadsheet")[0].jexcel.getData();
        if (spData === undefined || spData === null || spData.length == 0) {
            return;
        }
        let params = [];
        let rowData = [];
        for (let i = 0; i < spData.length; i++) {
            if (_jspDataKey.length != spData[i].length) {
                continue;
            }
            rowData = [];
            for (let j = 0; j < _jspDataKey.length; j++) {
                rowData[_jspDataKey[j]] = spData[i][j] === '' ? null: spData[i][j];
            }
            params.push(Object.assign({}, rowData));
        }
        let url = '{!! route('order.order_entry.update_datatable_dispatch') !!}';
        let jsonString = JSON.stringify(Object.assign({}, params));
        let blob = new Blob([jsonString], { type: 'application/json' });
        var formData = new FormData();
        formData.append('data', blob);
        loadingStatus();

        $.ajax({
            url : url,
            data : formData,
            type : 'POST',
            processData: false,
            contentType: false,
            beforeSend: function (request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
            },
            success : function(res) {
                loadingStatus(false);
                if (res.status == 200) {
                    Swal.fire({
                      'title': res.message,
                      'icon': 'success',
                    });
                    searchDispatch(false);
                } else {
                    Swal.fire({
                        'title': 'エラーが発生しました。管理者にお問い合わせください',
                        'icon': 'error',
                        customClass: {
                            popup: 'custom-modal-size'
                        }
                    })
                }
            },
            error: function (xhr, status, error) {
              console.log(error);
              loadingStatus(false);
            }
        });
    }

    // 0. init.
    function init() {
        $('.popup-datetime').datepicker({
            dateFormat: 'yy/mm/dd',
            onSelect: function(selectedDate, instance) {
            var name = $(this).attr('name');
                if(name.includes('_from')) {
                  $('input[name="'+ name.replace('_from', '_to') +'"]').datepicker("option", "minDate", selectedDate);
                }
            }
        });
        refreshInitSearchPopup();
        clearForm();
        initLocalStorage();
        resizeSpreadSheet();

        _ja_language = {
            noRecordsFound:'記録が見つかりませんでした',
            showingPage:'ページ: {0} / {1}',
            show:'表示: ',
            entries:' 件/ページ',
            search: '検索',
            insertANewColumnBefore:'前に新しい列を挿入します',
            insertANewColumnAfter:'後に新しい列を挿入します',
            deleteSelectedColumns:'選択した列を削除します',
            renameThisColumn:'この列の名前を変更します',
            orderAscending:'昇順',
            orderDescending:'降順',
            insertANewRowBefore:'前に新しい行を挿入します',
            insertANewRowAfter:'後に新しい行を挿入します',
            deleteSelectedRows:'選択した行を削除します',
            editComments:'コメント修正',
            addComments:'コメント追加',
            comments:'コメント',
            clearComments:'コメントクリア',
            copy:'コピー ...',
            paste:'貼り付け ...',
            saveAs:'名前を付けて保存 ...',
            about:'このライブラリーについて',
            areYouSureToDeleteTheSelectedRows:'選択した行を削除してもよろしいですか?',
            areYouSureToDeleteTheSelectedColumns:'選択した列を削除してもよろしいですか?',
            // thisActionWillDestroyAnyExistingMergedCellsAreYouSure:'Esta ação irá destruir todas as células mescladas existentes. Você tem certeza?',
            // thisActionWillClearYourSearchResultsAreYouSure:'Esta ação limpará seus resultados de pesquisa. Você tem certeza?',
            // thereIsAConflictWithAnotherMergedCell:'Há um conflito com outra célula mesclada',
            // invalidMergeProperties:'Propriedades mescladas inválidas',
            // cellAlreadyMerged:'Cell já mesclado',
            // noCellsSelected:'Nenhuma célula selecionada',
        };
    }

    // 0.1 loading popup.
    function loadingStatus(onFlag = true) {
        if (onFlag) {
            let elements = [];
            elements.push('<div id="loadingStatus" class="loading" style="background-color: rgb(120 92 92 / 50%) !important;">');
            elements.push('<div class="ten">');
            elements.push('<div class="dot dot1"></div>');
            elements.push('<div class="dot dot2"></div>');
            elements.push('<div class="dot dot3"></div>');
            elements.push('</div>');
            elements.push('</div>');
            $('body').append(elements.join(""));
        } else {
            $('#loadingStatus').remove();
        }
    }

    // 0.2 clear search condition.
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

    // 0.3 localStorage setting.
    function initLocalStorage() {
        localStorage.removeItem('urlSearchSuggestion');
        localStorage.setItem('urlSearchSuggestion', '{{ route('uriage.uriage_entry.search_suggestion') }}');
    }

    // 0.4 resize
    function resizeSpreadSheet() {
        var resizeHeight = $("div.content-wrapper").outerHeight(true) - $($("div.card")[0]).find("div.card-body").outerHeight(true);
        if (resizeHeight != undefined && resizeHeight > 0) {
            resizeHeight = Math.round(resizeHeight) - 45; // padding scale.
            $($("div.card")[1]).height(resizeHeight);
        }
        return resizeHeight;
    }

    // init.
    init();
})
</script>
@endsection