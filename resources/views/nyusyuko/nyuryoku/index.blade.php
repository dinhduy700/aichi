@extends('layouts.master')
@section('css')
<link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
<style>
  #ui-datepicker-div {
    z-index: 1051 !important;
  }
  .label-search-uriage {
    width: 70px;
    text-align: left;
  }
  .flex-g {
    display: flex;
    grid-gap: 10px;
    flex-wrap: nowrap;
  }
  #formInputs .col-form-label
  {
    margin-bottom: 0 !important;
  }
  #formInputs *[disabled]
  {
    cursor: not-allowed;
    opacity: 0.5;
  }
  .flex-suggestion
  {
    flex-wrap: nowrap; display: flex; flex: 1; position: relative; 
  }
  .modal-head, .modal-zaiko
  {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255, 0.8); z-index: 9999; display: none;
    
  }
  .modal-head.active , .modal-zaiko.active
  {
    display: flex;
  }
  .modal-head .content, .modal-zaiko .content
  {
    height: 100%; width: 100%; display: grid; grid-template-rows: 50px auto;
    
  }
  .modal-head .animation, .modal-zaiko .animation
  {
    animation: modal-show 0.3s ease;
  }
  #settingButtonCopyLeft 
  {
    display: none;
  }
  .mt-5px
  {
    margin-top: 5px;
  }
  @keyframes modal-show {
    from {
      opacity: 0;
      transform: translateY(-50px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  html.hideScroll 
  {
    overflow: hidden;
  }
  .class-show
  {
    display: none;
  }
  .class-show.active
  {
    display: block;
  }
  #totalRowsCopy
  {
    display: none;
  }
  #table input[readonly] {
    border: none !important;
  }
  #totalRowsCopy + span
  {
    display: none;
  }

  select[name="hed_nyusyuko_kbn"] + .select2 .select2-container .select2-selection--single
  {
    
  }
  select[name="hed_nyusyuko_kbn"] + .select2 .select2-selection.select2-selection--single
  {
    padding: 0 5px;
    line-height: 33px;
    height: 33px;
    border-color: #CED4DA;
  }

  select[name="hed_nyusyuko_kbn"] + .select2.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 33px;
  }

  select[name="hed_nyusyuko_kbn"][disabled] + .select2 {
    opacity: 0.5;
  }
</style>
@endsection
@section('page-content')
<div>
  <form id="formInputs" class="form-custom">
    <div class="card">
      <div class="card-body">
        <div>
          <div class="row">
            <div class="col-lg-3">
              <div class="form-group">
                <div class="group-input" style="display: flex;  grid-gap: 10px; flex-wrap: nowrap;">
                  <label class="col-form-label label-search-uriage" >部門</label>
                  <div class="group-input" style="flex-wrap: wrap; display: flex; flex: 1; position: relative; ">
                    <input type="text" class=" active-head form-control size-M" name="hed_bumon_cd" style="width: 100px; border-bottom-right-radius: 0; border-top-right-radius: 0" onkeyup="suggestionForm(this, 'bumon_cd', ['bumon_cd', 'kana', 'bumon_nm'], {'bumon_cd': 'hed_bumon_cd', 'bumon_nm': 'hed_bumon_nm'}, $(this).parent()), keyupBumon(this, event)" style="" autocomplete="off">

                    <input class="active-head form-control size-L" name="hed_bumon_nm" style="border-bottom-left-radius: 0; border-top-left-radius: 0" onkeyup="suggestionForm(this, 'bumon_nm', ['bumon_cd', 'kana', 'bumon_nm'], {'bumon_cd': 'hed_bumon_cd', 'bumon_nm': 'hed_bumon_nm'}, $(this).parent()), keyupBumon(this, event)" autocomplete="off">
                    <ul class="suggestion"></ul>
                    <div class="" style="width: 100%">
                      <span class="text-danger error_message"  id="error-hed_bumon_cd"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-3">
              <div class="form-group">
                <div class="row">
                  <label class="col-12 col-lg-4 col-form-label">入出庫区分</label>
                  <div class="col-lg-8 group-input">
                    <select class="form-control active-head" name="hed_nyusyuko_kbn" onchange="changeNyusyukoKbn(this)">
                      <option value=""></option>
                      @foreach($listKbn as $key => $nyusyuko) 
                      <option value="{{$key}}">{{$nyusyuko}}</option>
                      @endforeach
                    </select>
                    <div class=" text-danger" >
                      <span class="text-danger error_message" id="error-hed_nyusyuko_kbn"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-3">
              <div class="row">
                <label class="col-12 col-lg-4 col-form-label">伝票番号</label>
                <div class="col-lg-4">
                  <input type="text" class="form-control active-head" name="hed_nyusyuko_den_no">
                </div>
                <button class="btn btn-primary" type="button" id="showNyusyukoHead">入出庫NO検索</button>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="text-right">
                <label class="col-form-label text-primary" id="mode" style="font-size: 30px; font-weight: bold;"></label>
              </div>
            </div>
          </div>
          
        </div>
        <div class="mt-2">
          <div class="" style="display: flex;align-items:  center;justify-content: flex-end; flex-wrap: nowrap;">
            <div class="" style="white-space: nowrap;">
              <button id="clickClear" class="btn btn-clear min-wid-110" type="button" onclick="clearForm(this)">条件クリア</button>
              <button class="btn btn-search min-wid-110" type="button" onclick="searchList(this)" >検索</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
<div class="mt-3 class-show" >
  <div class="card">
    <div class="card-body">
        <div class="form-custom form-master">
          <div style="display: flex; justify-content: space-between;" class="mb-2">
            <div></div>
            <div>
              <button type="button" class="btn btn-insert min-wid-110" onclick="updateData(this)"> {{trans('app.labels.btn-update')}}</button>
            </div>
            <div>
              <button type="button" class="btn btn-delete" onclick="deleteData(this, 'handleDeleteTotal')">{{trans('app.labels.btn-delete')}}</button>
            </div>
          </div>
          <div id="form-nyusyuko_head" class="formConfirmChange">
            <div class="form-group">
              <div class="row">
                <div class="col-12 col-md-6">
                  <div class="row">
                    <label class="col-12 col-md-2 col-form-label text-nowrap ">荷　主</label>
                    <div class="col-12 col-md-8 group-input">
                      <div class="flex-suggestion" style="">
                        <input type="text" class="active-head form-control size-M" name="ninusi_cd" style="width: 100px; border-bottom-right-radius: 0; border-top-right-radius: 0" onkeyup="suggestionForm(this, 'ninusi_cd', ['ninusi_cd', 'kana', 'ninusi_ryaku_nm'], {'ninusi_cd': 'ninusi_cd', 'ninusi_ryaku_nm': 'ninusi_ryaku_nm'}, $(this).parent())" style="" autocomplete="off" onchange="changeNinusiHead(this)">

                        <input class="active-head form-control size-L" name="ninusi_ryaku_nm" style="border-bottom-left-radius: 0; border-top-left-radius: 0" onkeyup="suggestionForm(this, 'ninusi_nm', ['ninusi_cd', 'kana', 'ninusi_ryaku_nm'], {'ninusi_cd': 'ninusi_cd', 'ninusi_ryaku_nm': 'ninusi_ryaku_nm'}, $(this).parent())" autocomplete="off" onchange="changeNinusiHead(this)">
                        <ul class="suggestion"></ul>
                      </div>
                      <div>
                        <span class="text-danger error_message" id="error-ninusi_cd"></span>
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
                        <input type="text" class="form-control size-M" data-other_where="input[name=ninusi_cd]" name="hachaku_cd" style="width: 100px; border-bottom-right-radius: 0; border-top-right-radius: 0" onkeyup="suggestionForm(this, 'hachaku_cd', ['hachaku_cd', 'kana', 'hachaku_nm'], {'hachaku_cd': 'hachaku_cd', 'hachaku_nm': 'todokesaki_nm'}, $(this).parent())" style="" autocomplete="off">

                        <input class="form-control size-L" data-other_where="input[name=ninusi_cd]" name="todokesaki_nm" style="border-bottom-left-radius: 0; border-top-left-radius: 0" onkeyup="suggestionForm(this, 'hachaku_nm', ['hachaku_cd', 'kana', 'hachaku_nm'], {'hachaku_cd': 'hachaku_cd', 'hachaku_nm': 'todokesaki_nm'}, $(this).parent())" autocomplete="off">
                        <ul class="suggestion"></ul>
                      </div>
                      <div>
                        <span class="text-danger error_message" id="error-hachaku_cd"></span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="row">
                    <label class="col-12 col-md-2 col-form-label text-nowrap ">荷送り人</label>
                    <div class="col-12 col-md-8 group-input">
                      <div class="flex-suggestion" style="">
                        <input type="text" class="form-control size-M" name="hatuti_cd" style="width: 100px; border-bottom-right-radius: 0; border-top-right-radius: 0" onkeyup="suggestionForm(this, 'hatuti_cd', ['hatuti_cd', 'kana', 'hatuti_nm'], {'hatuti_cd': 'hatuti_cd', 'hatuti_nm': 'hatuti_nm'}, $(this).parent())" style="" autocomplete="off">

                        <input class="form-control size-L" name="hatuti_nm" style="border-bottom-left-radius: 0; border-top-left-radius: 0" onkeyup="suggestionForm(this, 'hatuti_nm', ['hatuti_cd', 'kana', 'hatuti_nm'], {'hatuti_cd': 'hatuti_cd', 'hatuti_nm': 'hatuti_nm'}, $(this).parent())" autocomplete="off">
                        <ul class="suggestion"></ul>
                      </div>
                      <div>
                        <span class="text-danger error_message" id="error-hatuti_cd"></span>
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
                      <div class="group-input">
                        <input type="text" class="form-control" name="haitatu_jyusyo1">
                        <div>
                          <span class="text-danger error_message" id="error-haitatu_jyusyo1"></span>
                        </div>
                      </div>
                      <div class="group-input">
                        <input type="text" class="form-control" name="haitatu_jyusyo2">
                        <div>
                          <span class="text-danger error_message" id="error-haitatu_jyusyo2"></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="row align-items-center">
                    <label class="col-md-2">
                      住　所     
                    </label>
                    <div class="col-md-10">
                      <div class="group-input">
                        <input type="text" class="form-control" name="hatuti_jyusyo1">
                        <div>
                          <span class="text-danger error_message" id="error-hatuti_jyusyo1"></span>
                        </div>
                      </div>
                      <div class="group-input">
                        <input type="text" class="form-control" name="hatuti_jyusyo2">
                        <div>
                          <span class="text-danger error_message" id="error-hatuti_jyusyo2"></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-md-6">
                  <div class="row">
                    <label class="col-12 col-md-2 col-form-label text-nowrap ">配達TEL</label>
                    <div class="col-md-4">
                      <div class="group-input">
                        <input type="text" class="form-control" name="haitatu_tel" style="max-width: 145px;">
                        <div>
                          <span class="text-danger error_message" id="error-haitatu_tel"></span>
                        </div>
                      </div>
                      
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="row">
                    <label class="col-12 col-md-2 col-form-label text-nowrap ">発地TEL</label>
                    <div class="col-md-4">
                      <div class="group-input">
                        <input type="text" class="form-control" name="hatuti_tel" style="max-width: 145px;">
                        <div>
                          <span class="text-danger error_message" id="error-hatuti_tel"></span>
                        </div>
                      </div>
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
                      <div class="group-input">
                        <input type="text" class="form-control" name="denpyo_dt" onchange="autoFillDate(this)"
                          onblur="onblurDenpyoDt(this)"
                        >
                        <div>
                          <span class="text-danger error_message" id="error-denpyo_dt"></span>
                        </div>
                      </div>
                    </div>
                    <label class="col-12 col-md-2 col-form-label text-nowrap ">起算日</label>
                    <div class="col-md-3">
                      <div class="group-input">
                        <input type="text" class="form-control" name="kisan_dt" onchange="autoFillDate(this)">
                        <div>
                          <span class="text-danger error_message" id="error-kisan_dt" ></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="row">
                    <label class="col-12 col-md-2 col-form-label text-nowrap ">納品日</label>
                    <div class="col-md-3">
                      <div class="group-input">
                        <input type="text" class="form-control" name="nouhin_dt" onchange="autoFillDate(this)">
                        <div>
                          <span class="text-danger error_message" id="error-nouhin_dt" ></span>
                        </div>
                      </div>
                    </div>
                    <label class="col-12 col-md-2 col-form-label text-nowrap ">荷役料負担</label>
                    <div class="col-md-3">
                      <div class="group-input">
                        <select class="form-control" name="nieki_futan_kbn">
                          <option value="1">有償</option>
                          <option value="2">無償</option>
                        </select>
                        <div>
                          <span class="text-danger error_message" id="error-nieki_futan_kbn"></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>
<div class="mt-3 class-show">
  <div class="card">
    <div class="card-body">
      <div class="form-custom ">
        <table id="table" class="hansontable editable" data-sticky-columns="['id']">
        </table>
      </div>
    </div>
  </div>
</div>

<div class="mt-3 class-show" id="nyusyukoHeadFoot">
  <div class="card">
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col-12 col-md-6">
          <div class="list-checkbox" style="display: flex; align-items: center; flex-wrap: wrap; grid-gap: 10px;">
            <div class="form-check form-check-flat form-check-primary">
              <label class="form-check-label text-nowrap">
                <input type="checkbox" class="form-check-input" name="denpyo_print_kbn" value="1">
                <i class="input-helper"></i>
                更新時に伝票発行
              </label>
            </div>
            <div class="form-check form-check-flat form-check-primary">
              <label class="form-check-label text-nowrap">
                <input type="checkbox" class="form-check-input" name="syamei_print_kbn" value="1">
                <i class="input-helper"></i>
                社名印字無し
              </label>
            </div>
            <div class="form-check form-check-flat form-check-primary">
              <label class="form-check-label text-nowrap">
                <input type="checkbox" class="form-check-input" name="nouhinsyo_kbn" value="1">
                <i class="input-helper"></i>
                納品書必要
              </label>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="row align-items-center" >
                <label class="col-md-5">数量合計</label>
                <div class="col-md-7">
                  <input type="text" class="form-control text-right" name="" readonly id="totalSu">
                </div>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <div class="row align-items-center">
                <label class="col-md-5">重量（㎥）合計</label>
                <div class="col-md-7">
                  <input type="text" class="form-control text-right" name="" readonly id="totalJyuryo">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="mt-3 class-show">
  <div class="card">
    <div class="card-body">
      <div class="form-custom form-master formConfirmChange" id="dataUriage">
        <div class="row">
          <div class="col-2">
            <div class="row">
              <label class="col-md-6 text-center">送料区分</label>
              <div class="col-md-6">
                <div class="group-input">
                  <select class="form-control" name="souryo_kbn">
                    <option></option>
                    @foreach(config('params.MENZEI_KBN') as $key => $souryoKbn)
                    <option value="{{ $key }}">{{ $souryoKbn }}</option>
                    @endforeach
                  </select>
                  <div class=""><span id="error-souryo_kbn" class="text-danger error_message"></span></div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-2">
            <div class="row">
              <label class="col-6 text-center">車番</label>
              <div class="col-6">
                <div class="group-input">
                  <input type="text" class="form-control" name="syaban">
                  <div class=""><span id="error-syaban" class="text-danger error_message"></span></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-4">
            <div class="row">
              <label class="col-4 text-center">
                乗務員
              </label>
              <div class="col-8">
                <div class="group-input">
                  <div style="display: flex; align-items: center;">
                    <div class="flex-suggestion" style="">
                      <input type="text" class="form-control" style="width: 100px; border-bottom-right-radius: unset; border-top-right-radius: unset;" name="jyomuin_cd" onkeyup="suggestionForm(this, 'jyomuin_cd', ['jyomuin_cd', 'kana', 'jyomuin_nm'], {'jyomuin_cd': 'jyomuin_cd', 'jyomuin_nm': 'jyomuin_nm'}, $(this).parent())">
                      <input type="text" class="form-control" style="flex: 1; border-bottom-left-radius: unset; border-top-left-radius: unset;" name="jyomuin_nm" readonly onkeyup="suggestionForm(this, 'jyomuin_nm', ['jyomuin_cd', 'kana', 'jyomuin_nm'], {'jyomuin_cd': 'jyomuin_cd', 'jyomuin_nm': 'jyomuin_nm'}, $(this).parent())">
                      <div class="suggestion"></div>
                    </div>
                  </div>
                  <div class=""><span id="error-jyomuin_cd" class="text-danger error_message"></span></div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-4">
            <div class="row">
              <label class="col-4 text-center">
                庸車先
              </label>
              <div class="col-8">
                <div class="group-input">
                  <div style="display: flex; align-items: center;">
                    <div class="flex-suggestion" style="">
                      <input type="text" class="form-control" style="width: 100px; border-bottom-right-radius: unset; border-top-right-radius: unset;" name="yousya_cd" onkeyup="suggestionForm(this, 'yousya_cd', ['yousya_cd', 'kana', 'yousya_ryaku_nm'], {'yousya_cd': 'yousya_cd', 'yousya_ryaku_nm': 'yousya_nm'}, $(this).parent())">
                      <input type="text" class="form-control" style="flex: 1; border-bottom-left-radius: unset; border-top-left-radius: unset;" name="yousya_nm" readonly>
                      <div class="suggestion"></div>
                    </div>
                  </div>
                  <div class=""><span id="error-yousya_cd" class="text-danger error_message"></span></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row mt-5px">
          <label class="col-1 text-center">摘要</label>
          <div class="col-11">
            <div class="group-input">
              <input type="text" class="form-control" name="biko">
              <div class=""><span id="error-biko" class="text-danger error_message"></span></div>
            </div>
          </div>
        </div>

        <div class="row align-items-center mt-5px">
          <div class="col-1 text-center">請求</div>
          <div class="col-11">
            <div style="display: grid; grid-template-columns: repeat(9, 1fr); grid-gap: 10px;">
              <div>
                <div class="text-center">運賃確定</div>
                <div class="group-input">
                  <select class="form-control" name="unchin_mikakutei_kbn">
                    <option value=""></option>
                    @foreach($unchinMikakuteiKbn as $key => $unchin)
                    <option value="{{ $unchin->meisyo_cd }}">{{ $unchin->meisyo_nm }}</option>
                    @endforeach
                  </select>
                  <div class=""><span id="error-unchin_mikakutei_kbn" class="text-danger error_message"></span></div>
                </div>
              </div>

              <div>
                <div class="text-center">基本運賃</div>
                <div class="group-input">
                  <input type="text" class="form-control text-right" name="unchin_kin" onkeypress="onlyNumber(event)" onchange="calculatorRoundKintax(this)">
                  <div class=""><span id="error-unchin_kin" class="text-danger error_message"></span></div>
                </div>
              </div>

              <div>
                <div class="text-center">中継料</div>
                <div class="group-input">
                  <input type="text" class="form-control text-right" name="tyukei_kin" onkeypress="onlyNumber(event)" onchange="calculatorRoundKintax(this)">
                  <div class=""><span id="error-tyukei_kin" class="text-danger error_message"></span></div>
                </div>
              </div>

              <div>
                <div class="text-center">通行料等</div>
                <div class="group-input">
                  <input type="text" class="form-control text-right" name="tukoryo_kin" onkeypress="onlyNumber(event)" onchange="calculatorRoundKintax(this)">
                  <div class=""><span id="error-tukoryo_kin" class="text-danger error_message"></span></div>
                </div>
              </div>

              <div>
                <div class="text-center">手数料</div>
                <div class="group-input">
                  <input type="text" class="form-control text-right" name="tesuryo_kin" onkeypress="onlyNumber(event)" onchange="calculatorRoundKintax(this)">
                  <div class=""><span id="error-tesuryo_kin" class="text-danger error_message"></span></div>
                </div>
              </div>

              <div>
                <div class="text-center">荷役料</div>
                <div class="group-input">
                  <input type="text" class="form-control text-right" name="nieki_kin" onkeypress="onlyNumber(event)" onchange="calculatorRoundKintax(this)">
                  <div class=""><span id="error-nieki_kin" class="text-danger error_message"></span></div>
                </div>
              </div>

              <div>
                <div class="text-center">集荷料</div>
                <div class="group-input">
                  <input type="text" class="form-control text-right" name="syuka_kin" onkeypress="onlyNumber(event)" onchange="calculatorRoundKintax(this)">
                  <div class=""><span id="error-syuka_kin" class="text-danger error_message"></span></div>
                </div>
              </div>

              <div>
                <div class="text-center">免税区分</div>
                <div class="group-input">
                  <select class="form-control" name="menzei_kbn" onchange="calculatorRoundKintax(this)">
                    <option value=""></option>
                    @foreach(config('params.MENZEI_KBN') as $key => $menzei) 
                    <option value="{{$key}}">{{$menzei}}</option>
                    @endforeach
                  </select>
                  <div class=""><span id="error-menzei_kbn" class="text-danger error_message"></span></div>
                </div>
              </div>

              <div>
                <div class="text-center">消費税</div>
                <div>
                  <input type="text" class="form-control text-right" name="seikyu_kin_tax" readonly="">
                </div>
              </div>

            </div>
          </div>
        </div>

        <div class="row align-items-center mt-5px" id="last-disabled">
          <div class="col-1 text-center">支払</div>
          <div class="col-11 ">
            <div style="display: grid; grid-template-columns: repeat(9, 1fr); grid-gap: 10px;">
              <div>
                <div class="text-center">庸車料確定</div>
                <div class="group-input">
                    <select name="yosya_kin_mikakutei_kbn" class="form-control">
                      <option></option>
                      @foreach(config('params.YOSYA_KIN_MIKAKUTEI_KBN') as $key => $mikakutei) 
                      <option value="{{$key}}">{{$mikakutei}}</option>
                      @endforeach
                    </select>
                    <div>
                      <span id="error-yosya_kin_mikakutei_kbn"></span>
                    </div>
                </div>
              </div>

              <div>
                <div class="text-center">庸車料</div>
                <div><input type="text" class="form-control text-right" name="yosya_tyukei_kin"></div>
              </div>

              <div>
                <div class="text-center">通行料等</div>
                <div><input type="text" class="form-control text-right" name="yosya_tukoryo_kin"></div>
              </div>

              <div>
                <div class="text-center">&nbsp;</div>
                <div><input type="text" class="form-control" name=""></div>
              </div>

              <div>
                <div class="text-center">&nbsp;</div>
                <div><input type="text" class="form-control" name=""></div>
              </div>

              <div>
                <div class="text-center">&nbsp;</div>
                <div><input type="text" class="form-control" name=""></div>
              </div>

              <div>
                <div class="text-center">&nbsp;</div>
                <div><input type="text" class="form-control" name=""></div>
              </div>

              <div>
                <div class="text-center">消費税</div>
                <div><input type="text" class="form-control text-right" name="yosya_kin_tax"></div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="modal-head" style="">
  <div class="content">
    <div> <button class="btn btn-secondary" id="closeModalHead">戻る</button> </div>
    <div class="animation">
      <iframe src="{{ route('nyusyuko.nyuryoku.index_nyusyuko_head') }}" style="width: 100%; height: 100%;"></iframe>
    </div>
  </div>
</div>

<div class="modal-zaiko">
  <div class="content" style="grid-template-rows: 100%">
    <iframe src="" style="width: 100%; height: 100%;"></iframe>
  </div>
</div>




@endsection

@section('js')
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script>
  $('#table').data('customTableSettings', {urlSearchSuggestion:'{{route('master-suggestion')}}'});
  var useAddFormFooter = true;
  var listButtonToolBar = '';
  var nyusyukoHead = null;
  var uriage = null;
  var nyusyukoMeisai = null;
  var columns = null;
  var columnDefault = null;
  var initCopy = null;
  var initCheckBox = @if(!empty($init)) {{ $init->choice1_bool ? 'true' : 'false' }}  @else {{ 'null' }} @endif;
  var currentDateSystem = "{{ \Carbon\Carbon::now()->format('Y/m/d') }}";
  function searchList(e) {
    if($('input[name="hed_nyusyuko_den_no"]').val()) {
      if($('.bootstrap-table').length > 0) {
        $.fn.customTable.destroy();
      }
      $.ajax({
        url: '{{route('nyusyuko.nyuryoku.get_nyusyuko_head')}}',
        method: 'POST',
        data: {
          hed_nyusyuko_den_no: $('input[name="hed_nyusyuko_den_no"]').val(),
        },
        success: function(res) {
          if(res.setting) {
            columns = res.setting;
            columnDefault = res.setting;
            initCopy = res.initCopy;
            var columnFake = [...columns];
            columnFake[6].visible = false;
            columnFake[7].visible = false;
            columnFake[8].visible = false;
          }
          if(res.head) {
            
            if(!res.head.lot_kanri_kbn) {
              createCustomTable(columnDefault);
            } else {
              if(res.head.lot_kanri_kbn == 3) {
                columnFake[6].visible = true;
                columnFake[7].visible = true;
                columnFake[8].visible = true;
                if(res.head.lot1_nm) {
                  columnFake[6].title = res.head.lot1_nm;
                } else {
                  columnFake[6].title = 'ロット１';
                }
                if(res.head.lot2_nm) {
                  columnFake[7].title = res.head.lot2_nm;
                } else {
                  columnFake[7].title = 'ロット２';
                }
                if(res.head.lot3_nm) {
                  columnFake[8].title = res.head.lot3_nm;
                } else {
                  columnFake[8].title = 'ロット３';
                }
              }

              if(res.head.lot_kanri_kbn == 2) {
                columnFake[6].visible = true;
                columnFake[7].visible = true;
                if(res.head.lot1_nm) {
                  columnFake[6].title = res.head.lot1_nm;
                } else {
                  columnFake[6].title = 'ロット１';
                }
                if(res.head.lot2_nm) {
                  columnFake[7].title = res.head.lot2_nm
                } else {
                  columnFake[7].title = 'ロット２';
                }
              } 

              if(res.head.lot_kanri_kbn == 1) {
                columnFake[6].visible = true;
                if(res.head.lot1_nm) {
                  columnFake[6].title = res.head.lot1_nm;
                } else {
                  columnFake[6].title = 'ロット１';
                }
              }

              createCustomTable(columnFake);
            }
            $('.class-show').removeClass('active');
            $.fn.customTable.searchList();
            $.fn.customTable.callbackAfterShow(showHead);
            $('.btn-insert').html('更新');
            $('#mode').html('更新');
          } else {
            Swal.fire({
              'title': '{{ trans('messages.E0016') }}',
              'icon': 'error',
              customClass: {
                popup: 'custom-modal-size'
              }
            });
          }
        }
      })
    } else {
      $('.btn-insert').html('登録');
      $('#mode').html('新規追加');
      redirectFormBefore();
    }
  }

  function createCustomTable(columnsTable) {
    $('#table').customTable({
         // Data source URL
        urlData: '{!! route('nyusyuko.nyuryoku.data_list', request()->query()) !!}',
        // Show columns button
        showColumns: false,
        // Column configurations
        columns: columnsTable,
        // Custom toolbar buttons
        listButtonToolBar: listButtonToolBar,
        // Initial page number
        pageNumber: pageNumber,
        // URL for inserting data record
        urlInsertDataRecord: '',
        // URL for updating data record
        urlUpdateDataRecord: '',
        // Search form element
        formSearch: $('#formInputs'),
        // URL for copying record
        urlCopyRecord: '',
        // URL for search suggestion
        urlSearchSuggestion: '{{route('master-suggestion')}}',
        // URL for exporting data table to Excel
        urlExportExcelDataTable: '',
        // Number of items per page
        pageSize: null,
        // textButtonExportExcel: '{{ trans('app.labels.btn-xls-export') }}',
        urlValidateRows: '{{ route('nyusyuko.nyuryoku.validate_row_nyusyuko_meisai') }}',
        // Option to insert new rows at the end
        insertLastRow: true,
         // URL for updating data table
        // urlUpdateDataTable: '{{route('uriage.uriage_entry.update_datatable')}}',
        // Option to copy data to the left
        isCopyLeft: true,
        isDelete: true,
        defaultSearchForm: false,
        isShow: false, // if is true will be show list when init
        usingPaginateTop: true,
        initCopy: initCopy,
        isResize: true
      });
  } 

  function showHead(data) {
    nyusyukoHead = data.head;
    if(nyusyukoHead == null || $('input[name="hed_nyusyuko_den_no"]').val() && Object.keys(nyusyukoHead).length == 0) {
      Swal.fire({
        'title': '{{ trans('messages.E0016') }}',
        'icon': 'error',
        customClass: {
          popup: 'custom-modal-size'
        }
      })
      return ;
    }

    $('.active-head').prop('disabled', true);
    $('.error_message').html('');
    $('.group-input').removeClass('error');

    if(nyusyukoHead != null && Object.keys(nyusyukoHead).length > 0) {
      Object.keys(nyusyukoHead).forEach(function (key) {
        var value = nyusyukoHead[key];
        if (key.includes('_dt') && value) {
          value = value.replace(/-/g, '/');
        }
        $('#form-nyusyuko_head').find('[name="'+key+'"]').val(value);

      });
      $('#form-nyusyuko_head').find('input[name="ninusi_cd"]').prop('disabled', true);
      $('#form-nyusyuko_head').find('input[name="ninusi_nm"]').prop('disabled', true);

      if(nyusyukoHead.denpyo_print_kbn) {
        $('input[name="denpyo_print_kbn"]').prop('checked', true);
      } else {
        $('input[name="denpyo_print_kbn"]').prop('checked', false);
      }
      if(nyusyukoHead.syamei_print_kbn) {
        $('input[name="syamei_print_kbn"]').prop('checked', true);
      } else {
        $('input[name="syamei_print_kbn"]').prop('checked', false);
      }
      if(nyusyukoHead.nouhinsyo_kbn) {
        $('input[name="nouhinsyo_kbn"]').prop('checked', true);
      } else {
        $('input[name="nouhinsyo_kbn"]').prop('checked', false);
      }
      $('select[name="hed_nyusyuko_kbn"]').val(nyusyukoHead.nyusyuko_kbn).trigger('change');
      $('input[name="hed_bumon_cd"]').val(nyusyukoHead.hed_bumon_cd).trigger('change');
      $('input[name="hed_bumon_nm"]').val(nyusyukoHead.hed_bumon_nm).trigger('change');
      if (!nyusyukoHead.nieki_futan_kbn) {
        $('select[name=nieki_futan_kbn]').val(1);
      }
      $('.btn-delete').css('display', 'block');
    } else {
      $('#form-nyusyuko_head').find('input, select').prop('disabled', false);
      $('#form-nyusyuko_head').find('input, select').val('');
      $('.btn-delete').css('display', 'none');
      $('select[name=nieki_futan_kbn]').val(1);
      nyusyukoHead = {};
    }
    uriage = data.uriage;
    if(uriage != null && Object.keys(uriage).length > 0 ) {
      Object.keys(uriage).forEach(function (key) {
        var value = uriage[key];
        if (key.includes('_dt') && value) {
          value = value.replace(/-/g, '/');
        }
        $('#dataUriage').find('[name="'+key+'"]').val(value);

      });
      $('#dataUriage').find('input, select').prop('disabled', false);
      if(uriage.sime_kakutei_kbn != 0) {
        $('#dataUriage').find('input, select').prop('disabled', true);
      }
    } else {
      $('#dataUriage').find('input, select').prop('disabled', true);
      $('#dataUriage').find('input, select').val('');
      uriage = {};
    }

    $('#last-disabled').find('input, select').prop('disabled', true);

    nyusyukoMeisai = data.rows;
    var totalSu = 0;
    var totalJyuryo = 0;
    if(nyusyukoMeisai && Object.keys(nyusyukoMeisai).length > 0) {
      Object.keys(nyusyukoMeisai).forEach(function (key) {
        if (nyusyukoMeisai[key].hasOwnProperty('su')) {
          totalSu += parseInt(nyusyukoMeisai[key].su || 0);
        }
        if (nyusyukoMeisai[key].hasOwnProperty('jyuryo')) {
          totalJyuryo += parseInt(nyusyukoMeisai[key].jyuryo || 0);
        }
      });
      $('#totalSu').val(numberFormat(totalSu || '', -1));
      $('#totalJyuryo').val(numberFormat(totalJyuryo || '', -1));
    } else {
      nyusyukoMeisai = {};
    }
    if($('[name="hed_nyusyuko_kbn"]').val() == 4 || $('[name="hed_nyusyuko_kbn"]').val() == 5) {
      $('#form-nyusyuko_head').find('input, select').prop('disabled', true);
      $('#form-nyusyuko_head').find('input[name="ninusi_cd"], input[name="ninusi_ryaku_nm"], input[name="denpyo_dt"], input[name="kisan_dt"]').prop('disabled', false);
    }
    $('.class-show').addClass('active');
  } 

  function changeNyusyukoKbn(e) {
    var value = $(e).val();
    if(value == 1) {
      $('input[name="syamei_print_kbn"]').prop('disabled', true);
      $('input[name="nouhinsyo_kbn"]').prop('disabled', true);
      $('#formInputs .card').css('background', '#90EE90');
    }

    if(value == 2) {
      $('input[name="syamei_print_kbn"]').prop('disabled', false);
      $('input[name="nouhinsyo_kbn"]').prop('disabled', false);
      $('#formInputs .card').css('background', '#ADD8E6');
    }

    if(value == 4) {
      $('input[name="syamei_print_kbn"]').prop('disabled', false);
      $('input[name="nouhinsyo_kbn"]').prop('disabled', false);
      $('#formInputs .card').css('background', '#ddd');
    }

    if(value == 5) {
      $('input[name="syamei_print_kbn"]').prop('disabled', false);
      $('input[name="nouhinsyo_kbn"]').prop('disabled', false);
      $('#formInputs .card').css('background', 'rgba(255,255,51,0.5)');
    }
    if(!value) {
      $('#formInputs .card').css('background', '#FFF');
    }
  }

  function redirectFormBefore() 
  {
    var flg = false;
    if($('input[name="hed_nyusyuko_den_no"]').val()) {
      return false;
    }

    if(!$('input[name="hed_bumon_cd"]').val()) {
      $('#error-hed_bumon_cd').html("{{trans('messages.E0002', ['attribute' => '部門'])}}");
      $('#error-hed_bumon_cd').parents('.group-input').addClass('error');
      flg = true;
    } else {
      $('#error-hed_bumon_cd').html('');
      $('#error-hed_bumon_cd').parents('.group-input').removeClass('error');
    }

    if(!$('select[name="hed_nyusyuko_kbn"]').val()) {
      $('#error-hed_nyusyuko_kbn').html("{{trans('messages.E0002', ['attribute' => '入出庫区分'])}}");
      $('#error-hed_nyusyuko_kbn').parents('.group-input').addClass('error');
      flg = true;
    } else {
      $('#error-hed_nyusyuko_kbn').html('');
      $('#error-hed_nyusyuko_kbn').parents('.group-input').removeClass('error');
    }

    if(flg == false) {
      $.ajax({
        url: '{{route('nyusyuko.nyuryoku.valiedate_form_search_nyusyuko_head')}}',
        data: {
          hed_bumon_cd: $('input[name="hed_bumon_cd"]').val(),
          hed_nyusyuko_kbn: $('select[name="hed_nyusyuko_kbn"]').val()
        },
        type: 'POST',
        success: function(res) {
          createCustomTable(res.setting);
          columns = res.setting;
          columnDefault = res.setting;
          initCopy = res.initCopy;
          $('.class-show').removeClass('active');
          $.fn.customTable.searchList();
          $.fn.customTable.callbackAfterShow(showHead);

          $('input[name="denpyo_print_kbn"]').prop('checked', false);
          $('input[name="syamei_print_kbn"]').prop('checked', false);
          $('input[name="nouhinsyo_kbn"]').prop('checked', false);
          var value = $('select[name="hed_nyusyuko_kbn"]').val();
          if(value == 1) {
            $('input[name="denpyo_print_kbn"]').prop('checked', true);
            $('input[name="syamei_print_kbn"]').prop('disabled', true);
            $('input[name="nouhinsyo_kbn"]').prop('disabled', true);
          }

          if(value == 2) {
            $('input[name="syamei_print_kbn"]').prop('disabled', false);
            $('input[name="nouhinsyo_kbn"]').prop('disabled', false);
            $('input[name="nouhinsyo_kbn"]').prop('checked', true);
          }
          if(initCheckBox !== null) {
            $('input[name="denpyo_print_kbn"]').prop('checked', initCheckBox);
          }
          $('#totalSu').val('');
          $('#totalJyuryo').val('');
        },
        error: function(error) {
          if (error.status == 422) {
            $('.error_message').html('');
            $('.group-input').removeClass('error');
            var errors = error.responseJSON.errors;
            $.each(errors, function (key, value) {
              $('#formInputs').find('[name="'+key+'"]').parents('.group-input').addClass('error');
              $('#formInputs').find('#error-' + key).text(value);
            });
          }
        }
      });
      
    }
  }

  function hideCreate(e) {
    if($(e).val()) {
      $('.btn-insertNew').attr('disabled', 'disabled');
      $('.btn-search').prop('disabled', false);
    } else{
      $('.btn-insertNew').removeAttr('disabled');
      $('.btn-search').prop('disabled', true);
    }
  }

  function clearForm(e) {
    if($('.bootstrap-table').length > 0) {
      $.fn.customTable.destroy();
    }
    $('.error_message').html('');
    $('.group-input').removeClass('error');
    $('.class-show').removeClass('active');
    $('#formInputs').find('input, select').val('').trigger('change');
    $('.btn-insertNew').removeAttr('disabled');
    $('#formInputs .card').css('background', '#FFF');
    $('#totalSu').val('');
    $('#totalJyuryo').val('');
    $('#mode').html('');
    $('#form-nyusyuko_head').find('input, select').prop('disabled', false);
    nyusyukoHead = null;
    nyusyukoMeisai = null;
    uriage = null;
    $('.active-head').prop('disabled', false);
    hasChangeData = false;
  }

  function changeNinusiHead(e) {
    var ninusiCd = $('[name="ninusi_cd"]').val();
    if(ninusiCd) {
      $.ajax({
        url: '{{route('nyusyuko.nyuryoku.get_ninusi')}}',
        method: 'POST',
        data: {
          ninusi_cd: ninusiCd
        },
        success: function(res) {
          if(res.data && res.data.ninusi_cd) {
            var columnFake = [...columns];
            columnFake[6].visible = false;
            columnFake[7].visible = false;
            columnFake[8].visible = false;
            if($('.bootstrap-table').length > 0) {
              $.fn.customTable.destroy();
            } 
            if(!res.data.lot_kanri_kbn) {
              createCustomTable(columnDefault);
            } else {
              if(res.data.lot_kanri_kbn == 3) {
                columnFake[6].visible = true;
                columnFake[7].visible = true;
                columnFake[8].visible = true;
                if(res.data.lot1_nm) {
                  columnFake[6].title = res.data.lot1_nm;
                } else {
                  columnFake[6].title = 'ロット１';
                }
                if(res.data.lot2_nm) {
                  columnFake[7].title = res.data.lot2_nm;
                } else {
                  columnFake[7].title = 'ロット２';
                }
                if(res.data.lot3_nm) {
                  columnFake[8].title = res.data.lot3_nm;
                } else {
                  columnFake[8].title = 'ロット３';
                }
              }

              if(res.data.lot_kanri_kbn == 2) {
                columnFake[6].visible = true;
                columnFake[7].visible = true;
                if(res.data.lot1_nm) {
                  columnFake[6].title = res.data.lot1_nm;
                } else {
                  columnFake[6].title = 'ロット１';
                }
                if(res.data.lot2_nm) {
                  columnFake[7].title = res.data.lot2_nm
                } else {
                  columnFake[7].title = 'ロット２';
                }
              } 

              if(res.data.lot_kanri_kbn == 1) {
                columnFake[6].visible = true;
                if(res.data.lot1_nm) {
                  columnFake[6].title = res.data.lot1_nm;
                } else {
                  columnFake[6].title = 'ロット１';
                }
              }
              createCustomTable(columnFake);
            }
            $.fn.customTable.searchList();
            if(hasChangeDataFlg) {
              $.fn.customTable.callbackAfterShow(haschangeDataFunction);
            }

            if (!$('input[name=hatuti_cd]').val()) {
              $('input[name=hatuti_nm]').val(res.data.ninusi_ryaku_nm);
              $('input[name=hatuti_jyusyo1]').val(res.data.jyusyo1_nm);
              $('input[name=hatuti_jyusyo2]').val(res.data.jyusyo2_nm);
              $('input[name=hatuti_tel]').val(res.data.tel);
            }
          }
        }
      })
    }
  }
  function haschangeDataFunction() {
    hasChangeData = true;
  }

  // window.addEventListener('message', listenParentIframe, false);

  // function listenParentIframe(value) {
  //   if(value.data != 'closeParent') {
  //     $('input[name="hed_nyusyuko_den_no"]').val(value.data).trigger('keyup');
  //     $('.modal-head').removeClass('active');
  //     $('html').removeClass('hideScroll');
  //   }
  // }

  $('#showNyusyukoHead').click(function() {
    var iframe = $('.modal-head iframe')[0];
    var hedNyusyukoKbn = $('[name="hed_nyusyuko_kbn"]').val();
    iframe.contentWindow.postMessage({data_nyusyuko_kbn: hedNyusyukoKbn}, '*');
    iframe.contentWindow.postMessage('research', '*');
    $('.modal-head').addClass('active');
    $('html').addClass('hideScroll');
  });

  $('#closeModalHead').click(function() {
    $('.modal-head').removeClass('active');
    $('html').removeClass('hideScroll');
  });

  function updateData(e) {
    var errorSpans = $('#table tbody tr .error span').filter(function () {
      return $(this).text().trim() !== '';
    });

    if (errorSpans.length > 0) {
      errorSpans.eq(0).closest('.div-row').find('input').focus();
      return false;
    }

    var settings = $('#table').data('customTableSettings');
    var currentData = $('#table').bootstrapTable('getData');
    if (settings.dataDelete.length > 0) {
      currentData = currentData.concat(settings.dataDelete);
    }
    var row = $('#table tfoot tr');
    var objectNew = {};
    row.find('input, select').each(function () {
      var key = $(this).attr('name');
      var value = $(this).val();
      if (value != null && value.trim() !== '') {
        objectNew[key] = value;
      }
    });
    if (Object.keys(objectNew).length > 0) {
      validateRows(objectNew).then(function (res) {
        currentData.push(objectNew);
        ajaxUpdateData(currentData);
      }).catch(function (xhr) {
        row.find('.error span').html('');
        if (xhr.status == 422) {
          var response = JSON.parse(xhr.responseText);
          var errors = response.errors;
          $.each(errors, function (key, value) {
            row.find('.error-' + key).parents('.group-input').addClass('error');
            row.find('.error-' + key + ' span').html(value);
          });
        } else {
          Swal.fire({
            'title': settings.errorException,
            'icon': 'error',
            customClass: {
              popup: 'custom-modal-size'
            }
          })
        }
        return false;
      });

    } else {
      ajaxUpdateData(currentData);
    }
  }

  function ajaxUpdateData(currentData) {
    var dataNyusyukoHead = {};
    if(nyusyukoHead == null ||  Object.keys(nyusyukoHead).length <= 0 ) {
      $('#form-nyusyuko_head').find('input, select').each(function() {
        dataNyusyukoHead[$(this).attr('name')] = $(this).val();
      });
      dataNyusyukoHead['bumon_cd'] = $('input[name="hed_bumon_cd"]').val();
      dataNyusyukoHead['nyusyuko_kbn'] = $('select[name="hed_nyusyuko_kbn"]').val();
    } 
    else {
      dataNyusyukoHead = nyusyukoHead;
      $('#form-nyusyuko_head').find('input, select').each(function() {
        dataNyusyukoHead[$(this).attr('name')] = $(this).val();
      });
    }
    if($('input[name="denpyo_print_kbn"]').is(':checked')) {
      dataNyusyukoHead['denpyo_print_kbn'] = true;
    } else {
      dataNyusyukoHead['denpyo_print_kbn'] = false;
    }
    if($('input[name="syamei_print_kbn"]').is(':checked')) {
      dataNyusyukoHead['syamei_print_kbn'] = true;
    } else {
      dataNyusyukoHead['syamei_print_kbn'] = false;
    }
    if($('input[name="nouhinsyo_kbn"]').is(':checked')) {
      dataNyusyukoHead['nouhinsyo_kbn'] = true;
    } else {
      dataNyusyukoHead['nouhinsyo_kbn'] = false;
    }
    $('#dataUriage').find('input, select').each(function() {
      var key = $(this).attr('name');
      uriage[key] = $(this).val();
    })
    $.ajax({
      url: '{{ route('nyusyuko.nyuryoku.update_datatable') }}',
      method: 'POST',
      data: {
        nyusyuko_head: dataNyusyukoHead,
        nyusyuko_meisai: currentData,
        uriage: uriage
      },
      success: function(res) {
        if(res.status == 200) {
          if(res.data.nyusyuko_den_no) {
            // $('input[name="hed_nyusyuko_den_no"]').val(res.data.nyusyuko_den_no);
            Swal.fire({
              'title': res.message || '',
              'icon': 'success',
            });
            $('.error_message').html('');
            // $('.btn-search').click();
            var dataTable = $('#table').bootstrapTable('getData');
            dataTable = [];
            $('#table').bootstrapTable('refresh');
            if($('input[name="denpyo_print_kbn"]').is(':checked')) {
              initCheckBox = true;
            } else {
              initCheckBox = false;
            }

            if($('[name="hed_nyusyuko_kbn"]').val() == 1 || $('[name="hed_nyusyuko_kbn"]').val() == 2) {
              if($('input[name="denpyo_print_kbn"]').is(':checked')) {
                var form = $('<form>', {
                  'action': '{{ route('nyusyuko.nyuryoku.export_pdf') }}',
                  'method': 'POST',
                  'target': '_blank'
                });
                form.append($('<input>', {
                  'type': 'hidden',
                  'name': '_token',
                  'value': $('meta[name="csrf-token"]').attr('content')
                }));
                form.append($('<input>', {
                  'type': 'hidden',
                  'name': 'nyusyuko_den_no',
                  'value': res.data.nyusyuko_den_no
                }));
                form.appendTo('body').submit().remove();
              }
            }
          }
        } else {
          Swal.fire({
            'title': res.message || '',
            'icon': 'error',
            customClass: {
              popup: 'custom-modal-size'
            }
          })
        }
      },
      error: function (error) {
        if (error.status == 422) {
          $('.error_message').html('');
          $('.group-input').removeClass('error');
          var errors = error.responseJSON.errors;
          $.each(errors, function (key, value) {
            if (key.startsWith('nyusyuko_head.')) {
              var fieldName = key.replace('nyusyuko_head.', '');
              $('#form-nyusyuko_head').find('input[name="'+fieldName+'"]').parents('.group-input').addClass('error');
              $('#form-nyusyuko_head').find('#error-' + fieldName).text(value);
            }

            if(key.startsWith('uriage')) {
              var fieldName = key.replace('uriage.', '');
              $('#dataUriage').find('[name="'+fieldName+'"]').parents('.group-input').addClass('error');
              $('#dataUriage').find('#error-' + fieldName).text(value);
            }

            if(key.startsWith('nyusyuko_meisai')) {
              var list = key.replace('nyusyuko_meisai.', '');
              var parts = list.split('.');
              var index = parts[0] || -1;
              var fieldName = parts[1] || '';

              if(index != -1) {
                $('#table tbody tr').eq(index).find('.error-'+fieldName).find('span').html(value);
              }
            }
          });
        }

      }
    });
  }
  function handleDeleteTotal() {
    var urlDelete = '{{route('nyusyuko.nyuryoku.destroy', ['id' => ':id'])}}';
    urlDelete = urlDelete.replace(':id', nyusyukoHead.nyusyuko_den_no);
    $.ajax({
      url: urlDelete,
      type: 'DELETE',
      success: function(res) {
        $('#clickClear').click();
      }, 
      error: function(error) {

      }
    })
  }
  function openModal(value, row, index) {
    return '<button type="button" tabindex="-1" class="btn btn-secondary" onclick="openModalZaikoNyusyukoMeisai(this, '+index+')">在庫選択</button>'
  }

  function openModalZaikoNyusyukoMeisai(e, index) {
    var urlZaiko = '{{ route('nyusyuko.nyuryoku.index_zaiko_nyusyuko_meisai', ['hinmei_cd' => '_hinmei_cd', 'ninusi_cd' => '_ninusi_cd', 'bumon_cd' => '_bumon_cd', 'su' => '_su']) }}';
    var tableData = $('#table').bootstrapTable('getData');
    if (index >= 0 && index < tableData.length) {
      var rowData = tableData[index];
      urlZaiko = urlZaiko.replace('_hinmei_cd', encodeURIComponent(rowData.hinmei_cd));
      urlZaiko = urlZaiko.replace('_ninusi_cd', encodeURIComponent($('input[name="ninusi_cd"]').val()));
      urlZaiko = urlZaiko.replace('_bumon_cd', encodeURIComponent($('input[name="hed_bumon_cd"]').val()));
      urlZaiko = urlZaiko.replace('_su', encodeURIComponent(rowData.su));
      var specificUrl = urlZaiko.replace(/&amp;/g, '&');
      $('.modal-zaiko iframe').attr('src', specificUrl);
      $('.modal-zaiko').addClass('active');
      $('.modal-zaiko iframe').on('load', function() {
            // Sau khi iframe hoàn thành tải, gửi tin nhắn
            var iframe = $('.modal-zaiko iframe')[0];
            iframe.contentWindow.postMessage(JSON.stringify(rowData), '*');
        });
        
        // Thiết lập src cho iframe sau khi xác định được sự kiện load
        $('.modal-zaiko iframe').attr('src', specificUrl);
    } else {
      console.error("Invalid row index.");
    }
  }
  window.addEventListener('message', function(event) {
    if(event.origin == '{{url('')}}') {
      if(event.data == 'closeParent') {
        $('.modal-zaiko').removeClass('active');
        $('.modal-zaiko iframe').attr('src', '');
        return true;
      }
      if(event.data.message == 'zaiko') {
        if(event.data.data.length > 0) {
          for(let i = 0; i < event.data.data.length; i++) {
            $('#table').bootstrapTable('append', event.data.data[i]);
          }
        }
        $('.modal-zaiko').removeClass('active');
        $('.modal-zaiko iframe').attr('src', '');
        $('html').removeClass('hideScroll');
        return true;
      }
      if(event.data != 'closeParent' && event.data.message =='head') {
        $('input[name="hed_nyusyuko_den_no"]').val(event.data.nyusyuko_den_no).trigger('keyup');
        $('.modal-head').removeClass('active');
        // $('.modal-head iframe').attr('src', '');
        $('html').removeClass('hideScroll');
        return true;
      }
    }
  })

  function formatterCaseSu(value, row, index) {
    var irisu = row.irisu || 0;
    var readonly = 'readonly tabindex="-1"';
    if(irisu > 0) {
      readonly = "";
    }
    return '<input onchange="calculator(this)" '+readonly+' onkeypress="onlyNumber(event)" type="text" maxlength="undefined" class="form-control text-right" name="case_su" value="'+numberFormat(value || '', -1)+'" onblur="setCellFocusStatus($(this), false), formatNumberOnBlur(this)" onfocus="setCellFocusStatus($(this), true), removeFormatOnFocus(this)"><div class="error error-case_su"><span class="text-danger"></span></div>';
  }

  function formatterHasu(value, row, index) {
    var irisu = row.irisu || 0;
    var readonly = 'readonly tabindex="-1"';
    if(irisu > 0) {
      readonly = "";
    }
    return '<input onchange="calculator(this)"  '+readonly+' onkeypress="onlyNumber(event)" type="text" maxlength="undefined" class="form-control text-right" name="hasu" value="'+numberFormat(value || '', -1)+'" onblur="setCellFocusStatus($(this), false), formatNumberOnBlur(this)" onfocus="setCellFocusStatus($(this), true), removeFormatOnFocus(this)"><div class="error error-hasu"><span class="text-danger"></span></div>';
  }

  function formatterIrisu(value, row, index) {
    return '<input readonly onchange="onchangeIrisu(this)" tabindex="-1" onkeypress="onlyNumber(event)" type="text" maxlength="undefined" class="form-control text-right" name="irisu" value="'+numberFormat(value || '', -1)+'" onblur="setCellFocusStatus($(this), false), formatNumberOnBlur(this)" onfocus="setCellFocusStatus($(this), true), removeFormatOnFocus(this)"><div class="error error-irisu"><span class="text-danger"></span></div>';
  }

  function formatterSu(value, row, index) {
    return '<input onkeypress="onlyNumber(event)" type="text" maxlength="undefined" class="form-control text-right" name="su" value="'+numberFormat(value ||'', -1)+'" onblur="setCellFocusStatus($(this), false), formatNumberOnBlur(this), calculatorSu(this)" onfocus="setCellFocusStatus($(this), true), removeFormatOnFocus(this)"><div class="error error-su"><span class="text-danger"></span></div>';
  }
  function formatterjJyuryo(value, row, index) {
    return '<div class="div-row" data-field="jyuryo"><input onkeypress="onlyNumber(event)" type="text" maxlength="undefined" class="form-control text-right" name="jyuryo" value="'+numberFormat(value || '', -1)+'" onblur="setCellFocusStatus($(this), false), formatNumberOnBlur(this), calculatorJyuryuo(this)" onfocus="setCellFocusStatus($(this), true), removeFormatOnFocus(this)"><div class="error error-jyuryo"><span class="text-danger"></span></div></div>';
  }

  function formatKikaku(value, row, index) {
    return '<input name="kikaku" type="text" class="form-control" tabindex="-1" readonly onblur="setCellFocusStatus($(this), false)" onfocus="setCellFocusStatus($(this), true)" value="'+(value || '')+'"/>'
  }

  function calculatorJyuryuo(e) {
    var totalJyuryo = 0;
    $('#table tr').each(function() {
      var jyuryo = $(this).find('[name="jyuryo"]').val() || '0';
      jyuryo = parseFloat(jyuryo.replace(/,/g, ''));
      totalJyuryo += jyuryo;
    });
    $('#totalJyuryo').val(numberFormat(totalJyuryo, -1));
  }

  function calculatorSu(e) {

    var su = $(e).val();
    
    if(su) {
      su = parseFloat(su.replace(/,/g, '')) || 0;
      var irisu = $(e).parents('tr').find('[name="irisu"]').val();
      irisu = parseFloat(irisu.replace(/,/g, '')) || 1;
      if(su >= 0) {
        var caseSu =  Math.floor(su / irisu);
      } else {
        var caseSu = Math.ceil(su / irisu);
      }
      var hasu = su % irisu;
      $(e).parents('tr').find('[name="hasu"]').val(numberFormat(hasu, -1));
      $(e).parents('tr').find('[name="case_su"]').val(numberFormat(caseSu, -1));
      if($(e).parents('tbody').length > 0 ) {
        $(e).parents('tr').find('[name="hasu"]').addClass('hasChangeValue');
        $(e).parents('tr').find('[name="case_su"]').addClass('hasChangeValue');
        var row = $('#table').bootstrapTable('getData')[$(e).parents('tr').index()];
        row.su = su;
        row.hasu = hasu;
        row.case_su = caseSu;
      }
    }

    var totalSu = 0;
    $('#table tr').each(function() {
      var su = $(this).find('[name="su"]').val() || '0';
      su = parseFloat(su.replace(/,/g, ''));
      totalSu += su;
    });

    $('#totalSu').val(numberFormat(totalSu, -1));
  }

  function onchangeIrisu(e) {
    var irisu = $(e).parents('tr').find('[name="irisu"]').val();
    irisu = parseFloat(irisu.replace(/,/g, '')) || 0;

    if(irisu > 0) {
      $(e).parents('tr').find('[name="case_su"]').removeAttr('readonly').removeAttr('tabindex');
      $(e).parents('tr').find('[name="hasu"]').removeAttr('readonly').removeAttr('tabindex');
    } else {
      $(e).parents('tr').find('[name="case_su"]').val('').attr('readonly');
      $(e).parents('tr').find('[name="hasu"]').val('').attr('readonly');
    }
  }

  function formatterFooter(column, index) {
    if(column.field == 'irisu') {
      return '<td><input readonly onchange="onchangeIrisu(this)" tabindex="-1" onkeypress="onlyNumber(event)" type="text" maxlength="undefined" class="form-control text-right" name="irisu" value="" onblur="setCellFocusStatus($(this), false), formatNumberOnBlur(this)" onfocus="setCellFocusStatus($(this), true), removeFormatOnFocus(this)"></td>';
    }

    if(column.field == 'case_su') {
      return '<td><input onchange="calculator(this)" readonly tabindex="-1" onkeypress="onlyNumber(event)" type="text" maxlength="undefined" class="form-control text-right" name="case_su" value="" onblur="setCellFocusStatus($(this), false), formatNumberOnBlur(this)" onfocus="setCellFocusStatus($(this), true), removeFormatOnFocus(this)"></td>'
    }

    if(column.field == 'hasu') {
      return '<td><input onchange="calculator(this)" readonly tabindex="-1" onkeypress="onlyNumber(event)" type="text" maxlength="undefined" class="form-control text-right" name="hasu" value="" onblur="setCellFocusStatus($(this), false), formatNumberOnBlur(this)" onfocus="setCellFocusStatus($(this), true), removeFormatOnFocus(this)"></td>';
    }

    if(column.field == 'su') {
      return '<td><input onkeypress="onlyNumber(event)" type="text" maxlength="undefined" class="form-control text-right" name="su" value="" onblur="setCellFocusStatus($(this), false), formatNumberOnBlur(this), calculatorSu(this)" onfocus="setCellFocusStatus($(this), true), removeFormatOnFocus(this)"><div class="error error-su"><span class="text-danger"></span></div></td>';
    }

    if(column.field == 'jyuryo') {
      return '<td><div class="div-row" data-field="jyuryo"><input onkeypress="onlyNumber(event)" type="text" maxlength="undefined" class="form-control text-right" name="jyuryo" value="" onblur="setCellFocusStatus($(this), false), formatNumberOnBlur(this), calculatorJyuryuo(this)" onfocus="setCellFocusStatus($(this), true), removeFormatOnFocus(this)"><div class="error error-jyuryo"><span class="text-danger"></span></div></div></td>';
    }

    if(column.field == 'nyusyuko_den_meisai_no') {
      return '<td></td>';
    }

    if(column.field == 'kikaku') {
      return '<td><div class="div-row" data-field="kikaku"><input tabindex="-1" type="text" class="form-control" name="kikaku" readonly onblur="setCellFocusStatus($(this), false)" onfocus="setCellFocusStatus($(this), true)" /></div><div class="error error-kikaku"><span class="text-danger"></span></div></div></td>';
    }

    if(column.field == 'biko') {
      return '<td><input type="text" class="form-control" onblur="setCellFocusStatus($(this), false), onKeyupBiko(this, event)" onfocus="setCellFocusStatus($(this), true), focusBiko(this)" name="biko" data-key="column_15" placeholder="備考" ><div class="error error-biko"><span class="text-danger"></span></div></td>';
    }
  }
  function calculator(e) {
    var caseSuRow = $(e).parents('tr').find('[name="case_su"]').val();
    var hasuRow = $(e).parents('tr').find('[name="hasu"]').val();
    var irisu = $(e).parents('tr').find('[name="irisu"]').val();
    caseSuRow = parseFloat(caseSuRow.replace(/,/g, '')) || 0;
    hasuRow = parseFloat(hasuRow.replace(/,/g, '')) || 0;
    irisu = parseFloat(irisu.replace(/,/g, '')) || 0;
    var total = caseSuRow * irisu + hasuRow;
    $(e).parents('tr').find('[name="su"]').val(numberFormat(total, -1));

    if($(e).parents('tbody').length > 0) {
      $(e).parents('tr').find('[name="su"]').val(numberFormat(total, -1)).addClass('hasChangeValue');
      var row = $('#table').bootstrapTable('getData')[$(e).parents('tr').index()];
      row.su = total;
      row.hasu = hasuRow;
      row.case_su = caseSuRow;
    }
  }

  function calculatorRoundKintax(e) {
    var unchinKin = $('#dataUriage').find('[name="unchin_kin"]').val() || '0';
    var tyukeiKin = $('#dataUriage').find('[name="tyukei_kin"]').val() || '0';
    var tukoryoKin = $('#dataUriage').find('[name="tukoryo_kin"]').val() || '0';
    var tesuryoKin = $('#dataUriage').find('[name="tesuryo_kin"]').val() || '0';
    var niekiKin = $('#dataUriage').find('[name="nieki_kin"]').val() || '0';
    var syukaKin = $('#dataUriage').find('[name="syuka_kin"]').val() || '0';
    var menzeiKbn = $('#dataUriage').find('[name="menzei_kbn"]').val() || '0';

    var yosyaTyukeiKin = $('#dataUriage').find('[name="yosya_tyukei_kin"]').val() || '0';

    unchinKin = unchinKin.replace(/,/g, '');
    tyukeiKin = tyukeiKin.replace(/,/g, '');
    tukoryoKin = tukoryoKin.replace(/,/g, '');
    tesuryoKin = tesuryoKin.replace(/,/g, '');
    niekiKin = niekiKin.replace(/,/g, '');
    syukaKin = syukaKin.replace(/,/g, '');

    yosyaTyukeiKin = yosyaTyukeiKin.replace(/,/g, '');
    if(menzeiKbn != 0) {
      $('input[name="seikyu_kin_tax"]').val('');
      $('input[name="yosya_kin_tax"]').val('');
    } else {
      var ninusiCd = $('input[name="ninusi_cd"]').val();
      if(ninusiCd) {
        $.ajax({
          url: '{{route('nyusyuko.nyuryoku.calculator_round_kin_tax')}}',
          data: {
            unchin_kin: unchinKin,
            tyukei_kin: tyukeiKin,
            tukoryo_kin: tukoryoKin,
            tesuryo_kin: tesuryoKin,
            nieki_kin: niekiKin,
            syuka_kin: syukaKin,
            menzei_kbn: menzeiKbn,
            ninusi_cd: ninusiCd,
            yosya_tyukei_kin: yosyaTyukeiKin
          },
          method: 'POST',
          success: function(res) {
            if(res.data && res.data.seikyu_kin_tax) {
              $('input[name="seikyu_kin_tax"]').val(res.data.seikyu_kin_tax);
            } else {
              $('input[name="seikyu_kin_tax"]').val('');
            }
            if(res.data && res.data.yosya_kin_tax) {
              $('input[name="yosya_kin_tax"]').val(res.data.yosya_kin_tax);
            } else {
              $('input[name="yosya_kin_tax"]').val('');
            }
          }
        })
      }
    }
  }

  function onblurDenpyoDt(e)
  {
    var val = null;

    if ($(e).val()) {
      val = $(e).val();
    } else {
      val = currentDateSystem;
    }

    $('input[name=kisan_dt], input[name=denpyo_dt]').val(val);
  }
  var tabbiko = false;
  var focusbiko = false;
  $(document).keydown(function(e) {
    if(e.keyCode == 9) {
      tabbiko = true;
    }
  })

  function focusBiko(e) {
    focusbiko = true;
  }

  function onKeyupBiko(e) {
    if(tabbiko == true && focusbiko == true) {
      if ($('#table tbody tr').not('.no-records-found').length > 0 ) {
        $('#table tbody tr:first-child').find('[name="hinmei_cd"]').focus();
      } else {
        $('#table tfoot tr:first-child').find('[name="hinmei_cd"]').focus();
      }
    }
    tabbiko = false;
    focusbiko = false;
  }

  function keyupBumon(e, event) {
    if(event.keyCode == 13) {
      $('[name="hed_nyusyuko_kbn"]').parent().find('.select2-selection.select2-selection--single').focus();
    }
  }

  $(document).ready(function() {
    $('.formConfirmChange').find('select, input').change(function() {
      hasChangeData = true;
      hasChangeDataFlg = true;
    });

    $('select[name="hed_nyusyuko_kbn"]').select2({minimumResultsForSearch: -1});
    
  });

  $(document).on('focus', '.select2-selection.select2-selection--single', function (e) {
    $(this).closest(".select2-container").siblings('select:enabled').select2('open');
  });
</script>
@endsection