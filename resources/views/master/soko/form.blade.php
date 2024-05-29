@extends('layouts.master')
@section('css')
  <style>

  </style>
@endsection
@section('page-content')
  @php
    $formSearchSokoIndex = null;
    if (!empty($request->sokoIndex)) {
        $formSearchSokoIndex = $request->sokoIndex;
    }

  @endphp
  <x-error-summary/>
  <div class="card form-custom form-master">
    <div class="card-body" style="position: relative;">
      <h4 class="card-title">
        @if (!empty($soko))
          {{ trans('app.screen.master.soko.edit') }}@else{{ trans('app.screen.master.soko.create') }}
        @endif
      </h4>
      <form method="POST" id="formsoko"
        action="{{ !empty($soko) ? route('master.soko.update', ['sokoCd' => urlencode($soko->soko_cd), 'bumonCd' => urlencode($soko->bumon_cd)]) : route('master.soko.store') }}">
        {{ csrf_field() }}
        @if (!empty($formSearchSokoIndex))
          @foreach ($formSearchSokoIndex as $key => $value)
            <input type="hidden" name="sokoIndex[{{ $key }}]" value="{{ $value }}">
          @endforeach
        @endif
        <div class="form-group">
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap">部門</label>
                <div class="col-12 col-md-10 group-input">
                  <div class="row px-3">
                    <input type="text" maxlength="255" class="form-control size-M" name="bumon_cd"
                      value="{{ old('bumon_cd', !empty($soko) ? $soko->bumon_cd : '') }}"
                      onkeyup="suggestionForm(this, 'bumon_cd', ['bumon_cd', 'bumon_nm'], '', $(this).parent())"
                           autocomplete="off"
                      style="" @if (!empty($soko)) {{ 'readonly' }} @endif>
                    <input class="form-control size-L" name="bumon_nm" readonly
                      value="{{ !empty($soko) ? old('bumon_nm', $soko->bumon_nm) : '' }}">
                    <ul class="suggestion mx-3" style="width: calc(100% - 2rem);"></ul>
                  </div>
                  <div class="error_message">
                    <span class=" text-danger" id="error-bumon_cd"></span>
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
                <label class="col-12 col-md-2 col-form-label text-nowrap ">倉庫コード</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="text" maxlength="255" name="soko_cd" class="form-control size-S"
                    value="{{ old('soko_cd', !empty($soko) ? $soko->soko_cd : getMaxCodeField(app(\App\Models\MSoko::class)->getTable(), 'soko_cd') + 1) }}"
                    @if (!empty($soko)) {{ 'readonly' }} @endif>
                  <div class="error_message">
                    <span class=" text-danger" id="error-soko_cd"></span>
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
                  <input type="text" maxlength="255" class="form-control size-L" name="kana"
                    value="{{ old('kana', !empty($soko) ? $soko->kana : '') }}"
                    @if (!empty($soko)) {{ 'readonly' }}" @endif>
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
                  <input type="text" maxlength="255" name="soko_nm" class="form-control size-2L"
                    value="{{ old('soko_nm', !empty($soko) ? $soko->soko_nm : '') }}"
                    @if (!empty($soko)) {{ 'readonly' }}" @endif>
                  <div class="error_message">
                    <span class=" text-danger" id="error-soko_nm"></span>
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
                    @foreach (config()->get('params.options.m_soko.kyumin_flg') as $key => $value)
                      <option value="{{ $key }}" @selected(old('kyumin_flg', !empty($soko) ? $soko->kyumin_flg : '0') == $key)> {{ $value }}</option>
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
          <button class="btn btn-back min-wid-110" id="backButton" data-href="{{ route('master.soko.index') }}"
            onclick="redirectBack(this, 'sokoIndex')" type="button">{{ trans('app.labels.btn-back') }}</button>
          <button class="btn btn-insert min-wid-110" type="button" onclick="submitForm(this)">
            @if (!empty($soko))
              {{ trans('app.labels.btn-update') }}
            @else
              {{ trans('app.labels.btn-insert') }}
            @endif
          </button>
          @if (!empty($soko))
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
    @if (!empty($soko))
      function handleDelete() {
        $.ajax({
          url: '{{ route('master.soko.destroy', ['sokoCd' => urlencode($soko->soko_cd), 'bumonCd' => urlencode($soko->bumon_cd)]) }}',
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
      formSearch: $('#formsoko'),
      urlSearchSuggestion: "{{ route('master-suggestion') }}",
    });

    $(document).ready(function() {
      $('form').find('select, input').change(function() {
        hasChangeData = true;
      });
    });
  </script>
@endsection
