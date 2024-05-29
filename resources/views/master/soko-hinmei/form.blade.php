@extends('layouts.master')
@section('css')
  <style>
    .modify-position-suggest {
      margin-top: -12px;
      margin-left: 15px;
      width: 95%;
    }
  </style>
@endsection
@section('page-content')
@php
  $ninusiCd         = null;
  $ninusiNm         = null;
  $hinmeiCd         = null;
  $hinmeiNm         = null;
  $kana             = null;
  $kikaku           = null;
  $ondo             = null;
  $zaikoKbn         = null;
  $caseCd           = null;
  $caseNm           = null;
  $irisu            = null;
  $hasuKiriage      = null;
  $baraTaniJuryo    = null;
  $baraTani         = null;
  $baraTaniNm       = null;
  $ukeTanka         = null;
  $keisanKb         = null;
  $seikyuKeta       = null;
  $seikyuBunbo      = null;
  $niekiNyukoTanka  = null;
  $hokanryoKin      = null;
  $bumonCd          = null;
  $bumonNm          = null;
  $seikyuHinmeiCd   = null;
  $seikyuHinmeiNm   = null;
  $niekiSyukoTanka  = null;
  $kyuminFlg        = 0;
  $SokoHinmeiIndex  = null;
  $readOnly         = null;
  $url              = route('master.soko_hinmei.store');

  if(!empty($request->SokoHinmeiIndex))
  {
    $formSearchSokoHinmeiIndex = $request->SokoHinmeiIndex;
  }
  if (!empty($sokoHinmei) && $mode == 'edit') {
    $ninusiCd         = $sokoHinmei->ninusi_cd;
    $ninusiNm         = $sokoHinmei->ninusi_nm;
    $hinmeiCd         = $sokoHinmei->hinmei_cd;
    $hinmeiNm         = $sokoHinmei->hinmei_nm;
    $kana             = $sokoHinmei->kana;
    $kikaku           = $sokoHinmei->kikaku;
    $ondo             = $sokoHinmei->ondo;
    $zaikoKbn         = $sokoHinmei->zaiko_kbn;
    $caseCd           = $sokoHinmei->case_cd;
    $caseNm           = $sokoHinmei->case_nm;
    $irisu            = $sokoHinmei->irisu;
    $hasuKiriage      = $sokoHinmei->hasu_kiriage;
    $baraTaniJuryo    = $sokoHinmei->bara_tani_juryo;
    $baraTani         = $sokoHinmei->bara_tani;
    $baraTaniNm       = $sokoHinmei->bara_tani_nm;
    $ukeTanka         = $sokoHinmei->uke_tanka;
    $keisanKb         = $sokoHinmei->keisan_kb;
    $seikyuKeta       = $sokoHinmei->seikyu_keta;
    $seikyuBunbo      = $sokoHinmei->seikyu_bunbo;
    $niekiSyukoTanka  = $sokoHinmei->nieki_syuko_tanka;
    $niekiNyukoTanka  = $sokoHinmei->nieki_nyuko_tanka;
    $hokanryoKin      = $sokoHinmei->hokanryo_kin;
    $bumonCd          = $sokoHinmei->bumon_cd;
    $bumonNm          = $sokoHinmei->bumon_nm;
    $seikyuHinmeiCd   = $sokoHinmei->seikyu_hinmei_cd;
    $seikyuHinmeiNm   = $sokoHinmei->seikyu_hinmei_nm;
    $kyuminFlg        = 0;
    $readOnly         = 'readonly';
    $url              = route('master.soko_hinmei.update', ['ninusiCd' => $ninusiCd, 'hinmeiCd' => $hinmeiCd]);
  }

  if (!empty($sokoHinmei) && $mode == 'copy') {
    $ninusiCd         = $sokoHinmei->ninusi_cd;
    $ninusiNm         = $sokoHinmei->ninusi_nm;
    $hinmeiCd         = $sokoHinmei->hinmei_cd;
    $hinmeiNm         = $sokoHinmei->hinmei_nm;
    $kana             = $sokoHinmei->kana;
    $kikaku           = $sokoHinmei->kikaku;
    $ondo             = $sokoHinmei->ondo;
    $zaikoKbn         = $sokoHinmei->zaiko_kbn;
    $caseCd           = $sokoHinmei->case_cd;
    $caseNm           = $sokoHinmei->case_nm;
    $irisu            = $sokoHinmei->irisu;
    $hasuKiriage      = $sokoHinmei->hasu_kiriage;
    $baraTaniJuryo    = $sokoHinmei->bara_tani_juryo;
    $baraTani         = $sokoHinmei->bara_tani;
    $baraTaniNm       = $sokoHinmei->bara_tani_nm;
    $ukeTanka         = $sokoHinmei->uke_tanka;
    $keisanKb         = $sokoHinmei->keisan_kb;
    $seikyuKeta       = $sokoHinmei->seikyu_keta;
    $seikyuBunbo      = $sokoHinmei->seikyu_bunbo;
    $niekiSyukoTanka  = $sokoHinmei->nieki_syuko_tanka;
    $niekiNyukoTanka  = $sokoHinmei->nieki_nyuko_tanka;
    $hokanryoKin      = $sokoHinmei->hokanryo_kin;
    $bumonCd          = $sokoHinmei->bumon_cd;
    $bumonNm          = $sokoHinmei->bumon_nm;
    $seikyuHinmeiCd   = $sokoHinmei->seikyu_hinmei_cd;
    $seikyuHinmeiNm   = $sokoHinmei->seikyu_hinmei_nm;
    $kyuminFlg        = 0;
    $url              = route('master.soko_hinmei.post_copy', ['ninusiCd' => $ninusiCd, 'hinmeiCd' => $hinmeiCd]);
  }
@endphp
<x-error-summary/>
  <div class="card form-custom form-master">
    <form method="post" action="{{ $url }}">
      <div class="card-body" style="position: relative;">
        <h4 class="card-title">@if(!empty($sokoHinmei)){{ trans('app.screen.master.soko_hinmei.edit') }}@else{{ trans('app.screen.master.soko_hinmei.create') }}@endif</h4>
          {{csrf_field()}}
          @if(!empty($formSearchSokoHinmeiIndex))
            @foreach($formSearchSokoHinmeiIndex as $key => $value)
              <input type="hidden" name="SokoHinmeiIndex[{{ $key }}]" value="{{ $value }}">
            @endforeach
          @endif
          <input type="hidden" name="mode" value="{{ $mode }}">
          <div class="form-group row">
            <div class="col-12 col-md-6">
              <div class="form-group row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">{{ trans('attributes.m_soko_hinmei.ninusi_cd') }}</label>
                <div class="col-12 col-lg-10 formCol input-group form-inline">
                  <div class="group-input">
                    <div class="input-group flex-nowrap">
                      <input type="text" name="ninusi_cd" class="form-control size-L"  maxlength="255" value="{{ $ninusiCd }}" {{ $readOnly }}
                      onkeyup="suggestionForm(this, 'ninusi_cd', ['ninusi_cd', 'ninusi_nm', 'kana'], {ninusi_cd: 'ninusi_cd', ninusi_nm: 'ninusi_nm'}, $(this).parent() )"
                              autocomplete="off" >
                      <input type="text" name="ninusi_nm" class="form-control size-2L" readonly value="{{ $ninusiNm }}">
                      <ul class=" suggestion"></ul>
                    </div>
                    <div class="error_message mb-0"><span class=" text-danger" id="error-ninusi_cd"></span></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_soko_hinmei.hinmei_cd')"
                  :readOnly="$readOnly"
                  nameInput="hinmei_cd"
                  class="size-L"  maxlength="255" value="{{ $hinmeiCd }}"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_soko_hinmei.kana')"
                  nameInput="kana"
                  class="size-L"  maxlength="255" value="{{ $kana }}"
                />
              </div>
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_soko_hinmei.hinmei_nm')"
                  nameInput="hinmei_nm"
                  class="size-2L"  maxlength="255" value="{{ $hinmeiNm }}"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_soko_hinmei.kikaku')"
                  nameInput="kikaku"
                  class="size-2L"  maxlength="255" value="{{ $kikaku }}"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-select-group
                  :label="trans('attributes.m_soko_hinmei.ondo')"
                  :list="config()->get('params.options.m_soko_hinmei.ondo')"
                  :data="$ondo"
                  nameInput="ondo"
                  class="size-S"
                />
              </div>
              <div class="col-12 col-md-6">
                <x-select-group
                  :label="trans('attributes.m_soko_hinmei.zaiko_kbn')"
                  :list="config()->get('params.options.m_soko_hinmei.zaiko_kbn')"
                  :data="$zaikoKbn"
                  nameInput="zaiko_kbn"
                  class="size-L"
                />
              </div>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-12 col-md-6">
              <div class="form-group row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">{{ trans('attributes.m_soko_hinmei.case_cd') }}</label>
                <div class="col-12 col-lg-10 formCol input-group form-inline">
                  <div class="group-input">
                    <div class="input-group flex-nowrap">
                      <input type="text" name="case_cd" class="form-control size-S"  maxlength="255" value="{{ $caseCd }}"
                              onkeyup="suggestionForm(this, 'case_cd', ['case_cd', 'case_nm', 'kana'], {case_cd: 'case_cd', case_nm: 'case_nm'}, $(this).parent() )"
                                     autocomplete="off" >
                      <input type="text" name="case_nm" class="form-control size-S" readonly value="{{ $caseNm }}">
                      <ul class=" suggestion"></ul>
                    </div>
                    <div class="error_message mb-0"><span class=" text-danger" id="error-case_cd"></span></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_soko_hinmei.irisu')"
                  nameInput="irisu"
                  class="size-S"  maxlength="255" value="{{ $irisu }}"
                />
              </div>
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_soko_hinmei.hasu_kiriage')"
                  nameInput="hasu_kiriage"
                  class="size-S"  maxlength="255" value="{{ $hasuKiriage }}"
                />
              </div>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-12 col-md-6">
              <div class="form-group row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">{{ trans('attributes.m_soko_hinmei.bara_tani') }}</label>
                <div class="col-12 col-lg-10 formCol input-group form-inline">
                  <div class="group-input">
                    <div class="input-group flex-nowrap">
                      <input type="text" name="bara_tani" class="form-control size-S"  maxlength="255" value="{{ $baraTani }}"
                              onkeyup="suggestionForm(this, 'bara_tani', ['bara_tani', 'bara_tani_nm', 'kana'], {bara_tani: 'bara_tani', bara_tani_nm: 'bara_tani_nm'}, $(this).parent() )"
                                     autocomplete="off" >
                      <input type="text" name="bara_tani_nm" class="form-control size-S" readonly value="{{ $baraTaniNm }}">
                      <ul class=" suggestion"></ul>
                    </div>
                    <div class="error_message mb-0"><span class=" text-danger" id="error-bara_tani"></span></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_soko_hinmei.bara_tani_juryo')"
                  nameInput="bara_tani_juryo"
                  class="size-L"  maxlength="255" value="{{ $baraTaniJuryo }}"
                />
              </div>
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_soko_hinmei.uke_tanka')"
                  nameInput="uke_tanka"
                  class="size-L"  maxlength="255" value="{{ $ukeTanka }}"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <div class="form-group row">
                  <label class="col-12 col-md-2 col-form-label text-nowrap ">{{ trans('attributes.m_soko_hinmei.seikyu_hinmei_cd') }}</label>
                  <div class="col-12 col-lg-10 formCol input-group form-inline">
                    <div class="group-input">
                      <div class="input-group flex-nowrap">
                        <input type="text" name="seikyu_hinmei_cd" class="form-control size-L"  maxlength="255" value="{{ $seikyuHinmeiCd }}"
                                onkeyup="suggestionForm(this, 'seikyu_hinmei_cd', ['seikyu_hinmei_cd', 'seikyu_hinmei_nm', 'kana'], {seikyu_hinmei_cd: 'seikyu_hinmei_cd', seikyu_hinmei_nm: 'seikyu_hinmei_nm'}, $(this).parent() )"
                                        autocomplete="off" >
                        <input type="text" name="seikyu_hinmei_nm" class="form-control size-2L" readonly value="{{ $seikyuHinmeiNm }}">
                        <ul class=" suggestion"></ul>
                      </div>
                      <div class="error_message mb-0"><span class=" text-danger" id="error-seikyu_hinmei_cd"></span></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <x-select-group
                  :label="trans('attributes.m_soko_hinmei.keisan_kb')"
                  :list="config()->get('params.options.m_soko_hinmei.keisan_kb')"
                  :data="$keisanKb"
                  nameInput="keisan_kb"
                  class="size-L"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-select-group
                  :label="trans('attributes.m_soko_hinmei.seikyu_keta')"
                  :list="config()->get('params.options.m_soko_hinmei.seikyu_keta')"
                  :data="$seikyuKeta"
                  nameInput="seikyu_keta"
                  class="size-L"
                />
              </div>
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_soko_hinmei.seikyu_bunbo')"
                  nameInput="seikyu_bunbo"
                  class="size-M"  maxlength="255" value="{{ $seikyuBunbo }}"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_soko_hinmei.nieki_nyuko_tanka')"
                  nameInput="nieki_nyuko_tanka"
                  class="size-L"  maxlength="255" value="{{ $niekiNyukoTanka }}"
                />
              </div>
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_soko_hinmei.nieki_syuko_tanka')"
                  nameInput="nieki_syuko_tanka"
                  class="size-L"  maxlength="255" value="{{ $niekiSyukoTanka }}"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <x-input-group
                  :label="trans('attributes.m_soko_hinmei.hokanryo_kin')"
                  nameInput="hokanryo_kin"
                  class="size-L"  maxlength="255" value="{{ $hokanryoKin }}"
                />
              </div>
              <div class="col-12 col-md-6">
                <div class="form-group row">
                  <label class="col-12 col-md-2 col-form-label text-nowrap ">{{ trans('attributes.m_soko_hinmei.bumon_cd') }}</label>
                  <div class="col-12 col-lg-10 formCol input-group form-inline">
                    <div class="group-input">
                      <div class="input-group flex-nowrap">
                        <input type="text" name="bumon_cd" class="form-control size-M"  maxlength="255" value="{{ $bumonCd }}"
                                onkeyup="suggestionForm(this, 'bumon_cd', ['bumon_cd', 'bumon_nm', 'kana'], {bumon_cd: 'bumon_cd', bumon_nm: 'bumon_nm'}, $(this).parent() )"
                                       autocomplete="off" >
                        <input type="text" name="bumon_nm" class="form-control size-L" readonly value="{{ $bumonNm }}">
                        <ul class=" suggestion"></ul>
                      </div>
                      <div class="error_message mb-0"><span class=" text-danger" id="error-bumon_cd"></span></div>
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
                  :label="trans('attributes.m_soko_hinmei.kyumin_flg')"
                  :list="config()->get('params.options.m_soko_hinmei.kyumin_flg')"
                  :data="$kyuminFlg"
                  nameInput="kyumin_flg"
                  class="size-S"
                  :prompt="false"
                />
              </div>
            </div>
          </div>

        <div class="button-form">
          <button class="btn btn-back min-wid-110" id="backButton" data-href="{{route('master.soko_hinmei.index')}}" onclick="redirectBack(this, 'SokoHinmeiIndex')" type="button">{{ trans('app.labels.btn-back') }}</button>
          <button class="btn btn-insert min-wid-110" type="button" onclick="submitForm(this)">@if(!empty($sokoHinmei)) {{trans('app.labels.btn-update')}} @else {{trans('app.labels.btn-insert')}} @endif</button>
          @if(!empty($sokoHinmei) && $mode == 'edit')
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

  @if(!empty($sokoHinmei))
    function handleDelete() {
      $.ajax({
        url: '{{route('master.soko_hinmei.destroy', ['ninusiCd' => $sokoHinmei->ninusi_cd, 'hinmeiCd' => $sokoHinmei->hinmei_cd])}}',
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
