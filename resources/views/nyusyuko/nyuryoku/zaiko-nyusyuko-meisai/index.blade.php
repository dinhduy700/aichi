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
  <link rel="shortcut icon" href="images/favicon.png" />
</head>
<body>
  <div class="">
    <style>
      .page-body-wrapper
      {
        padding-top: 0 !important;
      }
      #backButton
      {
        display: none !important;
      }
      .bootstrap-table .fixed-table-toolbar
      {
        display: none !important;
      }
      #table input[readonly] {
        border: none;
        background: none;
      }
      .fixed-table-body
      {
        padding-top: 0 !important;
      }
    </style>
    <form id="formZaikoNyusyukoMeisai" class="form-master" method="post">
      <div class="card">
        <div class="card-body">
          <div class="form-custom ">
            <div class="row">
              <div class="col-2">
                <button class="btn btn-secondary" type="button" onclick="closeParent()">戻る</button>
                <button class="btn btn-primary" type="button" onclick="searchList()">再表示</button>
              </div>
              <div class="col-10">
                <div class="row">
                  <div class="col-7">
                      
                    <div style="display: grid; grid-template-columns: auto 30%; grid-gap: 0;">
                      <input type="text" class="form-control" name="" style="border-right: none; border-top-right-radius: unset;border-bottom-right-radius: unset;" readonly value="{{ @$hinmei->hinmei_cd .' '.@$hinmei->hinmei_nm }}">
                      <input type="text" class="form-control text-right" name="" style="border-top-left-radius: unset;border-bottom-left-radius: unset;" readonly value="入数：{{ @numberFormat($hinmei->irisu, -1) }}">
                    </div>

                  </div>
                  <div class="col-5">
                    <div class="row align-items-center">
                      <div class="col-6">
                        <div style="display: flex; align-items: center; flex-wrap: wrap; grid-gap: 10px;">
                          <div class="form-check form-check-primary" style="margin: 0">
                            <label class="form-check-label">
                              <input type="radio" class="form-check-input" name="radio_su" value="tujyo_hyouji" checked="">
                              通常表示    
                              <i class="input-helper"></i>
                            </label>
                          </div>
                          <div class="form-check form-check-primary" style="margin: 0">
                            <label class="form-check-label">
                              <input type="radio" class="form-check-input" name="radio_su" value="zaiko_nasi_hyouj" >
                              在庫無しも表示
                              <i class="input-helper"></i>
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="col-6">
                        <div style="display: flex; align-items: center; grid-gap: 10px;">
                          <label style="margin: 0;">出庫総数</label>
                          <input type="" class="form-control" name="" value="{{ @request()->get('su') }}" style="flex: 1; text-align: right">
                          <span>{{ @$hinmei->case_cd_nm }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="card">
    <div class="card-body">
      <div class="form-custom " style="overflow: auto;">
        <div style="display: grid; grid-template-columns: auto  100px 200px 100px ; min-width: 1200px">
          <div class="text-center">
            <button type="button" class="btn btn-secondary" onclick="submitPopup()">引当数入力完了</button>
          </div>
          <div style="display: flex; align-items: center; justify-content: flex-end;">合計</div>
          <div style="border: 1px solid #CED4DA">
            <span></span>
          </div>
          <div style="border: 1px solid #CED4DA; border-left: none">
            <span></span>
          </div>
        </div>
        <div>
          <table id="table" class="hansontable" data-sticky-columns="['id']">
        </table>
        </div>
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
  var useAddFormFooter = true;
  var listButtonToolBar = '';
  var columns = @json($setting);
  var row = {};
  var searchDatas = @json(request() -> query());
  $('#table').customTable({
     // Data source URL
    urlData: '{!! route('nyusyuko.nyuryoku.data_list_zaiko_nyusyuko_meisai', request()->query()) !!}',
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
    formSearch: $('#formZaikoNyusyukoMeisai'),
    // URL for copying record
    // Number of items per page
    pageSize: 0,
    insertLastRow: false,

    isCopyLeft: false,
    isDelete: false,
    defaultSearchForm: false,
    isShow: true, // if is true will be show list when init
  });

    window.addEventListener('message', function(event) {
      var receivedObject = JSON.parse(event.data);
      Object.assign(row, receivedObject);
    }, false);
    function closeParent() {
      window.parent.postMessage('closeParent', '*');
    }
    $.fn.customTable.callbackAfterShow(showHead);

    function searchList() 
    {
      $.fn.customTable.searchList();
    }

    function showHead() {
      console.log('111');
    }
    function formatterSokoNm(value, row, index) {
      return (value || '') + '<input name="soko_nm" type="hidden" value="'+ (value || '') +'" readonly class="form-control"> <input type="hidden" value="'+ (row.soko_cd || '')+'"  name="soko_cd"> <input type="hidden" name="seq_no" value="'+row.seq_no+'" > <input type="hidden" name="location" value="'+row.location+'">';
    }
    function formatterHikiateKannoSu(value, row, index) {
      return '<input name="hikiate_kanno_su" value="'+numberFormat(value || '', -1)+'" readonly class="form-control text-right" style="max-width: 200px">'; 
    }

    function formatterHikiateSu(value, row, index) {
      return '<input name="hikiate_su" value="'+(value || '')+'" class="form-control text-right" onkeypress="onlyNumber(event)">';
    }

    function formatterZensu(value, row, index) {
      return '<div class="text-center"><input type="checkbox" value ="1" ></div>';
    }

    function submitPopup() {
      var invalid = false;
      $('#table tbody tr').each(function() {
        var hikiateKannoSu = parseFloat($(this).find('[name="hikiate_kanno_su"]').val().replace(/,/g, '')) || 0;
        var hikiateSu = parseFloat($(this).find('[name="hikiate_su"]').val().replace(/,/g, '')) || 0;

        if (hikiateKannoSu < hikiateSu) {
          alert('マイナス在庫です。');
          invalid = true;
          return false;
        }
      });
      if(invalid) {
        return false;
      }

      var listRow = [];
      $('#table tbody tr input[type="checkbox"]:checked').each(function() {
        row.soko_nm = $(this).parents('tr').find('[name="soko_nm"]').val();
        row.soko_cd = $(this).parents('tr').find('[name="soko_cd"]').val();
        row.seq_no = $(this).parents('tr').find('[name="seq_no"]').val();
        row.su = $(this).parents('tr').find('[name="hikiate_su"]').val();
        row.location = $(this).parents('tr').find('[name="location"]').val();
        row.case_su = null;
        row.hasu = null;
        // row.nyuko_dt = null;
        // row.seizo_no = null;
        // row.situryo = null;
        // row.tani_cd = null;
        // row.tani_nm = null;
        // row.jyuryo = null;
        // row.biko = null;
        row.nyusyuko_den_meisai_no = null;
        var newRow = Object.assign({}, row);
        listRow.push(newRow);
      });
      window.parent.postMessage({message: 'zaiko', data: listRow}, '*');
    }
  </script>
</body>

</html>

