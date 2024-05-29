@extends('layouts.master')
@section('css')
  <style>


  </style>
@endsection
@section('page-content')
@php
  $formSearchMeisyoIndex= null;
  if(!empty($request->MeisyoIndex))
  {
    $formSearchMeisyoIndex = $request->MeisyoIndex;
  }


@endphp
  <x-error-summary/>
  <div class="card form-custom form-master">
    <form method="post" action="{{ !empty($meisyo) ? route('master.meisyo.update', ['meisyoCd' => $meisyo->meisyo_cd, 'meisyoKbn' => $meisyo->meisyo_kbn]) : route('master.meisyo.store') }}">
      <div class="card-body" style="position: relative;">
        <h4 class="card-title">@if(!empty($meisyo)){{ trans('app.screen.master.meisyo.edit') }}@else{{ trans('app.screen.master.meisyo.create') }}@endif</h4>
          {{csrf_field()}}
          @if(!empty($formSearchMeisyoIndex))
            @foreach($formSearchMeisyoIndex as $key => $value)
              <input type="hidden" name="MeisyoIndex[{{ $key }}]" value="{{ $value }}">
            @endforeach
          @endif
          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <div class="row">
                  <label class="col-12 col-md-2 col-form-label text-nowrap ">名称区分</label>
                  <div class="col-12 col-md-8 group-input">
                    <select class="form-control size-2L" name="meisyo_kbn" @if(!empty($meisyo)) {{'disabled'}} @endif>
                      <option value=""></option>
                      @foreach(config()->get('params.options.m_meisyo.meisyo_kbn') as $key => $value)
                      <option value="{{ $key }}" @selected(old('meisyo_kbn', (!empty($meisyo) ? $meisyo->meisyo_kbn : '')) == $key)>{{ $value }}</option>
                      @endforeach
                    </select>
                    @if(!empty($meisyo))
                      <input type="hidden" readonly name="meisyo_kbn" readonly value="{{ $meisyo->meisyo_kbn }}">
                    @endif
                    <div class="error_message">
                      <span class=" text-danger" id="error-meisyo_kbn"></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="row">
                  <label class="col-12 col-md-2 col-form-label text-nowrap ">名称コード</label>
                  <div class="col-12 col-md-4 group-input">
                    <input type="text" class="form-control size-M" name="meisyo_cd" maxlength="255" value="{{ old('meisyo_cd', !empty($meisyo) ? $meisyo->meisyo_cd : '' ) }}" @if(!empty($meisyo)) {{ 'readonly'}} @endif>
                    <div class="error_message">
                      <span class=" text-danger" id="error-meisyo_cd"></span>
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
                  <div class="col-12 col-md-8 group-input">
                    <input type="text" class="form-control size-L" name="kana" maxlength="255" value="{{ old('kana', !empty($meisyo) ? $meisyo->kana : '' ) }}" @if(!empty($meisyo)) {{ 'readonly'}}" @endif>
                    <div class="error_message">
                      <span class=" text-danger" id="error-kana"></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="row">
                  <label class="col-12 col-md-2 col-form-label text-nowrap ">名称</label>
                  <div class="col-12 col-md-8 group-input">
                    <input type="text" class="form-control size-2L" name="meisyo_nm" maxlength="255" value="{{ old('meisyo_nm', !empty($meisyo) ? $meisyo->meisyo_nm : '' ) }}" @if(!empty($meisyo)) {{ 'readonly'}}" @endif>
                    <div class="error_message">
                      <span class=" text-danger" id="error-meisyo_nm"></span>
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
                  <label class="col-12 col-md-2 col-form-label text-nowrap ">重量換算係</label>
                  <div class="col-12 col-md-4 group-input">
                    <input type="text" class="form-control size-L" name="jyuryo_kansan" maxlength="11" value="{{ old('jyuryo_kansan', !empty($meisyo) ? numberFormat($meisyo->jyuryo_kansan, -1, '') : '' ) }}" @if(!empty($meisyo)) {{ 'readonly'}}" @endif>
                    <div class="error_message">
                      <span class=" text-danger" id="error-jyuryo_kansan"></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="row">
                  <label class="col-12 col-md-2 col-form-label text-nowrap ">積載区分</label>
                  <div class="col-12 col-md-4 group-input">
                    <input type="text" class="form-control size-M" name="sekisai_kbn" maxlength="1" value="{{ old('sekisai_kbn', !empty($meisyo) ? $meisyo->sekisai_kbn : '' ) }}" @if(!empty($meisyo)) {{ 'readonly'}}" @endif>
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
                  <label class="col-12 col-md-2 col-form-label text-nowrap ">休眠フラグ</label>
                  <div class="col-12 col-md-4 group-input">
                    <!-- <input type="text" class="form-control size-S" name="kyumin_flg" maxlength=""> -->
                    <select class="form-control size-S" name="kyumin_flg">
                      @foreach(config()->get('params.options.m_meisyo.kyumin_flg') as $key => $value)
                      <option value="{{ $key }}" @selected(old('kyumin_flg', (!empty($meisyo) ? $meisyo->kyumin_flg : '0')) !== null && old('kyumin_flg', (!empty($meisyo) ? $meisyo->kyumin_flg : '0')) == $key)>
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
          <button class="btn btn-back min-wid-110" id="backButton" data-href="{{route('master.meisyo.index')}}" onclick="redirectBack(this, 'MeisyoIndex')" type="button">{{ trans('app.labels.btn-back') }}</button>
          <button class="btn btn-insert min-wid-110" type="button" onclick="submitForm(this)">@if(!empty($meisyo)) {{trans('app.labels.btn-update')}} @else {{trans('app.labels.btn-insert')}} @endif</button>
          @if(!empty($meisyo))
          <button class="btn btn-delete min-wid-110" type="button" onclick="deleteData(this)">{{trans('app.labels.btn-delete')}}</button>
          @endif
        </div>
      </div>
    </form>
  </div>
  <div class="popup-confirm"></div>
@endsection

@section('js')
<script>
  @if(!empty($meisyo))
    function handleDelete() {
      $.ajax({
        url: '{{route('master.meisyo.destroy', ['meisyoKbn' => $meisyo->meisyo_kbn, 'meisyoCd' => $meisyo->meisyo_cd])}}',
        method: 'DELETE',
        data: {},
        success: function(res) {
          if(res.status == 200) {
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
  $(document).ready(function() {
    $('form').find('select, input').change(function() {
      hasChangeData = true;
    })
  })
</script>
@endsection
