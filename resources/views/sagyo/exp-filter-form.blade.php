@extends('layouts.master')
@section('css')
  <style>
    .input-group-prepend .input-group-text {
      color: initial;
    }

    div[name=tyohyokbns] div.col-auto:last-child {
      margin-left: 11px;
    }
   
  </style>
@endsection
@section('page-content')
  @php
    $errorClass = ["error_message", "mb-0"];
    $rowGroupClass = ["col-12", "row", "ml-0"];
    $columns = [
        'bumon_cd' => $bumon = [
          'suggestion_show' => [
            'bumon_cd',
            'kana',
            'bumon_nm',

          ],
        ],
        'bumon_nm' => $bumon,

        'jyomuin_cd' => $jyomuin = [
          'suggestion_show' => [
            'jyomuin_cd',
            'kana',
            'jyomuin_nm',

          ],
        ],
        'jyomuin_nm' => $jyomuin,

        'yousya_cd' => $yousya = [
          'suggestion_show' => [
            'yousya_cd',
            'kana',
            'yousya_ryaku_nm',

          ],
        ],
        'yousya_nm' => $yousya,
    ];

    $bumonCdFrom  = $initValues['bumon_cd_from'] ?? '';
    $bumonCdTo    = $initValues['bumon_cd_to'] ?? '';
    $bumonNmFrom  = $initValues['bumon_nm_from'] ?? '';
    $bumonNmTo    = $initValues['bumon_nm_to'] ?? '';
  @endphp
  <div class="card form-custom">
    <div class="card-body">
      <form id="sagyoExport" target="_blank" method="post">
        @csrf
        <input type="hidden" name="mode_save_muser_pg_function" value="">

        <div class="form-group row">
          <div class="col">
            <button class="btn btn-primary min-wid-110 btn-xls-export" type="button" onclick="expFile(this, 'xls')"
                    href="{{ route('sagyo.exp.xls') }}">EXCEL出力
            </button>
            <button class="btn btn-primary min-wid-110 btn-xls-export" type="button" onclick="expFile(this, 'pdf')"
                    href="{{ route('sagyo.exp.pdf') }}">プレビュー
            </button>
            <button class="btn btn-primary min-wid-110 btn-csv-export" type="button" onclick="expFile(this, 'csv')"
                    href="{{ route('sagyo.exp.csv') }}">ファイル出力
            </button>
          </div>
        </div>

        <div class="form-group row mb-0">
          <div @class($rowGroupClass)>
            <div class="blockquote col-12 col-lg-12">
              <div class="row">
                <label class="col-form-label col-12 col-lg-1">配車部門</label>
                <div class="col-auto d-flex flex-column">
                  <div class="group-input">
                    @php
                      $cdAttrs = [
                        'name' => 'exp[bumon_cd_from]', 'base' => 'bumon_cd', 'id' => 'bumon_cd_from',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[bumon_cd_from]', 'exp[bumon_nm_from]')",
                        'maxlength' => 4, 'value' => $bumonCdFrom,
                      ];
                      $nmAttrs = [
                        'name' => 'exp[bumon_nm_from]', 'base' => 'bumon_nm', 'id' => 'bumon_nm_from',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[bumon_cd_from]', 'exp[bumon_nm_from]')",
                        'maxlength' => 20, 'value' => $bumonNmFrom
                      ];
                    @endphp
                    <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs">
                      <x-slot:prepend>
                        <div class="input-group-prepend">
                          <span class="input-group-text">開始</span>
                        </div>
                        </x-slot>
                    </x-suggestion-cd-name>
                    <div @class($errorClass)><span class=" text-danger" id="error-bumon_cd_from"></span></div>
                  </div>
                  <div class="group-input mt-3">
                    @php
                      $cdAttrs = [
                        'name' => 'exp[bumon_cd_to]', 'base' => 'bumon_cd', 'id' => 'bumon_cd_to',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[bumon_cd_to]', 'exp[bumon_nm_to]')",
                        'maxlength' => 4, 'value' => $bumonCdTo
                      ];
                      $nmAttrs = [
                        'name' => 'exp[bumon_nm_to]', 'base' => 'bumon_nm', 'id' => 'bumon_nm_to',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[bumon_cd_to]', 'exp[bumon_nm_to]')",
                        'maxlength' => 20, 'value' => $bumonNmTo
                      ];
                    @endphp
                    <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs">
                      <x-slot:prepend>
                        <div class="input-group-prepend">
                          <span class="input-group-text">終了</span>
                        </div>
                        </x-slot>
                    </x-suggestion-cd-name>
                    <div @class($errorClass)><span class=" text-danger" id="error-bumon_cd_to"></span></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div @class($rowGroupClass)>
            <div class="blockquote col-12 col-lg-12">
              <div class="row">
                <label class="col-form-label col-12 col-lg-1 mb-0">帳票区分</label>
                <div class="col-auto col-lg-11 d-flex" name="tyohyokbns">
                  @foreach($tyohyokbnJyomuinOpts as $key => $opt)
                    <div class="form-check col-auto justify-content-start align-self-center">
                      <label class="form-check-label mb-0">
                        <input type="radio" class="form-check-input" name="exp[tyohyokbn]"
                               value="{{ $key }}" @checked($key==($initValues['tyohyokbn'] ?? '1'))>
                        {{ $opt['text'] }}
                      </label>
                    </div>
                  @endforeach
                  <div id="area_tyohyokbn_jyomuin_modal">
                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#jyomuinModal">
                      自社コメント
                    </button>
                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#yousyaModal">
                      庸車コメント
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div @class($rowGroupClass)>
            <div class="blockquote col-12 col-lg-12">
              <div class="row">
                <label class="col-form-label col-12 col-lg-1 mb-0">出力方法</label>
                <div class="col-auto col-lg-11 d-flex">
                  @foreach($injiGroupOpts as $key => $opt)
                    <div class="form-check col-auto justify-content-start align-self-center">
                      <label class="form-check-label mb-0">
                        <input type="radio" class="form-check-input" name="exp[inji_group]"
                                         value="{{ $key }}" @checked($key==($initValues['inji_group'] ?? '1'))>
                        {{ $opt['text'] }}
                      </label>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>

          <div @class($rowGroupClass)>
            <div class="blockquote col-12 col-lg-6">
              <div class="row">
                <label class="col-form-label col-12 col-lg-2">日付</label>
                <div class="col-auto d-flex">
                  <div class="group-input">
                    <input type="text" class="form-control size-L datepicker" name="exp[dt_from]"
                            value="">
                    <div @class($errorClass)><span class=" text-danger" id="error-dt_from"></span></div>
                  </div>
                  <span class="col-form-label px-2"> ～ </span>
                  <div class="group-input">
                    <input type="text" class="form-control size-L datepicker" name="exp[dt_to]"
                            value="">
                    <div @class($errorClass)><span class=" text-danger" id="error-dt_to"></span></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="blockquote col-12 col-lg-6">
              <div class="row">
                <label class="col-form-label col-12 col-lg-2">運転者</label>
                <div class="col-auto d-flex flex-column">
                  <div class="group-input">
                    @php
                      $cdAttrs = [
                        'name' => 'exp[jyomuin_cd_from]', 'base' => 'jyomuin_cd',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[jyomuin_cd_from]', 'exp[jyomuin_nm_from]')",
                      ];
                      $nmAttrs = [
                        'name' => 'exp[jyomuin_nm_from]', 'base' => 'jyomuin_nm',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[jyomuin_cd_from]', 'exp[jyomuin_nm_from]')",
                      ];
                    @endphp
                    <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs">
                      <x-slot:prepend>
                        <div class="input-group-prepend">
                          <span class="input-group-text">開始</span>
                        </div>
                        </x-slot>
                    </x-suggestion-cd-name>
                    <div @class($errorClass)><span class=" text-danger" id="error-jyomuin_cd_from"></span></div>
                  </div>
                  <div class="group-input mt-3">
                    @php
                      $cdAttrs = [
                        'name' => 'exp[jyomuin_cd_to]', 'base' => 'jyomuin_cd',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[jyomuin_cd_to]', 'exp[jyomuin_nm_to]')",
                      ];
                      $nmAttrs = [
                        'name' => 'exp[jyomuin_nm_to]', 'base' => 'jyomuin_nm',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[jyomuin_cd_to]', 'exp[jyomuin_nm_to]')",
                      ];
                    @endphp
                    <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs">
                      <x-slot:prepend>
                        <div class="input-group-prepend">
                          <span class="input-group-text">終了</span>
                        </div>
                        </x-slot>
                    </x-suggestion-cd-name>
                    <div @class($errorClass)><span class=" text-danger" id="error-jyomuin_cd_to"></span></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div @class($rowGroupClass)>
            <div class="blockquote col-12 col-lg-12">
              <div class="row">
                <label class="col-form-label col-12 col-lg-1">庸車先</label>
                <div class="col-auto d-flex flex-column">
                  <div class="group-input">
                    @php
                      $cdAttrs = [
                        'name' => 'exp[yousya_cd_from]', 'base' => 'yousya_cd',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[yousya_cd_from]', 'exp[yousya_nm_from]')",
                      ];
                      $nmAttrs = [
                        'name' => 'exp[yousya_nm_from]', 'base' => 'yousya_nm',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[yousya_cd_from]', 'exp[yousya_nm_from]')",
                      ];
                    @endphp
                    <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs">
                      <x-slot:prepend>
                        <div class="input-group-prepend">
                          <span class="input-group-text">開始</span>
                        </div>
                        </x-slot>
                    </x-suggestion-cd-name>
                    <div @class($errorClass)><span class=" text-danger" id="error-yousya_cd_from"></span></div>
                  </div>
                  <div class="group-input mt-3">
                    @php
                      $cdAttrs = [
                        'name' => 'exp[yousya_cd_to]', 'base' => 'yousya_cd',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[yousya_cd_to]', 'exp[yousya_nm_to]')",
                      ];
                      $nmAttrs = [
                        'name' => 'exp[yousya_nm_to]', 'base' => 'yousya_nm',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[yousya_cd_to]', 'exp[yousya_nm_to]')",
                      ];
                    @endphp
                    <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs">
                      <x-slot:prepend>
                        <div class="input-group-prepend">
                          <span class="input-group-text">終了</span>
                        </div>
                        </x-slot>
                    </x-suggestion-cd-name>
                    <div @class($errorClass)><span class=" text-danger" id="error-yousya_cd_to"></span></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div @class($rowGroupClass)>
            <div class="blockquote col-12 col-lg-12">
              <div class="row">
                <label class="col-form-label col-12 col-lg-1 mb-0">オプション</label>
                <div class="col-auto col-lg-11">
                  @foreach($printOtherOpts as $key => $opt)
                    <div class="form-check col-auto justify-content-start align-self-center">
                      <label class="form-check-label mb-0">
                        <input type="checkbox" class="form-check-input" name="exp[print_other][]" value="{{ $key }}">
                        {{ $opt['text'] }}
                      </label>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
        
        @include('sagyo.__modal', [
          'idModal' => 'jyomuinModal',
          'title'   => '作業指示書',
          'key'     => $EXP_TYOHYO_KBN_JYOMUIN_CD,
          'onClick' => "saveMUserPg(this, 'jyomuinModal', $EXP_TYOHYO_KBN_JYOMUIN_CD)",
        ])
        @include('sagyo.__modal', [
          'idModal' => 'yousyaModal',
          'title'   => '業務依頼書',
          'key'     => $EXP_TYOHYO_KBN_YOUSYA_CD,
          'onClick' => "saveMUserPg(this, 'yousyaModal', $EXP_TYOHYO_KBN_YOUSYA_CD)",
        ])
      </form>
    </div>
  </div>



@endsection
@section('js')
  <script src="{{ asset('assets/custom/export.js') }}"></script>
  <script>
    $(function() {
      exportJs.formId = 'sagyoExport';
      exportJs.suggestColumns = @json($columns);
      exportJs.urls.masterSuggestion = '{!! route('master-suggestion') !!}';
      exportJs.urls.validate = '{!! route('sagyo.exp.filterValidate') !!}';

      $('.datepicker').change(function() {
        autoFillDate(this);
      });

      var tbl = $('<div>').attr({id: 'table'});
      tbl.data('customTableSettings', {urlSearchSuggestion:exportJs.urls.masterSuggestion});
      $('#' + exportJs.formId).append(tbl);
      
      // init data modal
      $.ajax({
        type: "POST",
        url: "{{ route('sagyo.exp.initDataModalMUserPg') }}",
        success: function (res) {
          var record = res.record;
          $('#sagyoExport input[name=mode_save_muser_pg_function]').val(res.mode);
          $.each( res.config, function( key, item ) {
            var col1 = item.fieldsUserPgFunc[0];
            var col2 = item.fieldsUserPgFunc[1];
            var col3 = item.fieldsUserPgFunc[2];
            var col4 = item.fieldsUserPgFunc[3];

            $('#sagyoExport input[name="exp[tyohyokbn_modal]['+key+'][upper]"]').val(record[col1]);
            $('#sagyoExport input[name="exp[tyohyokbn_modal]['+key+'][middle]"]').val(record[col2]);
            $('#sagyoExport input[name="exp[tyohyokbn_modal]['+key+'][bottom]"]').val(record[col3]);
            $('#sagyoExport input[name="exp[tyohyokbn_modal]['+key+'][under_title]"]').val(record[col4]);
          });
        }
      });
    });


    function expFile(e, type)
    {
      var isDownloadAll = false;

      if (type == 'xls' && $("input:radio[name='exp[tyohyokbn]']:checked").val() == '{{ $EXP_TYOHYO_KBN_ALL }}') {
        isDownloadAll = true;
      }

      mainExp(e, exportJs.formId, exportJs.urls.validate, isDownloadAll);
    }

    function mainExp(e, formId, urlValidate, isDownloadAll)
    {
      var form = $('#' + formId);
      var btn = $(e);
      $('.group-input').removeClass('error');
      $('.error_message span').html('');

      $.ajax({
        url: urlValidate,
        method: 'POST',
        data: form.serialize(),
        success: function(res) {
          if (res.sendForward == 'no') {
            alert(res.message);
            return;
          }

          if (!isDownloadAll) {
            form.attr('action', btn.attr('href')).submit();
          } else {
            createFormToDownload(btn.attr('href'), '{{ $EXP_TYOHYO_KBN_JYOMUIN_CD }}'); // 2 download jyomuin_cd
            createFormToDownload(btn.attr('href'), '{{ $EXP_TYOHYO_KBN_YOUSYA_CD }}'); // 3 download yousya_cd
          }

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

    function expSuggestionKeyup(e, fieldCd, fieldNm){
      exportJs.expSuggestionKeyup(e, fieldCd, fieldNm);
    }

    function saveMUserPg(e, idModal, key)
    {
      var upper                   = $('#' + idModal + ' input[name="exp[tyohyokbn_modal]['+key+'][upper]"]').val();
      var middle                  = $('#' + idModal + ' input[name="exp[tyohyokbn_modal]['+key+'][middle]"]').val();
      var bottom                  = $('#' + idModal + ' input[name="exp[tyohyokbn_modal]['+key+'][bottom]"]').val();
      var underTitle              = $('#' + idModal + ' input[name="exp[tyohyokbn_modal]['+key+'][under_title]"]').val();
      var mode                    = $('#sagyoExport input[name=mode_save_muser_pg_function]').val();

      var dataSend = {
        key                   : key,
        upper                 : upper,
        middle                : middle,
        bottom                : bottom,
        underTitle            : underTitle,
        mode                  : mode,
      };

      $.ajax({
        type: "POST",
        url: $(e).data('href'),
        data: dataSend,
        success: function (res) {
          if (res.success) {
            $('#sagyoExport input[name="exp[tyohyokbn_modal]['+ key +'][upper]"]').val(upper);
            $('#sagyoExport input[name="exp[tyohyokbn_modal]['+ key +'][middle]"]').val(middle);
            $('#sagyoExport input[name="exp[tyohyokbn_modal]['+ key +'][bottom]"]').val(bottom);
            $('#sagyoExport input[name="exp[tyohyokbn_modal]['+ key +'][under_title]"]').val(underTitle);

            $('#sagyoExport input[name=mode_save_muser_pg_function]').val('edit');
          }
          $('#' + idModal).modal('hide');
        }
      });
    }

    function createFormToDownload(url, keyDownload)
    {
      var form = $('<form>', {
            'action': url,
            'method': 'POST',
            'target': '_blank'
          });

      form.append($('<input>', {
        'type': 'hidden',
        'name': '_token',
        'value': $('meta[name="csrf-token"]').attr('content')
      }));

      var inputs = $('input[type!="radio"][type!="checkbox"], select');

      inputs.each(function () {
        form.append($('<input>', {
          'type': 'hidden',
          'name': $(this).attr('name'),
          'value': $(this).val()
        }))
      });

      var checkboxs = $('input[type="checkbox"]:checked');

      checkboxs.each(function () {
        form.append($('<input>', {
          'type': 'hidden',
          'name': $(this).attr('name'),
          'value': $(this).val()
        }))
      });

      var radio = $('input[name="exp[inji_group]"]:checked');
      form.append($('<input>', {
        'type': 'hidden',
        'name': radio.attr('name'),
        'value': radio.val()
      }));

      form.append($('<input>', {
        'type': 'hidden',
        'name': 'exp[tyohyokbn]',
        'value': keyDownload
      }));

      form.appendTo('body').submit().remove();
    }
  </script>
@endsection
