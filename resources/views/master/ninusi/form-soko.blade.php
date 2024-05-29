@php
$sokoInfo = [
    'lot_kanri_kbn' => ['name' => 'lot_kanri_kbn', 'size' => 'L'],
    'lot1_nm'=> ['name' => 'lot1_nm','size' => 'L'],
    'lot2_nm'=> ['name' => 'lot2_nm','size' => 'L'],
    'lot3_nm'=> ['name' => 'lot3_nm','size' => 'L'],

    'kisei_kbn'=> ['name' => 'kisei_kbn', 'size' => 'M'],
    'ki1_from'=> ['name' => 'ki1_from', 'size' => 'S', 'maxlength' => 2],
    'ki1_to'=> ['name' => 'ki1_to','size' => 'S', 'maxlength' => 2],
    'ki2_from'=> ['name' => 'ki2_from', 'size' => 'S', 'maxlength' => 2],
    'ki2_to'=> ['name' => 'ki2_to','size' => 'S', 'maxlength' => 2],
    'ki3_from'=> ['name' => 'ki3_from', 'size' => 'S', 'maxlength' => 2],
    'ki3_to'=> ['name' => 'ki3_to','size' => 'S', 'maxlength' => 2],

    'sekisu_kbn'=> ['name' => 'sekisu_kbn','size' => '2L'],
    'soko_hokan_hasu_kbn'=> ['name' => 'soko_hokan_hasu_kbn','size' => 'L'],
    'soko_hokan_hasu_tani'=> ['name' => 'soko_hokan_hasu_tani','size' => 'L'],
    'hokanryo_meisyo'=> ['name' => 'hokanryo_meisyo','size' => 'L'],

    'nieki_sansyutu_kbn'=> ['name' => 'nieki_sansyutu_kbn','size' => 'L'],
    'nieki_hokan_hasu_kbn'=> ['name' => 'nieki_hokan_hasu_kbn','size' => 'L'],
    'nieki_hokan_hasu_tani'=> ['name' => 'nieki_hokan_hasu_tani','size' => 'L'],
    'nieki_nyuko_nm'=> ['name' => 'nieki_nyuko_nm','size' => 'L'],
    'nieki_syuko_nm'=> ['name' => 'nieki_syuko_nm','size' => 'L'],
    'nieki_nieki_nm'=> ['name' => 'nieki_nieki_nm','size' => 'L'],

    'soko_seikyu_cd'=> ['name' => 'soko_seikyu_cd','size' => ['cd' => 'L','nm' => '2L'], 'suggest' => 'soko_seikyu_nm'],
    'soko_bumon_cd'=> ['name' => 'soko_bumon_cd','size' => ['cd' => 'M','nm' => 'L'], 'suggest' => 'soko_bumon_nm'],

    'nyuko_tanka'=> ['name' => 'nyuko_tanka','size' => 'L'],
    'syuko_tanka'=> ['name' => 'syuko_tanka','size' => 'L'],
    'hokan_tanka'=> ['name' => 'hokan_tanka','size' => 'L'],

];
@endphp

<h4 class="card-title mt-5">倉庫関連情報</h4>
<div class="form-group">
  <div class="row">
    <div class="col-12 col-md-6">
      <div class="row">
        @php $value = $sokoInfo['lot_kanri_kbn']; @endphp
        <label class="col-12 col-md-3 col-form-label text-nowrap">
          {{ trans("attributes.m_ninusi.{$value['name']}") }}
        </label>
        <div class="col-12 col-md-9 group-input" style="margin-bottom: 1px">
          @include('master.ninusi.inputs.select', ['value'=>$value, 'listOption'=>$listOption])
          @include('master.ninusi.inputs.error-message', ['value'=>$value])
        </div>
      </div>
      @for($i=1; $i<=3; $i++)
      <div class="row">
        @php $value = $sokoInfo["lot{$i}_nm"]; @endphp
        <label class="col-12 col-md-3 col-form-label text-nowrap">
          {{ trans("attributes.m_ninusi.{$value['name']}") }}
        </label>
        <div class="col-12 col-md-9 group-input">
          @include('master.ninusi.inputs.text-field', ['value'=>$value])
          @include('master.ninusi.inputs.error-message', ['value'=>$value])
        </div>
      </div>
      @endfor
    </div>
    <div class="col-12 col-md-6">
      @php $value = $sokoInfo['kisei_kbn']; @endphp
      <div class="row">
        <label class="col-12 col-md-3 col-form-label text-nowrap">
          {{ trans("attributes.m_ninusi.{$value['name']}") }}
        </label>
        <div class="col-12 col-md-9 group-input" style="margin-bottom: 1px">
          @include('master.ninusi.inputs.select', ['value'=>$value, 'listOption'=>$listOption])
          @include('master.ninusi.inputs.error-message', ['value'=>$value])
        </div>
      </div>

      @for($i=1; $i<=3; $i++)
        <div class="row">
          <label class="col-12 col-md-3 col-form-label text-nowrap">
            {{$i}}期期間
          </label>
          <div class="col-12 col-md-9 group-input d-flex align-items-start">
            <div class="group-input ">
              @php $value = $sokoInfo["ki{$i}_from"]; @endphp
              @include('master.ninusi.inputs.text-field', ['value'=>$value])
              @include('master.ninusi.inputs.error-message', ['value'=>$value])
            </div>
            <span class="form-control range"> ～ </span>
            <div class="group-input">
              @php $value = $sokoInfo["ki{$i}_to"]; @endphp
              @include('master.ninusi.inputs.text-field', ['value'=>$value])
              @include('master.ninusi.inputs.error-message', ['value'=>$value])
            </div>
          </div>
        </div>
      @endfor
    </div>
  </div>
</div>
<div class="form-group">
  <div class="row">
    <div class="col-12 col-md-6">
      @foreach(['sekisu_kbn','soko_hokan_hasu_kbn','soko_hokan_hasu_tani'] as $field)
        <div class="row">
          @php $value = $sokoInfo[$field]; @endphp
          <label class="col-12 col-md-3 col-form-label text-nowrap">
            {{ trans("attributes.m_ninusi.{$value['name']}") }}
          </label>
          <div class="col-12 col-md-9 group-input" style="margin-bottom: 1px; margin-top: 1px">
            @include('master.ninusi.inputs.select', ['value'=>$value, 'listOption'=>$listOption])
            @include('master.ninusi.inputs.error-message', ['value'=>$value])
          </div>
        </div>
      @endforeach
      <div class="row">
        @php $value = $sokoInfo['hokanryo_meisyo']; @endphp
        <label class="col-12 col-md-3 col-form-label text-nowrap">
          {{ trans("attributes.m_ninusi.{$value['name']}") }}
        </label>
        <div class="col-12 col-md-9 group-input">
          @include('master.ninusi.inputs.text-field', ['value'=>$value])
          @include('master.ninusi.inputs.error-message', ['value'=>$value])
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6">
      @foreach(['nieki_sansyutu_kbn','nieki_hokan_hasu_kbn','nieki_hokan_hasu_tani'] as $field)
        <div class="row">
          @php $value = $sokoInfo[$field]; @endphp
          <label class="col-12 col-md-3 col-form-label text-nowrap">
            {{ trans("attributes.m_ninusi.{$value['name']}") }}
          </label>
          <div class="col-12 col-md-9 group-input" style="margin-bottom: 1px; margin-top: 1px">
            @include('master.ninusi.inputs.select', ['value'=>$value, 'listOption'=>$listOption])
            @include('master.ninusi.inputs.error-message', ['value'=>$value])
          </div>
        </div>
      @endforeach

      <div class="row">
        <label class="col-12 col-md-3 col-form-label text-nowrap">
          荷役料請求書名称
        </label>
        <div class="col-12 col-md-9">
          @foreach(['nieki_nyuko_nm', 'nieki_syuko_nm', 'nieki_nieki_nm'] as $field)
            <div class="row col-auto group-input d-flex align-items-start">
              @php $value = $sokoInfo[$field]; @endphp
              <label class="col-form-label text-nowrap mb-0">
                {{ trans("attributes.m_ninusi.{$value['name']}") }}
              </label>
              <div class="col group-input">
                @include('master.ninusi.inputs.text-field', ['value'=>$value])
                @include('master.ninusi.inputs.error-message', ['value'=>$value])
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
<div class="form-group">
  <div class="row">
    <div class="col-12 col-md-6">
      @php $value = $sokoInfo['soko_seikyu_cd']; @endphp
      <div class="row">
        <label class="col-12 col-md-3 col-form-label text-nowrap">
          {{ trans("attributes.m_ninusi.{$value['name']}") }}
        </label>
        <div class="col-12 col-md-9 group-input">
          @include('master.ninusi.inputs.suggest', ['value'=>$value])
          @include('master.ninusi.inputs.error-message', ['value'=>$value])
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6">
      @php $value = $sokoInfo['soko_bumon_cd']; @endphp
      <div class="row">
        <label class="col-12 col-md-3 col-form-label text-nowrap">
          {{ trans("attributes.m_ninusi.{$value['name']}") }}
        </label>
        <div class="col-12 col-md-9 group-input">
          @include('master.ninusi.inputs.suggest', ['value'=>$value])
          @include('master.ninusi.inputs.error-message', ['value'=>$value])
        </div>
      </div>
    </div>
  </div>
</div>

<div class="form-group">
  <div class="row">
    <div class="col-12 col-md-6">
      @foreach(['nyuko_tanka', 'syuko_tanka', 'hokan_tanka'] as $field)
        @php $value = $sokoInfo[$field]; @endphp
        <div class="row">
          <label class="col-12 col-md-3 col-form-label text-nowrap">
            {{ trans("attributes.m_ninusi.{$value['name']}") }}
          </label>
          <div class="col-12 col-md-9 group-input">
            @include('master.ninusi.inputs.text-field', ['value'=>$value])
            @include('master.ninusi.inputs.error-message', ['value'=>$value])
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>
