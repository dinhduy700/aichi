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
    $errorClass = ["error_message", "mb-0"];
    $rowGroupClass = ["col-12", "row", "ml-0"];
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
    ];
  @endphp
  <div class="card form-custom">
    <div class="card-body">
      <form id="soryoExport" target="_blank" method="post">
        @csrf

        <div class="form-group row">
          <div class="col">
            <button class="btn btn-primary min-wid-110 btn-xls-export" type="button"
                    href="{{ route('picking.soryo.exp.xls') }}">EXCEL出力
            </button>
            <button class="btn btn-primary min-wid-110 btn-xls-export" type="button"
                    href="{{ route('picking.soryo.exp.pdf') }}">プレビュー
            </button>
            <button class="btn btn-primary min-wid-110 btn-csv-export" type="button"
                    href="{{ route('picking.soryo.exp.csv') }}">ファイル出力
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
                <label class="col-form-label col-12 col-lg-1">出庫日</label>
                <div class="col-auto d-flex">
                  <div class="group-input">
                    <input type="text" class="form-control size-L datepicker" name="exp[kisan_dt_from]"
                            value="">
                    <div @class($errorClass)><span class=" text-danger" id="error-kisan_dt_from"></span></div>
                  </div>
                  <span class="col-form-label px-2"> ～ </span>
                  <div class="group-input">
                    <input type="text" class="form-control size-L datepicker" name="exp[kisan_dt_to]"
                            value="">
                    <div @class($errorClass)><span class=" text-danger" id="error-kisan_dt_to"></span></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div @class($rowGroupClass)>
            <div class="blockquote col-12 col-lg-12">
              <div class="row">
                <label class="col-form-label col-12 col-lg-1">荷　主</label>
                <div class="col-auto d-flex flex-column">
                  <div class="group-input">
                    @php
                      $cdAttrs = [
                          'name' => 'exp[ninusi_cd_from]', 'base' => 'ninusi_cd',
                          'onkeyup' => "expSuggestionKeyup(this, 'exp[ninusi_cd_from]', 'exp[ninusi_nm_from]')",
                        ];
                        $nmAttrs = [
                          'name' => 'exp[ninusi_nm_from]', 'base' => 'ninusi_nm',
                          'onkeyup' => "expSuggestionKeyup(this, 'exp[ninusi_cd_from]', 'exp[ninusi_nm_from]')",
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
                        ];
                        $nmAttrs = [
                          'name' => 'exp[ninusi_nm_to]', 'base' => 'ninusi_nm',
                          'onkeyup' => "expSuggestionKeyup(this, 'exp[ninusi_cd_to]', 'exp[ninusi_nm_to]')",
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
                <label class="col-form-label col-12 col-lg-1 mb-0">オプション</label>
                <div class="col-auto col-lg-11">
                  @foreach($printOtherOpts as $key => $opt)
                    <div class="form-check col-auto justify-content-start align-self-center">
                      <label class="form-check-label mb-0">
                        <input type="checkbox" class="form-check-input" name="exp[print_other][]" value="{{ $key }}">
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
    $(function() {
      exportJs.formId = 'soryoExport';
      exportJs.suggestColumns = @json($columns);
      exportJs.urls.masterSuggestion = '{!! route('master-suggestion') !!}';
      exportJs.urls.validate = '{!! route('picking.soryo.exp.filterValidate') !!}';
      exportJs.documentReady();
    });
    
    function expSuggestionKeyup(e, fieldCd, fieldNm){
      exportJs.expSuggestionKeyup(e, fieldCd, fieldNm);
    }
  </script>
@endsection
