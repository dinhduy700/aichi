@extends('layouts.master')
@section('css')
  <style>
    #ui-datepicker-div {
      z-index: 1051 !important;
    }

    .loading {
      z-index: 9999 !important;
    }

    @media print {
      body * {
        display: none !important;
      }

      #list-img, #list-img img {
        display: block !important;
      }
    }

  </style>
@endsection
@section('page-content')
  @php
    $labelClass = ["col-12", "col-lg-2", "col-form-label"];
    $inputClass = ["col-12", "col-lg-10", "formCol", "px-0", "input-group", "form-inline"];
    $errorClass = ["error_message", "mb-0"];
  @endphp
  <div class="card form-custom">
    <div class="card-body">
      <form id="seikyuSearchForm" method="post">
        @csrf
        <div class="form-group row">
          <div class="col">
            <button class="btn btn-primary min-wid-110 btn-xls-export" type="button" onclick="showModalExpFilter()">
              請求書発行
            </button>
          </div>
        </div>

        <div class="form-group row">
          <div class="col-12 col-md-12 row">
            <div class="col-12 col-md-6">
              <div class="row">
                <label @class(array_merge($labelClass))>締日選択</label>
                <div @class($inputClass)>
                  <div class="group-input">
                    <input type="text" class="form-control size-L datepicker" name="seikyu_sime_dt" value="{{ App\Helpers\Formatter::date($maxDate) }}">
                    <div @class($errorClass)><span class=" text-danger" id="error-seikyu_sime_dt"></span></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12 col-lg-4">
              <div class="d-flex" style="justify-content: space-between; align-items: center;">
                <div class="d-flex">
                  <label class="col-form-label">&nbsp;</label>
                  <div class="text-right" style="display: flex; flex-wrap: wrap; align-items: center;justify-content: flex-end;grid-gap: 10px; flex: 1">
                    <button class="btn btn-search min-wid-110" type="button">{{ trans('app.labels.btn-search') }}</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </form>


    </div>
  </div>
  <div class="mt-3" id="content-list">
    <div class="card">
      <div class="card-body">
        <div>
          <table id="table" class="hansontable" data-sticky-columns="['id']">
          </table>
        </div>
      </div>
    </div>
  </div>

  @include('seikyu.seikyu-sho._modal_exp_filter_form', [
    'midasisiteiOpts' => $midasisiteiOpts,
    'seikyusskOpts' => $seikyusskOpts,
    'printOtherOpts' => $printOtherOpts,
  ])

@include('seikyu.seikyu-sho._modal_preview_pdf')

@endsection
@section('js')
  <script src="{{ asset('assets/js/pdfjs/pdf.min.js') }}"></script>
  <script>
    var useAddFormFooter = null;

    var pageNumber = {{ request() -> get('page') ?? 1 }};
    var columns = @json($setting);

    $('#table').customTable({
      urlData: '{!! route('seikyu.seikyu_sho.exp.data_list', request()->query()) !!}',
      columns: columns,
      pageNumber: pageNumber,
      formSearch: $('#seikyuSearchForm'),
      pageSize: {{ config()->get('params.PAGE_SIZE') }},
      isShow: true,
    });

    $(function() {
      // prevent submit form when click enter keyboard
      $('#seikyuSearchForm').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
          e.preventDefault();
          return false;
        }
      });

      $('.datepicker').change(function() {
        autoFillDate(this);
      });

      //$('input[name=seikyu_sime_dt]').change(function(){
      $('button.btn-search').click(function(){
        //validate
        var dataSend = {
          seikyu_sime_dt : $('form#seikyuSearchForm input[name=seikyu_sime_dt]').val(),
          action : 'search',
        };

        $('form#seikyuSearchForm .group-input').removeClass('error');
        $('form#seikyuSearchForm .error_message span').html('');

        $.ajax({
          type: "POST",
          url: "{{ route('seikyu.seikyu_sho.exp.filterValidate') }}",
          data: dataSend,
          success: function (res) {
            $.fn.customTable.searchList();
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
      });

      // close modal preiview-pdf
      $('#modalPreviewPdfSeikyu').on('hidden.bs.modal', function (e) {
        $('#pdfContainer canvas').remove();
        $('#list-img img').remove();
      })

      var $div = $('<div />').appendTo('body');
      $div.attr('id', 'list-img');
    });

    function showModalExpFilter()
    {
      var selections    = $('#table').bootstrapTable('getSelections');
      var seikyuSimeDt  = $('#seikyuSearchForm input[name=seikyu_sime_dt]').val();
      var listNinusiCd  = [];

      if (selections.length == 0) {
        alert(@JSON(__('messages.E0022')));
        return
      }

      $.each(selections, function (key, item) {
       listNinusiCd.push(item.ninusi_cd);
      });

      $('#modalExpFilterSeikyu input[name=list_ninusi_cd]').val(listNinusiCd);
      $('#modalExpFilterSeikyu input[name=seikyu_sime_dt]').val(seikyuSimeDt);
      $('#modalExpFilterSeikyu').modal('show');
    }

    function expFile(e)
    {
      var form = $('#seikyuExport');
      var btn = e;
      $.ajax({
        url: '{{route('seikyu.seikyu_sho.exp.filterValidate')}}',
        method: 'POST',
        data: $('#seikyuExport').serialize(),
        success: function(res) {
          var settings = $('#table').data('customTableSettings');

          // Get query parameters from the Bootstrap Table
          var params = $('#seikyuExport').serializeArray();

          createFormToDownload(e, params);
        },
        error: function(jqXHR, textStatus, errorThrown) {
          //console.error('AJAX Error:', textStatus, errorThrown);
          if(jqXHR.status == 422) {
            $('.group-input').removeClass('error');
            $('.error_message span').html('');
            var errors = jqXHR.responseJSON.errors;
            $.each(errors, function(key, value) {
              key = key.replace('exp.', '');
              form.find('#error-'+key).parents('.group-input').addClass('error');
              form.find('#error-'+key).html(value);
            });
          }
        },
        complete: function() {

        }
      });
    }

    function createFormToDownload(e, params)
    {
      // Create a form for submitting the parameters to the export URL
      var form = $('<form>', {
            'action': $(e).data('href'),
            'method': 'POST',
            'target': '_blank' // Open the export URL in a new tab/window
          });

      // Append hidden input fields for each parameter
      $.each(params, function (key, item) {
        form.append($('<input>', {
          'type': 'hidden',
          'name': item.name,
          'value': item.value
        }));
      });

      form.append($('<input>', {
        'type': 'hidden',
        'name': '_token',
        'value': $('meta[name="csrf-token"]').attr('content')
      }));

      // Append the form to the body, submit it, and remove it
      form.appendTo('body').submit().remove();
    }

    function previewPdf(e)
    {
      loading();

      $.ajax({
        type: "POST",
        url: $(e).data('href'),
        data: $('#seikyuExport').serialize(),
        success: async function (res) {
          let pdfUrl = res.path;

          try {
            const pdf       = await pdfjsLib.getDocument(pdfUrl).promise;
            const numPages  = pdf.numPages;
            const container = document.getElementById('pdfContainer');

            for (let pageNum = 1; pageNum <= numPages; pageNum++) {
              const page = await pdf.getPage(pageNum);

              const canvas = document.createElement('canvas');
              canvas.setAttribute('id', 'page-' + pageNum);
              canvas.style.border = "2px solid #000000";
              const context = canvas.getContext('2d');

              const scale     = 1.7;
              const viewport  = page.getViewport({ scale: scale });
              canvas.width    = viewport.width;
              canvas.height   = viewport.height;
              container.appendChild(canvas);

              await page.render({
                  canvasContext: context,
                  viewport: viewport
              }).promise;
            }

            $('#pdfContainer canvas').each(function (index) {
              var img = $('<img>');
              img.attr('src', $(this)[0].toDataURL());
              img.appendTo('#list-img');
            });

            $('#list-img').addClass('d-none');

            loading(false);

            $('#modalPreviewPdfSeikyu').modal('show');

          } catch (error) {
            loading(false);
            console.error('Error rendering PDF:', error);
          }
        }
      });
    }

    function printPDF()
    {
      if (confirm("{{ __('messages.E0024') }}")) {
        // update flg
        let dataSend = {
          listNinusiCd: $('#modalExpFilterSeikyu input[name=list_ninusi_cd]').val(),
          seikyuSimeDt: $('#modalExpFilterSeikyu input[name=seikyu_sime_dt]').val(),
        };

        $.ajax({
          type: "POST",
          url: '{{ route('seikyu.seikyu_sho.exp.updateTSeikyu') }}',
          data: dataSend,
          success: function (res) {
            if (res.success) {
              window.print();
            } else {
              alert('Print Error');
              return;
            }
          }
        });
      }
    }
  </script>
@endsection
