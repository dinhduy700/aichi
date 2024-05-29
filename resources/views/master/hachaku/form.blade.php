@extends('layouts.master')
@section('css')
  <style>
    .err-suggest {
      width: 100%;
      /*padding-left: 131px;*/
      /*margin-top: -14px;*/
      font-size: 12px;
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
  $hachakuCd              = getMaxCodeField(app(\App\Models\MHachaku::class)->getTable(), 'hachaku_cd') + 1;
  $kana                   = null;
  $hachakuNm              = null;
  $atenaNinusiId          = null;
  $atenaNinusiNm          = null;
  $atena                  = null;
  $jyusyo1Nm              = null;
  $jyusyo2Nm              = null;
  $tel                    = null;
  $fax                    = null;
  $ninusiId               = null;
  $ninusiNm               = null;
  $kyuminFlg              = 0;
  $formSearchHachakuIndex = null;
  $readOnly               = null;

  if(!empty($request->HachakuIndex))
  {
    $formSearchHachakuIndex = $request->HachakuIndex;
  }

  if (!empty($hachaku)) {
    $hachakuCd      = $hachaku->hachaku_cd;
    $kana           = $hachaku->kana;
    $hachakuNm      = $hachaku->hachaku_nm;
    $atenaNinusiId  = $hachaku->atena_ninusi_id;
    $atenaNinusiNm  = $hachaku->atena_ninusi_nm;
    $atena          = $hachaku->atena;
    $jyusyo1Nm      = $hachaku->jyusyo1_nm;
    $jyusyo2Nm      = $hachaku->jyusyo2_nm;
    $tel            = $hachaku->tel;
    $fax            = $hachaku->fax;
    $ninusiId       = $hachaku->ninusi_id;
    $ninusiNm       = $hachaku->ninusi_nm;
    $kyuminFlg      = $hachaku->kyumin_flg;
    $readOnly       = 'readonly';
  }

@endphp
<x-error-summary/>
  <div class="card form-custom form-master">
    <form method="post" action="{{ !empty($hachaku) ? route('master.hachaku.update', ['hachakuCd' => $hachaku->hachaku_cd]) : route('master.hachaku.store') }}">
      <div class="card-body" style="position: relative;">
        <h4 class="card-title">@if(!empty($hachaku)){{ trans('app.screen.master.hachaku.edit') }}@else{{ trans('app.screen.master.hachaku.create') }}@endif</h4>
          {{csrf_field()}}
          @if(!empty($formSearchHachakuIndex))
            @foreach($formSearchHachakuIndex as $key => $value)
              <input type="hidden" name="HachakuIndex[{{ $key }}]" value="{{ $value }}">
            @endforeach
          @endif

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_hachaku.hachaku_cd')"
                  :readOnly="$readOnly"
                  nameInput="hachaku_cd"
                  class="size-L"  maxlength="255" value="{{ $hachakuCd }}"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_hachaku.kana')"
                  nameInput="kana"
                  class="size-L"  maxlength="255" value="{{ $kana }}"
                />
              </div>
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_hachaku.hachaku_nm')"
                  nameInput="hachaku_nm"
                  class="size-2L"  maxlength="255" value="{{ $hachakuNm }}"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <div class="row">
                  <label class="col-12 col-md-2 col-form-label text-nowrap ">{{ trans('attributes.m_hachaku.atena_ninusi_id_form') }}</label>
                  <div class="col-12 col-md-10 group-input d-flex" style="flex-wrap: wrap;">
                    <input type="text" name="atena_ninusi_id" class="form-control size-L"  maxlength="255" value="{{ $atenaNinusiId }}"
                    onkeyup="suggestionForm(this, 'atena_ninusi_id', ['ninusi_cd', 'ninusi_ryaku_nm', 'kana'], {ninusi_cd: 'atena_ninusi_id', ninusi_ryaku_nm: 'atena_ninusi_nm'}, $(this).parent() )"
                           autocomplete="off" >
                    <input type="text" name="atena_ninusi_nm" class="form-control size-2L" readonly value="{{ $atenaNinusiNm }}">
                    <ul class="suggestion modify-position-suggest"></ul>
                    <div class="error_message err-suggest" style="width: 100%;">
                      <span class="text-danger" id="error-atena_ninusi_id"></span>
                    </div>
                  </div>

                </div>
              </div>
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_hachaku.atena')"
                  nameInput="atena"
                  class="size-2L"  maxlength="255" value="{{ $atena }}"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_hachaku.jyusyo1_nm')"
                  nameInput="jyusyo1_nm"
                  class="size-2L"  maxlength="255" value="{{ $jyusyo1Nm }}"
                />
              </div>
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_hachaku.jyusyo2_nm')"
                  nameInput="jyusyo2_nm"
                  class="size-2L"  maxlength="255" value="{{ $jyusyo2Nm }}"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_hachaku.tel')"
                  nameInput="tel"
                  class="size-2L"  maxlength="255" value="{{ $tel }}"
                />
              </div>
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_hachaku.fax')"
                  nameInput="fax"
                  class="size-2L"  maxlength="255" value="{{ $fax }}"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <div class="row">
                  <label class="col-12 col-md-2 col-form-label text-nowrap ">{{ trans('attributes.m_hachaku.ninusi_id') }}</label>
                  <div class="col-12 col-md-10 group-input d-flex" style="flex-wrap: wrap;">
                    <input type="text" name="ninusi_id" class="form-control size-L"  maxlength="255" value="{{ $ninusiId }}"
                    onkeyup="suggestionForm(this, 'ninusi_id', ['ninusi_cd', 'ninusi_ryaku_nm', 'kana'], {ninusi_cd: 'ninusi_id', ninusi_ryaku_nm: 'ninusi_nm'}, $(this).parent() )"
                           autocomplete="off" >
                    <input type="text" name="ninusi_nm" class="form-control size-2L" readonly value="{{ $ninusiNm }}">
                    <ul class="suggestion modify-position-suggest"></ul>
                    <div class="error_message err-suggest" style="width: 100%;">
                      <span class="text-danger" id="error-ninusi_id"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-select-group
                  :label="trans('attributes.m_hachaku.kyumin_flg')"
                  :list="config()->get('params.options.m_hachaku.kyumin_flg')"
                  :data="$kyuminFlg"
                  nameInput="kyumin_flg"
                  class="size-S"
                  :prompt="false"
                />
              </div>
            </div>
          </div>


        <div class="button-form">
          <button class="btn btn-back min-wid-110" id="backButton" data-href="{{route('master.hachaku.index')}}" onclick="redirectBack(this, 'HachakuIndex')" type="button">{{ trans('app.labels.btn-back') }}</button>
          <button class="btn btn-insert min-wid-110" type="button" onclick="submitForm(this)">@if(!empty($hachaku)) {{trans('app.labels.btn-update')}} @else {{trans('app.labels.btn-insert')}} @endif</button>
          @if(!empty($hachaku))
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

  @if(!empty($hachaku))
    function handleDelete() {
      $.ajax({
        url: '{{route('master.hachaku.destroy', ['hachakuCd' => $hachaku->hachaku_cd])}}',
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
