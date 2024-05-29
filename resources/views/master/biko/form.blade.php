@extends('layouts.master')
@section('css')
  <style>
    .err-suggest {
      width: 100%;
      padding-left: 131px;
      margin-top: -14px;
    }

    .modify-position-suggest {
      margin-top: -12px;
      margin-left: 15px;
      width: 95%;
    }
  </style>
@endsection
@section('page-content')
@php
  $bikoCd              = getMaxCodeField(app(\App\Models\MBiko::class)->getTable(), 'biko_cd') + 1;
  $kana                = null;
  $bikoNm              = null;
  $syubetuKbn          = null;
  $kyuminFlg           = 0;
  $formSearchBikoIndex = null;
  $readOnly            = null;

  if(!empty($request->BikoIndex))
  {
    $formSearchBikoIndex = $request->BikoIndex;
  }

  if (!empty($biko)) {
    $bikoCd      = $biko->biko_cd;
    $kana        = $biko->kana;
    $bikoNm      = $biko->biko_nm;
    $syubetuKbn  = $biko->syubetu_kbn;
    $kyuminFlg   = $biko->kyumin_flg;
    $readOnly    = 'readonly';
  }

@endphp
<x-error-summary/>
  <div class="card form-custom form-master">
    <form method="post" action="{{ !empty($biko) ? route('master.biko.update', ['bikoCd' => $biko->biko_cd]) : route('master.biko.store') }}">
      <div class="card-body" style="position: relative;">
        <h4 class="card-title">@if(!empty($biko)){{ trans('app.screen.master.biko.edit') }}@else{{ trans('app.screen.master.biko.create') }}@endif</h4>
          {{csrf_field()}}
          @if(!empty($formSearchBikoIndex))
            @foreach($formSearchBikoIndex as $key => $value)
              <input type="hidden" name="BikoIndex[{{ $key }}]" value="{{ $value }}">
            @endforeach
          @endif

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_biko.biko_cd')"
                  :readOnly="$readOnly"
                  nameInput="biko_cd"
                  class="size-M"  maxlength="255" value="{{ $bikoCd }}"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_biko.kana')"
                  nameInput="kana"
                  class="size-L"  maxlength="255" value="{{ $kana }}"
                />
              </div>
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_biko.biko_nm')"
                  nameInput="biko_nm"
                  class="size-4L"  maxlength="255" value="{{ $bikoNm }}"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-select-group
                  :label="trans('attributes.m_biko.syubetu_kbn')"
                  :list="config()->get('params.options.m_biko.syubetu_kbn')"
                  :data="$syubetuKbn"
                  nameInput="syubetu_kbn"
                  class="size-L"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-select-group
                  :label="trans('attributes.m_biko.kyumin_flg')"
                  :list="config()->get('params.options.m_biko.kyumin_flg')"
                  :data="$kyuminFlg"
                  nameInput="kyumin_flg"
                  class="size-S"
                  :prompt="false"
                />
              </div>
            </div>
          </div>


        <div class="button-form">
          <button class="btn btn-back min-wid-110" id="backButton" data-href="{{route('master.biko.index')}}" onclick="redirectBack(this, 'BikoIndex')" type="button">{{ trans('app.labels.btn-back') }}</button>
          <button class="btn btn-insert min-wid-110" type="button" onclick="submitForm(this)">@if(!empty($biko)) {{trans('app.labels.btn-update')}} @else {{trans('app.labels.btn-insert')}} @endif</button>
          @if(!empty($biko))
          <button class="btn btn-delete min-wid-110" type="button" onclick="deleteData(this)">{{trans('app.labels.btn-delete')}}</button>
          @endif
        </div>
      </div>
    </form>
  </div>
  <div id="table"></div>
@endsection

@section('js')
<script>
  var urlSearchSuggestion = '';
  var columns             = @json([]);

  $('#table').customTable({
    columns: columns,
    urlSearchSuggestion: '{!! route('master-suggestion') !!}',
  });

  @if(!empty($biko))
    function handleDelete() {
      $.ajax({
        url: '{{route('master.biko.destroy', ['bikoCd' => $biko->biko_cd])}}',
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
    });
  });
</script>
@endsection
