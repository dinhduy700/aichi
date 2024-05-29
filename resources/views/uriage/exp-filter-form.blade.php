@extends('layouts.master')
@section('css')
  <style>
  .input-group .size-M {
    width: calc(5em + var(--padding-left) + var(--padding-right) + 4px) !important;
  }
  </style>
@endsection
@section('page-content')
  @php
    $labelClass = ["col-sm-1", "col-form-label"];
    $inputClass = ["col-sm-11", "px-0"];
    $errorClass = ["error_message", "mb-0"];
    $columns = [
        'bumon_cd' => [
            'suggestion_show' => $sugBumon = [
            'bumon_cd',
            'kana',
            'bumon_nm',
          ],
          'suggestion_change' => array_combine($sugBumon, $sugBumon)
        ],
        'ninusi_cd' => [
          'suggestion_show' => $sugNinusi = [
            'ninusi_cd',
            'kana',
            'ninusi_ryaku_nm',
          ],
          'suggestion_change' => array_combine($sugNinusi, $sugNinusi)
        ],
    ];
    $lastSunday = today()->previous('Sunday')->format(\App\Helpers\Formatter::DF_DATE);
  @endphp
  <div class="card form-custom">
    <div class="card-body">
      <form id="uriageExport" target="_blank" method="post">
        @csrf
        <div class="form-group row">
          <div class="col">
            <button class="btn btn-primary min-wid-110 btn-xls-export" type="button"
                    href="{{ route('uriage.exp.xls') }}">EXCEL出力
            </button>
            <button class="btn btn-primary min-wid-110 btn-xls-export" type="button"
                    href="{{ route('uriage.exp.pdf') }}">プレビュー
            </button>
            <button class="btn btn-primary min-wid-110 btn-csv-export" type="button"
                    href="{{ route('uriage.exp.csv') }}">ファイル出力
            </button>
          </div>
        </div>
        <div class="pl-0">
          <div class="form-group row">
            <label for="exampleInputUsername2" @class($labelClass)>部門</label>
            <div @class($inputClass)>
              <div class="col-12 form-inline align-items-start ">
                <div class="group-input">
                  @php
                    $cdAttrs = [
                      'name' => 'exp[bumon_cd_from]', 'base' => 'bumon_cd', 'id' => 'bumon_cd_from',
                      'onkeyup' => "expSuggestionKeyup(this, 'exp[bumon_cd_from]', 'exp[bumon_nm_from]')",
                      'value' => !empty($initValue['bumon_cd_from']) ? $initValue['bumon_cd_from'] : '' , 'maxlength' => 4
                    ];
                    $nmAttrs = [
                      'name' => 'exp[bumon_nm_from]', 'base' => 'bumon_nm', 'id' => 'bumon_nm_from',
                      'onkeyup' => "expSuggestionKeyup(this, 'exp[bumon_cd_from]', 'exp[bumon_nm_from]')",
                      'disabled' => 'disabled'
                    ];
                  @endphp
                  <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs" />
                  <div @class($errorClass)><span class=" text-danger" id="error-bumon_cd_from"></span></div>
                </div>
                <span class="col-form-label px-2"> ～ </span>
                <div class="group-input">
                  @php
                    $cdAttrs = [
                      'name' => 'exp[bumon_cd_to]', 'base' => 'bumon_cd', 'id' => 'bumon_cd_to',
                      'onkeyup' => "expSuggestionKeyup(this, 'exp[bumon_cd_to]', 'exp[bumon_nm_to]')",
                      'value' => !empty($initValue['bumon_cd_to']) ? $initValue['bumon_cd_to'] : '', 'maxlength' => 4
                    ];
                    $nmAttrs = [
                      'name' => 'exp[bumon_nm_to]', 'base' => 'bumon_nm', 'id' => 'bumon_nm_to',
                      'onkeyup' => "expSuggestionKeyup(this, 'exp[bumon_cd_to]', 'exp[bumon_nm_to]')",
                      'disabled' => 'disabled'
                    ];
                  @endphp
                  <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs" />
                  <div @class($errorClass)><span class=" text-danger" id="error-bumon_cd_to"></span></div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label for="exampleInputUsername2" @class($labelClass)>運送日</label>
            <div @class($inputClass)>
              <div class="col-sm form-inline">
                <div class="group-input">
                  <input type="text" class="form-control size-L datepicker" name="exp[unso_dt_from]" value="{{ $lastSunday }}">
                  <div @class($errorClass)><span class=" text-danger" id="error-unso_dt_from"></span></div>
                </div>
                <span class="col-form-label px-2"> ～ </span>
                <div class="group-input">
                  <input type="text" class="form-control size-L datepicker" name="exp[unso_dt_to]" value="{{ $lastSunday }}">
                  <div @class($errorClass)><span class=" text-danger" id="error-unso_dt_to"></span></div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label for="exampleInputUsername2" @class($labelClass)>荷主</label>
            <div @class($inputClass)>
              <div class="col-sm form-inline">
                <div class="group-input">
                  @php
                    $cdAttrs = [
                      'name' => 'exp[ninusi_cd_from]', 'base' => 'ninusi_cd',
                      'onkeyup' => "expSuggestionKeyup(this, 'exp[ninusi_cd_from]', 'exp[ninusi_nm_from]')",
                      'value' => '', 'maxlength' => 10
                    ];
                    $nmAttrs = [
                      'name' => 'exp[ninusi_nm_from]', 'base' => 'ninusi_nm',
                      'onkeyup' => "expSuggestionKeyup(this, 'exp[ninusi_cd_from]', 'exp[ninusi_nm_from]')",
                      'disabled' => 'disabled'
                    ];
                  @endphp
                  <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs" />
                  <div @class($errorClass)><span class=" text-danger" id="error-ninusi_cd_from"></span></div>
                </div>
                <span class="col-form-label px-2"> ～ </span>
                <div class="group-input">
                  @php
                    $cdAttrs = [
                      'name' => 'exp[ninusi_cd_to]', 'base' => 'ninusi_cd',
                      'onkeyup' => "expSuggestionKeyup(this, 'exp[ninusi_cd_to]', 'exp[ninusi_nm_to]')",
                      'value' => '', 'maxlength' => 10
                    ];
                    $nmAttrs = [
                      'name' => 'exp[ninusi_nm_to]', 'base' => 'ninusi_nm',
                      'onkeyup' => "expSuggestionKeyup(this, 'exp[ninusi_cd_to]', 'exp[ninusi_nm_to]')",
                      'disabled' => 'disabled'
                    ];
                  @endphp
                  <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs" />
                  <div @class($errorClass)><span class=" text-danger" id="error-ninusi_cd_to"></span></div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label for="exampleInputUsername2" @class($labelClass)>売上NO</label>
            <div @class($inputClass)>
              <div class="col-sm form-inline">
                <div class="group-input">
                  <input type="text" class="form-control size-L" name="exp[uriage_den_no_from]">
                  <div @class($errorClass)><span class=" text-danger" id="error-uriage_den_no_from"></span></div>
                </div>
                <span class="col-form-label px-2"> ～ </span>
                <div class="group-input">
                  <input type="text" class="form-control size-L" name="exp[uriage_den_no_to]">
                  <div @class($errorClass)><span class=" text-danger" id="error-uriage_den_no_to"></span></div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group row mb-0">
            <label for="exampleInputUsername2" class="col-sm-1 col-form-label">印刷順</label>
            <div @class($inputClass)>
              <div class="col-md-6">
                <div class="form-group mb-0">
                  @foreach($orderByOpts as $val => $opt)
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="exp[orderBy]" id="optionsRadios1"
                               value="{{ $val }}" @checked($val==1)/>
                      {{ $opt['text'] }}
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
@section('js')
  <script src="{{ asset('assets/custom/export.js') }}"></script>
  <script>
    // btn-xls-export EXCEL出力
    // btn-csv-export CSV出力
    $(function() {
      exportJs.formId = 'uriageExport';
      exportJs.suggestColumns = @json($columns);
      exportJs.urls.masterSuggestion = '{!! route('master-suggestion') !!}';
      exportJs.urls.validate = '{!! route('uriage.exp.filterValidate') !!}';
      exportJs.documentReady();
    });
    function expSuggestionKeyup(e, fieldCd, fieldNm){
      exportJs.expSuggestionKeyup(e, fieldCd, fieldNm);
    }
  </script>
@endsection
