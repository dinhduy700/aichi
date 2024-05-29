@extends('layouts.master')
@section('css')
  <style>

  </style>
@endsection
@section('page-content')
  @php
    $formSearchHinmeiIndex = null;
    if (!empty($request->HinmeiIndex)) {
        $formSearchHinmeiIndex = $request->HinmeiIndex;
    }

  @endphp
  <x-error-summary/>
  <div class="card form-custom form-master">
    <div class="card-body" style="position: relative;">
      <h4 class="card-title">
        @if (!empty($hinmei))
          {{ trans('app.screen.master.hinmei.edit') }}@else{{ trans('app.screen.master.hinmei.create') }}
        @endif
      </h4>
      <form method="POST" id="formHinmei"
        action="{{ !empty($hinmei) ? route('master.hinmei.update', ['hinmeiCd' => urlencode($hinmei->hinmei_cd)]) : route('master.hinmei.store') }}">
        {{ csrf_field() }}
        @if (!empty($formSearchHinmeiIndex))
          @foreach ($formSearchHinmeiIndex as $key => $value)
            <input type="hidden" name="HinmeiIndex[{{ $key }}]" value="{{ $value }}">
          @endforeach
        @endif
        <div class="form-group">
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">品名コード</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="text" maxlength="255" name="hinmei_cd" class="form-control size-L"
                    value="{{ old('hinmei_cd', !empty($hinmei) ? $hinmei->hinmei_cd
                        : getMaxCodeField(app(\App\Models\MHinmei::class)->getTable(), 'hinmei_cd') + 1) }}"
                    @if (!empty($hinmei)) {{ 'readonly' }} @endif>
                  <div class="error_message">
                    <span class=" text-danger" id="error-hinmei_cd"></span>
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
                <div class="col-12 col-md-8 group-input">
                  <input type="text" maxlength="255" class="form-control size-L" name="kana"
                    value="{{ old('kana', !empty($hinmei) ? $hinmei->kana : '') }}"
                    @if (!empty($hinmei)) {{ 'readonly' }}" @endif>
                  <div class="error_message">
                    <span class=" text-danger" id="error-kana"></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">名称</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="text" maxlength="255" name="hinmei_nm" class="form-control size-2L col-10"
                    value="{{ old('hinmei_nm', !empty($hinmei) ? $hinmei->hinmei_nm : '') }}"
                    @if (!empty($hinmei)) {{ 'readonly' }}" @endif>
                  <div class="error_message">
                    <span class=" text-danger" id="error-hinmei_nm"></span>
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
                <label class="col-12 col-md-2 col-form-label text-nowrap ">品名２コード</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="text" maxlength="255" class="form-control size-2L" name="hinmei2_cd"
                    value="{{ old('hinmei2_cd', !empty($hinmei) ? $hinmei->hinmei2_cd : '') }}"
                    @if (!empty($hinmei)) {{ 'readonly' }}" @endif>
                  <div class="error_message">
                    <span class=" text-danger" id="error-hinmei2_cd"></span>
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
                <label class="col-12 col-md-2 col-form-label text-nowrap">品目コード</label>
                <div class="col-12 col-md-10 group-input">
                  <div class="row px-3">
                    <input type="text" maxlength="255" class="form-control size-M" name="hinmoku_cd"
                      value="{{ old('hinmoku_cd', !empty($hinmei) ? $hinmei->hinmoku_cd : '') }}"
                      onkeyup="suggestionForm(this, 'hinmoku_cd', ['hinmoku_cd', 'hinmoku_nm'], '', $(this).parent())" autocomplete="off"
                      style="" @if (!empty($hinmei)) {{ 'readonly' }}" @endif>
                    <input class="form-control size-2L" name="hinmoku_nm" readonly
                      value="{{ !empty($hinmei) ? old('hinmoku_nm', $hinmei->hinmoku_nm) : '' }}">
                    <ul class="suggestion mx-3" style="width: 42%; min-width:190px;"></ul>
                  </div>
                  <div class="error_message">
                    <span class=" text-danger" id="error-hinmoku_cd"></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">単位コード</label>
                <div class="col-12 col-md-10 group-input">
                  <div class="row px-3">
                    <input type="text" maxlength="255" class="form-control size-S" name="tani_cd"
                      value="{{ old('tani_cd', !empty($hinmei) ? $hinmei->tani_cd : '') }}"
                      onkeyup="suggestionForm(this, 'tani_cd', ['tani_cd', 'tani_nm'], '', $(this).parent())" autocomplete="off"
                      style="" @if (!empty($hinmei)) {{ 'readonly' }}" @endif>
                    <input class="form-control size-S" name="tani_nm" readonly
                      value="{{ !empty($hinmei) ? old('tani_nm', $hinmei->tani_nm) : '' }}">
                    <ul class="suggestion mx-3" style="width: 40%; min-width:170px;"></ul>
                  </div>
                  <div class="error_message">
                    <span class=" text-danger" id="error-tani_cd"></span>
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
                <label class="col-12 col-md-2 col-form-label text-nowrap ">単位重量</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="text" maxlength="255" class="form-control size-L" name="tani_jyuryo"
                    value="{{ old('tani_jyuryo', !empty($hinmei) && $hinmei->tani_jyuryo !== null ? numberFormat($hinmei->tani_jyuryo, -1, '') : '') }}"
                    @if (!empty($hinmei)) {{ 'readonly' }}" @endif>
                  <div class="error_message">
                    <span class=" text-danger" id="error-tani_jyuryo"></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">配車単位重量</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="text" maxlength="255" class="form-control size-L" name="haisya_tani_jyuryo"
                    value="{{ old('haisya_tani_jyuryo', !empty($hinmei) && $hinmei->haisya_tani_jyuryo !== null ? numberFormat($hinmei->haisya_tani_jyuryo, -1, '') : '') }}"
                    @if (!empty($hinmei)) {{ 'readonly' }}" @endif>
                  <div class="error_message">
                    <span class=" text-danger" id="error-haisya_tani_jyuryo"></span>
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
                <label class="col-12 col-md-2 col-form-label text-nowrap ">諸口区分１</label>
                <div class="col-12 col-md-10 group-input">
                  <select class="form-control size-L" name="syoguti_kbn1">
                    <option value=""></option>
                    @foreach (config()->get('params.options.m_hinmei.syoguti_kbn1') as $key => $value)
                      <option value="{{ $key }}" @selected(old('syoguti_kbn1', !empty($hinmei) ? $hinmei->syoguti_kbn1 : '1') == $key)> {{ $value }}</option>
                    @endforeach
                  </select>
                  <div class="error_message">
                    <span class=" text-danger" id="error-syoguti_kbn1"></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">諸口区分２</label>
                <div class="col-12 col-md-10 group-input">
                  <select class="form-control size-L" name="syoguti_kbn2">
                    <option value=""></option>
                    @foreach (config()->get('params.options.m_hinmei.syoguti_kbn2') as $key => $value)
                      <option value="{{ $key }}" @selected(old('syoguti_kbn2', !empty($hinmei) ? $hinmei->syoguti_kbn2 : '1') == $key)> {{ $value }}</option>
                    @endforeach
                  </select>
                  <div class="error_message">
                    <span class=" text-danger" id="error-syoguti_kbn2"></span>
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
                <label class="col-12 col-md-2 col-form-label text-nowrap ">荷主コード</label>
                <div class="col-12 col-md-10 group-input">
                  <div class="row px-3">
                    <input type="text" maxlength="255" class="form-control size-L" name="ninusi_id"
                      value="{{ old('ninusi_id', !empty($hinmei) ? $hinmei->ninusi_id : '') }}"
                      onkeyup="suggestionForm(this, 'ninusi_id', ['ninusi_cd', 'ninusi_nm'], {ninusi_cd: 'ninusi_id', ninusi_nm: 'ninusi_nm'}, $(this).parent())" autocomplete="off"
                      style="" @if (!empty($hinmei)) {{ 'readonly' }}" @endif>
                    <input class="form-control size-2L" name="ninusi_nm" readonly
                      value="{{ !empty($hinmei) ? old('ninusi_nm', $hinmei->ninusi_nm) : '' }}">
                    <ul class="suggestion mx-3" style="width: 80%; min-width:200px;"></ul>
                  </div>
                  <div class="error_message">
                    <span class=" text-danger" id="error-ninusi_id"></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">部門コード</label>
                <div class="col-12 col-md-10 group-input">
                  <div class="row px-3">
                    <input type="text" maxlength="255" class="form-control size-M" name="bumon_cd"
                      value="{{ old('bumon_cd', !empty($hinmei) ? $hinmei->bumon_cd : '') }}"
                      onkeyup="suggestionForm(this, 'bumon_cd', ['bumon_cd', 'bumon_nm'], '', $(this).parent())" autocomplete="off"
                      style="" @if (!empty($hinmei)) {{ 'readonly' }}" @endif>
                    <input class="form-control size-L" name="bumon_nm" readonly
                      value="{{ !empty($hinmei) ? old('bumon_nm', $hinmei->bumon_nm) : '' }}">
                    <ul class="suggestion mx-3" style="width: 50%; min-width:200px;"></ul>
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
                <label class="col-12 col-md-2 col-form-label text-nowrap ">休眠フラグ</label>
                <div class="col-12 col-md-10 group-input">
                  <select class="form-control size-S" name="kyumin_flg">
                    @foreach (config()->get('params.options.m_hinmei.kyumin_flg') as $key => $value)
                      <option value="{{ $key }}" @selected(old('kyumin_flg', !empty($hinmei) ? $hinmei->kyumin_flg : '0') == $key)> {{ $value }}</option>
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
          <button class="btn btn-back min-wid-110" id="backButton" data-href="{{ route('master.hinmei.index') }}"
            onclick="redirectBack(this, 'HinmeiIndex')" type="button">{{ trans('app.labels.btn-back') }}</button>
          <button class="btn btn-insert min-wid-110" type="button" onclick="submitForm(this)">
            @if (!empty($hinmei))
              {{ trans('app.labels.btn-update') }}
            @else
              {{ trans('app.labels.btn-insert') }}
            @endif
          </button>
          @if (!empty($hinmei))
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
    @if (!empty($hinmei))
      function handleDelete() {
        $.ajax({
          url: '{{ route('master.hinmei.destroy', ['hinmeiCd' => urlencode($hinmei->hinmei_cd)]) }}',
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
