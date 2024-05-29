@extends('layouts.master')
@section('css')
  <style>
    .p-15 {
      padding: 0px 15px;
    }
    span.range {
      margin-left: 0 !important; margin-right: 0 !important;
      padding-left: 0.5rem !important; padding-right: 0.5rem !important;
      background: none;
      border: none;
      width: fit-content;
    }
  </style>
@endsection
@section('page-content')
  @php
    $formSearchNinusiIndex = null;
    if (!empty($request->NinusiIndex)) {
        $formSearchNinusiIndex = $request->NinusiIndex;
    }
    $listOption = config()->get('params.options.m_ninusi');
    $listRow = [
        [
            [
              'name' => 'ninusi_cd', 'key' => 1, 'size' => 'L',
              'default' => getMaxCodeField(app(\App\Models\MNinusi::class)->getTable(), 'ninusi_cd') + 1
            ],
            []
        ],
        [['name' => 'kana', 'size' => 'L'], []],
        [['name' => 'ninusi1_nm', 'size' => '2L'], []],
        [['name' => 'ninusi2_nm', 'size' => '2L'], ['name' => 'ninusi_ryaku_nm', 'size' => 'L']],
        [[], ['name' => 'bumon_cd', 'suggest' => 'bumon_nm', 'size' => ['cd'=>'M', 'nm'=>'L']]],
        [['name' => 'yubin_no', 'size' => 'L'], []],
        [['name' => 'jyusyo1_nm', 'size' => '2L'], ['name' => 'jyusyo2_nm', 'size' => '2L']],
        [['name' => 'tel', 'size' => '2L'], ['name' => 'fax', 'size' => '2L']],
        [['name' => 'seikyu_kbn', 'select' => 1, 'size' => 'L'], ['name' => 'seikyu_cd', 'suggest' => 'seikyu_nm', 'size' => ['cd'=>'L', 'nm'=>'2L']]],
        [['name' => 'seikyu_mu_kbn', 'select' => 1, 'size' => 'L'], []],
        [['name' => 'simebi1', 'size' => 'S'], ['name' => 'simebi2', 'size' => 'S']],
        [['name' => 'simebi3', 'size' => 'S'], []],
        [['name' => 'mikakutei_seigyo_kbn', 'select' => 1, 'size' => 'L'], []],
        [['name' => 'kin_hasu_kbn', 'select' => 1, 'size' => 'L'], ['name' => 'kin_hasu_tani', 'select' => 1, 'size' => 'M']],
        [['name' => 'zei_keisan_kbn', 'select' => 1, 'size' => 'L'], []],
        [['name' => 'zei_hasu_kbn', 'select' => 1, 'size' => 'L'], ['name' => 'zei_hasu_tani', 'select' => 1, 'size' => 'M']],
        [['name' => 'urikake_saki_cd', 'suggest' => 'urikake_saki_nm', 'size' => ['cd'=>'L', 'nm'=>'2L']], ['name' => 'nyukin_umu_kbn', 'select' => 1, 'size' => 'L']],
        [['name' => 'kaisyu1_dd', 'size' => 'S'], ['name' => 'kaisyu2_dd', 'size' => 'S']],
        [['name' => 'comennt', 'size' => '5L'], ['name' => 'seikyu_teigi_no', 'select' => 1, 'size' => 'L']],
        [['name' => 'unchin_teigi_no', 'size' => 'L'], ['name' => 'kensaku_kbn', 'select' => 1, 'size' => '2L', 'prompt' => false]],
        [['name' => 'unso_bi_kbn', 'select' => 1, 'size' => 'M'], []],
        [
          ['name' => 'nebiki_ritu', 'size' => 'M', 'append' => '<span class="col-form-label text-nowrap">%</span>'],
          ['name' => 'nebiki_hasu_kbn', 'select' => 1, 'size' => 'L']
        ],
        [['name' => 'nebiki_hasu_tani', 'select' => 1, 'size' => 'M'], []],
        [['name' => 'mail', 'mail' => 1, 'size' => '3L'], ['name' => 'okurijyo_hako_kbn', 'select' => 1, 'size' => 'M']],
        [['name' => 'biko', 'size' => '5L'], []],
        [['name' => 'kyumin_flg', 'select' => 1, 'default' => 0, 'size' => 'S', 'prompt' => false], []],
    ];
    $ninusi = isset($ninusi) ? $ninusi : [];
    $columns = [
        'bumon_cd' => [
            'suggestion_show' => ['bumon_cd', 'kana', 'bumon_nm'],
            'suggestion_change' => [
                'field_cd' => 'bumon_cd',
                'field_nm' => 'bumon_nm',
            ],
        ],
        'seikyu_cd' => [
            'suggestion_show' => ['ninusi_cd', 'kana', 'ninusi_ryaku_nm'],
            'suggestion_change' => [
                'field_cd' => 'seikyu_cd',
                'field_nm' => 'seikyu_nm',
            ],
        ],
        'urikake_saki_cd' => [
            'suggestion_show' => ['ninusi_cd', 'kana', 'ninusi_ryaku_nm'],
            'suggestion_change' => [
                'field_cd' => 'urikake_saki_cd',
                'field_nm' => 'urikake_saki_nm',
            ],
        ],
        'soko_bumon_cd' => [
            'suggestion_show' => ['bumon_cd', 'kana', 'bumon_nm'],
            'suggestion_change' => [
                'field_cd' => 'soko_bumon_cd',
                'field_nm' => 'soko_bumon_nm',
            ],
        ],
        'soko_seikyu_cd' => [
            'suggestion_show' => ['ninusi_cd', 'kana', 'ninusi_ryaku_nm'],
            'suggestion_change' => [
                'field_cd' => 'soko_seikyu_cd',
                'field_nm' => 'soko_seikyu_nm',
            ],
        ],
    ];
  @endphp
  <x-error-summary/>
  <div class="card form-custom form-master">
    <div class="card-body" style="position: relative;">
      <h4 class="card-title">登録/修正/削除画面</h4>

      <form method="post" id="formNinusi"
        action="{{ !empty($ninusi) ? route('master.ninusi.update', ['ninusiCd' => $ninusi->ninusi_cd]) : route('master.ninusi.store') }}">
        {{ csrf_field() }}
        @if (!empty($formSearchNinusiIndex))
          @foreach ($formSearchNinusiIndex as $key => $value)
            <input type="hidden" name="NinusiIndex[{{ $key }}]" value="{{ $value }}">
          @endforeach
        @endif
        @foreach ($listRow as $rows)
          @if (is_array($rows))
            <div class="form-group">
              <div class="row">
                @foreach ($rows as $key => $value)
                  <div class="col-12 col-md-6">
                    @if (count($value))
                      <div class="row">
                        <label
                          class="col-12 col-md-3 col-form-label text-nowrap">{{ trans("attributes.m_ninusi.{$value['name']}") }}</label>
                        <div class="col-12 col-md-9 group-input">
                          @if (isset($value['select']))
                            <select class="form-control size-{{ $value['size'] }}"
                              name="{{ $value['name'] }}">
                              @if(data_get($value, 'prompt', true))<option value=""></option>@endif
                              @foreach ($listOption[$value['name']] as $key => $name)
                                <option value="{{ $key }}" @selected(data_get($ninusi, $value['name'], isset($value['default']) ? $value['default'] : '') == $key)>
                                  {{ $name }}</option>
                              @endforeach
                            </select>
                          @elseif(isset($value['suggest']))
                            <div class="row p-15">
                              <input type="text" class="form-control size-{{ data_get($value, "size.cd", 'M') }}" autocomplete="off"
                                     name="{{ $value['name'] }}"
                                     id="{{ $value['name'] }}" maxlength="255"
                                     value="{{ old($value['name'], data_get($ninusi, $value['name'], '')) }}"
                                     onkeyup="eSuggestionKeyup(this)">
                              <input type="text" class='form-control size-{{ data_get($value, "size.nm", '2L') }}' readonly
                                value="{{ data_get($ninusi, $value['suggest'], '') }}"
                                id='{{ $value['suggest'] }}'></input>
                              <ul class="suggestion modify-position-suggest"></ul>
                            </div>
                          @else
                            @php
                            $isInputGroup = collect($value)->only(['append'])->count();
                            @endphp
                            @if($isInputGroup)<div class="form-inline">@endif
                            <input type="text" class="form-control size-{{ $value['size'] }}"
                              name="{{ $value['name'] }}" maxlength="255"
                              value="{{ old($value['name'], empty($ninusi) ? @$value['default'] : data_get($ninusi, $value['name'], '')) }}"
                              @if (isset($value['key'])) required @if (!empty($ninusi)) readonly @endif
                              @endif>
                            @if($isInputGroup)
                            {!! $value['append'] !!}
                            </div>
                            @endif
                          @endif
                          <div class="error_message">
                            <span class=" text-danger" id="error-{{ $value['name'] }}"></span>
                          </div>
                        </div>
                      </div>
                    @endif
                  </div>
                @endforeach
              </div>
            </div>
          @else
            <div class="break-form"></div>
          @endif
        @endforeach

        @include('master.ninusi.form-soko')

        <div class="button-form">
          <button class="btn btn-back min-wid-110" id="backButton" data-href="{{ route('master.ninusi.index') }}"
            onclick="redirectBack(this, 'NinusiIndex')" type="button">{{ trans('app.labels.btn-back') }}</button>
          <button class="btn btn-insert min-wid-110" type="button" onclick="submitForm(this)">
            @if (!empty($ninusi))
              {{ trans('app.labels.btn-update') }}
            @else
              {{ trans('app.labels.btn-insert') }}
            @endif
          </button>
          @if (!empty($ninusi))
            <button class="btn btn-delete min-wid-110" type="button"
              onclick="deleteData(this)">{{ trans('app.labels.btn-delete') }}</button>
          @endif
        </div>
    </div>
    </form>
    <div class="popup-confirm"></div>
  @endsection

  @section('js')
    <script>
      @if (!empty($ninusi))
        function handleDelete() {
          $.ajax({
            url: '{{ route('master.ninusi.destroy', ['ninusiCd' => $ninusi->ninusi_cd]) }}',
            method: 'DELETE',
            data: {},
            success: function(res) {
              if (res.status == 200) {
                $('#backButton').click();
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
      @endif

      var tbl = $('<div>').attr({
        id: 'table'
      });
      tbl.data('customTableSettings', @json(['urlSearchSuggestion' => route('master-suggestion')]));
      $('#formNinusi').append(tbl);

      var columns = @json($columns);

      function eSuggestionKeyup(e) {
        var currentInput = $(e);
        var field = $(e).attr('id');
        suggestionForm(
          currentInput, field,
          columns[field]['suggestion_show'],
          '',
          currentInput.closest('div.row'),
          function(selected, field, fieldShow, $row) {
            $('#' + columns[field]['suggestion_change']['field_cd']).val($(selected).data(fieldShow[0]));
            $('#' + columns[field]['suggestion_change']['field_nm']).val($(selected).data(fieldShow[2]));
          }
        );
      }

      $(function() {
        $("[name=kisei_kbn]").on( "change", function() {
          var items = ["ki1_from", "ki1_to", "ki2_from", "ki2_to", "ki3_from", "ki3_to"];
          $.each(items, function( index, value ) {
            $("[name="+value+"]").prop('disabled', false);
          });
          switch ($(this).val()) {
            case '0': items = ["ki1_from", "ki1_to", "ki2_from", "ki2_to", "ki3_from", "ki3_to"]; break;
            case '1': items = ["ki2_from", "ki2_to", "ki3_from", "ki3_to"]; break;
            case '2': items = ["ki3_from", "ki3_to"]; break;
            default: items = [];
          }
          $.each(items, function( index, value ) {
            $("[name="+value+"]").prop('disabled', true).val('');
          });
        });
        $("[name=kisei_kbn]").trigger('change');

        $('form').find('select, input').change(function() {
          hasChangeData = true;
        });
      });
    </script>
  @endsection
