@extends('layouts.master')
@section('css')
  <style>
    .err-suggest {
      width: 100%;
      padding: 0;
      text-align: left;
    }

    .modify-position-suggest {
      margin-top: 2px;
      margin-left: 15px;
      width: 95%;
    }

  </style>
@endsection
@section('page-content')
@php
  $formSearchYousyaIndex  = null;
  $yousyaCd               = getMaxCodeField(app(\App\Models\MYousya::class)->getTable(), 'yousya_cd') + 1;
  $kana                   = null;
  $yousya1Nm              = null;
  $yousya2Nm              = null;
  $yousyaRyakuNm          = null;
  $bumonCd                = null;
  $bumonNm                = null;
  $yubinNo                = null;
  $jyusyo1Nm              = null;
  $jyusyo2Nm              = null;
  $tel                    = null;
  $fax                    = null;
  $siharaiKbn             = null;
  $siharaiCd              = null;
  $siharaiNm              = null;
  $seikyuMuKbn            = null;
  $yousyaRitu             = null;
  $siharaiKbn             = null;
  $simebi1                = null;
  $simebi2                = null;
  $simebi3                = null;
  $mikakuteiSeigyoKbn     = null;
  $kinHasuKbn             = null;
  $kinHasuTani            = null;
  $zeiKeisanKbn           = null;
  $zeiHasuKbn             = null;
  $zeiHasuTani            = null;
  $kaikakeSakiCd          = null;
  $kaikakeSakiNm          = null;
  $siharaiUmuKbn          = null;
  $siharai1Dd             = null;
  $siharai2Dd             = null;
  $comennt                = null;
  $kensakuKbn             = null;
  $mail                   = null;
  $biko                   = null;
  $kyuminFlg              = 0;
  $haisyaBiko             = null;
  $siharaiNyuryokuUmuKbn  = null;
  $readOnly               = null;

  if(!empty($request->YousyaIndex)) {
    $formSearchYousyaIndex = $request->YousyaIndex;
  }

  if (!empty($yousya)) {
    $yousyaCd               = $yousya->yousya_cd;
    $kana                   = $yousya->kana;
    $yousya1Nm              = $yousya->yousya1_nm;
    $yousya2Nm              = $yousya->yousya2_nm;
    $yousyaRyakuNm          = $yousya->yousya_ryaku_nm;
    $bumonCd                = $yousya->bumon_cd;
    $bumonNm                = $yousya->bumon_nm;
    $yubinNo                = $yousya->yubin_no;
    $jyusyo1Nm              = $yousya->jyusyo1_nm;
    $jyusyo2Nm              = $yousya->jyusyo2_nm;
    $tel                    = $yousya->tel;
    $fax                    = $yousya->fax;
    $siharaiKbn             = $yousya->siharai_kbn;
    $siharaiCd              = $yousya->siharai_cd;
    $siharaiNm              = $yousya->siharai_nm;
    $seikyuMuKbn            = $yousya->seikyu_mu_kbn;
    $yousyaRitu             = $yousya->yousya_ritu;
    $siharaiUmuKbn          = $yousya->siharai_umu_kbn;
    $simebi1                = $yousya->simebi1;
    $simebi2                = $yousya->simebi2;
    $simebi3                = $yousya->simebi3;
    $mikakuteiSeigyoKbn     = $yousya->mikakutei_seigyo_kbn;
    $kinHasuKbn             = $yousya->kin_hasu_kbn;
    $kinHasuTani            = $yousya->kin_hasu_tani;
    $zeiKeisanKbn           = $yousya->zei_keisan_kbn;
    $zeiHasuKbn             = $yousya->zei_hasu_kbn;
    $zeiHasuTani            = $yousya->zei_hasu_tani;
    $kaikakeSakiCd          = $yousya->kaikake_saki_cd;
    $kaikakeSakiNm          = $yousya->kaikake_saki_nm;
    $siharaiNyuryokuUmuKbn  = $yousya->siharai_nyuryoku_umu_kbn;
    $siharai1Dd             = $yousya->siharai1_dd;
    $siharai2Dd             = $yousya->siharai2_dd;
    $comennt                = $yousya->comennt;
    $kensakuKbn             = $yousya->kensaku_kbn;
    $mail                   = $yousya->mail;
    $biko                   = $yousya->biko;
    $kyuminFlg              = $yousya->kyumin_flg;
    $haisyaBiko             = $yousya->haisya_biko;
    $readOnly               = 'readonly';
  }

@endphp
<x-error-summary/>
  <div class="card form-custom form-master">
    <form method="post" action="{{ !empty($yousya) ? route('master.yousya.update', ['yousyaCd' => $yousya->yousya_cd]) : route('master.yousya.store') }}">
      <div class="card-body" style="position: relative;">
        <h4 class="card-title">@if(!empty($yousya)){{ trans('app.screen.master.yousya.edit') }}@else{{ trans('app.screen.master.yousya.create') }}@endif</h4>
          {{csrf_field()}}
          @if(!empty($formSearchYousyaIndex))
            @foreach($formSearchYousyaIndex as $key => $value)
              <input type="hidden" name="YousyaIndex[{{ $key }}]" value="{{ $value }}">
            @endforeach
          @endif

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_yousya.yousya_cd')"
                  :readOnly="$readOnly"
                  nameInput="yousya_cd"
                  class="size-L"  maxlength="255" value="{{ $yousyaCd }}"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_yousya.kana')"
                  nameInput="kana"
                  class="size-L"  maxlength="255" value="{{ $kana }}"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_yousya.yousya1_nm')"
                  nameInput="yousya1_nm"
                  class="size-2L"  maxlength="255" value="{{ $yousya1Nm }}"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_yousya.yousya2_nm')"
                  nameInput="yousya2_nm"
                  class="size-2L"  maxlength="255" value="{{ $yousya2Nm }}"
                />
              </div>
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_yousya.yousya_ryaku_nm')"
                  nameInput="yousya_ryaku_nm"
                  class="size-L"  maxlength="255" value="{{ $yousyaRyakuNm }}"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
              </div>
              <div class="col-12 col-md-6">
                <div class="row">
                  <label class="col-12 col-md-2 col-form-label text-nowrap ">{{ trans('attributes.m_yousya.bumon_cd_form') }}</label>
                  <div class="col-12 col-md-10 group-input d-flex flex-wrap">
                    <input type="text" name="bumon_cd" class="form-control size-M"  maxlength="255" value="{{ $bumonCd }}"
                    onkeyup="suggestionForm(this, 'bumon_cd', ['bumon_cd', 'bumon_nm', 'kana'], {bumon_cd: 'bumon_cd', bumon_nm: 'bumon_nm'}, $(this).parent() )"
                           autocomplete="off">
                    <input type="text" name="bumon_nm" class="form-control size-L" readonly value="{{ $bumonNm }}">
                    <ul class="suggestion modify-position-suggest"></ul>
                    <div class="error_message err-suggest">
                      <span class="text-danger" id="error-bumon_cd"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_yousya.yubin_no')"
                  nameInput="yubin_no"
                  class="size-L"  maxlength="255" value="{{ $yubinNo }}"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_yousya.jyusyo1_nm')"
                  nameInput="jyusyo1_nm"
                  class="size-2L"  maxlength="255" value="{{ $jyusyo1Nm }}"
                />
              </div>
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_yousya.jyusyo2_nm')"
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
                  :label="trans('attributes.m_yousya.tel')"
                  nameInput="tel"
                  class="size-2L"  maxlength="255" value="{{ $tel }}"
                />
              </div>
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_yousya.fax')"
                  nameInput="fax"
                  class="size-2L"  maxlength="255" value="{{ $fax }}"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-select-group
                  :label="trans('attributes.m_yousya.siharai_kbn')"
                  :list="config()->get('params.options.m_yousya.siharai_kbn')"
                  :data="$siharaiKbn"
                  nameInput="siharai_kbn"
                  class="size-L"
                />
              </div>
              <div class="col-12 col-md-6">
                <div class="row">
                  <label class="col-12 col-md-2 col-form-label text-nowrap ">{{ trans('attributes.m_yousya.siharai_cd') }}</label>
                  <div class="col-12 col-md-10 group-input d-flex flex-wrap">
                    <input type="text" name="siharai_cd" class="form-control size-L"  maxlength="255" value="{{ $siharaiCd }}"
                    onkeyup="suggestionForm(this, 'siharai_cd', ['yousya_cd', 'yousya_ryaku_nm', 'kana'], {yousya_cd: 'siharai_cd', yousya_ryaku_nm: 'siharai_nm'}, $(this).parent() )"
                           autocomplete="off">
                    <input type="text" name="siharai_nm" class="form-control size-2L" readonly value="{{ $siharaiNm }}">
                    <ul class="suggestion modify-position-suggest"></ul>
                    <div class="error_message err-suggest">
                      <span class=" text-danger" id="error-siharai_cd"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_yousya.yousya_ritu')"
                  nameInput="yousya_ritu"
                  class="size-M"  maxlength="255" value="{{ $yousyaRitu }}"
                />
              </div>
              <div class="col-12 col-md-6">
                <x-select-group
                  :label="trans('attributes.m_yousya.siharai_umu_kbn')"
                  :list="config()->get('params.options.m_yousya.siharai_umu_kbn')"
                  :data="$siharaiUmuKbn"
                  nameInput="siharai_umu_kbn"
                  class="size-L"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_yousya.simebi1')"
                  nameInput="simebi1"
                  class="size-S"  maxlength="255" value="{{ $simebi1 }}"
                />
              </div>
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_yousya.simebi2')"
                  nameInput="simebi2"
                  class="size-S"  maxlength="255" value="{{ $simebi2 }}"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_yousya.simebi3')"
                  nameInput="simebi3"
                  class="size-S"  maxlength="255" value="{{ $simebi3 }}"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-select-group
                  :label="trans('attributes.m_yousya.mikakutei_seigyo_kbn')"
                  :list="config()->get('params.options.m_yousya.mikakutei_seigyo_kbn')"
                  :data="$mikakuteiSeigyoKbn"
                  nameInput="mikakutei_seigyo_kbn"
                  class="size-L"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-select-group
                  :label="trans('attributes.m_yousya.kin_hasu_kbn')"
                  :list="config()->get('params.options.m_yousya.kin_hasu_kbn')"
                  :data="$kinHasuKbn"
                  nameInput="kin_hasu_kbn"
                  class="size-L"
                />
              </div>
              <div class="col-12 col-md-6">
                <x-select-group
                  :label="trans('attributes.m_yousya.kin_hasu_tani')"
                  :list="config()->get('params.options.m_yousya.kin_hasu_tani')"
                  :data="$kinHasuTani"
                  nameInput="kin_hasu_tani"
                  class="size-M"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-select-group
                  :label="trans('attributes.m_yousya.zei_keisan_kbn')"
                  :list="config()->get('params.options.m_yousya.zei_keisan_kbn')"
                  :data="$zeiKeisanKbn"
                  nameInput="zei_keisan_kbn"
                  class="size-L"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-select-group
                  :label="trans('attributes.m_yousya.zei_hasu_kbn')"
                  :list="config()->get('params.options.m_yousya.zei_hasu_kbn')"
                  :data="$zeiHasuKbn"
                  nameInput="zei_hasu_kbn"
                  class="size-L"
                />
              </div>
              <div class="col-12 col-md-6">
                <x-select-group
                  :label="trans('attributes.m_yousya.zei_hasu_tani')"
                  :list="config()->get('params.options.m_yousya.zei_hasu_tani')"
                  :data="$zeiHasuTani"
                  nameInput="zei_hasu_tani"
                  class="size-M"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <div class="row">
                  <label class="col-12 col-md-2 col-form-label text-nowrap ">{{ trans('attributes.m_yousya.kaikake_saki_cd') }}</label>
                  <div class="col-12 col-md-10 group-input d-flex flex-wrap">
                    <input type="text" name="kaikake_saki_cd" class="form-control size-L"  maxlength="255" value="{{ $kaikakeSakiCd }}"
                    onkeyup="suggestionForm(this, 'kaikake_saki_cd', ['yousya_cd', 'yousya_ryaku_nm', 'kana'], {yousya_cd: 'kaikake_saki_cd', yousya_ryaku_nm: 'kaikake_saki_nm'}, $(this).parent() )"
                           autocomplete="off">
                    <input type="text" name="kaikake_saki_nm" class="form-control size-2L" readonly value="{{ $kaikakeSakiNm }}">
                    <ul class="suggestion modify-position-suggest"></ul>
                    <div class="error_message err-suggest">
                      <span class=" text-danger" id="error-kaikake_saki_cd"></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <x-select-group
                  :label="trans('attributes.m_yousya.siharai_nyuryoku_umu_kbn')"
                  :list="config()->get('params.options.m_yousya.siharai_nyuryoku_umu_kbn')"
                  :data="$siharaiNyuryokuUmuKbn"
                  nameInput="siharai_nyuryoku_umu_kbn"
                  class="size-L"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_yousya.siharai1_dd')"
                  nameInput="siharai1_dd"
                  class="size-S"  maxlength="255" value="{{ $siharai1Dd }}"
                />
              </div>
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_yousya.siharai2_dd')"
                  nameInput="siharai2_dd"
                  class="size-S"  maxlength="255" value="{{ $siharai2Dd }}"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_yousya.comennt')"
                  nameInput="comennt"
                  class="size-3L"  maxlength="255" value="{{ $comennt }}"
                />
              </div>
              <div class="col-12 col-md-6">
                <x-select-group
                  :label="trans('attributes.m_yousya.kensaku_kbn')"
                  :list="config()->get('params.options.m_yousya.kensaku_kbn')"
                  :data="$kensakuKbn"
                  nameInput="kensaku_kbn"
                  class="size-L"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_yousya.mail')"
                  nameInput="mail"
                  class="size-3L"  maxlength="255" value="{{ $mail }}"
                />
              </div>
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_yousya.haisya_biko')"
                  nameInput="haisya_biko"
                  class="size-L"  maxlength="255" value="{{ $haisyaBiko }}"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_yousya.biko')"
                  nameInput="biko"
                  class="size-5L"  maxlength="255" value="{{ $biko }}"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-select-group
                  :label="trans('attributes.m_yousya.kyumin_flg')"
                  :list="config()->get('params.options.m_yousya.kyumin_flg')"
                  :data="$kyuminFlg"
                  nameInput="kyumin_flg"
                  class="size-S"
                  :prompt="false"
                />
              </div>
            </div>
          </div>


        <div class="button-form">
          <button class="btn btn-back min-wid-110" id="backButton" data-href="{{route('master.yousya.index')}}" onclick="redirectBack(this, 'YousyaIndex')" type="button">{{ trans('app.labels.btn-back') }}</button>
          <button class="btn btn-insert min-wid-110" type="button" onclick="submitForm(this)">@if(!empty($yousya)) {{trans('app.labels.btn-update')}} @else {{trans('app.labels.btn-insert')}} @endif</button>
          @if(!empty($yousya))
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

  @if(!empty($yousya))
    function handleDelete() {
      $.ajax({
        url: '{{route('master.yousya.destroy', ['yousyaCd' => $yousya->yousya_cd])}}',
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
