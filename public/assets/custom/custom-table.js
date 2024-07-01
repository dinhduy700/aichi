var isValidate = false;
// Flag to track whether the list button toolbar has been appended
var isAppendListButtonToolBar = false;
var exportButtonAdded = false;
// Flag to track whether the copy button has been added
var copyButtonAdded = false;
var copyButonLeftAdded = false;
var deleteButtonAdded = false;
var copyButtonActiveAdded = false;
var updateDataTableButtonAdded = false;
var dataMultiSort = {};
var eventMultiSort = false;
var pageNumber = 1;
var hasChangeData = false;
var useAddFormFooter = false;
var resHeader = 1;
var isCallBack = false;
var callbackF = function(){};
$.fn.customTable = function (options) {


  // Object to store query parameters for the table
  var queryParamsData = {};

  // Merge user-defined options with default settings
  var settings = $.extend({
    urlData: '',
    showColumns: false,
    columns: null,
    listButtonToolBar: '',
    pageNumber: 1,
    urlInsertDataRecord: '',
    formSearch: false,
    urlCopyRecord: '',
    urlUpdateDataRecord: '',
    urlSearchSuggestion: '',
    isShowBtnExcel: true,
    urlExportExcelDataTable: '',
    pageSize: 50,
    textButtonExportExcel: 'EXCEL出力',
    urlValidateRows: '',
    insertLastRow: false,
    urlUpdateDataTable: '',
    isCopyLeft: false,
    urlUpdateInitDatacopy: '',
    initCopy: [],
    isDelete: false,
    dataDelete: [],
    readonlyRowField: '',
    readonlyRowWhere: '',
    defaultSearchForm: true,
    formatNoMatches: '指定の条件に一致するデータが見つかりません',
    formatLoadingMessage: 'ローディング中。。。',
    errorNoChoiceOneRecord: '対象行を選択してください',
    errorException: 'エラーが発生しました。管理者にお問い合わせください',
    isShow: false,
    defaultParams: null,
    usingPaginateTop: true,
    sortName: '',
    isResize: false,
    focusAfterName: '',
    isCopyActiveRow: false

  }, options);

  settings.columns.forEach(function (column) {
    if (column.editable && !column.formatter) {
      column.formatter = inputFormatter;
    }
  });
  this.data('customTableSettings', settings);

  if (settings.defaultParams) {
    queryParamsData = settings.defaultParams;
  }

  $.fn.customTable.isShowTable = function () {
    // Check if URL for data is provided
    if (settings.isShow == true) {
      if (settings.urlData != '') {
        // Initialize Bootstrap table
        $('#table').bootstrapTable({
          columns: settings.columns,
          url: settings.urlData,
          showMultiSort: true,
          sortResetPage: true,
          sortName: settings.sortName,
          sortOrder: 'asc',
          responseHandler: function (res) {
            resHeader = res;
            return res;
          },
          formatNoMatches: function () {
            return settings.formatNoMatches;
          },
          formatLoadingMessage: function () {
            return settings.formatLoadingMessage;
          },
          onPostBody: function () {
            hasChangeData = false;
            $('#table thead th div.sortable').css('pointer-events', 'unset');
            if(isCallBack == true) {
              callbackF(resHeader);
            }
            $('.datepickerGrid').datepicker({
              dateFormat: 'yy/mm/dd',
              autoclose: true
            });
            $('#table input, #table select').on('focus', function () {
              var inputRect = this.getBoundingClientRect();
              var tableRect = $('.fixed-table-body').get(0).getBoundingClientRect();
              var scrollAdjustment = 0;
              if (inputRect.right + 200 > tableRect.right) {
                scrollAdjustment = inputRect.right - tableRect.right + 200;
              } else if (inputRect.left < tableRect.left) {
                scrollAdjustment = inputRect.left - tableRect.left - 200;
              }
              if (scrollAdjustment === 0 && inputRect.right > tableRect.right) {
                scrollAdjustment = inputRect.right - tableRect.right + 10;
              }

              $('.fixed-table-body').scrollLeft($('.fixed-table-body').scrollLeft() + scrollAdjustment);
            });
            $('#table tbody input:not([name="btSelectItem"]), #table tbody select').on('change', function () {
              // suggestion popup - check validate after run bootstrapTable.updateCell
              // bootstrapTable.updateCell => because this function reset event of tag
              // re-add change event again.
              if ($(this).attr("onkeyup") === 'suggestionKeyup(this)') {
                let currentTh = $(this).closest('td').find(".suggestion");
                // if select data form suggestion > dont need validate on change event.
                if (keyDownCode == 13 && currentTh.find("li.key-focusing").length > 0) {
                  return;
                }
              }

              var $row = $(this).closest('tr');
              var newValue = $(this).val();
              var index = $row.index();
              var row = $('#table').bootstrapTable('getData')[$row.index()];
              var field = $(this).attr('name');
              if (settings.urlValidateRows != '') {
                var tr = $(this).closest('tr');
                var newRow = Object.assign({}, row);
                tr.find('select, input').each(function() {
                  if($(this).hasClass('text-right')) {
                    newRow[$(this).attr('name')] = $(this).val().replace(',', '');
                  } else {
                    newRow[$(this).attr('name')] = $(this).val();
                  }
                })
                validateRows(newRow)
                  .then(function (res) {
                    row[field] = newValue;
                    // row = newRow;
                    Object.keys(newRow).forEach(function(key) {
                      var flgHasChangeValue = false;
                      if (key.endsWith('_dt') && (row[key] || newRow[key]) ) {
                        let rowValueRPL = row[key] ? row[key].replace(/-/g, '/') : null;
                        let newRowValueRPL = newRow[key] ? newRow[key].replace(/-/g, '/') : null;
                        if (rowValueRPL != newRowValueRPL) {
                          flgHasChangeValue = true;
                        }
                      } else if( (row[key] || newRow[key]) && (row[key] != newRow[key])   ) {
                        flgHasChangeValue = true;
                      }

                      if(flgHasChangeValue) {
                        setTimeout(function() {
                          $row.find('[name="'+key+'"]').addClass('hasChangeValue');
                          hasChangeData = true;
                          $('#table thead th div.sortable').css('pointer-events', 'none');
                        }, 100);
                      }
                      row[key]= newRow[key];
                    });
                    setTimeout(function() {
                      $row.find('[name="'+field+'"]').addClass('hasChangeValue');
                      hasChangeData = true;
                      $('#table thead th div.sortable').css('pointer-events', 'none');
                    }, 100);
                    tr.find('.error span').html('');
                  }).catch(function (xhr) {
                    tr.find('.error span').html('');
                    if (xhr.status == 422) {
                      var response = JSON.parse(xhr.responseText);
                      var errors = response.errors;
                      $.each(errors, function (key, value) {
                        tr.find('.error-' + key).parents('.group-input').addClass('error');
                        tr.find('.error-' + key + ' span').html(value);
                      });
                    }
                  })
              }
            });
            $('#table ').on('keyup', 'tfoot input', function() {
              var row = $('#table tfoot tr');
              var hasChangeDataFooter = false;
              row.find('input, select').each(function () {
                var value = $(this).val();
                if (value != null && value.trim() !== '') {
                  hasChangeDataFooter = true;
                  $('#table thead th div.sortable').css('pointer-events', 'none');
                  return ;
                } 
              });
              if(hasChangeDataFooter == false && hasChangeData == false) {
                $('#table thead th div.sortable').css('pointer-events', 'unset');
              }
            });
            $('#table tbody, #table tfoot').on('keydown', 'input:not("checkbox"), select', function(event) {
              if($(this).parents('td').find('.suggestion').length == 0) {
                if (event.key === "ArrowUp") {
                  var indexTd = $(this).parents('td').index();
                  var indexTr = $(this).parents('tr').index();
                  if(indexTr > 0) {
                    $('#table tbody tr').eq(indexTr - 1).find('td').eq(indexTd).find('input,select').focus();
                  }
                }

                if (event.key === "ArrowDown") {
                  var indexTd = $(this).parents('td').index();
                  var indexTr = $(this).parents('tr').index();
                  if(indexTr >= 0) {
                    $('#table tbody tr').eq(indexTr + 1).find('td').eq(indexTd).find('input,select').focus();
                  }
                }
              }
            });


            $('#table tbody').on('keyup', 'input, select', function(event) {
              if (event.which === 13) {
                event.preventDefault();
                var inputsAndSelectsInRow = $(this).closest('tr').find('input, select');
                var indexTr = $(this).closest('tr').index();
                var indexTd = $(this).closest('td').index();
                var currentIndex = inputsAndSelectsInRow.index(this);
                if (currentIndex === inputsAndSelectsInRow.length - 1) {
                  var nextRow = $(this).closest('tr').next('tr');
                  if (nextRow.length > 0) {
                    setTimeout(function() {
                      nextRow.find('input, select').first().focus();
                    }, 100);
                  }
                } else {
                  setTimeout(function() {
                    inputsAndSelectsInRow = $('#table tbody tr[data-index="'+indexTr+'"]').find('input, select');
                    var nextInput = inputsAndSelectsInRow.eq(currentIndex + 1);
                    nextInput.focus();
                    var val = nextInput.val();
                    nextInput.val('');
                    nextInput.val(val);
                  }, 100);
                }
              }
            });
            if (settings.readonlyRowField != '') {
              var readonlyFields = Array.isArray(settings.readonlyRowField) ? settings.readonlyRowField : [settings.readonlyRowField];

              if (readonlyFields.length > 0) {
                var data = $('#table').bootstrapTable('getData');
                data.forEach(function (row) {
                  var disableRow = false;
                  if(settings.readonlyRowWhere == '') {
                    // Check if at least one field in the row is not null
                    var disableRow = readonlyFields.some(function (field) {
                      return row.hasOwnProperty(field) && (row[field] !== null && row[field] !== '' && row[field] !== 'undefined');
                    });
                  } else if(settings.readonlyRowWhere == '!=0') {
                    var disableRow = readonlyFields.some(function (field) {
                      return row.hasOwnProperty(field) && row[field] != 0 && row[field] !== null;
                    });
                  }

                  var rowIndex = $('#table').bootstrapTable('getData').indexOf(row);

                  /// Find the row and all child elements
                  var $row = $('#table').find('tr[data-index="' + rowIndex + '"]');
                  var $rowElements = $row.find('input, select, textarea');

                  // Disable all child elements
                  $rowElements.prop('disabled', disableRow);

                  // Add a CSS class for disabled elements
                  if (disableRow) {
                    $row.addClass('disabled-row');
                  } else {
                    $row.removeClass('disabled-row');
                  }
                });
              }

            }

            // console.log($('#table').bootstrapTable('getOptions'));


          },
          onPreBody: function () {

          },
          onCheck: function (row, $element) {
            handleSelectionChange();
          },
          onUncheck: function (row, $element) {
            handleSelectionChange();
          },
          onCheckAll: function (rows) {
            handleSelectionChange();
          },
          onUncheckAll: function (rows) {
            handleSelectionChange();
          },
          onSort: function (name, order) {
            pageNumber = 1;
          },
          onLoadSuccess: function (data) {

            // $('#table').bootstrapTable('load', data);

            // copyButtonAdded = false;
            // isAppendListButtonToolBar = false;
            // exportButtonAdded = false;
            // updateDataTableButtonAdded = false;
            // copyButonLeftAdded = false;
            // deleteButtonAdded = false;

            // Add copy button if not added already
            if (!copyButtonAdded) {
              addCopyButton();
              copyButtonAdded = true;
            }
            // Get visible columns and update footer inputs if needed
            var visibleColumns = $('#table').bootstrapTable('getVisibleColumns');
            if (Array.isArray(visibleColumns) && visibleColumns.length > 0) {
              if (useAddFormFooter) {
                updateFooterInputs(visibleColumns);
              }
            }
            // Append list button toolbar if not appended already
            if (!isAppendListButtonToolBar && settings.listButtonToolBar != '') {
              addListButtonToolBar();
              isAppendListButtonToolBar = true;
            }
            if (!exportButtonAdded) {
              addExportExcelDataTable();
              exportButtonAdded = true;
            }
            if (!updateDataTableButtonAdded) {
              addUpateDataTable();
              updateDataTableButtonAdded = true;
            }
            if (settings.isCopyLeft == true && !copyButonLeftAdded) {
              addCopyButtonLeft();
              copyButonLeftAdded = true;
              if ($('#columnSelectionModalBody').length == 0) {
                $('body').append(`<div class="modal fade" id="columnSelectionModal" tabindex="-1" role="dialog" aria-labelledby="columnSelectionModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document" style="max-width: 65%; width: 800px">
                    <div class="modal-content">
                      <div class="modal-body" id="columnSelectionModalBody">
                        <!-- Checkboxes for copitable columns will be appended here -->
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="applyColumnSelection()">保存</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                      </div>
                    </div>
                  </div>
                </div>`);
              }
              openColumnSelectionPopup();
            }

            // if(settings.isCopyActiveRow == true && !copyButtonActiveAdded) {
            //   addCopyButtonActiveRow();
            //   copyButtonActiveAdded = true;
            // }

            if (settings.isDelete == true && !deleteButtonAdded) {
              addDeleteDataTable();
              deleteButtonAdded = true;
            }


            // $('.fixed-table-body').css('padding-top', '1px');
            if ($('#table').bootstrapTable('getOptions').hasOwnProperty('sortable') && $('#table').bootstrapTable('getOptions').sortable === false) {

              $('#checkboxMultiSort').prop('checked', true);
              if ($('#table').bootstrapTable('getOptions').hasOwnProperty('useSortMulti') && $('#table').bootstrapTable('getOptions').useSortMulti === true) {
                var tableOptions = $('#table').bootstrapTable('getOptions');
                var columns = tableOptions.columns[0]; // Lấy danh sách cột

                $.each(columns, function (index, column) {
                  if (column.hasOwnProperty('sortable') && column.sortable === true) {
                    // Thêm class cho các cột có thể sắp xếp
                    $('#table th[data-field="' + column.field + '"] div.th-inner').addClass('sortable both');

                  }
                });
              }
              $('#table').off('click', 'th div.th-inner.sortable', onClickTh);
              $('#table').on('click', 'th div.th-inner.sortable', onClickTh);

            } else {
              dataMultiSort = {};
            }
            Object.keys(dataMultiSort).forEach(function (key) {
              var value = dataMultiSort[key];
              $('#table th[data-field="' + key + '"] div.sortable.th-inner').addClass(value);
            });

            if (settings.usingPaginateTop) {
              $('.fixed-table-toolbar .fixed-table-pagination').remove();
              var html = $('.fixed-table-pagination').html();
              $('.fixed-table-toolbar').append('<div class="fixed-table-pagination">' + html + '</div>');

              $('.fixed-table-toolbar .fixed-table-pagination').on('click', 'li.page-pre a', function (event) {
                var pageActive = $('.fixed-table-toolbar .fixed-table-pagination li.active a').text();
                event.preventDefault();

                var row = $('#table tfoot tr');
                row.find('input, select').each(function () {
                  var value = $(this).val();
                  if (value != null && value.trim() !== '') {
                    hasChangeData = true;
                    $('#table thead th div.sortable').css('pointer-events', 'none');
                  }
                });

                if(hasChangeData == true) {
                  if (!confirm('修正内容が破棄されますが、宜しいですか？')) {
                    event.preventDefault();
                    pageActive.addClass('active');
                    return false;
                  }
                }
                if (pageActive == 1) {
                  $('#table').bootstrapTable('selectPage', $('#table').bootstrapTable('getOptions').totalPages);
                } else {
                  $('#table').bootstrapTable('prevPage');
                }
              });

              $('.fixed-table-toolbar .fixed-table-pagination').on('click', 'li.page-next a', function (event) {
                var pageActive = $('.fixed-table-toolbar .fixed-table-pagination li.active a').text();
                event.preventDefault();
                var row = $('#table tfoot tr');
                row.find('input, select').each(function () {
                  var value = $(this).val();
                  if (value != null && value.trim() !== '') {
                    hasChangeData = true;
                    $('#table thead th div.sortable').css('pointer-events', 'none');
                  }
                });
                if(hasChangeData == true) {
                  if (!confirm('修正内容が破棄されますが、宜しいですか？')) {
                    event.preventDefault();
                    pageActive.addClass('active');
                    return false;
                  }
                }
                if ($('#table').bootstrapTable('getOptions').totalPages == pageActive) {
                  $('#table').bootstrapTable('selectPage', 1);
                } else {
                  $('#table').bootstrapTable('nextPage');
                }
              });

              $('.fixed-table-toolbar .fixed-table-pagination').on('click', 'li.page-item a', function (event) {
                event.preventDefault();
                var row = $('#table tfoot tr');
                row.find('input, select').each(function () {
                  var value = $(this).val();
                  if (value != null && value.trim() !== '') {
                    hasChangeData = true;
                    $('#table thead th div.sortable').css('pointer-events', 'none');
                  }
                });
                if(hasChangeData == true ) {
                  if(!$(this).parents('li').hasClass('page-next') && !$(this).parents('li').hasClass('page-pre')) {
                    if (!confirm('修正内容が破棄されますが、宜しいですか？')) {
                      event.preventDefault();
                      return false;
                    }
                  }
                }
                $('.fixed-table-toolbar .fixed-table-pagination li').removeClass('active');
                $(this).parents('li').addClass('active');
                $('#table').bootstrapTable('selectPage', parseInt($(this).text()));
              });
            }
            if(settings.isResize == true) {
              $('#table').colResizable({ disable: true });
              $('#table').colResizable({
                liveDrag: true,
                resizeMode:'overflow'
              });
            }

            $('#table input').on('focus', function() {
              clearTimeout(letTimeout);
            });

            $('#table').on('focus', 'input', function() {
              flagOutFocus = false;
            });

            $('#table').on('change', 'tfoot input', function() {
              $(this).addClass('hasChangeValue');
            });

            $('#table [name="btSelectItem"]').attr('tabindex', -1);
          },

          showColumns: settings.showColumns,
          onColumnSwitch: function (field, checked) {
            // updateSearchInputs();
            var visibleColumns = $('#table').bootstrapTable('getVisibleColumns');
            if (useAddFormFooter) {
              updateFooterInputs(visibleColumns);
            }
          },
          formatShowingRows: function (pageFrom, pageTo, totalRows, totalNotFiltered) {
            if (totalRows == 0) {
              return '0件';
            }
            if(settings.pageSize == null) {
              return totalRows + '件中'; 
            }
            return totalRows + '件中' + pageFrom + '～' + pageTo + '件を表示';
          },
          pagination: true,
          sidePagination: 'server',
          showPaginationSwitch: false,
          pageSize: settings.pageSize,
          pageList: [settings.pageSize],
          // stickyHeaderOffsetY: 100,
          // height: 700,
          pageNumber: settings.pageNumber,
          // Custom function to handle query parameters
          queryParams: function (params) {
            // Handle form search parameters
            if (settings.defaultSearchForm) {
              if (settings.formSearch != false) {
                settings.formSearch.find('select, input').each(function () {
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
                })
              }
            }
            // Handle search inputs
            var searchInputs = $('#table thead tr.search-row input');
            searchInputs.each(function (index, input) {
              var field = $(input).closest('th').attr('data-field');
              params[field] = $(input).val();
            });
            params['page'] = pageNumber;
            params['dataMultiSort'] = dataMultiSort;
            queryParamsData = params;
            return params;
          },
          rowStyle: function (row, index) {
            return hideRows(row) ? { classes: 'hidden' } : {};
          },

          // Custom function to handle page change
          onPageChange: function (number, size) {
            pageNumber = number;
            var searchParams = {};
            if (settings.defaultSearchForm) {
              if (settings.formSearch != false) {
                settings.formSearch.find('select, input').each(function () {
                  if ($(this).attr('name')) {
                    searchParams[$(this).attr('name')] = $(this).val();
                  }
                })
              }
            }

            var searchInputs = $('#table thead tr.search-row input');

            searchInputs.each(function (index, input) {
              var field = $(input).closest('th').attr('data-field');
              var value = $(input).val();

              searchParams[field] = value;

            });
            searchDatas['page'] = number;
            // updateUrlWithSearchParams(pageNumber, searchParams);
          }
        });
      }
    }
  }

  $.fn.customTable.isShowTable();

  $.fn.customTable.refreshCustom = function () {
    $.fn.customTable.isShowTable();
  }

  // Double click event handler for the table cells
  // $('#table').on('dbl-click-cell.bs.table', function (e, field, value, row, $element) {
  //   var columnConfig = $('#table').bootstrapTable('getOptions').columns[0].find(col => col.field === field);
  //   if (columnConfig && columnConfig.editable) {
  //     if ($element.parents('tr').find('input').length > 1) {
  //       var currentValue = $element.find('input').val();
  //       return false;
  //     } else {
  //       var currentValue = $element.text();
  //     }
  //     var input = $('<input type="text" class="" value="' + currentValue + '">');
  //     $element.html(input);
  //     var length = currentValue.length;
  //     input.focus();
  //     input[0].setSelectionRange(length, length);
  //     if (columnConfig.type == 'date') {
  //       input.datepicker({
  //         dateFormat: 'yy-mm-dd',
  //         autoclose: true
  //       }).on('change', function () {
  //         var newValue = input.val();
  //         updateDataRecord(field, value, row, $element, newValue)
  //           .then(function (status) {
  //             if (status === true) {
  //               row[field] = newValue;
  //               $element.text(newValue);
  //             }
  //           })
  //           .catch(function (error) {
  //             var errorSpan = $element.find('div.text-error.text-danger');
  //             if (errorSpan.length > 0) {
  //               errorSpan.text(error.message);
  //             } else {
  //               $element.append('<div class=" text-error text-danger">' + error.message + '</div>');
  //             }
  //             input.addClass('border border-danger');
  //           });
  //       })
  //     } else {

  //       input.blur(function () {
  //         var newValue = $(this).val();
  //         updateDataRecord(field, value, row, $element, newValue)
  //           .then(function (status) {
  //             if (status === true) {
  //               row[field] = newValue;
  //               $element.text(newValue);
  //             }
  //           })
  //           .catch(function (error) {
  //             var errorSpan = $element.find('div.text-error.text-danger');
  //             if (errorSpan.length > 0) {
  //               errorSpan.text(error.message);
  //             } else {
  //               $element.append('<div class=" text-error text-danger">' + error.message + '</div>');
  //             }
  //             input.addClass('border border-danger');
  //           });
  //       });
  //     }
  //   }
  // });

  function hideRows(row) {
    // Ẩn các dòng khi edit_flg = 1
    return row.edit_flg === 1;
  }
  /**
   * Update a data record on the server.
   *
   * @param {string} field - The field to be updated.
   * @param {any} value - The current value of the field.
   * @param {object} row - The entire data row associated with the record.
   * @param {object} $element - The jQuery element representing the cell in the table.
   * @param {any} newValue - The new value to be set for the field.
   * @returns {Promise} - A Promise that resolves with a boolean indicating the success of the update.
   */
  function updateDataRecord(field, value, row, $element, newValue) {
    // Get the URL for updating the data record from settings
    var url = settings.urlUpdateDataRecord;
    // Check if the URL is provided
    if (url != '') {
      // Return a Promise for asynchronous handling
      return new Promise(function (resolve, reject) {
        // Check if the new value is different from the current value
        if (value != newValue) {
          // Create a new row object with the updated field value
          var newRow = Object.assign({}, row);
          newRow[field] = newValue;

          // Make an AJAX request to update the record on the server
          $.ajax({
            url: url,
            type: 'POST',
            data: newRow,
            success: function (response) {
              if (response.status == 1) {
                // If the server update is successful, resolve the Promise with true
                resolve(true);
              } else {
                // If the server update fails, reject the Promise with false
                reject(false);
              }
            },
            error: function (xhr, status, error) {
              // If the server returns a validation error, reject with the validation error response
              if (xhr.status == 422) {
                var response = JSON.parse(xhr.responseText);
                reject(response);
              }
              // If there is a general error, reject the Promise with false
              reject(false);
            }
          });
        } else {
          // If the new value is the same as the current value, resolve the Promise with true
          resolve(true);
        }
      });
    }
    // If the URL is not provided, log visible columns (this might need additional explanation)
    var visibleColumns = $('#table').bootstrapTable('getVisibleColumns');
  }

  /**
   * Add a custom list button to the toolbar.
   */
  function addListButtonToolBar() {
    // Append the HTML content of the listButtonToolBar to the fixed-table-toolbar
    $('.fixed-table-toolbar').append(listButtonToolBar);
  }

  function updateSearchInputs() {
    if ($('#table thead tr.search-row').length < 1) {
      var visibleColumns = $('#table').bootstrapTable('getVisibleColumns');
      var html = '<tr class="search-row">';
      html = html + '<th></th>';
      visibleColumns.forEach(function (col) {
        var field = col.field;
        var valueSearch = '';
        if (searchDatas[field]) {
          valueSearch = searchDatas[field];
        }
        var columnConfig = $('#table').bootstrapTable('getOptions').columns[0].find(col => col.field === field);
        if (columnConfig && columnConfig.suggestion) {
          html = html + '<th data-field="' + field + '"><input type="text" class="form-control" placeholder="検索..." value="' + valueSearch + '"> <span class="icon-suggestion"><i class="fa fa-list"></i></span> </th>';
        } else {
          html = html + '<th data-field="' + field + '"><input type="text" class="form-control" placeholder="検索..." value="' + valueSearch + '"></th>';
        }
      })
      html += '<tr>';
      var header = $('#table').find('thead');
      header.append(html);
      $('#table thead tr.search-row input').on('blur', function () {
        // $(this).closest('th').find('.suggestion').hide();
        $(this).closest('th').find('.suggestion').remove();
      });
      $('#table thead tr.search-row input').on('keyup', function (event) {
        var currentInput = $(this);
        var currentTh = currentInput.closest('th');

        if (event.key === 'Enter') {
          var value = $(this).val();
          var field = $(this).closest('th').attr('data-field');
          var suggestionList = null;
          if (searchDatas[field] != value) {
            if (value == '' && !searchDatas.hasOwnProperty(field)) {
              return false;
            }
            searchDatas[field] = value;
            pageNumber = 1;
            $('#table').bootstrapTable('refresh');
            var searchInputs = $('#table thead tr.search-row input');
            var searchParams = {};
            searchInputs.each(function (index, input) {
              var field = $(input).closest('th').attr('data-field');
              var value = $(input).val();
              searchParams[field] = value;
            });
            updateUrlWithSearchParams(pageNumber, searchParams);
          }
        } else {
          var field = $(this).closest('th').attr('data-field');
          var value = $(this).val();
          var columnConfig = $('#table').bootstrapTable('getOptions').columns[0].find(col => col.field === field);
          if (columnConfig && columnConfig.suggestion) {
            var suggestionList = currentTh.find('.suggestion');
            suggestionList.remove();

            if (value != '') {
              $(this).closest('th').removeClass('none-important');
              searchSuggestion(field, value).then(function (res) {
                var html = '';
                if (res.data) {
                  res.data.forEach(function (item) {
                    if (field == 'order_department') {
                      html = html + '<li value="' + item[field] + '" data-order_department_cd="' + item.order_department_cd + '">' + item.order_department_cd + ' ' + item.order_department_search_key + ' ' + item.order_department + '</li>';
                    } else if (field == 'shipper') {
                      html = html + '<li value="' + item[field] + '" data-shipper_cd="' + item.shipper_cd + '">' + item.shipper_cd + ' ' + item.shipper_search_key + ' ' + item.shipper + '</li>';
                    } else {
                      html = html + '<li value="' + item[field] + '">' + item[field] + '</li>';
                    }
                  });
                }
                if (html != '') {
                  html = '<ul class="suggestion active">' + html + '</ul>';
                }
                currentTh.find('.suggestion').remove();
                currentInput.closest('th').append(html);
                suggestionList = currentTh.find('.suggestion');
                suggestionList.on('mousedown', 'li', function (e) {
                  e.stopPropagation();
                  var selectedField = $(this).parents('th').attr('data-field');
                  if (selectedField == 'order_department') {
                    $('.search-row th[data-field="order_department_cd"] input').val($(this).attr('data-order_department_cd'));
                    searchDatas['order_department_cd'] = $(this).attr('data-order_department_cd');
                  }
                  if (selectedField == 'shipper') {
                    $('.search-row th[data-field="shipper_cd"] input').val($(this).attr('data-shipper_cd'));
                    searchDatas['shipper_cd'] = $(this).attr('data-shipper_cd');
                  }
                  var selectedValue = $(this).attr('value');
                  currentInput.val(selectedValue);
                  searchDatas[field] = selectedValue;
                  pageNumber = 1;
                  $('#table').bootstrapTable('refresh');
                  var searchInputs = $('#table thead tr.search-row input');
                  var searchParams = {};
                  searchInputs.each(function (index, input) {
                    var field = $(input).closest('th').attr('data-field');
                    var value = $(input).val();
                    searchParams[field] = value;
                  });
                  updateUrlWithSearchParams(pageNumber, searchParams);
                  suggestionList.hide();
                });
              }).catch(function (error) {

              })
            } else {
              $(this).closest('th').addClass('none-important');
              suggestionList.remove();
            }
          }

        }
      });

      $('#table thead tr.search-row input').on('focus', function (event) {
        var currentInput = $(this);
        var currentTh = currentInput.closest('th');
        var field = currentTh.attr('data-field');
        var value = currentInput.val().toLowerCase();
        var suggestionList = currentTh.find('.suggestion');

        if (value !== '') {
          var filteredItems = suggestionList.find('li').filter(function () {
            return $(this).text().toLowerCase().includes(value);
          });
          suggestionList.find('li').hide();
          filteredItems.show();
        } else {
          suggestionList.find('li').show();
        }
        suggestionList.show();
      });
    }
  }



  function updateUrlWithSearchParams(pageNumber, searchParams) {
    var currentUrl = window.location.href;
    var urlParts = currentUrl.split('?');
    var baseUrl = urlParts[0];
    var queryParams = urlParts.length > 1 ? urlParts[1] : '';
    var currentParams = {};

    queryParams.split('&').forEach(function (param) {
      var paramParts = param.split('=');
      currentParams[paramParts[0]] = paramParts[1];
    });
    currentParams['page'] = pageNumber;

    for (var key in searchParams) {
      if (searchParams[key] !== undefined && searchParams[key] !== '') {
        currentParams[key] = searchParams[key];
      } else {
        if (currentParams.hasOwnProperty(key)) {
          delete currentParams[key];
        }
      }
    }

    var newUrl = baseUrl + '?' + Object.keys(currentParams)
      .filter(key => key !== '' && currentParams[key] !== undefined && currentParams[key] !== '')
      .map(key => key + '=' + currentParams[key])
      .join('&');
    history.pushState(null, null, newUrl);
  }

  $.fn.customTable.searchList = function (e) {
    settings.defaultSearchForm = true;
    settings.isShow = true;
    if ($('#table').parents('.bootstrap-table').length < 1) {
      $.fn.customTable.refreshCustom();
    }
    pageNumber = 1;
    $('#table').bootstrapTable('refresh', {
      pageNumber: 1
    });
  };

  $.fn.customTable.callbackAfterShow = function(callback) {
    isCallBack = true;
    callbackF = callback;
  };

  $.fn.customTable.destroy = function() {
    isCallBack = false;
    pageNumber = 1;
    copyButonLeftAdded = false;
    deleteButtonAdded = false;
    exportButtonAdded = false;
    isAppendListButtonToolBar = false;
    $('#table').bootstrapTable('destroy');
  };

  // Get a parameter list of dataTables
  $.fn.customTable.getQueryParams = function () {
    return queryParamsData;
  };

  function addUpateDataTable() {
    var url = settings.urlUpdateDataTable;
    if (url != '') {
      var updateButton = $('<div class="columns columns-right btn-group float-right"><button class="btn btn-update min-wid-110" id="addRow">更新</button></div>');
      updateButton.click(function () {
        var errorSpans = $('#table tbody tr .error span').filter(function () {
          return $(this).text().trim() !== '';
        });

        if (errorSpans.length > 0) {
          errorSpans.eq(0).closest('.div-row').find('input').focus();
          return false;
        }
        var currentData = $('#table').bootstrapTable('getData');
        if (settings.dataDelete.length > 0) {
          currentData = currentData.concat(settings.dataDelete);
        }
        var row = $('#table tfoot tr');
        var objectNew = {};
        row.find('input, select').each(function () {
          var key = $(this).attr('name');
          var value = $(this).val();
          if (value != null && value.trim() !== '') {
            objectNew[key] = value;
          }
        });
        if (Object.keys(objectNew).length > 0) {
          validateRows(objectNew).then(function (res) {
            currentData.push(objectNew);
            ajaxUpdateDataTable(currentData)
              .then(function (res) {
                if (res.status == 200) {
                  Swal.fire({
                    'title': res.message,
                    'icon': 'success',
                  })
                  $('#table').bootstrapTable('refresh');
                  settings.dataDelete = [];
                } else {
                  Swal.fire({
                    'title': settings.errorException,
                    'icon': 'error',
                    customClass: {
                      popup: 'custom-modal-size'
                    }
                  })
                }
              })
              .catch(function (err) {
                Swal.fire({
                  'title': settings.errorException,
                  'icon': 'error',
                  customClass: {
                    popup: 'custom-modal-size'
                  }
                })
              });
          }).catch(function (xhr) {
            row.find('.error span').html('');
            if (xhr.status == 422) {
              var response = JSON.parse(xhr.responseText);
              var errors = response.errors;
              $.each(errors, function (key, value) {
                row.find('.error-' + key).parents('.group-input').addClass('error');
                row.find('.error-' + key + ' span').html(value);
              });
            } else {
              Swal.fire({
                'title': settings.errorException,
                'icon': 'error',
                customClass: {
                  popup: 'custom-modal-size'
                }
              })
            }
            return false;
          });

        } else {
          if (currentData.length <= 0) {
            return false;
          }
          ajaxUpdateDataTable(currentData)
            .then(function (res) {
              if (res.status == 200) {
                Swal.fire({
                  'title': res.message,
                  'icon': 'success',
                })
                $('#table').bootstrapTable('refresh');
              } else {
                Swal.fire({
                  'title': settings.errorException,
                  'icon': 'error',
                  customClass: {
                    popup: 'custom-modal-size'
                  }
                })
              }
            })
            .catch(function (err) {
              Swal.fire({
                'title': settings.errorException,
                'icon': 'error',
                customClass: {
                  popup: 'custom-modal-size'
                }
              })
            });
        }
      });
      $('.fixed-table-toolbar').append(updateButton);
    }
  }

  function ajaxUpdateDataTable(currentData) {
    var url = settings.urlUpdateDataTable;
    return new Promise(function (resolve, reject) {
      // Make an AJAX request to update the record on the server
      $.ajax({
        url: url,
        type: 'POST',
        data: { list: currentData },
        success: function (response) {
          resolve(response);
        },
        error: function (xhr, status, error) {
          // If the server returns a validation error, reject with the validation error response
          if (xhr.status == 422) {
            // var response = JSON.parse(xhr.responseText);
            reject(xhr);
          }
          // If there is a general error, reject the Promise with false
          reject(false);
        }
      });
    });
  }

  /**
 * Add a copy button to the toolbar.
 */
  function addCopyButton() {
    // Get the URL for copying records from settings
    var url = settings.urlCopyRecord;
    // Check if the URL is provided
    if (url != '') {
      // Create a copyButton with an icon and a click event handler
      var copyButton = $('<div class="columns columns-right btn-group float-right"><button class="btn btn-secondary"><i class="fa fa-copy"></i></button></div>');
      copyButton.click(function () {
        // Get the selected rows from the table
        var selectedRows = $('#table').bootstrapTable('getSelections');

        // Check if there are selected rows
        if (selectedRows.length > 0) {
          $.ajax({
            url: url,
            data: { list: selectedRows },
            type: 'POST',
            success: function (res) {
              // If the server response is successful, append the copied row to the table
              if (res.status == 1) {
                if (res.data) {
                  var clonedRow = Object.assign({}, res.data);
                  clonedRow.checkbox = false;
                  $('#table').bootstrapTable('append', clonedRow);

                }
              }
            },
            error: function (xhr, status, error) {
              // Handle errors if needed
            }
          })
        } else {
          // Alert the user to select rows for copying
          alert('コピーするために行を選択してください。');
        }
      });
      // Append the copyButton to the fixed-table-toolbar
      $('.fixed-table-toolbar').append(copyButton);
    }
  }
  function updateFooterInputs(columns) {
    if (settings.insertLastRow == true) {
      var footerInputs = columns.map(function (column, index) {
        var key = 'column_' + index;
        if(column.formatterFooter) {
          if(typeof formatterFooter === 'function') {
            return formatterFooter(column, index);
          } else {
            return '<td></td>';
          }
        }
        if (column.suggestion) {
          return '<td><div class="div-row" data-field="' + column.field + '"><input onfocus="setCellFocusStatus($(this), true)" type="text" onBlur="outFocusSuggestion(this), setCellFocusStatus($(this), false), suggestionBlur(this)" onKeyup="suggestionKeyup(this)" class="form-control" name="' + column.field + '" value="" placeholder="' + column.title + '" autocomplete="off"><div class="error error-' + column.field + '"><span class="text-danger"></span></div></div></td>';
        }
        if (column.type == 'date') {
          return '<td><div class="div-row" data-field="' + column.field + '"><input onblur="setCellFocusStatus($(this), false)" onfocus="setCellFocusStatus($(this), true)" type="text" class=" form-control" name="' + column.field + '" data-key="' + key + '" placeholder="' + column.title + '" autocomplete="off" onchange="autoFillDate(this)"><div class="error error-' + column.field + '"><span class="text-danger"></span></div></div></td>'
        }
        if (column.type == 'select') {
          var field = column.field;
          var select = '<div class="div-row" data-field="' + field + '"><select name="' + field + '" class="form-control">';
          if (column.selections && typeof column.selections === 'object') {
            // Duyệt qua mảng selection và thêm từng tùy chọn vào select box
            if (column.selections[''] != undefined) {
              select += '<option value="">' + column.selections[''] + '</option>';
            }

            for (var key in column.selections) {
              if (column.selections.hasOwnProperty(key) && key !== '') {
                select += '<option value="' + key + '" >' + column.selections[key] + '</option>';
              }
            }
          }
          select += '</select>';
          return '<td>' + select + '<div class="error error-' + column.field + '"><span class="text-danger"></span></div></td>';
        }
        if (column.type == 'number') {
          var field = column.field;
          var attrMaxLengh = '';
          var inputValue = '';
          return '<td><div class="div-row" data-field="' + field + '"><input  onkeypress="onlyNumber(event)" type="text" '+attrMaxLengh+' class="form-control text-right" name="' + field + '" value="' + inputValue + '" onblur="setCellFocusStatus($(this), false), formatNumberOnBlur(this)" onfocus="setCellFocusStatus($(this), true), removeFormatOnFocus(this)"><div class="error error-' + field + '"><span class="text-danger"></span></div></div></td>';
        }

        if(column.type == 'numberDecimal') {
          var field = column.field;
          var attrMaxLengh = '';
          var inputValue = '';
          var maxdecimal = '3';
          return '<td><div class="div-row" data-field="' + field + '"><input data-maxdecimal="'+maxdecimal+'" onkeypress="onlyNumberDecimal(event)" type="text" '+attrMaxLengh+' class="form-control text-right" name="' + field + '" value="' + inputValue + '" onblur="setCellFocusStatus($(this), false), formatNumberOnBlur(this)" onfocus="setCellFocusStatus($(this), true), removeFormatOnFocus(this)"><div class="error error-' + field + '"><span class="text-danger"></span></div></div></td>';
        }
        if(column.field == 1 || column.nocreate ) {
          return '<td></td>';
        }
        return '<td><input type="text" class="form-control" onblur="setCellFocusStatus($(this), false)" onfocus="setCellFocusStatus($(this), true)" name="' + column.field + '" data-key="' + key + '" placeholder="' + column.title + '"><div class="error error-' + column.field + '"><span class="text-danger"></span></div></td>';
      }).join('');



      var footerContent = '<tr><td class="checkbox-footer"><button tabindex="-1"><i class="fa fa-plus"></i></button></td>' + footerInputs + '</tr>';

      if ($('#table tfoot').length >= 1) {
        $('#table tfoot').html(footerContent);
      } else {
        $('#table').append('<tfoot>' + footerContent + '</tfoot>');
      }

      $('.datepickerGrid').datepicker({
        dateFormat: 'yy/mm/dd',
        autoclose: true
      });
      $('#table').off('click', 'tfoot button', onclickAdd);
      $('#table').on('click', 'tfoot button', onclickAdd);
      // $('#table').on('click', 'tfoot button', function () {
      //   var values = {};
      //   var row = $(this).closest('tr');
      //   var hasValues = false;
      //   row.find('input, select').each(function () {
      //     var key = $(this).attr('name');
      //     var value = $(this).val();
      //     values[key] = value;
      //     if (value.trim() !== '') {
      //       hasValues = true;
      //     }
      //   });
      //   if (!hasValues) {
      //     alert('すべてのフィールドは空白にできません');
      //     return;
      //   }
      //   var newRow = Object.assign({});
      //   row.find('select, input').each(function() {
      //     newRow[$(this).attr('name')] = $(this).val();
      //   })

      //   validateRows(newRow)
      //   .then(function(res) {
      //     var currentData =  $('#table').bootstrapTable('getData');
      //     $('#table').bootstrapTable('append', newRow);
      //     row.find('.error span').html('');
      //     row.find('input, select').val('');
      //   })
      //   .catch(function(xhr){
      //     row.find('.error span').html('');
      //     if(xhr.status == 422) {
      //       var response = JSON.parse(xhr.responseText);
      //       var errors = response.errors;
      //       $.each(errors, function(key, value) {
      //         row.find('.error-'+key).parents('.group-input').addClass('error');
      //         row.find('.error-'+key + ' span').html(value);
      //       });
      //     } else {
      //       Swal.fire({
      //         'title': settings.errorException,
      //         'icon': 'error',
      //         customClass: {
      //           popup: 'custom-modal-size'
      //         }
      //       })
      //     }
      //   });
      // });

      // $('#table tfoot tr input').on('blur', function () {
      //   $(this).closest('td').find('.suggestion').hide();
      // });


      // $('#table tfoot tr input').on('keyup', function (event) {
      //   var currentInput = $(this);
      //   var currentTh = currentInput.closest('td');
      //   var field = $(this).attr('name');
      //   var value = $(this).val();
      //   var columnConfig = $('#table').bootstrapTable('getOptions').columns[0].find(col => col.field === field);
      //   if (columnConfig && columnConfig.suggestion) {
      //     var suggestionList = currentTh.find('.suggestion');
      //     suggestionList.remove();

      //     if (value != '') {
      //       $(this).closest('td').removeClass('none-important');
      //       searchSuggestion(field, value).then(function (res) {
      //         var html = '';
      //         if (res.data) {
      //           res.data.forEach(function (item) {
      //             if (field == 'order_department' || field == 'order_department_cd') {
      //               html = html + '<li value="' + item[field] + '" data-order_department_cd="' + item.order_department_cd + '"data-order_department="' + item.order_department + '">' + item.order_department_cd + ' ' + item.order_department_search_key + ' ' + item.order_department + '</li>';
      //             } else if (field == 'shipper' || field == 'shipper_cd') {
      //               html = html + '<li value="' + item[field] + '" data-shipper_cd="' + item.shipper_cd + '" data-shipper="' + item.shipper + '">' + item.shipper_cd + ' ' + item.shipper_search_key + ' ' + item.shipper + '</li>';
      //             } else {
      //               html = html + '<li value="' + item[field] + '">' + item[field] + '</li>';
      //             }
      //           });
      //         }
      //         if (html != '') {
      //           html = '<ul class="suggestion active">' + html + '</ul>';
      //         }
      //         currentTh.find('.suggestion').remove();
      //         currentInput.closest('td').append(html);
      //         suggestionList = currentTh.find('.suggestion');
      //         suggestionList.on('mousedown', 'li', function (e) {
      //           e.stopPropagation();
      //           var selectedField = field;
      //           if (selectedField == 'order_department' || selectedField == 'order_department_cd') {
      //             currentTh.parents('tr').find('input[name="order_department"]').val($(this).attr('data-order_department'))
      //             currentTh.parents('tr').find('input[name="order_department_cd"]').val($(this).attr('data-order_department_cd'))
      //           }
      //           if (selectedField == 'shipper_cd' || selectedField == 'shipper') {
      //             currentTh.parents('tr').find('input[name="shipper"]').val($(this).attr('data-shipper'))
      //             currentTh.parents('tr').find('input[name="shipper_cd"]').val($(this).attr('data-shipper_cd'))
      //           }
      //           var selectedValue = $(this).attr('value');
      //           currentInput.val(selectedValue);
      //           suggestionList.hide();
      //         });
      //       }).catch(function (error) {

      //       })
      //     } else {
      //       $(this).closest('td').addClass('none-important');
      //       suggestionList.remove();
      //     }
      //   }
      // });

      // $('#table tfoot tr input').on('focus', function (event) {
      //   var currentInput = $(this);
      //   var currentTh = currentInput.closest('td');
      //   var field = $(this).attr('name');
      //   var value = currentInput.val().toLowerCase();
      //   var suggestionList = currentTh.find('.suggestion');

      //   if (value !== '') {
      //     var filteredItems = suggestionList.find('li').filter(function () {
      //       return $(this).text().toLowerCase().includes(value);
      //     });
      //     suggestionList.find('li').hide();
      //     filteredItems.show();
      //   } else {
      //     suggestionList.find('li').show();
      //   }
      //   suggestionList.show();
      // });
    }
  }

  function addExportExcelDataTable() {
    // Check if the URL for exporting Excel data table is provided
    if (settings.urlExportExcelDataTable != '' && settings.isShowBtnExcel) {
      // Create an Excel export button
      var excelButton = $('<div class="columns columns-right btn-group float-right"><button class="btn btn-success">' + settings.textButtonExportExcel + '</button></div>');

      // Attach a click event to the Excel export button
      excelButton.click(function () {
        // Get query parameters from the Bootstrap Table
        var params = $('#table').customTable.getQueryParams();

        // Create a form for submitting the parameters to the export URL
        var form = $('<form>', {
          'action': settings.urlExportExcelDataTable,
          'method': 'POST',
          'target': '_blank' // Open the export URL in a new tab/window
        });

        // Append hidden input fields for each parameter
        $.each(params, function (key, value) {
          form.append($('<input>', {
            'type': 'hidden',
            'name': key,
            'value': value
          }));
        });
        form.append($('<input>', {
          'type': 'hidden',
          'name': '_token',
          'value': $('meta[name="csrf-token"]').attr('content')
        }));
        // Append the form to the body, submit it, and remove it
        form.appendTo('body').submit().remove();
      });

      // Append the Excel export button to the Bootstrap Table toolbar
      $('.fixed-table-toolbar').append(excelButton);
    }
  }



  // Add button copy left to the toolbar
  function addCopyButtonLeft() {

    if (typeof settings.addCopyButtonLeft === 'function') {
      settings.addCopyButtonLeft();
      return;
    }
    var htmlActive = '';
    if(settings.isCopyActiveRow) {
      htmlActive = '<button type="button" id="copyActiveRow" class="btn btn-primary">カーソル位置の行コピー</button>';
    }

    var html = `<div class="columns columns-left btn-group float-left">
      <div class="group-copy">
        <input type="text" min="" oninput="validateNumberInput(this)" id="totalRowsCopy"> 
        <span>行</span>
        <button class="btn btn-primary min-wid-110" id="buttonCopyLeft">行コピー</button>
        ${htmlActive}
        <button class="btn btn-secondary min-wid-110" id="settingButtonCopyLeft">複写列選択</button>
      </div>
     </div>`;
    $('.fixed-table-toolbar').append(html);
    $('#buttonCopyLeft').click(function () {
      var totalRowsCopy = 0;
      var selectedRows = $('#table').bootstrapTable('getSelections');
      if (selectedRows.length == 0) {
        Swal.fire({
          title: settings.errorNoChoiceOneRecord,
          icon: 'error'
        });
        return;
      }

      if (selectedRows.length > 1) {
        totalRowsCopy = 1;
      } else {
        totalRowsCopy = $('#totalRowsCopy').val();
        if (totalRowsCopy == '') {
          totalRowsCopy = 1;
        }
      }
      var copyableFields = settings.initCopy;
      for (var i = 0; i < selectedRows.length; i++) {
        for (var j = 0; j < totalRowsCopy; j++) {
          var copiedRow = Object.assign({}, selectedRows[i]);
          for (var key in copiedRow) {
            if ((Array.isArray(copyableFields) && copyableFields.length > 0 &&  !copyableFields.includes(key)) || copyableFields == 1 ) {
              copiedRow[key] = null;
            }
          }
          $('#totalRowsCopy').val('');
          $('#table').bootstrapTable('append', copiedRow);
          $('#table').bootstrapTable('uncheckAll');
          if(settings.focusAfterName) {
            $('#table tbody tr:last-of-type [name="'+settings.focusAfterName+'"]').focus();
            setTimeout(function() { 
              $('#table tbody input[name="btSelectItem"]').attr('tabindex', -1);
            }, 100)
          }
        }
      }
      // Xử lý logic copy dựa trên totalRowsCopy và danh sách trường
      // for (var i = 0; i < totalRowsCopy; i++) {
      //   var copiedRow = Object.assign{};

      //   // Copy giá trị từ các trường cho phép copy, còn lại set = null hoặc ''
      //   for (var j = 0; j < copyableFields.length; j++) {
      //     var field = copyableFields[j];
      //     copiedRow[field] = selectedRows[0][field];
      //   }

      //   // Thực hiện các thao tác cần thiết với dòng đã copy (copiedRow)
      //   console.log('Copying row:', copiedRow);
      // }

    });
    $('#settingButtonCopyLeft').click(function () {
      $('#columnSelectionModal').modal('show');
    })

    $('#copyActiveRow').on('mousedown', function() {
      var index =  $('#table tbody .cell-focus').parents('tr').index();
      if(index != -1) {
        var currentData = $('#table').bootstrapTable('getData')[index];
        if(currentData) {
          var copyableFields = settings.initCopy;
          var totalRowsCopy = 1;
          totalRowsCopy = $('#totalRowsCopy').val();
          if (totalRowsCopy == '') {
            totalRowsCopy = 1;
          }
          for (var j = 0; j < totalRowsCopy; j++) {
            var copiedRow = Object.assign({}, currentData);
            for (var key in copiedRow) {
              if ((Array.isArray(copyableFields) && copyableFields.length > 0 &&  !copyableFields.includes(key)) || copyableFields == 1 ) {
                copiedRow[key] = null;
              }
            }
            $('#totalRowsCopy').val('');
            $('#table').bootstrapTable('append', copiedRow);
            // $('#table').bootstrapTable('uncheckAll');
            if(settings.focusAfterName) {
              setTimeout(function() { 
                $('#table tbody tr:last-of-type [name="'+settings.focusAfterName+'"]').focus();
                $('#table tbody input[name="btSelectItem"]').attr('tabindex', -1);
              }, 100)
            }
          }
          
        }
      }
    });
  }

  function addDeleteDataTable() {
    var html = `
      <div class="columns columns-left btn-group float-left">
        <button class="btn-delete btn min-wid-110" id="gridDelete">行削除</button>
      </div>
    `;

    $('.fixed-table-toolbar').append(html);
    $('#gridDelete').click(function () {
      var selected = $('#table').bootstrapTable('getSelections');
      if (selected.length <= 0) {
        Swal.fire({
          title: settings.errorNoChoiceOneRecord,
          icon: 'error'
        });
        return false;
      }
      deleteData(this);
    });
  }

  function addCopyButtonActiveRow() {
    var html = `
      <div class="columns columns-left btn-group float-left">
        <button class="btn-primary btn">カーソル位置の行コピー</button>
      </div>
    `;

    $('.fixed-table-toolbar').append(html);
  }

  function handleSelectionChange() {
    var selected = $('#table').bootstrapTable('getSelections');
    if (selected.length > 1) {
      $('#totalRowsCopy').attr('readonly', 'readonly');
      $('#totalRowsCopy').val(1);
    } else {
      $('#totalRowsCopy').removeAttr('readonly');
    }
  }
  return this;
};

function inputFormatter(value, row, index, field) {
  var columnConfig = $('#table').bootstrapTable('getOptions').columns[0].find(col => col.field === field);
  var attrMaxLengh = '';
  var onchange = '';
  if (columnConfig.onchange) {
    onchange = ' onchange="' + columnConfig.onchange + '(this)"';
  }

  if(columnConfig.maxlength && (columnConfig.type == 'number' || columnConfig.type == 'numberDecimal') ) {
    attrMaxLengh = 'data-length = "'+columnConfig.maxlength+'"';
  } else {
    attrMaxLengh = 'maxlength = "'+columnConfig.maxlength+'"';
  }
  if (columnConfig.suggestion) {
    var inputValue = value || '';
    var input = '<div class="div-row" data-field="' + field + '"><input type="text" ' + onchange + ' onBlur="outFocusSuggestion(this), suggestionBlur(this)" onKeyup="suggestionKeyup(this)" onfocus="setCellFocusStatus($(this), true)" class="form-control" name="' + field + '" value="' + inputValue + '" autocomplete="off"><div class="error error-' + field + '"><span class="text-danger"></span></div></div>';
    return input;
  }


  if (columnConfig.type == 'date') {
    var inputValue = value || '';
    inputValue = inputValue.replace(/-/g, '/');
    row[field] = inputValue;
    var input = '<div class="div-row" data-field="' + field + '"><input style="text-align: center" type="text" name="' + field + '" class="form-control" value="' + inputValue + '" autocomplete="off" onchange="autoFillDate(this)" onblur="setCellFocusStatus($(this), false)" onfocus="setCellFocusStatus($(this), true)"><div class="error error-' + field + '"><span class="text-danger"></span></div></div>';
    return input;
  }

  if (columnConfig.type == 'select') {
    var inputValue = value || '';
    var select = '<div class="div-row" data-field="' + field + '"><select name="' + field + '" class="form-control" onblur="setCellFocusStatus($(this), false)" onfocus="setCellFocusStatus($(this), true)">';
    if (columnConfig.selections && typeof columnConfig.selections === 'object') {

      if (columnConfig.selections[''] != undefined) {
        select += '<option value="">' + columnConfig.selections[''] + '</option>';
      }

      for (var key in columnConfig.selections) {
        if (columnConfig.selections.hasOwnProperty(key) && key !== '') {
          var isSelected = (inputValue == key) ? 'selected' : '';
          select += '<option value="' + key + '" ' + isSelected + '>' + columnConfig.selections[key] + '</option>';
        }
      }
    }
    select = select + '</select><div class="error error-' + field + '"><span class="text-danger"></span></div><div>';
    return select;
  }

  if (columnConfig.type == 'number') {
    var inputValue = value || '';
    inputValue = numberFormat(inputValue, -1);
    return '<div class="div-row" data-field="' + field + '"><input  onkeypress="onlyNumber(event)" type="text" '+attrMaxLengh+' class="form-control text-right" name="' + field + '" value="' + inputValue + '" onblur="setCellFocusStatus($(this), false), formatNumberOnBlur(this)" onfocus="setCellFocusStatus($(this), true), removeFormatOnFocus(this)"><div class="error error-' + field + '"><span class="text-danger"></span></div></div>';
  }

  if(columnConfig.type == 'numberDecimal') {
    var inputValue = value || '';
    var maxdecimal = columnConfig.maxdecimal || -1;
    inputValue = numberFormat(inputValue, maxdecimal);
    return '<div class="div-row" data-field="' + field + '"><input data-maxdecimal="'+maxdecimal+'" onkeypress="onlyNumberDecimal(event)" type="text" '+attrMaxLengh+' class="form-control text-right" name="' + field + '" value="' + inputValue + '" onblur="setCellFocusStatus($(this), false), formatNumberOnBlur(this)" onfocus="setCellFocusStatus($(this), true), removeFormatOnFocus(this)"><div class="error error-' + field + '"><span class="text-danger"></span></div></div>';
  }

  var inputValue = value || '';
  return '<div class="div-row" data-field="' + field + '"><input type="text" class="form-control" name="' + field + '" value="' + inputValue + '" '+attrMaxLengh+' onblur="setCellFocusStatus($(this), false)" onfocus="setCellFocusStatus($(this), true)"><div class="error error-' + field + '"><span class="text-danger"></span></div></div>';
}

function searchSuggestion(field, value, urlReplace, otherFieldElements) {
  var settings = $('#table').data('customTableSettings');
  var url = ''; 

  if (settings !== undefined && settings.hasOwnProperty("urlSearchSuggestion")) {
      url = settings.urlSearchSuggestion;
  }
  if(urlReplace) {
    url = urlReplace;
  }
  if (url.length == 0 && localStorage.hasOwnProperty("urlSearchSuggestion") && localStorage.urlSearchSuggestion.length > 0) {
      url = localStorage.urlSearchSuggestion;
  }
  var otherWhere = {};
  if (otherFieldElements && Array.isArray(otherFieldElements)) {
    for(let i = 0; i < otherFieldElements.length; i++) {
      otherWhere[$(otherFieldElements[i]).attr('name')] = $(otherFieldElements[i]).val();
    } 
  }    
  
  if (url != '') {
    return new Promise(function (resolve, reject) {
      $.ajax({
        url: url,
        type: 'POST',
        data: {
          field: field,
          value: value,
          otherWhere: otherWhere
        },
        success: function (response) {
          resolve(response);
        },
        error: function (xhr, status, error) {
          reject(error);
        }
      });
    });
  } else {
    return new Promise(function (resolve, reject) {
      reject('NOT URL');
    });
  }
}
var suggestionSelected = false;
var keyDownCode = 999999;
var updatedRowIndex = -1;
var notBlur = false;
function suggestionBlur(e) {
  if(notBlur) {
    return false;
  }
  setCellFocusStatus($(e), false);
  // suggestion: support select item by key-up & key-down
  if (!needCloseSuggestion(e)) {
    return;
  }

  var currentObj = e;
  setTimeout(function (e) {
    // $(currentObj).closest('td').find('.suggestion').hide();
    $(currentObj).closest('td').find('.suggestion').remove();
    suggestionSelectedFocus(currentObj);
  }.bind(this, currentObj), 100);
}

function suggestionSelectedFocus(e) {
  // after selected by click item from suggestion. current input focus.
  // if selected item by enter key. don't need focus again.
  if (suggestionSelected) {
    var inputName = $(e).attr("name");
    if (updatedRowIndex >= 0) {
      let endPos = $(e).val().length;
      if ($($("#table>tbody>tr")[updatedRowIndex]).find("input[name='" + inputName + "']").length > 0) {
        var inputObj = $($("#table>tbody>tr")[updatedRowIndex]).find("input[name='" + inputName + "']");
        inputObj[0].setSelectionRange(endPos, endPos);
        // inputObj.focus();
        inputObj.change(function () {
          let currentTh = $(this).closest('td').find(".suggestion");
          // if select data form suggestion > dont need validate on change event.
          if (keyDownCode == 13 && currentTh.find("li.key-focusing").length > 0) {
            return;
          }
          checkValidRow($(this));
        });
        checkValidRow($($("#table>tbody>tr")[updatedRowIndex]).find("input[name='" + inputName + "']"));
      }
      updatedRowIndex = -1;
    }
    suggestionSelected = false;
  }
}

function needCloseSuggestion(e) {
  var keyCode = e.keyCode;
  if (keyCode != 38 && keyCode != 40) {
    return true;
  }
  if ($(".suggestion.active") === undefined) {
    return true;
  } else if ($(".suggestion.active").length == 0) {
    return true;
  } else if ($(".suggestion.active").css('display') == 'none') {
    return true;
  }
  return false;
}

$(document).keydown(function (e) {
  keyDownCode = e.keyCode;
});

// function focusItemSuggestion(e, keyCode) {
//   var currentInput = $(e);
//   var currentTh = currentInput.closest('td');
//   var suggestionList = currentTh.find('.suggestion');

//   // push arrow down.
//   if (keyCode == 40) {
//     if (suggestionList.find("li.key-focusing").length == 0) {
//       suggestionList.find("li:first-child").addClass("key-focusing");
//     } else {
//       var liIdx = suggestionList.find("li.key-focusing").index();
//       if (liIdx >= 0 && liIdx < suggestionList.find("li").length - 1) {
//         suggestionList.find("li.key-focusing").removeClass("key-focusing");
//         $(suggestionList.find("li")[liIdx + 1]).addClass("key-focusing");
//         suggestionList.scrollTop(liIdx * 36);
//       }
//     }
//   }

//   // push arrow up.
//   if (keyCode == 38) {
//     var liIdx = suggestionList.find("li.key-focusing").index();
//     if (liIdx > 0) {
//       suggestionList.find("li.key-focusing").removeClass("key-focusing");
//       $(suggestionList.find("li")[liIdx - 1]).addClass("key-focusing");
//       suggestionList.scrollTop(liIdx * 36 - 36);
//     }
//   }
// }

// function enterItemSuggestion(e) {
//   var currentInput = $(e);
//   var currentTh = currentInput.closest('td');
//   var suggestionList = currentTh.find('.suggestion');
//   suggestionList.find("li.key-focusing").mousedown();
// }

var letTimeout;
function suggestionKeyup(e) {
  if (flagOutFocus == true) {
    return;
  }
  var settings = $('#table').data('customTableSettings');
  var currentInput = $(e);
  var currentTh = currentInput.closest('td');
  var field = $(e).attr('name');
  var value = $(e).val();
  var columnConfig = $('#table').bootstrapTable('getOptions').columns[0].find(col => col.field === field);

  if (keyDownCode == 38 || keyDownCode == 40) {
    focusItemSuggestion(e, keyDownCode, currentTh);
    return;
  } else if (keyDownCode == 13) {
    enterItemSuggestion(e, currentTh);
    return;
  } else if(keyDownCode == 9) {
    return;
  }

  if (columnConfig && columnConfig.suggestion) {

    if($(e).parents('td').find('.suggestion-sticky-bot input[type="checkbox"]').length > 0) {
      clearTimeout(interValSuggestionCheckbox);
      $(e).parents('td').find('.suggestion-sticky-bot').find('input[type="checkbox"]:nth-child(1)').trigger('change');
      return false;
    }

    var suggestionList = currentTh.find('.suggestion');
    // suggestionList.remove();
    $('#table .suggestion').remove();
    var suggestionHide = columnConfig.suggestion_hide;
    if (value != '') {

      $(this).closest('td').removeClass('none-important');
      clearTimeout(letTimeout);
      letTimeout = setTimeout(function () {
        if(columnConfig.url_suggestion) {
          var urlSearch = columnConfig.url_suggestion;
        } else {
          var urlSearch = '';
        }
        if(columnConfig.otherFieldElements) {
          var otherFieldElements = columnConfig.otherFieldElements;
        } else {
          var otherFieldElements = [];
        }
        searchSuggestion(field, value, urlSearch, otherFieldElements).then(function (res) {
          var html = '';
          if (res.data) {
            res.data.forEach(function (item) {
              if (columnConfig && Array.isArray(columnConfig.suggestion_change)) {
                html = html + '<li value="' + item[field] + '"';
                for (let i = 0; i < columnConfig.suggestion_change.length; i++) {
                  var dataLi = item[columnConfig.suggestion_change[i]] ?? '';
                  html = html + ' data-' + columnConfig.suggestion_change[i] + '=' + '"' + dataLi + '"';
                }
                html = html + '>';
                for (let i = 0; i < columnConfig.suggestion_change.length; i++) {
                  if (suggestionHide) {
                    if (!suggestionHide.includes(columnConfig.suggestion_change[i])) {
                      var textLi = item[columnConfig.suggestion_change[i]] ?? '--';
                      html = html + textLi + '　';
                    }
                  } else {
                    var textLi = item[columnConfig.suggestion_change[i]] ?? '--';
                    html = html + textLi + '　';
                  }
                }
                html = html + '</li>';

              }

            });
            var totalCheckbox = 0;
            var bottom = 0;
            var htmlC = '';
            if(columnConfig.suggestion_checkbox) {
              Object.keys(columnConfig.suggestion_checkbox).forEach(function(key) {
                totalCheckbox ++;
                if(columnConfig.suggestion_checkbox[key]) {
                  htmlC = htmlC + '<div class="form-check form-check-flat form-check-primary" onmousedown="notBlurF(this)"><label class="form-check-label text-nowrap" style="padding: 0 20px;"><input onchange="changeSuggestionCheckbox(this)"  type="checkbox" value="'+key+'" class="form-check-input">'+columnConfig.suggestion_checkbox[key]+'<i class="input-helper"></i></label></div>';
                }
              });
            }
            if(totalCheckbox) {
              bottom = totalCheckbox * 35;
            }

            if (columnConfig.link) {
              let url = new URL(columnConfig.link);
              let searchParams = new URLSearchParams(url.search);
              searchParams.append('create_by_iframe', '1');
              url.search = searchParams.toString();
              html = html + '<li style="border-top: 1px solid #CED4DA; position:sticky; bottom: 0px; background:#FFF" class="suggestion-sticky-bot"><a data-href="' + url.href + '" style="text-decoration: underline;" onclick="modalInsertIframe(this)">マスタへの登録</a> '+htmlC+'</li>';
            }
          }
          if (html != '') {
            html = '<ul class="suggestion active">' + html + '</ul>';
          }
          // Check if currentTh and currentInput are defined before using them
          if (currentTh && currentInput) {
            currentTh.find('.suggestion').remove();
            currentInput.closest('td').append(html);
            suggestionList = currentTh.find('.suggestion');
            var trNumber = $('#table tbody tr').length;
            if (trNumber < 2) {
              $('#table tfoot tr').addClass('suggestion-bot');
            } else {
              $('#table tfoot tr').removeClass('suggestion-bot');
            }
            var editRowIndex = currentTh.parents('tr').attr("data-index");
            suggestionList.on('mousedown', 'li', function (e) {
              e.stopPropagation();
              if ($(this).find('a').length <= 0) {
                var selectedField = field;
                if (columnConfig && Array.isArray(columnConfig.suggestion_change)) {
                  var liSelectedValue = Array();
                  var updateField = Array();

                  for (let i = 0; i < columnConfig.suggestion_change.length; i++) {
                    var flagNotValue = false;
                    if(Array.isArray(columnConfig.suggestion_change_not_value)) {
                      if(columnConfig.suggestion_change_not_value.includes(columnConfig.suggestion_change[i])) {
                        if(currentTh.parents('tr').find('input[name="' + columnConfig.suggestion_change[i] + '"]').val() != '') {
                          flagNotValue = true;
                        }
                      }
                    }
                    if(!flagNotValue) {
                      currentTh.parents('tr').find('input[name="' + columnConfig.suggestion_change[i] + '"]').val($(this).attr('data-' + columnConfig.suggestion_change[i]) || '').trigger('change');
                      liSelectedValue.push($(this).attr('data-' + columnConfig.suggestion_change[i]) || '');
                      updateField.push(columnConfig.suggestion_change[i]);
                    }
                  }
                  setTimeout(function () {
                    for (var i = 0; i < liSelectedValue.length; i++) {
                      $("#table").bootstrapTable(
                        "updateCell",
                        {
                          index: editRowIndex,
                          field: updateField[i],
                          value: liSelectedValue[i],
                          reinit: false,
                        }
                      );
                    }
                    suggestionSelected = true;
                    updatedRowIndex = editRowIndex;
                  }, 100);
                }
                currentTh.find('.error span').html('');
                $('#table .suggestion').remove();
                suggestionSelected = true;
                if(updateField.length > 0) {
                  var index = currentInput.parents('tr').index();
                  if(currentTh.parents('tbody').length == 1) {
                    setTimeout(function() {
                      for(var i = 0; i < updateField.length; i++) {
                        hasChangeData = true;
                        $('#table thead th div.sortable').css('pointer-events', 'none');
                        $('#table tr').eq(index+1).find('[name="'+updateField[i]+'"]').addClass('hasChangeValue').trigger('change');
                      }
                    }, 100)
                  }
                }
              } else {
                // var url = $(this).find('a').data('href');
                // $('#modalCreate').remove();
                // $('body').append(`
                //   <div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                //     <div class="modal-dialog modal-lg">
                //       <div class="modal-content">
                //         <div class="modal-header">
                //           <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                //             <span aria-hidden="true" style="font-size:30px">&times;</span>
                //           </button>
                //         </div>
                //         <div class="modal-body">
                //           <iframe id="modalIframe" width="100%" height="400" frameborder="0" src="${url}"></iframe>
                //         </div>
                //       </div>
                //     </div>
                //   </div>
                // `);
                // var url = $(this).data('href');
                // $('#modalCreate').modal('show');
              }
            });
          }
        }).catch(function (error) {
          Swal.fire({
            'title': settings.errorException,
            'icon': 'error',
            customClass: {
              popup: 'custom-modal-size'
            }
          });
        });
      }, 300);

    } else {
      $(this).closest('td').addClass('none-important');
      suggestionList.remove();
    }
  }
}
function notBlurF(e) {
  notBlur = true;
}

var interValSuggestionCheckbox ;
function changeSuggestionCheckbox(e) {
  var origin = window.location.origin;
  $(e).parents('.suggestion').find('li:not(.suggestion-sticky-bot)').remove();
  $(e).parents('.suggestion').prepend('<li class="img-ld"></li>');

  var listCheck = {};
  
  $(e).parents('.suggestion').find('input[type="checkbox"]').each(function() {
    if($(this).is(':checked')) {
      listCheck[$(this).attr('value')] = $(this).parents('tr').find('input[name="'+$(this).attr('value')+'"]').val();
    }
  });
  var field = $(e).parents('td').find('input:not(checkbox)').attr('name');
  var value = $(e).parents('td').find('input:not(checkbox)').val();
  var settings = $('#table').data('customTableSettings');

  var columnConfig = $('#table').bootstrapTable('getOptions').columns[0].find(col => col.field === field);
  var suggestionHide = columnConfig.suggestion_hide;
  var _this = $(e);
  interValSuggestionCheckbox  = setTimeout(function() {
    $.ajax({
      url: settings.urlSearchSuggestion,
      method: 'POST',
      data: {
        field: field,
        value: value,
        list_check: listCheck
      },
      success: function(res) {
        var html = '';
        
        if(res.data) {

          res.data.forEach(function (item) {
            if (columnConfig && Array.isArray(columnConfig.suggestion_change)) {
              html = html + '<li value="' + item[field] + '"';
              for (let i = 0; i < columnConfig.suggestion_change.length; i++) {
                var dataLi = item[columnConfig.suggestion_change[i]] ?? '';
                html = html + ' data-' + columnConfig.suggestion_change[i] + '=' + '"' + dataLi + '"';
              }
              html = html + '>';
              for (let i = 0; i < columnConfig.suggestion_change.length; i++) {
                if (suggestionHide) {
                  if (!suggestionHide.includes(columnConfig.suggestion_change[i])) {
                    var textLi = item[columnConfig.suggestion_change[i]] ?? '--';
                    html = html + textLi + '　';
                  }
                } else {
                  var textLi = item[columnConfig.suggestion_change[i]] ?? '--';
                  html = html + textLi + '　';
                }
              }
              html = html + '</li>';

            }

          });
          _this.parents('.suggestion').find('li:not(.suggestion-sticky-bot)').remove();
          _this.parents('.suggestion').prepend(html);

        }
        _this.parents('td').find('input[name="'+field+'"]').focus();
        notBlur = false;
        
      }
    });
  }, 500);
    
}

function modalInsertIframe(e) {
  var url = $(e).data('href');
  $('#modalCreate').remove();
  $('body').append(`
    <div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
              <span aria-hidden="true" style="font-size:30px">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <iframe id="modalIframe" width="100%" height="400" frameborder="0" src="${url}"></iframe>
          </div>
        </div>
      </div>
    </div>
  `);
  var url = $(this).data('href');
  $('#modalCreate').modal('show');
}

// Input only number
function validateNumberInput(e) {
  var inputElement = $(e);
  var inputValue = inputElement.val();

  // Remove all non-numeric characters from the input value
  inputValue = inputValue.replace(/\D/g, '');

  // Convert the value to a number
  var numericValue = '';
  if (inputValue.length > 0) {
    numericValue = parseInt(inputValue)
  }
  if (inputValue == '') {
    numericValue = '';
  }
  // Update the input value
  inputElement.val(numericValue);
}


function openColumnSelectionPopup() {
  var settings = $('#table').data('customTableSettings');
  var initCopy = settings.initCopy;
  // Clear existing content in the modal
  $('#columnSelectionModalBody').empty();
  $('#columnSelectionModalBody').append(`<div class="column1-child"></div><div class="column2-child"></div>`);
  // Get columns with copitable property set to true
  var copitableColumns = settings.columns.filter(column => column.copitable);
  var checkAllHtml = `<div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap text-center" >
        <input type="checkbox" class="form-check-input" id="checkAll"> 
        <i class="input-helper"></i>
        <span style="margin-left: -60px; font-weight: bold">前回選択項目</span>
      </label>
    </div>`;
  $('#columnSelectionModalBody .column1-child').append(checkAllHtml);
  // Build HTML for checkboxes based on copitable columns
  copitableColumns.forEach(column => {
    var htmlHidden = '';
    var label = column.title;
    if (column && Array.isArray(column.copyListHidden)) {
      column.copyListHidden.forEach(fieldCopy => {
        label += '/'+ settings.columns.filter(columnF => columnF.field == fieldCopy)[0].title;
        htmlHidden += '<input type="hidden" value="true" name="'+fieldCopy+'">';
      });
    }
    if(Array.isArray(initCopy)) {
      if(initCopy.length == 0) {
        var isChecked = true;
      } else {
        var isChecked = initCopy.includes(column.field);
      }
    } else if(initCopy == 1) {
      var isChecked = false;
    }
    if (Object.keys('1').length === 0) {
      isChecked = true;
    }
    var checkboxHtml = `<div class="form-check form-check-flat form-check-primary"><label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" value="${column.field}" ${isChecked ? 'checked' : ''}>${label}
      <i class="input-helper"></i>
    </label>${htmlHidden}</div>`;
    $('#columnSelectionModalBody .column2-child').append(checkboxHtml);
  });

  // Add event listener for "Check All" checkbox
  $('#checkAll').on('change', function () {
    var isChecked = $(this).prop('checked');
    // Set the state of all checkboxes based on the state of "Check All" checkbox
    $('#columnSelectionModalBody input[type="checkbox"]').prop('checked', isChecked);
  });

  // Add event listener for individual checkboxes
  $('#columnSelectionModalBody input[type="checkbox"]').on('change', function () {
    // Update the state of "Check All" based on the state of individual checkboxes
    var allChecked = $('#columnSelectionModalBody input[type="checkbox"]:not(#checkAll)').length === $('#columnSelectionModalBody input[type="checkbox"]:not(#checkAll):checked').length;
    $('#checkAll').prop('checked', allChecked);
  });
  if($('#columnSelectionModalBody input[type="checkbox"]:not(#checkAll)').length === $('#columnSelectionModalBody input[type="checkbox"]:not(#checkAll):checked').length) {
    $('#checkAll').prop('checked', true);
  }
}

function applyColumnSelection() {
  var settings = $('#table').data('customTableSettings');
  var selectedColumns = {};
  $('#columnSelectionModalBody input[type="checkbox"]').each(function () {
    var columnName = $(this).val();
    var isChecked = $(this).prop('checked');
    selectedColumns[columnName] = isChecked;
    $(this).parents('.form-check').find('input[type="hidden"]').each(function() {
      selectedColumns[$(this).attr('name')] = isChecked;
    });
  });
  // In ra đối tượng chứa giá trị của từng checkbox

  $.ajax({
    url: settings.urlUpdateInitDatacopy,
    data: selectedColumns,
    type: 'POST',
    success: function (res) {
      if (res.status == 200) {
        settings.initCopy = res.data;
      }
      $('#columnSelectionModal').modal('hide');
    }
  })
}

function handleDelete() {
  var selected = $('#table').bootstrapTable('getSelections');
  var settings = $('#table').data('customTableSettings');
  selected.forEach(function (row) {
    row.del_flg = 1;
    var index = $('#table').bootstrapTable('getData').indexOf(row);
    if (!Array.isArray(settings.dataDelete)) {
      settings.dataDelete = [];
    }
    settings.dataDelete.push(row);
    $('#table tr[data-index="' + index + '"]').remove();
    $('#table').bootstrapTable('remove', { field: '$index', values: [index] });
  });
}

function onClickTh() {
  var $th = $(this).parents('th');
  var field = $th.data('field');
  var currentSortOrder = $th.find('div.th-inner').hasClass('asc') ? 'asc' : ($th.hasClass('desc') ? 'desc' : undefined);

  $th.siblings().addBack().removeClass('asc desc');

  if (currentSortOrder !== 'asc') {
    dataMultiSort[field] = 'asc';
    $th.find('div.sortable.th-inner').removeClass('desc');
    $th.find('div.sortable.th-inner').addClass('asc');
  } else {
    dataMultiSort[field] = 'desc';
    $th.find('div.sortable.th-inner').removeClass('asc');
    $th.find('div.sortable.th-inner').addClass('desc');
  }

  refreshSilentMultiSort();
}
function refreshSilentMultiSort() {
  pageNumber = 1;
  $('#table').bootstrapTable('refresh', {
    silent: true,
    pageNumber: 1,
    query: {
      dataMultiSort
    }
  });
}
function validateRows(newRow) {
  var settings = $('#table').data('customTableSettings');
  var url = settings.urlValidateRows;
  isValidate = true;
  // Check if the URL is provided
  isValidate  = true;
  if (url != '') {
    // Return a Promise for asynchronous handling
    return new Promise(function (resolve, reject) {
      // Check if the new value is different from the current value
      // if (value != newValue) {
      // Make an AJAX request to update the record on the server
      $.ajax({
        url: url,
        type: 'POST',
        data: newRow,
        success: function (response) {
          if (response.status == 200) {
            // If the server update is successful, resolve the Promise with true
            resolve(true);
            isValidate = true;
            $('#addRow').prop('disabled', false);
          } else {
            // If the server update fails, reject the Promise with false
            reject(false);
          }
        },
        error: function (xhr, status, error) {
          // If the server returns a validation error, reject with the validation error response
          if (xhr.status == 422) {
            // var response = JSON.parse(xhr.responseText);
            reject(xhr);
          }
          // If there is a general error, reject the Promise with false
          reject(false);
        },
        complete: function() {
          isValidate = false;
          $('#addRow').prop('disabled', false);
        }
      });
      // } else {
      //   // If the new value is the same as the current value, resolve the Promise with true
      //   resolve(true);
      // }
    });
  }
}
function onclickAdd() {
  var values = {};
  var row = $(this).closest('tr');
  var hasValues = false;
  var settings = $('#table').data('customTableSettings');
  row.find('input, select').each(function () {
    var key = $(this).attr('name');
    var value = $(this).val();
    values[key] = value;
    if (value != null && value.trim() !== '') {
      hasValues = true;
    }
  });
  if (!hasValues) {
    alert('すべてのフィールドは空白にできません');
    return;
  }
  var newRow = Object.assign({});
  row.find('select, input').each(function () {
    newRow[$(this).attr('name')] = $(this).val();
  })

  validateRows(newRow)
    .then(function (res) {
      var currentData = $('#table').bootstrapTable('getData');
      $('#table').bootstrapTable('append', newRow);
      hasChangeData = true;
      $('#table thead th div.sortable').css('pointer-events', 'none');
      row.find('.error span').html('');
      row.find('input, select').val('');
      row.find('.hasChangeValue').removeClass('hasChangeValue');

    })
    .catch(function (xhr) {
      row.find('.error span').html('');
      if (xhr.status == 422) {
        var response = JSON.parse(xhr.responseText);
        var errors = response.errors;
        $.each(errors, function (key, value) {
          row.find('.error-' + key).parents('.group-input').addClass('error');
          row.find('.error-' + key + ' span').html(value);
        });
      } else {
        Swal.fire({
          'title': settings.errorException,
          'icon': 'error',
          customClass: {
            popup: 'custom-modal-size'
          }
        })
      }
    });
}

$(document).ready(function () {
  $('#table').on('focus', ' tbody input', function () {
    $('#addRow').prop('disabled', true);
  });

  $('#table').on('blur', 'input', function () {
    if(isValidate == false) {
      $('#addRow').prop('disabled', false);
    }
  });
});

// input : mm/dd → convert to yyyy/mm/dd.
function autoFillDate(inputDate) {
  var mmdd_regex = /^\d{1,2}\/\d{1,2}$/;
  if (mmdd_regex.test($(inputDate).val())) {
    var splitMMDD = $(inputDate).val().split("/");
    var mm = splitMMDD[0];
    var dd = splitMMDD[1];
    if (mm.length == 1) {
      mm = '0' + mm;
    }
    if (dd.length == 1) {
      dd = '0' + dd;
    }
    var Today = new Date();
    $(inputDate).val(Today.getFullYear() + "/" + mm + "/" + dd);
  }
}

// check validate for a row.
// case: select data from suggestion.
function checkValidRow(inputObj) {
  var $row = inputObj.closest('tr');
  var newValue = inputObj.val();
  var index = $row.index();
  var settings = $('#table').data('customTableSettings');
  var row = $('#table').bootstrapTable('getData')[$row.index()];
  var field = inputObj.attr('name');
  if (settings.urlValidateRows != '') {
    var tr = inputObj.closest('tr');
    var newRow = Object.assign({}, row);
    // newRow[field] = newValue;
    $row.find('select, input').each(function() {
      if($(this).hasClass('text-right')) {
        newRow[$(this).attr('name')] = $(this).val().replace(',', '');
      } else {
        newRow[$(this).attr('name')] = $(this).val();
      }
    })
    validateRows(newRow)
      .then(function (res) {
        // row[field] = newValue;
        row = newRow;
        tr.find('.error span').html('');
      }).catch(function (xhr) {
        tr.find('.error span').html('');
        if (xhr.status == 422) {
          var response = JSON.parse(xhr.responseText);
          var errors = response.errors;
          $.each(errors, function (key, value) {
            tr.find('.error-' + key).parents('.group-input').addClass('error');
            tr.find('.error-' + key + ' span').html(value);
          });
        }
      })
  }
}

// table > cell > focus.
function setCellFocusStatus(e, active) {
  if (active) {
    e.addClass("cell-focus");
  } else {
    e.removeClass("cell-focus");
  }
}

var flagOutFocus = false;
function outFocusSuggestion(e) {
  flagOutFocus = true;
  clearTimeout(letTimeout);
  var currentInput = $(e);
  var field = $(e).attr('name');
  var value = $(e).val();
  if(!value) {
    return;
  }
  var columnConfig = $('#table').bootstrapTable('getOptions').columns[0].find(col => col.field === field);
  if(columnConfig.url_suggestion) {
    var urlSearch = columnConfig.url_suggestion;
  } else {
    var urlSearch = '';
  }
  if(columnConfig.otherFieldElements) {
    var otherFieldElements = columnConfig.otherFieldElements;
  } else {
    var otherFieldElements = [];
  }
  var currentTh = $(e).closest('td');
  var tr = $(e).parents('tr');
  if(tr.parents('tbody').length > 0) {
    var indexTr = tr.attr('data-index');
    var dataRow = $('#table').bootstrapTable('getData')[indexTr];
  } else {
    var indexTr = -1;
    var dataRow = {};
  }
  if(value != dataRow[field]) {
    searchSuggestion(field, value, urlSearch, otherFieldElements).then(function (res) {
      var editRowIndex = currentTh.parents('tr').attr("data-index");
      if(res.data.length > 0) {
        var data = res.data;
        let results = data.filter(item => item[field] == value);
        if(results) {
          results = results[0];
          var selectedField = field;
          if (columnConfig && Array.isArray(columnConfig.suggestion_change)) {
            var liSelectedValue = Array();
            var updateField = Array();

            for (let i = 0; i < columnConfig.suggestion_change.length; i++) {
              var flagNotValue = false;
              if(Array.isArray(columnConfig.suggestion_change_not_value)) {
                if(columnConfig.suggestion_change_not_value.includes(columnConfig.suggestion_change[i])) {
                  if(currentTh.parents('tr').find('input[name="' + columnConfig.suggestion_change[i] + '"]').val() != '') {
                    flagNotValue = true;
                  }
                }
              }
              if(!flagNotValue) {
                currentTh.parents('tr').find('input[name="' + columnConfig.suggestion_change[i] + '"]').val(results[columnConfig.suggestion_change[i]] || '').addClass('hasChangeValue');
                liSelectedValue.push(results[columnConfig.suggestion_change[i]] || '');
                updateField.push(columnConfig.suggestion_change[i]);
                if(dataRow.hasOwnProperty(columnConfig.suggestion_change[i]) && indexTr != -1) {
                  dataRow[columnConfig.suggestion_change[i]] = results[columnConfig.suggestion_change[i]] || '';
                }
              }
            }
          }
          currentTh.find('.error span').html('');
          $('#table .suggestion').remove();
          suggestionSelected = true;
        }
      }
    })
  }
}

$('#table').on('keyup', 'input', function(event) {
  if(event.keyCode == 9) {
    event.target.select();
  }
});