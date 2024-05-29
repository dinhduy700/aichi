@extends('layouts.master')
@section('css')
  <style>
    #bumonTable thead th {
      padding: 10px 0px;
     
    }
    a.disabled {
      pointer-events: none;
      cursor: default;
    }
    .btn-in-list {
      display: inline;
      padding: 4px 10px !important;
    }
  </style>
@endsection
@section('page-content')
<form method="" id="formSeikyuShimebi" class="form-custom">
<div class="card list-master-search-area">
  <div class="card-body">
    <div class="row">
      <div class="col-12 col-md-7">
        <div class="row" id="">
          <div class="col-md-5">
            <div class="row">
              <label class="col-12 col-md-4 col-form-label text-nowrap ">検索開始日付</label>
              <div class="col-12 col-md-8">
                <div class="group-input">
                  <input type="text" name="seikyu_sime_dt" class="form-control size-L" autocomplete="off" value="{{ \App\Helpers\Formatter::date($defaultSeikyuSimeDt) }}" onchange="autoFillDate(this)">
                  <div class="error_message mb-0"><span class=" text-danger" id="error-seikyu_sime_dt"></span></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-5" style="display: flex; justify-content: flex-end;">
        <div class="d-flex">
          <label class="col-form-label">&nbsp;</label>
          <div class="text-right" style="display: flex; flex-wrap: wrap; align-items: center;justify-content: flex-end;grid-gap: 10px; flex: 1">
            <button class="btn btn-clear min-wid-110" type="button" onclick="clearForm(this)">{{ trans('app.labels.btn-clear') }}</button>
            <button class="btn btn-search min-wid-110" type="button" name="search_list" onclick="searchList(this)">{{ trans('app.labels.btn-search') }}</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</form>


<div class="mt-3 form-custom" id="content-list">
  <div class="card">
    <div class="card-body">
      <div class="row">

        <div class="col-8">
          <table id="table" class="hansontable" data-sticky-columns="['id']">
          </table>
        </div>

        <div class="col-4" id="formAddSeikyuShimebi">
          <div class="row">
            <label class="col-12 col-lg-3 col-form-label">対象締日追加</label>
            <div class="col-12 col-lg-9 formCol px-0 input-group form-inline">
              <div class="group-input">
                <input type="text" class="form-control size-L" name="seikyu_sime_dt" onchange="autoFillDate(this)" autocomplete="off">
                <div class="error_message mb-0"><span class=" text-danger" id="error-seikyu_sime_dt"></span></div>
              </div>
              <button type="button" class="btn btn-search min-wid-110 ml-3" 
                      data-href="{{ route('seikyu.seikyu_shimebi.store') }}"
                      onclick="insertSeikyuSimebiSiji(this)">
                追加
              </button>
            </div>
          </div>

          <br>
          <div class="row">
            <label class="col-12 col-lg-3 col-form-label" style="align-self: start">処理担当部門</label>
            <div class="col-12 col-lg-9 formCol px-0 input-group form-inline">
                <div id="bumonTable" style="max-height: 600px; overflow: auto; width: 100%">
                  <table class="hansontable table table-bordered table-hover">
                    <thead class="">
                      <tr>
                        <th style="width: 5%">
                          <input type="checkbox" name="bumon_check_all" value="" id="bumon_check_all">
                        </th>
                        <th style="width: 20%"></th>
                        <th>全社</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($bumons as $bumon)
                        <tr>
                          <td><input type="checkbox" name="bumons[]" value="{{ $bumon->bumon_cd }}"></td>
                          <td>{{ $bumon->bumon_cd }}</td>
                          <td>{{ $bumon->bumon_nm}}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
         
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
<style type="text/css">

</style>
@endsection

@section('js')
<script>
  var useAddFormFooter = true;

  var pageNumber  = {{ request() -> get('page') ?? 1 }};
  var columns     = @json($setting);
  var searchDatas = @json(request() -> query());

  $(function() {
    $("#bumon_check_all").prop('checked', true);
    $('#bumonTable tbody input[type="checkbox"]').prop('checked', true);

    $('#bumon_check_all').on('change', function () {
      var isChecked = $(this).prop('checked');
      $('#bumonTable tbody input[type="checkbox"]').prop('checked', isChecked);
    });

    $('#formAddSeikyuShimebi').find('select, input').change(function() {
      hasChangeData = true;
    })
  })
  
  $('#table').customTable({
    urlData: '{!! route('seikyu.seikyu_shimebi.data_list', request()->query()) !!}',
    showColumns: false,
    columns: columns,
    pageNumber: pageNumber,
    formSearch: $('#formSeikyuShimebi'),
    pageSize: {{ config()->get('params.PAGE_SIZE') }},
    isShow: true,
  });

  
  function displayBtnInsert(value, row, index)
  {
    var url = '{{route('seikyu.seikyu_shimebi.handle_seikyu_zaiko', ['seikyuSimeDt' => ':seikyuSimeDt'])}}';
    url = url.replace(':seikyuSimeDt', encodeURIComponent(row.seikyu_sime_dt));
    value = '{{ trans('app.labels.btn-update') }}';
    var disabled = row.seikyu_kakutei_flg == "{{ config('params.SEIKYU_KAKUTEI_FLG_ALL_1') }}" ? 'disabled' : null; // đúng
    return '<a href="javascript:void(0)" data-href="' + url + '" class="btn btn-primary text-white rounded btn-in-list '+ disabled +'" onclick="handleSeikyuZaiko(this)">' + value + '</a>'
  }

  function displayBtnDelete(value, row, index)
  {
    var url = '{{route('seikyu.seikyu_shimebi.destroy', ['seikyuSimeDt' => ':seikyuSimeDt'])}}';
    url = url.replace(':seikyuSimeDt', encodeURIComponent(row.seikyu_sime_dt));
    value = "{{ trans('app.labels.btn-delete') }}";
    var btn = null;
    if (row.seikyu_hako_flg == null) {
      btn = '<a href="javascript:void(0)" data-href="' + url + '" class="btn btn-delete text-white rounded btn-in-list" onclick="deleteData(this)">' + value + '</a>';
    }
    return btn;
  }

  function formatDate(value, row, index) {
      if (value) {
        let date = new Date(value);
        return $.datepicker.formatDate("yy/mm/dd", date);
      }
      return value;
    }

  function searchList(e) {
    $('#content-list').css('display', 'block');

    //validate
    var dataSend = {
      seikyu_sime_dt : $('form#formSeikyuShimebi input[name=seikyu_sime_dt]').val(),
      action : 'search',
    };

    $('form#formSeikyuShimebi .group-input').removeClass('error');
    $('form#formSeikyuShimebi .error_message span').html('');

    $.ajax({
      type: "POST",
      url: "{{ route('seikyu.seikyu_shimebi.validateSearchForm') }}",
      data: dataSend,
      success: function (res) {
        $.fn.customTable.searchList();

        //insert m_user_pg_function
        handleMUserPg(e);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        if(jqXHR.status == 422) {
          var errors = jqXHR.responseJSON.errors;
          $.each(errors, function(key, value) {
            $('#error-'+key).parents('.group-input').addClass('error');
            $('#error-'+key).html(value);
          });
        }
      },
    });

    
  }

  function clearForm(e) {
    $(e).parents('form').find('select, input[type=text]').val('');
    $(e).parents('form').find('input[type="checkbox"]').prop('checked', true);
  }

  function handleMUserPg(e)
  {
    var dataSend = {
      seikyu_sime_dt : $('form#formSeikyuShimebi input[name=seikyu_sime_dt]').val(),
    };

      $.ajax({
        type: "POST",
        url: "{{ route('seikyu.seikyu_shimebi.handleMUserPg') }}",
        data: dataSend,
        success: function (res) {
        }
      });
  }

  function insertSeikyuSimebiSiji(e)
  {
    var dataSend = {
      seikyu_sime_dt: $('#formAddSeikyuShimebi input[name=seikyu_sime_dt]').val()
    };

    $.ajax({
      type: "POST",
      url: $(e).data('href'),
      data: dataSend,
      success: function (res) {
        $('#formAddSeikyuShimebi .error_message span').html('');
        $('#formAddSeikyuShimebi .group-input').removeClass('error');
        loadPage();
      },
      error: function(jqXHR, textStatus, errorThrown) {
        if(jqXHR.status == 422) {
          var errors = jqXHR.responseJSON.errors;
          $.each(errors, function(key, value) {
            $('#formAddSeikyuShimebi #error-'+key).parents('.group-input').addClass('error');
            $('#formAddSeikyuShimebi #error-'+key).html(value);
          });
        }
      },
      complete: function() {

      }
    });
  }

  function handleSeikyuZaiko(e)
  {
    var selectedValues = [];
    $("#bumonTable tbody input[type=checkbox]:checked").each(function(){
      selectedValues.push($(this).val());
    });

    if (selectedValues.length == 0) {
      alert(@json(__('messages.E0021', ['attribute' => '部門'])));
      return
    }

    var dataSend = {
      bumons: selectedValues
    };
    loading();
    $.ajax({
      type: "POST",
      url: $(e).data('href'),
      data: dataSend,
      success: function (res) {
        console.log(res);
        if (res.success) {
          loading(false);
          alert(res.msg);
          return;
        } else {
          loading(false);
          alert(res.msg);
          return;
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
      },
      complete: function() {

      }
    });
  }

  function handleDelete(e) 
  {
    $.ajax({
      url: $(e).data('href'),
      method: 'DELETE',
      data: {},
      success: function(res) {
        if(res.status == 200) {
          loadPage();
        } else {
          Swal.fire({
            title: res.message,
            icon: "error"
          });
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.error('AJAX Error:', textStatus, errorThrown);
      },
      complete: function() {

      }
    })
  }

  function loadPage()
  {
    $('form#formSeikyuShimebi button[name=search_list]').trigger('click');
  }
</script>
@endsection