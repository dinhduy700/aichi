@extends('layouts.master')
@section('css')
  <style>

  </style>
@endsection
@section('page-content')
  @php
    $formSearchHinmokuIndex = null;
    if (!empty($request->HinmokuIndex)) {
        $formSearchHinmokuIndex = $request->HinmokuIndex;
    }

  @endphp
  <x-error-summary/>
  <div class="card form-custom form-master">
    <div class="card-body" style="position: relative;">
      <h4 class="card-title">
        @if (!empty($hinmoku))
          {{ trans('app.screen.master.hinmoku.edit') }}@else{{ trans('app.screen.master.hinmoku.create') }}
        @endif
      </h4>
      <form method="POST"
        action="{{ !empty($hinmoku) ? route('master.hinmoku.update', ['hinmokuCd' => urlencode($hinmoku->hinmoku_cd)]) : route('master.hinmoku.store') }}">
        {{ csrf_field() }}
        @if (!empty($formSearchHinmokuIndex))
          @foreach ($formSearchHinmokuIndex as $key => $value)
            <input type="hidden" name="HinmokuIndex[{{ $key }}]" value="{{ $value }}">
          @endforeach
        @endif
        <div class="form-group">
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">品目コード</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="text" maxlength="255" name="hinmoku_cd" class="form-control size-M"
                    value="{{ old('hinmoku_cd', !empty($hinmoku) ? $hinmoku->hinmoku_cd
                        : getMaxCodeField(app(\App\Models\MHinmoku::class)->getTable(), 'hinmoku_cd') + 1) }}"
                    @if (!empty($hinmoku)) {{ 'readonly' }} @endif>
                  <div class="error_message">
                    <span class=" text-danger" id="error-hinmoku_cd"></span>
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
                    value="{{ old('kana', !empty($hinmoku) ? $hinmoku->kana : '') }}"
                    @if (!empty($hinmoku)) {{ 'readonly' }}" @endif>
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
                  <input type="text" maxlength="255" class="form-control size-2L" name="hinmoku_nm"
                    value="{{ old('hinmoku_nm', !empty($hinmoku) ? $hinmoku->hinmoku_nm : '') }}"
                    @if (!empty($hinmoku)) {{ 'readonly' }}" @endif>
                  <div class="error_message">
                    <span class=" text-danger" id="error-hinmoku_nm"></span>
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
                    @foreach (config()->get('params.options.m_hinmoku.kyumin_flg') as $key => $value)
                      <option value="{{ $key }}" @selected(old('kyumin_flg', (!empty($hinmoku) ? $hinmoku->kyumin_flg : '0')) == $key)> {{ $value }}</option>
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
          <button class="btn btn-back min-wid-110" id="backButton" data-href="{{ route('master.hinmoku.index') }}"
            onclick="redirectBack(this, 'HinmokuIndex')" type="button">{{ trans('app.labels.btn-back') }}</button>
          <button class="btn btn-insert min-wid-110" type="button" onclick="submitForm(this)">
            @if (!empty($hinmoku))
              {{ trans('app.labels.btn-update') }}
            @else
              {{ trans('app.labels.btn-insert') }}
            @endif
          </button>
          @if (!empty($hinmoku))
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
    @if (!empty($hinmoku))
      function handleDelete() {
        $.ajax({
          url: '{{ route('master.hinmoku.destroy', ['hinmokuCd' => urlencode($hinmoku->hinmoku_cd)]) }}',
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
