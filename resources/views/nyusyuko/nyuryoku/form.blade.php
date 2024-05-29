@extends('layouts.master')
@section('css')
  <style>
    .flex-suggestion
    {
      flex-wrap: nowrap; display: flex; flex: 1; position: relative; 
    }
  </style>
@endsection
@section('page-content')
@php
  $formSearchInputsIndex= null;
  if(!empty($request->InputsIndex))
  {
    $formSearchInputsIndex = $request->InputsIndex;
  }


@endphp
  <div class="card form-custom form-master">
    <form method="post" action="{{ !empty($meisyo) ? route('master.meisyo.update', ['meisyoCd' => $meisyo->meisyo_cd, 'meisyoKbn' => $meisyo->meisyo_kbn]) : route('master.meisyo.store') }}">
      <div class="card-body" style="position: relative;">
        <h4 class="card-title">@if(!empty($meisyo)){{ trans('app.screen.master.meisyo.edit') }}@else{{ trans('app.screen.master.meisyo.create') }}@endif</h4>
          {{csrf_field()}}
          @if(!empty($formSearchInputsIndex))
            @foreach($formSearchInputsIndex as $key => $value)
              <input type="hidden" name="InputsIndex[{{ $key }}]" value="{{ $value }}">
            @endforeach
          @endif
          <div class="form-group">
            <div class="row">
              <div class="col-12 col-md-6">
                <div class="row">
                  <label class="col-12 col-md-2 col-form-label text-nowrap ">荷　主</label>
                  <div class="col-12 col-md-8 group-input">
                    <div class="flex-suggestion" style="">
                      <input type="text" class="form-control size-M" name="ninusi_cd" style="width: 100px; border-bottom-right-radius: 0; border-top-right-radius: 0" onkeyup="suggestionForm(this, 'ninusi_cd', ['ninusi_cd', 'kana', 'ninusi_ryaku_nm'], {'ninusi_cd': 'ninusi_cd', 'ninusi_ryaku_nm': 'ninusi_ryaku_nm'}, $(this).parent())" style="" autocomplete="off">

                      <input class="form-control size-L" name="ninusi_ryaku_nm" style="border-bottom-left-radius: 0; border-top-left-radius: 0" onkeyup="suggestionForm(this, 'ninusi_nm', ['ninusi_cd', 'kana', 'ninusi_ryaku_nm'], {'ninusi_cd': 'ninusi_cd', 'ninusi_ryaku_nm': 'ninusi_ryaku_nm'}, $(this).parent())" autocomplete="off">
                      <ul class="suggestion"></ul>
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
                  <label class="col-12 col-md-2 col-form-label text-nowrap ">荷届先</label>
                  <div class="col-12 col-md-8 group-input">
                    <div class="flex-suggestion" style="">
                      <input type="text" class="form-control size-M" name="hachaku_cd" style="width: 100px; border-bottom-right-radius: 0; border-top-right-radius: 0" onkeyup="suggestionForm(this, 'hachaku_cd', ['hachaku_cd', 'kana', 'todokesaki_nm'], {'hachaku_cd': 'hachaku_cd', 'todokesaki_nm': 'todokesaki_nm'}, $(this).parent())" style="" autocomplete="off">

                      <input class="form-control size-L" name="todokesaki_nm" style="border-bottom-left-radius: 0; border-top-left-radius: 0" onkeyup="suggestionForm(this, 'todokesaki_nm', ['hachaku_cd', 'kana', 'todokesaki_nm'], {'hachaku_cd': 'hachaku_cd', 'todokesaki_nm': 'todokesaki_nm'}, $(this).parent())" autocomplete="off">
                      <ul class="suggestion"></ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="row">
                  <label class="col-12 col-md-2 col-form-label text-nowrap ">荷送り人</label>
                  <div class="col-12 col-md-8 group-input">
                    <div class="flex-suggestion" style="">
                      <input type="text" class="form-control size-M" name="ninusi_cd" style="width: 100px; border-bottom-right-radius: 0; border-top-right-radius: 0" onkeyup="suggestionForm(this, 'ninusi_cd', ['ninusi_cd', 'kana', 'ninusi_ryaku_nm'], {'ninusi_cd': 'ninusi_cd', 'ninusi_ryaku_nm': 'ninusi_ryaku_nm'}, $(this).parent())" style="" autocomplete="off">

                      <input class="form-control size-L" name="ninusi_ryaku_nm" style="border-bottom-left-radius: 0; border-top-left-radius: 0" onkeyup="suggestionForm(this, 'ninusi_nm', ['ninusi_cd', 'kana', 'ninusi_ryaku_nm'], {'ninusi_cd': 'ninusi_cd', 'ninusi_ryaku_nm': 'ninusi_ryaku_nm'}, $(this).parent())" autocomplete="off">
                      <ul class="suggestion"></ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-6">
                <div class="row align-items-center">
                  <label class="col-md-2">
                    住　所     
                  </label>
                  <div class="col-md-10">
                    <input type="text" class="form-control" name="">
                    <input type="text" class="form-control" name="">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="row align-items-center">
                  <label class="col-md-2">
                    住　所     
                  </label>
                  <div class="col-md-10">
                    <input type="text" class="form-control" name="">
                    <input type="text" class="form-control" name="">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-md-6">
                <div class="row">
                  <label class="col-12 col-md-2 col-form-label text-nowrap ">電話番号</label>
                  <div class="col-md-4">
                    <input type="text" class="form-control" name="haitatu_tel">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="row">
                  <label class="col-12 col-md-2 col-form-label text-nowrap ">電話番号</label>
                  <div class="col-md-4">
                    <input type="text" class="form-control" name="haitatu_tel">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-md-6">
                <div class="row">
                  <label class="col-12 col-md-2 col-form-label text-nowrap ">伝票日付</label>
                  <div class="col-md-3">
                    <input type="text" class="form-control" name="denpyo_dt">
                  </div>
                  <label class="col-12 col-md-2 col-form-label text-nowrap ">起算日</label>
                  <div class="col-md-3">
                    <input type="text" class="form-control" name="kisan_dt">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="row">
                  <label class="col-12 col-md-2 col-form-label text-nowrap ">納品日</label>
                  <div class="col-md-3">
                    <input type="text" class="form-control" name="nouhin_dt">
                  </div>
                  <label class="col-12 col-md-2 col-form-label text-nowrap ">荷役料負担</label>
                  <div class="col-md-3">
                    <select class="form-control" name="nieki_futan_kbn">
                      <option value=""></option>
                      <option value="0">無し</option>
                      <option value="1">有り</option>
                    </select>
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

</script>
@endsection
