<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ getPageHeadTitle(request()->route()->getName()) }}</title>

  <link rel="stylesheet" href="{{asset('vendors/feather/feather.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/ti-icons/css/themify-icons.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/css/vendor.bundle.base.css')}}">
  <link href="{{asset('/vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
  <link href="{{ asset('assets/css/bootstrap-table.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
  <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">

  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom_page.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jspreadsheet.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jsuites.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jspreadsheet.theme.css') }}">
  @yield('css')
  <style>
    
  </style>
  <link rel="shortcut icon" href="images/favicon.png" />
</head>
<body>
  <div>
    <form id="formNyusyukoHead" class="form-custom" onsubmit="return false;">
      <div class="card">
        <div class="card-body">
          <div>
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <div class="group-input row">
                    <label class="col-form-label col-3" >部門</label>
                    <div class="col-9">
                      <div class="group-input group-messages error-all" style="flex-wrap: wrap; display: flex; flex: 1; position: relative; ">
                        <input type="text" class="form-control size-M" name="hed_bumon_cd" style="width: 100px;" onkeyup="suggestionForm(this, 'bumon_cd', ['bumon_cd', 'kana', 'bumon_nm'], {'bumon_cd': 'hed_bumon_cd', 'bumon_nm': 'hed_bumon_nm'}, $(this).parent())" style="" autocomplete="off">

                        <input class="form-control size-L" name="hed_bumon_nm" style="border-bottom-left-radius: 0; border-top-left-radius: 0" onkeyup="suggestionForm(this, 'bumon_nm', ['bumon_cd', 'kana', 'bumon_nm'], {'bumon_cd': 'hed_bumon_cd', 'bumon_nm': 'hed_bumon_nm'}, $(this).parent())" autocomplete="off">
                        <ul class="suggestion"></ul>
                        <div class="error_message" style="width: 100%">
                          <span class="text-danger" ></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <div class="group-input group-messages row">
                    <label class="col-3 col-form-label">入出庫区分</label>
                    <div class="col-3">
                      <select class="form-control" name="hed_nyusyuko_kbn">
                        <option value=""></option>
                        @foreach(config('params.NYUSYUKO_KBN_SUPPORT') as $key => $nyusyuko) 
                        <option value="{{$key}}">{{$nyusyuko}}</option>
                        @endforeach
                      </select>
                      <div class="error_message" style="width: 100%">
                        <span class="text-danger" ></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="group-input row">
                  <label class="col-form-label col-3">伝票日付</label>
                  <div class="col-9">
                    <div class="group-messages">
                      <div class="group-input-ft">
                        <div class="group-input">
                          <input type="" class="form-control" name="denpyo_dt_from" onchange="autoFillDate(this)">
                        </div>
                        <span class="text-center">～</span>
                        <div class="group-input">
                          <input type="" class="form-control" name="denpyo_dt_to" onchange="autoFillDate(this)">
                        </div>
                      </div>
                      <div class="error_message">
                        <span class="text-danger"></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-6">
                <div class="form-group">
                  <div class="group-input row">
                    <label class="col-2 col-form-label">荷届け先名</label>
                    <div class="col-9">
                      <div class="group-messages">
                        <input type="" class="form-control" name="todokesaki_nm">
                        <div class="error_message">
                          <span class="text-danger"></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-2">
                <div class="form-group">
                  <div class="group-input row">
                    <label class="col-6 col-form-label">検索条件</label>
                    <div class="col-6">
                      <select class="form-control" name="todokesaki_nm_jyoken">
                        <option value="0">
                          で始まる
                        </option>
                        <option value="1">を含む</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-4">
                <div class="group-input row">
                  <label class="col-form-label col-3">伝票NO</label>
                  <div class="col-9">
                    <div class="group-messages">
                      <div class="group-input-ft">
                        <div class="group-input">
                          <input type="" class="form-control" name="nyusyuko_den_no_from">
                        </div>
                        <span class="text-center">～</span>
                        <div class="group-input">
                          <input type="" class="form-control" name="nyusyuko_den_no_to">
                        </div>
                      </div>
                      <div class="error_message"><span class="text-danger"></span></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-6">
                <div class="form-group">
                  <div class="group-input row">
                    <label class="col-2 col-form-label">商品名</label>
                    <div class="col-9">
                      <div class="group-messages">
                        <input type="" class="form-control" name="hinmei_nm">
                        <div class="error_message"><span class="text-danger"></span></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-2">
                <div class="form-group">
                  <div class="group-input row">
                    <label class="col-6 col-form-label">検索条件</label>
                    <div class="col-6">
                      <select class="form-control" name="hinmei_nm_jyoken">
                        <option value="0">
                          で始まる
                        </option>
                        <option value="1">を含む</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-1 col-form-label">荷主</label>
              <div class="col-9">
                <div class="form-group">
                  <div class="group-input-ft">
                    <div class="group-messages error-all">
                      <div style="display: grid; grid-template-columns: 100px auto; position: relative; " class="group-input">
                        <input type="text" class="form-control" name="ninusi_cd_from" onkeyup="suggestionForm(this, 'ninusi_cd', ['ninusi_cd', 'kana', 'ninusi_nm'], {'ninusi_cd': 'ninusi_cd_from', 'ninusi_nm': 'ninusi_nm_from'}, $(this).parent())">
                        <input type="text" class="form-control" name="ninusi_nm_from" onkeyup="suggestionForm(this, 'ninusi_nm', ['ninusi_cd', 'kana', 'ninusi_nm'], {'ninusi_cd': 'ninusi_cd_from', 'ninusi_nm': 'ninusi_nm_from'}, $(this).parent())">
                        <ul class="suggestion"></ul>
                      </div>
                      <div class="error_message"><span class="text-danger"></span></div>
                    </div>
                    <span class="text-center">～</span>
                    <div class="group-messages error-all">
                      <div style="display: grid; grid-template-columns: 100px auto; position: relative;" class="group-input">
                        <input type="text" class="form-control" name="ninusi_cd_to" onkeyup="suggestionForm(this, 'ninusi_cd', ['ninusi_cd', 'kana', 'ninusi_nm'], {'ninusi_cd': 'ninusi_cd_to', 'ninusi_nm': 'ninusi_nm_to'}, $(this).parent())">
                        <input type="text" class="form-control" name="ninusi_nm_to" onkeyup="suggestionForm(this, 'ninusi_nm', ['ninusi_cd', 'kana', 'ninusi_nm'], {'ninusi_cd': 'ninusi_cd_to', 'ninusi_nm': 'ninusi_nm_to'}, $(this).parent())">
                        <ul class="suggestion"></ul>
                      </div>
                      <div class="error_message"><span class="text-danger"></span></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="group-input row">
                  <label class="col-form-label col-3">着地</label>
                  <div class="col-9">
                    <div class="group-messages">
                      <div class="group-input-ft">
                        <div style="">
                          <input type="" class="form-control" name="hachaku_cd_from" onkeyup="suggestionForm(this, 'hachaku_cd', ['hachaku_cd', 'kana', 'hachaku_nm'], {'hachaku_cd': 'hachaku_cd_from', 'hachaku_nm': 'hachaku_nm_from'}, $(this).parent())">
                          <ul class="suggestion"></ul>
                        </div>
                        <span class="text-center">～</span>
                        <div>
                          <input type="" class="form-control" name="hachaku_cd_to" onkeyup="suggestionForm(this, 'hachaku_cd', ['hachaku_cd', 'kana', 'hachaku_nm'], {'hachaku_cd': 'hachaku_cd_to', 'hachaku_nm': 'hachaku_nm_to'}, $(this).parent())"> 
                          <ul class="suggestion"></ul>
                        </div>
                      </div>
                      <div class="error_message"><span class="text-danger"></span></div>
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="mt-2">
            <div class=" d-flex-center-end-nowarp" style="">
              <div class="" style="white-space: nowrap;">
                <button class="btn btn-clear min-wid-110" type="button" onclick="clearForm(this)">条件クリア</button>
                <button class="btn btn-search min-wid-110" type="button" onclick="searchList(this)">検索</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="card">
    <div class="card-body">
      <div class="form-custom ">
        <table id="table" class="hansontable" data-sticky-columns="['id']">
        </table>
      </div>
    </div>
  </div>

  <script src="{{asset('vendors/js/vendor.bundle.base.js')}}"></script>
  <script src="{{asset('assets/js/template.js')}}"></script>
  <script src="{{asset('assets/js/settings.js')}}"></script>
  <!-- endinject -->
  <script src="{{ asset('assets/js/bootstrap-table.min.js') }}"></script>
  <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
  <script src="{{ asset('assets/js/jquery.ui.datepicker-ja.js') }}"></script>
  <!-- End custom js for this page-->
  <script src="{{ asset('assets/js/sweetalert.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/custom/common.js') }}"></script>
  <script src="{{ asset('assets/custom/custom-table.js') }}"> </script>

  <script src="{{ asset('assets/custom/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('assets/js/jspreadsheet.js') }}"></script>
  <script src="{{ asset('assets/js/jsuites.js') }}"></script>
  <script>
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var isUseIframe;
    @if(request()->get('create_by_iframe'))
      isUseIframe = true;
    @endif
  </script>
  @yield('js')

  <script>
    var useAddFormFooter = false;
    var listButtonToolBar = '';
    var columns = @json($setting);
    var searchDatas = @json(request() -> query());
    $('#table').customTable({
       // Data source URL
      urlData: '{!! route('nyusyuko.nyuryoku.data_list_nyusyuko_head', request()->query()) !!}',
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
      formSearch: $('#formNyusyukoHead'),
      // URL for copying record
      urlCopyRecord: '',
      // URL for search suggestion
      urlSearchSuggestion: '{{route('uriage.uriage_entry.search_suggestion')}}',
      // URL for exporting data table to Excel
      urlExportExcelDataTable: '',
      // Number of items per page
      pageSize: {{ config()->get('params.PAGE_SIZE') }},
      // textButtonExportExcel: '{{ trans('app.labels.btn-xls-export') }}',
      // urlValidateRows: '{{ route('uriage.uriage_entry.validate_row') }}',
      // Option to insert new rows at the end
      insertLastRow: false,
       // URL for updating data table
      // urlUpdateDataTable: '{{route('uriage.uriage_entry.update_datatable')}}',
      // Option to copy data to the left
      isCopyLeft: false,
      isDelete: false,
      defaultSearchForm: false,
      isShow: true, // if is true will be show list when init
      usingPaginateTop: true
    });

    function inputsEntryHead(value, row, index) {
      var nyusyukoHead = row.nyusyuko_den_no;
      return '<button class="btn btn-primary" type="button" onclick="parentIframe(this, '+nyusyukoHead+')">選択</button>';
    }

    function formatDateGrid(value, row, index) {
      return convertDateFormat(value);
    }
    function parentIframe(e, nyusyukoDenNo) {
      parent.postMessage({nyusyuko_den_no:nyusyukoDenNo, message: 'head'}, '*');
    }

    window.addEventListener('message', function(event) {
      var receivedObject = event.data;
      if(event.data == 'research') {
        searchList();
      }
    }, false);

    function searchList() {
      $.ajax({
        url: '{{route('nyusyuko.nyuryoku.validate_nyusyuko_head_form_search')}}',
        data: $('#formNyusyukoHead').serialize(),
        method: 'POST',
        success: function(res) {
          $.fn.customTable.searchList();
        },
        error: function(error) {
          clearHandleError($('#formNyusyukoHead'));
          if(typeof handleError == 'function') {
            handleError(error);
          } 
        }
      })
    }
    
    function clearForm(e) {
      clearHandleError($('#formNyusyukoHead'));
      $('#formNyusyukoHead').find('input, select').val('');
    }

    function handleError(error) {
      if (error.status == 422) {
        var errors = error.responseJSON.errors;
        $.each(errors, function (key, value) {
          var row = $('[name="'+key+'"]').parents('.group-messages');
          row.addClass('error-input');
          row.find('.error_message span').html(value);
          $('[name="'+key+'"]').addClass('error-input');
        });
      }
    }

    function clearHandleError($elementForm) {
      $elementForm.find('.error_message span').html('');
      $elementForm.find('.error-input').removeClass('error-input');
      $elementForm.find('.group-messages').removeClass('error-input');
    }

  </script>
</body>

</html>

