@extends('layouts.master')
@section('css')
  <style>
	  .input-group-prepend .input-group-text {
		  color: initial;
	  }
  </style>
@endsection
@section('page-content')
  @php
    $formId = 'shouhinUkebaraiExport';
    $rowGroupClass = ["col-12", "row", "ml-0"];
    $labelClass = ["col-12", "col-lg-1", "col-form-label"];
    $inputClass = ["col-12", "col-lg-10", "formCol", "input-group", "form-inline"];
    $errorClass = ["error_message", "mb-0"];
    $columns = [
        'bumon_cd' => $bumon = [
          'suggestion_show' => [
            'bumon_cd',
            'kana',
            'bumon_nm',

          ],
        ],
        'bumon_nm' => $bumon,
        'ninusi_cd' => $ninusi = [
          'suggestion_show' => [
            'ninusi_cd',
            'kana',
            'ninusi_ryaku_nm',
          ],
        ],
        'ninusi_nm' => $ninusi,
        'soko_hinmei_cd' => $hinmei = [
          'suggestion_show' => [
            'hinmei_cd',
            'kana',
            'hinmei_nm',
          ],
        ],
        'soko_hinmei_nm' => $hinmei,
    ];
  @endphp
  <div class="card form-custom">
    <div class="card-body">
      <form id="{{ $formId }}" target="_blank" method="post">
        @csrf
        <div class="form-group row">
          <div class="col">
            <button class="btn btn-primary min-wid-110 btn-xls-export" type="button"
                    href="{{ route('shouhin_ukebarai.exp.excel') }}">EXCEL出力
            </button>
            <button class="btn btn-primary min-wid-110 btn-pdf-export" type="button"
                    href="{{ route('shouhin_ukebarai.exp.pdf') }}">プレビュー
            </button>
            <button class="btn btn-primary min-wid-110 btn-csv-export" type="button"
                    href="{{ route('shouhin_ukebarai.exp.csv') }}">ファイル出力
            </button>
          </div>
        </div>
        <div class="form-group row mb-0">
          <div @class($rowGroupClass)>
            <div class="blockquote col-12 col-lg-12">
              <div class="row">
                <label class="col-form-label col-12 col-lg-1">部門</label>
                <div class="col-auto d-flex flex-column">
                  <div class="group-input">
                    @php
                      $cdAttrs = [
                        'name' => 'exp[bumon_cd_from]', 'base' => 'bumon_cd', 'id' => 'bumon_cd_from',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[bumon_cd_from]', 'exp[bumon_nm_from]')",
                        'maxlength' => 4, 'value' => ''
                      ];
                      $nmAttrs = [
                        'name' => 'exp[bumon_nm_from]', 'base' => 'bumon_nm', 'id' => 'bumon_nm_from',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[bumon_cd_from]', 'exp[bumon_nm_from]')",
                        'maxlength' => 20, 'value' => ''
                      ];
                    @endphp
                    <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs">
                      <x-slot:prepend>
                        <div class="input-group-prepend">
                          <span class="input-group-text">開始</span>
                        </div>
                        </x-slot>
                    </x-suggestion-cd-name>
                    <div @class($errorClass)><span class=" text-danger" id="error-bumon_cd_from"></span></div>
                  </div>
                  <div class="group-input mt-3">
                    @php
                      $cdAttrs = [
                        'name' => 'exp[bumon_cd_to]', 'base' => 'bumon_cd', 'id' => 'bumon_cd_to',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[bumon_cd_to]', 'exp[bumon_nm_to]')",
                        'maxlength' => 4, 'value' => ''
                      ];
                      $nmAttrs = [
                        'name' => 'exp[bumon_nm_to]', 'base' => 'bumon_nm', 'id' => 'bumon_nm_to',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[bumon_cd_to]', 'exp[bumon_nm_to]')",
                        'maxlength' => 20, 'value' => ''
                      ];
                    @endphp
                    <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs">
                      <x-slot:prepend>
                        <div class="input-group-prepend">
                          <span class="input-group-text">終了</span>
                        </div>
                        </x-slot>
                    </x-suggestion-cd-name>
                    <div @class($errorClass)><span class=" text-danger" id="error-bumon_cd_to"></span></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div @class($rowGroupClass)>
            <div class="blockquote col-12 col-lg-12">
              <div class="row">
                <label class="col-form-label col-12 col-lg-1">荷主</label>
                <div class="col-auto d-flex flex-column">
                  <div class="group-input">
                    @php
                      $cdAttrs = [
                        'name' => 'exp[ninusi_cd_from]', 'base' => 'ninusi_cd',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[ninusi_cd_from]', 'exp[ninusi_nm_from]')",
                        'maxlength' => 10, 'value' => ''
                      ];
                      $nmAttrs = [
                        'name' => 'exp[ninusi_nm_from]', 'base' => 'ninusi_nm',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[ninusi_cd_from]', 'exp[ninusi_nm_from]')",
                        'maxlength' => 40, 'value' => ''
                      ];
                    @endphp
                    <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs">
                      <x-slot:prepend>
                        <div class="input-group-prepend">
                          <span class="input-group-text">開始</span>
                        </div>
                        </x-slot>
                    </x-suggestion-cd-name>
                    <div @class($errorClass)><span class=" text-danger" id="error-ninusi_cd_from"></span></div>
                  </div>
                  <div class="group-input mt-3">
                    @php
                      $cdAttrs = [
                        'name' => 'exp[ninusi_cd_to]', 'base' => 'ninusi_cd',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[ninusi_cd_to]', 'exp[ninusi_nm_to]')",
                        'maxlength' => 10
                      ];
                      $nmAttrs = [
                        'name' => 'exp[ninusi_nm_to]', 'base' => 'ninusi_nm',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[ninusi_cd_to]', 'exp[ninusi_nm_to]')",
                        'maxlength' => 40
                      ];
                    @endphp
                    <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs">
                      <x-slot:prepend>
                        <div class="input-group-prepend">
                          <span class="input-group-text">終了</span>
                        </div>
                        </x-slot>
                    </x-suggestion-cd-name>
                    <div @class($errorClass)><span class=" text-danger" id="error-ninusi_cd_to"></span></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div @class($rowGroupClass)>
            <div class="blockquote col-12 col-lg-12">
              <div class="row">
                <label class="col-form-label col-12 col-lg-1">商品</label>
                <div class="col-auto d-flex flex-column">
                  <div class="group-input">
                    @php
                      $cdAttrs = [
                        'name' => 'exp[hinmei_cd_from]', 'base' => 'soko_hinmei_cd',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[hinmei_cd_from]', 'exp[hinmei_nm_from]')",
                        'maxlength' => 10, 'value' => ''
                      ];
                      $nmAttrs = [
                        'name' => 'exp[hinmei_nm_from]', 'base' => 'soko_hinmei_nm',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[hinmei_cd_from]', 'exp[hinmei_nm_from]')",
                        'maxlength' => 40, 'value' => ''
                      ];
                    @endphp
                    <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs">
                      <x-slot:prepend>
                        <div class="input-group-prepend">
                          <span class="input-group-text">開始</span>
                        </div>
                        </x-slot>
                    </x-suggestion-cd-name>
                    <div @class($errorClass)><span class=" text-danger" id="error-hinmei_cd_from"></span></div>
                  </div>
                  <div class="group-input mt-3">
                    @php
                      $cdAttrs = [
                        'name' => 'exp[hinmei_cd_to]', 'base' => 'soko_hinmei_cd',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[hinmei_cd_to]', 'exp[hinmei_nm_to]')",
                        'maxlength' => 10
                      ];
                      $nmAttrs = [
                        'name' => 'exp[hinmei_nm_to]', 'base' => 'soko_hinmei_nm',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[hinmei_cd_to]', 'exp[hinmei_nm_to]')",
                        'maxlength' => 40
                      ];
                    @endphp
                    <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs">
                      <x-slot:prepend>
                        <div class="input-group-prepend">
                          <span class="input-group-text">終了</span>
                        </div>
                        </x-slot>
                    </x-suggestion-cd-name>
                    <div @class($errorClass)><span class=" text-danger" id="error-hinmei_cd_to"></span></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div @class($rowGroupClass)>
            <div class="blockquote col-12 col-lg-12">
              <div class="row">
                <label @class(array_merge($labelClass))>日付</label>
                <div @class($inputClass)>
                  <div class="group-input">
                    <input type="text" class="form-control size-L datepicker" name="exp[kisan_dt_from]">
                    <div @class($errorClass)><span class=" text-danger" id="error-kisan_dt_from"></span></div>
                  </div>
                  <span class="col-form-label px-2"> ～ </span>
                  <div class="group-input">
                    <input type="text" class="form-control size-L datepicker" name="exp[kisan_dt_to]">
                    <div @class($errorClass)><span class=" text-danger" id="error-kisan_dt_to"></span></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div @class($rowGroupClass)>
            <div class="blockquote col-12 col-lg-12">
              <div class="row">
                <label class="col-form-label col-12 col-lg-1 mb-0">オプション</label>
                <div class="col-auto col-lg-3">
                  @foreach($optionOpts as $value => $opt)
                    <div class="form-check col-auto justify-content-start align-self-center">
                      <label class="form-check-label mb-0">
                        <input type="checkbox" class="form-check-input" name="exp[option][]" value="{{ $value }}">
                        {{ $opt['text'] }}
                      </label>
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
      exportJs.formId = 'shouhinUkebaraiExport';
      exportJs.suggestColumns = @json($columns);
      exportJs.urls.masterSuggestion = '{!! route('master-suggestion') !!}';
      exportJs.urls.validate = '{!! route('shouhin_ukebarai.exp.filterValidate') !!}';
      exportJs.documentReady();
    });
    function expSuggestionKeyup(e, fieldCd, fieldNm){
      exportJs.expSuggestionKeyup(e, fieldCd, fieldNm);
    }
  </script>
@endsection