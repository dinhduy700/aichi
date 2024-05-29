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

        'hachaku_cd' => $hachaku = [
           'suggestion_show' => [
            'hachaku_cd',
            'kana',
            'hachaku_nm',
          ],
        ],
        'hachaku_nm' => $hachaku,
        'jyomuin_cd' => $jyomuin = [
          'suggestion_show' => [
            'jyomuin_cd',
            'kana',
            'jyomuin_nm',

          ],
        ],
        'jyomuin_nm' => $jyomuin,

        'yousya_cd' => $yousya = [
          'suggestion_show' => [
            'yousya_cd',
            'kana',
            'yousya_ryaku_nm',

          ],
        ],
        'yousya_nm' => $yousya,
    ];
  @endphp
  <div class="card form-custom">
    <div class="card-body">
      <form id="pickingGurisutoExport" target="_blank" method="post">
        @csrf

        <div class="form-group row">
          <div class="col">
            <button class="btn btn-primary min-wid-110 btn-xls-export" type="button"
                    href="{{ route('picking.picking_gurisuto.exp.xls') }}">EXCEL出力
            </button>
            <button class="btn btn-primary min-wid-110 btn-xls-export" type="button"
                    href="{{ route('picking.picking_gurisuto.exp.pdf') }}">プレビュー
            </button>
            <button class="btn btn-primary min-wid-110 btn-csv-export" type="button"
                    href="{{ route('picking.picking_gurisuto.exp.csv') }}">ファイル出力
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
            <div class="blockquote col-12 col-lg-6">
              <div class="row">
                <label class="col-form-label col-12 col-lg-2">荷届け先</label>
                <div class="col-auto d-flex flex-column">
                  <div class="group-input">
                    @php
                      $cdAttrs = [
                        'name' => 'exp[hachaku_cd_from]', 'base' => 'hachaku_cd',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[hachaku_cd_from]', 'exp[hachaku_nm_from]')",
                      ];
                      $nmAttrs = [
                        'name' => 'exp[hachaku_nm_from]', 'base' => 'hachaku_nm',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[hachaku_cd_from]', 'exp[hachaku_nm_from]')",
                      ];
                    @endphp
                    <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs">
                      <x-slot:prepend>
                        <div class="input-group-prepend">
                          <span class="input-group-text">開始</span>
                        </div>
                        </x-slot>
                    </x-suggestion-cd-name>
                    <div @class($errorClass)><span class=" text-danger" id="error-hachaku_cd_from"></span></div>
                  </div>
                  <div class="group-input mt-3">
                    @php
                      $cdAttrs = [
                        'name' => 'exp[hachaku_cd_to]', 'base' => 'hachaku_cd',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[hachaku_cd_to]', 'exp[hachaku_nm_to]')",
                      ];
                      $nmAttrs = [
                        'name' => 'exp[hachaku_nm_to]', 'base' => 'hachaku_nm',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[hachaku_cd_to]', 'exp[hachaku_nm_to]')",
                      ];
                    @endphp
                    <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs">
                      <x-slot:prepend>
                        <div class="input-group-prepend">
                          <span class="input-group-text">終了</span>
                        </div>
                        </x-slot>
                    </x-suggestion-cd-name>
                    <div @class($errorClass)><span class=" text-danger" id="error-hachaku_cd_to"></span></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="blockquote col-12 col-lg-6">
              <div class="row">
                <label class="col-form-label col-12 col-lg-2">車　番</label>
                <div class="col-auto d-flex flex-column">
                  <div class="group-input">
                    <div class="input-group flex-nowrap">
                      <div class="input-group-prepend">
                        <span class="input-group-text">開始</span>
                      </div>
                      <input type="text" class="form-control size-L" name="exp[syaban_from]" >
                    </div>
                    <div @class($errorClass)><span class=" text-danger" id="error-syaban_from"></span></div>
                  </div>
                  <div class="group-input mt-3">
                    <div class="input-group flex-nowrap">
                      <div class="input-group-prepend">
                        <span class="input-group-text">終了</span>
                      </div>
                      <input type="text" class="form-control size-L" name="exp[syaban_to]" >
                    </div>
                    <div @class($errorClass)><span class=" text-danger" id="error-syaban_to"></span></div>
                  </div>
                </div>
              </div>
            </div>
          </div>


          <div @class($rowGroupClass)>
            <div class="blockquote col-12 col-lg-6">
              <div class="row">
                <label class="col-form-label col-12 col-lg-2">乗務員</label>
                <div class="col-auto d-flex flex-column">
                  <div class="group-input">
                    @php
                      $cdAttrs = [
                        'name' => 'exp[jyomuin_cd_from]', 'base' => 'jyomuin_cd',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[jyomuin_cd_from]', 'exp[jyomuin_nm_from]')",
                      ];
                      $nmAttrs = [
                        'name' => 'exp[jyomuin_nm_from]', 'base' => 'jyomuin_nm',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[jyomuin_cd_from]', 'exp[jyomuin_nm_from]')",
                      ];
                    @endphp
                    <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs">
                      <x-slot:prepend>
                        <div class="input-group-prepend">
                          <span class="input-group-text">開始</span>
                        </div>
                        </x-slot>
                    </x-suggestion-cd-name>
                    <div @class($errorClass)><span class=" text-danger" id="error-jyomuin_cd_from"></span></div>
                  </div>
                  <div class="group-input mt-3">
                    @php
                      $cdAttrs = [
                        'name' => 'exp[jyomuin_cd_to]', 'base' => 'jyomuin_cd',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[jyomuin_cd_to]', 'exp[jyomuin_nm_to]')",
                      ];
                      $nmAttrs = [
                        'name' => 'exp[jyomuin_nm_to]', 'base' => 'jyomuin_nm',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[jyomuin_cd_to]', 'exp[jyomuin_nm_to]')",
                      ];
                    @endphp
                    <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs">
                      <x-slot:prepend>
                        <div class="input-group-prepend">
                          <span class="input-group-text">終了</span>
                        </div>
                        </x-slot>
                    </x-suggestion-cd-name>
                    <div @class($errorClass)><span class=" text-danger" id="error-jyomuin_cd_to"></span></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="blockquote col-12 col-lg-6">
              <div class="row">
                <label class="col-form-label col-12 col-lg-2">傭車先</label>
                <div class="col-auto d-flex flex-column">
                  <div class="group-input">
                    @php
                      $cdAttrs = [
                        'name' => 'exp[yousya_cd_from]', 'base' => 'yousya_cd',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[yousya_cd_from]', 'exp[yousya_nm_from]')",
                      ];
                      $nmAttrs = [
                        'name' => 'exp[yousya_nm_from]', 'base' => 'yousya_nm',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[yousya_cd_from]', 'exp[yousya_nm_from]')",
                      ];
                    @endphp
                    <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs">
                      <x-slot:prepend>
                        <div class="input-group-prepend">
                          <span class="input-group-text">開始</span>
                        </div>
                        </x-slot>
                    </x-suggestion-cd-name>
                    <div @class($errorClass)><span class=" text-danger" id="error-yousya_cd_from"></span></div>
                  </div>
                  <div class="group-input mt-3">
                    @php
                      $cdAttrs = [
                        'name' => 'exp[yousya_cd_to]', 'base' => 'yousya_cd',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[yousya_cd_to]', 'exp[yousya_nm_to]')",
                      ];
                      $nmAttrs = [
                        'name' => 'exp[yousya_nm_to]', 'base' => 'yousya_nm',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[yousya_cd_to]', 'exp[yousya_nm_to]')",
                      ];
                    @endphp
                    <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs">
                      <x-slot:prepend>
                        <div class="input-group-prepend">
                          <span class="input-group-text">終了</span>
                        </div>
                        </x-slot>
                    </x-suggestion-cd-name>
                    <div @class($errorClass)><span class=" text-danger" id="error-yousya_cd_to"></span></div>
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
      exportJs.formId = 'pickingGurisutoExport';
      exportJs.suggestColumns = @json($columns);
      exportJs.urls.masterSuggestion = '{!! route('master-suggestion') !!}';
      exportJs.urls.validate = '{!! route('picking.picking_gurisuto.exp.filterValidate') !!}';
      exportJs.documentReady();
    });
    
    function expSuggestionKeyup(e, fieldCd, fieldNm){
      exportJs.expSuggestionKeyup(e, fieldCd, fieldNm);
    }
  </script>
@endsection
