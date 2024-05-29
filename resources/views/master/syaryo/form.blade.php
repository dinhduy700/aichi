@extends('layouts.master')
@section('css')
@endsection
@section('page-content')
  @php
    $formSearchSyaryoIndex = null;
    if (!empty($request->SyaryoIndex)) {
        $formSearchSyaryoIndex = $request->SyaryoIndex;
    }
    $syaryo = isset($syaryo) ? $syaryo : [];
    $listOption = config()->get('params.options.m_syaryo');
    $format = new App\Helpers\Formatter();
    $haisyaDt = $format->date(data_get($syaryo, 'haisya_dt', ''));
    if (!empty($syaryo)) {
        $syaryo['sekisai_jyuryo'] = $format->numberFormat($syaryo['sekisai_jyuryo'], -1, '');
        $syaryo['point'] = $format->numberFormat($syaryo['point'], -1, '');
        $syaryo['himoku_ritu'] = $format->numberFormat($syaryo['himoku_ritu'], -1, '');
    }
  @endphp
  <x-error-summary/>
  <div class="card form-custom form-master">
    <div class="card-body" style="position: relative;">
      <h4 class="card-title">登録/修正/削除画面</h4>
      <form method="post" id="formSyaryo"
            action="{{ !empty($syaryo) ? route('master.syaryo.update', ['syaryoCd' => $syaryo->syaryo_cd]) : route('master.syaryo.store') }}">
        {{ csrf_field() }}
        @if (!empty($formSearchSyaryoIndex))
          @foreach ($formSearchSyaryoIndex as $key => $value)
            <input type="hidden" name="SyaryoIndex[{{ $key }}]" value="{{ $value }}">
          @endforeach
        @endif
        <div class="form-group">
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">車両コード</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="text" class="form-control size-M" name="syaryo_cd" maxlength="255"
                         value="{{ data_get($syaryo, 'syaryo_cd', '') }}"
                  @if (!empty($syaryo)) {{ 'readonly' }} @endif>
                  <div class="error_message">
                    <span class=" text-danger" id="error-syaryo_cd"></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6"></div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">車種コード</label>
                <div class="col-12 col-md-10 group-input">
                  <div class="row p-15">
                    <input type="text" class="form-control size-S" name="syasyu_cd" maxlength="255"
                           value="{{ data_get($syaryo, 'syasyu_cd', '') }}"
                           onkeyup="suggestionForm(this, 'syasyu_cd', ['meisyo_cd', 'kana', 'meisyo_nm'], {meisyo_cd: 'syasyu_cd', meisyo_nm: 'syasyu_nm'}, $(this).parent())"
                           autocomplete="off">
                    <input type="text" class="form-control size-L" name="syasyu_nm" disabled
                           value="{{ data_get($syaryo, 'syasyu_nm', '') }}">
                    <ul class="suggestion modify-position-suggest"></ul>
                  </div>
                  <div class="error_message">
                    <span class=" text-danger" id="error-syasyu_cd"></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">自庸区分</label>
                <div class="col-12 col-md-10 group-input">
                  <select class="form-control size-M" name="jiyo_kbn">
                    <option value=""></option>
                    @foreach ($listOption['jiyo_kbn'] as $key => $name)
                      <option value="{{ $key }}" @selected(data_get($syaryo, 'jiyo_kbn', '') == $key)>{{ $name }}</option>
                    @endforeach
                  </select>
                  <div class="error_message">
                    <span class=" text-danger" id="error-jiyo_kbn"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">乗務員コード</label>
                <div class="col-12 col-md-10 group-input">
                  <div class="row p-15">
                    <input type="text" class="form-control size-M" name="jyomuin_cd" maxlength="255"
                           value="{{ data_get($syaryo, 'jyomuin_cd', '') }}"
                           onkeyup="suggestionForm(this, 'jyomuin_cd', ['jyomuin_cd', 'kana', 'jyomuin_nm'], {jyomuin_cd: 'jyomuin_cd', jyomuin_nm: 'jyomuin_nm'}, $(this).parent())"
                           autocomplete="off">
                    <input type="text" class="form-control size-L" name="jyomuin_nm" disabled
                           value="{{ data_get($syaryo, 'jyomuin_nm', '') }}">
                    <ul class="suggestion modify-position-suggest"></ul>
                  </div>
                  <div class="error_message">
                    <span class=" text-danger" id="error-jyomuin_cd"></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">庸車コード</label>
                <div class="col-12 col-md-10 group-input">
                  <div class="row p-15">
                    <input type="text" class="form-control size-L" name="yousya_cd" maxlength="255"
                           value="{{ data_get($syaryo, 'yousya_cd', '') }}"
                           onkeyup="suggestionForm(this, 'yousya_cd', ['yousya_cd', 'kana', 'yousya_ryaku_nm'], {yousya_cd: 'yousya_cd', yousya_ryaku_nm: 'yousya_ryaku_nm'}, $(this).parent() )"
                           autocomplete="off">
                    <input type="text" class="form-control size-2L" name="yousya_ryaku_nm" disabled
                           value="{{ data_get($syaryo, 'yousya_ryaku_nm', '') }}">
                    <ul class="suggestion modify-position-suggest"></ul>
                  </div>
                  <div class="error_message">
                    <span class=" text-danger" id="error-yousya_cd"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">部門コード</label>
                <div class="col-12 col-md-10 group-input">
                  <div class="row p-15">
                    <input type="text" class="form-control size-M" name="bumon_cd" maxlength="255"
                           value="{{ data_get($syaryo, 'bumon_cd', '') }}"
                           onkeyup="suggestionForm(this, 'bumon_cd', ['bumon_cd', 'kana', 'bumon_nm'], {bumon_cd: 'bumon_cd', bumon_nm: 'bumon_nm'}, $(this).parent() )"
                           autocomplete="off">
                    <input type="text" class="form-control size-L" name="bumon_nm" disabled
                           value="{{ data_get($syaryo, 'bumon_nm', '') }}">
                    <ul class="suggestion modify-position-suggest"></ul>
                  </div>
                  <div class="error_message">
                    <span class=" text-danger" id="error-bumon_cd"></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">積載区分</label>
                <div class="col-12 col-md-10 group-input">
                  <select class="form-control size-L" name="sekisai_kbn">
                    <option value=""></option>
                    @foreach ($listOption['sekisai_kbn'] as $key => $name)
                      <option value="{{ $key }}" @selected(data_get($syaryo, 'sekisai_kbn', '') == $key)>{{ $name }}</option>
                    @endforeach
                  </select>
                  <div class="error_message">
                    <span class=" text-danger" id="error-sekisai_kbn"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">積載重量</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="text" class="form-control size-L" name="sekisai_jyuryo"
                         value="{{ data_get($syaryo, 'sekisai_jyuryo', '') }}">
                  <div class="error_message">
                    <span class=" text-danger" id="error-sekisai_jyuryo"></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">ポイント</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="text" class="form-control size-M" name="point"
                         value="{{ data_get($syaryo, 'point', '') }}">
                  <div class="error_message">
                    <span class=" text-danger" id="error-point"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">費目計算用率</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="text" class="form-control size-M" name="himoku_ritu"
                         value="{{ data_get($syaryo, 'himoku_ritu', '') }}">
                  <div class="error_message">
                    <span class=" text-danger" id="error-himoku_ritu"></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">廃車日付</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="text" class="form-control size-L datepicker" name="haisya_dt"
                         value="{{ $haisyaDt }}">
                  <div class="error_message">
                    <span class=" text-danger" id="error-haisya_dt"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">陸運支局コード</label>
                <div class="col-12 col-md-10 group-input">
                  <div class="row p-15">
                    <input type="text" class="form-control size-M" name="rikuun_cd" maxlength="255"
                           value="{{ data_get($syaryo, 'rikuun_cd', '') }}"
                           onkeyup="suggestionForm(this, 'rikuun_cd', ['meisyo_cd', 'kana', 'meisyo_nm'], {meisyo_cd: 'rikuun_cd', meisyo_nm: 'rikuun_nm'}, $(this).parent() )"
                           autocomplete="off">
                    <input type="text" class="form-control size-2L" name="rikuun_nm" disabled
                           value="{{ data_get($syaryo, 'rikuun_nm', '') }}">
                    <ul class="suggestion modify-position-suggest"></ul>
                  </div>
                  <div class="error_message">
                    <span class=" text-danger" id="error-rikuun_cd"></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">種別</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="text" class="form-control size-S" name="car_number_syubetu" maxlength="255"
                         value="{{ data_get($syaryo, 'car_number_syubetu', '') }}">
                  <div class="error_message">
                    <span class=" text-danger" id="error-car_number_syubetu"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">かな</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="text" class="form-control size-S" name="car_number_kana" maxlength="255"
                         value="{{ data_get($syaryo, 'car_number_kana', '') }}">
                  <div class="error_message">
                    <span class=" text-danger" id="error-car_number_kana"></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">ナンバー</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="text" class="form-control size-M" name="car_number" maxlength="255"
                         value="{{ data_get($syaryo, 'car_number', '') }}">
                  <div class="error_message">
                    <span class=" text-danger" id="error-car_number"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">配車備考</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="text" class="form-control size-L" name="haisya_biko" maxlength="255"
                         value="{{ data_get($syaryo, 'haisya_biko', '') }}">
                  <div class="error_message">
                    <span class=" text-danger" id="error-haisya_biko"></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">備考</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="text" class="form-control size-5L" name="biko" maxlength="255"
                         value="{{ data_get($syaryo, 'biko', '') }}">
                  <div class="error_message">
                    <span class=" text-danger" id="error-biko"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">休眠フラグ</label>
                <div class="col-12 col-md-6 group-input">
                  <select class="form-control size-S" name="kyumin_flg">
                    @foreach ($listOption['kyumin_flg'] as $key => $name)
                      <option value="{{ $key }}" @selected(data_get($syaryo, 'kyumin_flg', '0') == $key)>{{ $name }}</option>
                    @endforeach
                  </select>
                  <div class="error_message">
                    <span class=" text-danger" id="error-kyumin_flg"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="button-form">
          <button class="btn btn-back min-wid-110" id="backButton" data-href="{{ route('master.syaryo.index') }}"
                  onclick="redirectBack(this, 'SyaryoIndex')" type="button">{{ trans('app.labels.btn-back') }}</button>
          <button class="btn btn-insert min-wid-110" type="button" onclick="submitForm(this)">
            @if (!empty($syaryo))
              {{ trans('app.labels.btn-update') }}
            @else
              {{ trans('app.labels.btn-insert') }}
            @endif
          </button>
          @if (!empty($syaryo))
            <button class="btn btn-delete min-wid-110" type="button"
                    onclick="deleteData(this)">{{ trans('app.labels.btn-delete') }}</button>
          @endif
        </div>
      </form>
    </div>
  </div>
@endsection

@section('js')
  <script>
    $('.datepicker').datepicker({
      "locale": "ja"
    });
    @if (!empty($syaryo))
      function handleDelete() {
        $.ajax({
          url: '{{ route('master.syaryo.destroy', ['syaryoCd' => $syaryo->syaryo_cd]) }}',
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
    $('#formSyaryo').append(tbl);

    $(document).ready(function() {
      $('form').find('select, input').change(function() {
        hasChangeData = true;
      });
    });
  </script>
@endsection
