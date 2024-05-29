@extends('layouts.master')
@section('css')
  <style>

  </style>
@endsection
@section('page-content')
  @php
    $formSearchBumonIndex = null;
    if (!empty($request->BumonIndex)) {
        $formSearchBumonIndex = $request->BumonIndex;
    }

  @endphp
  <x-error-summary/>
  <div class="card form-custom form-master">
    <div class="card-body" style="position: relative;">
      <h4 class="card-title">
        @if (!empty($bumon))
          {{ trans('app.screen.master.bumon.edit') }}@else{{ trans('app.screen.master.bumon.create') }}
        @endif
      </h4>
      <form method="POST"
        action="{{ !empty($bumon) ? route('master.bumon.update', ['bumonCd' => urlencode($bumon->bumon_cd)]) : route('master.bumon.store') }}">
        {{ csrf_field() }}
        @if (!empty($formSearchBumonIndex))
          @foreach ($formSearchBumonIndex as $key => $value)
            <input type="hidden" name="BumonIndex[{{ $key }}]" value="{{ $value }}">
          @endforeach
        @endif
        <div class="form-group">
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">部門コード</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="text" maxlength="255" name="bumon_cd" class="form-control size-M"
                    value="{{ old('bumon_cd', !empty($bumon) ? $bumon->bumon_cd
                        : getMaxCodeField(app(\App\Models\MBumon::class)->getTable(), 'bumon_cd') + 1) }}"
                    @if (!empty($bumon)) {{ 'readonly' }} @endif>
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
                <label class="col-12 col-md-2 col-form-label text-nowrap ">読みカナ</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="text" maxlength="255" class="form-control size-L" name="kana"
                    value="{{ old('kana', !empty($bumon) ? $bumon->kana : '') }}"
                    @if (!empty($bumon)) {{ 'readonly' }}" @endif>
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
                  <input type="text" maxlength="255" class="form-control size-L" name="bumon_nm"
                    value="{{ old('bumon_nm', !empty($bumon) ? $bumon->bumon_nm : '') }}"
                    @if (!empty($bumon)) {{ 'readonly' }}" @endif>
                  <div class="error_message">
                    <span class=" text-danger" id="error-bumon_nm"></span>
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
                    @foreach (config()->get('params.options.m_bumon.kyumin_flg') as $key => $value)
                      <option value="{{ $key }}" @selected(old('kyumin_flg', (!empty($bumon) ? $bumon->kyumin_flg : '0')) == $key)> {{ $value }}</option>
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
          <button class="btn btn-back min-wid-110" id="backButton" data-href="{{ route('master.bumon.index') }}"
            onclick="redirectBack(this, 'BumonIndex')" type="button">{{ trans('app.labels.btn-back') }}</button>
          <button class="btn btn-insert min-wid-110" type="button" onclick="submitForm(this)">
            @if (!empty($bumon))
              {{ trans('app.labels.btn-update') }}
            @else
              {{ trans('app.labels.btn-insert') }}
            @endif
          </button>
          @if (!empty($bumon))
            <button class="btn btn-delete min-wid-110" type="button"
              onclick="deleteData(this)">{{ trans('app.labels.btn-delete') }}</button>
          @endif
        </div>
      </form>
    </div>
  </div>
  <div class="popup-confirm"></div>
@endsection

@section('js')
  <script>
    @if (!empty($bumon))
      function handleDelete() {
        $.ajax({
          url: '{{ route('master.bumon.destroy', ['bumonCd' => urlencode($bumon->bumon_cd)]) }}',
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

    $(document).ready(function() {
      $('form').find('select, input').change(function() {
        hasChangeData = true;
      });
    });
  </script>
@endsection
