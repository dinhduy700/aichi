@extends('layouts.master')
@section('css')
  <style>

  </style>
@endsection
@section('page-content')
  @php
    $formSearchJyomuinIndex = null;
    if (!empty($request->JyomuinIndex)) {
        $formSearchJyomuinIndex = $request->JyomuinIndex;
    }

  @endphp
  <x-error-summary/>
  <div class="card form-custom form-master">
    <div class="card-body" style="position: relative;">
      <h4 class="card-title">
        @if (!empty($jyomuin))
          {{ trans('app.screen.master.jyomuin.edit') }}@else{{ trans('app.screen.master.jyomuin.create') }}
        @endif
      </h4>
      <form method="POST"
        action="{{ !empty($jyomuin) ? route('master.jyomuin.update', ['jyomuinCd' => urlencode($jyomuin->jyomuin_cd)]) : route('master.jyomuin.store') }}">
        {{ csrf_field() }}
        @if (!empty($formSearchJyomuinIndex))
          @foreach ($formSearchJyomuinIndex as $key => $value)
            <input type="hidden" name="JyomuinIndex[{{ $key }}]" value="{{ $value }}">
          @endforeach
        @endif
        <div class="form-group">
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">乗務員コード</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="text" maxlength="255" name="jyomuin_cd" class="form-control size-M"
                    value="{{ old('jyomuin_cd', !empty($jyomuin) ? $jyomuin->jyomuin_cd
                        : getMaxCodeField(app(\App\Models\MJyomuin::class)->getTable(), 'jyomuin_cd') + 1) }}"
                    @if (!empty($jyomuin)) {{ 'readonly' }} @endif>
                  <div class="error_message">
                    <span class=" text-danger" id="error-jyomuin_cd"></span>
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
                <label class="col-12 col-md-2 col-form-label text-nowrap ">ヨミガナ</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="text" maxlength="255" class="form-control size-2L" name="kana"
                    value="{{ old('kana', !empty($jyomuin) ? $jyomuin->kana : '') }}"
                    @if (!empty($jyomuin)) {{ 'readonly' }}" @endif>
                  <div class="error_message">
                    <span class=" text-danger" id="error-kana"></span>
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
                <label class="col-12 col-md-2 col-form-label text-nowrap ">名称</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="text" maxlength="255" class="form-control size-L" name="jyomuin_nm"
                    value="{{ old('jyomuin_nm', !empty($jyomuin) ? $jyomuin->jyomuin_nm : '') }}"
                    @if (!empty($jyomuin)) {{ 'readonly' }}" @endif>
                  <div class="error_message">
                    <span class=" text-danger" id="error-jyomuin_nm"></span>
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
                <label class="col-12 col-md-2 col-form-label text-nowrap ">所属部門コード</label>
                <div class="col-12 col-md-10 group-input">
                  <div class="row px-3">
                    <input type="text" maxlength="255" class="form-control size-M" name="bumon_cd"
                      value="{{ old('bumon_cd', !empty($jyomuin) ? $jyomuin->bumon_cd : '') }}"
                      onkeyup="suggestionForm(this, 'bumon_cd', ['bumon_cd', 'bumon_nm'], '', $(this).parent())"
                           autocomplete="off"
                      style="" @if (!empty($jyomuin)) {{ 'readonly' }}" @endif>
                    <input class="form-control size-L" name="bumon_nm" readonly
                      value="{{ !empty($jyomuin) ? old('bumon_nm', $jyomuin->bumon_nm) : '' }}">
                    <ul class="suggestion mx-3" style="width: calc(100% - 2rem);"></ul>
                  </div>
                  <div class="error_message">
                    <span class=" text-danger" id="error-bumon_cd"></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">携帯電話</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="text" maxlength="255" class="form-control size-2L" name="mobile_tel"
                         value="{{ old('mobile_tel', !empty($jyomuin) ? $jyomuin->mobile_tel : '') }}"
                  @if (!empty($jyomuin)) {{ 'readonly' }}" @endif>
                  <div class="error_message">
                    <span class=" text-danger" id="error-mobile_tel"></span>
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
                <label class="col-12 col-md-2 col-form-label text-nowrap ">{{trans("attributes.m_jyomuin.mail")}}</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="text" maxlength="255" class="form-control size-3L" name="mail"
                    value="{{ old('mail', !empty($jyomuin) ? $jyomuin->mail : '') }}"
                    @if (!empty($jyomuin)) {{ 'readonly' }}" @endif>
                  <div class="error_message">
                    <span class=" text-danger" id="error-mail"></span>
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
                <div class="col-12 col-md-10 group-input">
                  <select class="form-control size-S" name="kyumin_flg">
                    @foreach(config()->get('params.options.m_jyomuin.kyumin_flg') as $key => $value)
                      <option value="{{ $key }}" @selected(old('kyumin_flg', (!empty($jyomuin) ? $jyomuin->kyumin_flg : '0')) !== null
                      && old('kyumin_flg', (!empty($jyomuin) ? $jyomuin->kyumin_flg : '0')) == $key)>
                       {{ $value }}
                      </option>
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
          <button class="btn btn-back min-wid-110" id="backButton" data-href="{{ route('master.jyomuin.index') }}"
            onclick="redirectBack(this, 'JyomuinIndex')" type="button">{{ trans('app.labels.btn-back') }}</button>
          <button class="btn btn-insert min-wid-110" type="button" onclick="submitForm(this)">
            @if (!empty($jyomuin))
              {{ trans('app.labels.btn-update') }}
            @else
              {{ trans('app.labels.btn-insert') }}
            @endif
          </button>
          @if (!empty($jyomuin))
            <button class="btn btn-delete min-wid-110" type="button"
              onclick="deleteData(this)">{{ trans('app.labels.btn-delete') }}</button>
          @endif
        </div>
      </form>
    </div>
  </div>
  <div class="popup-confirm"></div>
  <table id="table"></table>
@endsection

@section('js')
  <script>
    @if (!empty($jyomuin))
      function handleDelete() {
        $.ajax({
          url: '{{ route('master.jyomuin.destroy', ['jyomuinCd' => urlencode($jyomuin->jyomuin_cd)]) }}',
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
        })
      }
    @endif

    $('#table').customTable({
      columns: [],
      formSearch: $('#formHinmei'),
      urlSearchSuggestion: "{{ route('master-suggestion') }}",
    });

    $(document).ready(function() {
      $('form').find('select, input').change(function() {
        hasChangeData = true;
      });
    });
  </script>
@endsection
