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
    $errorClass     = ["error_message", "mb-0"];
    $rowGroupClass  = ["col-12", "row", "ml-0"];
    $listOndo       = config()->get('params.options.m_soko_hinmei.ondo');

    $columns = [
        'bumon_cd' => $bumon = [
          'suggestion_show' => [
            'bumon_cd',
            'kana',
            'bumon_nm',

          ],
        ],
        'bumon_nm' => $bumon,

        'soko_cd' => $soko = [
          'suggestion_show' => [
            'soko_cd',
            'kana',
            'soko_nm'
          ]
        ],
        'soko_nm' => $soko,

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
      <form id="tanaorosiExport" target="_blank" method="post">
        @csrf

        <div class="form-group row">
          <div class="col">
            <button class="btn btn-primary min-wid-110 btn-xls-export" type="button"
                    href="{{ route('tanaorosi.exp.xls') }}">EXCEL出力
            </button>
            <button class="btn btn-primary min-wid-110 btn-xls-export" type="button"
                    href="{{ route('tanaorosi.exp.pdf') }}">プレビュー
            </button>
            <button class="btn btn-primary min-wid-110 btn-csv-export" type="button"
                    href="{{ route('tanaorosi.exp.csv') }}">ファイル出力
            </button>
          </div>
        </div>

        <div class="form-group row mb-0">
          <div @class($rowGroupClass)>
            <div class="blockquote col-12 col-lg-12">
              <div class="row">
                <label class="col-form-label col-12 col-lg-1 mb-0">棚卸日</label>
                <div class="col-auto col-lg-11">
                  <span>{{ \App\Helpers\Formatter::date(now()) }}</span>
                  <span>時点</span>
                </div>
              </div>
            </div>
          </div>

          <div @class($rowGroupClass)>
            <div class="blockquote col-12 col-lg-6">
              <div class="row">
                <label class="col-form-label col-12 col-lg-2">部門</label>
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
                        'maxlength' => 20, 'value' => '', 'disabled' => 'disabled'
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
                        'maxlength' => 20, 'value' => '', 'disabled' => 'disabled'
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
            <div class="blockquote col-12 col-lg-6">
              <div class="row">
                <label class="col-form-label col-12 col-lg-2">倉庫</label>
                <div class="col-auto d-flex flex-column">
                  <div class="group-input">
                    @php
                      $cdAttrs = [
                        'name' => 'exp[soko_cd_from]', 'base' => 'soko_cd', 'id' => 'soko_cd_from',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[soko_cd_from]', 'exp[soko_nm_from]')",
                        'value' => ''
                      ];
                      $nmAttrs = [
                        'name' => 'exp[soko_nm_from]', 'base' => 'soko_nm', 'id' => 'soko_nm_from',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[soko_cd_from]', 'exp[soko_nm_from]')",
                        'value' => '', 'disabled' => 'disabled'
                      ];
                    @endphp
                    <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs">
                      <x-slot:prepend>
                        <div class="input-group-prepend">
                          <span class="input-group-text">開始</span>
                        </div>
                        </x-slot>
                    </x-suggestion-cd-name>
                    <div @class($errorClass)><span class=" text-danger" id="error-soko_cd_from"></span></div>
                  </div>
                  <div class="group-input mt-3">
                    @php
                      $cdAttrs = [
                        'name' => 'exp[soko_cd_to]', 'base' => 'soko_cd', 'id' => 'soko_cd_to',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[soko_cd_to]', 'exp[soko_nm_to]')",
                        'value' => ''
                      ];
                      $nmAttrs = [
                        'name' => 'exp[soko_nm_to]', 'base' => 'soko_nm', 'id' => 'soko_nm_to',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[soko_cd_to]', 'exp[soko_nm_to]')",
                        'value' => '', 'disabled' => 'disabled'
                      ];
                    @endphp
                    <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs">
                      <x-slot:prepend>
                        <div class="input-group-prepend">
                          <span class="input-group-text">終了</span>
                        </div>
                        </x-slot>
                    </x-suggestion-cd-name>
                    <div @class($errorClass)><span class=" text-danger" id="error-soko_cd_to"></span></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="blockquote col-12 col-lg-6">
              <div class="row">
                <label class="col-form-label col-12 col-lg-2">ロケーション</label>
                <div class="col-auto d-flex">
                  <div class="group-input">
                    <input type="text" class="form-control" name="exp[location_from]"
                    value="">
                    <div @class($errorClass)><span class=" text-danger" id="error-location_from"></span></div>
                  </div>
                  <span class="col-form-label px-2"> ～ </span>
                  <div class="group-input">
                    <input type="text" class="form-control" name="exp[location_to]"
                    value="">
                    <div @class($errorClass)><span class=" text-danger" id="error-location_to"></span></div>
                  </div>
                 
                  
                </div>
              </div>
            </div>
          </div>

          <div @class($rowGroupClass)>
            <div class="blockquote col-12 col-lg-6">
              <div class="row">
                <label class="col-form-label col-12 col-lg-2">荷　主</label>
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
                        'value' => '', 'disabled' => 'disabled'
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
                          'value' => '', 'disabled' => 'disabled'
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

            <div class="blockquote col-12 col-lg-6">
              <div class="row">
                <label class="col-form-label col-12 col-lg-2">商　品</label>
                <div class="col-auto d-flex flex-column">
                  <div class="group-input">
                    @php
                      $cdAttrs = [
                        'name' => 'exp[hinmei_cd_from]', 'base' => 'soko_hinmei_cd',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[hinmei_cd_from]', 'exp[hinmei_nm_from]')",
                        'value' => ''
                      ];
                      $nmAttrs = [
                        'name' => 'exp[hinmei_nm_from]', 'base' => 'soko_hinmei_nm',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[hinmei_cd_from]', 'exp[hinmei_nm_from]')",
                        'value' => '', 'disabled' => 'disabled'
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
                        'value' => ''
                      ];
                      $nmAttrs = [
                        'name' => 'exp[hinmei_nm_to]', 'base' => 'soko_hinmei_nm',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[hinmei_cd_to]', 'exp[hinmei_nm_to]')",
                        'value' => '', 'disabled' => 'disabled'
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
      exportJs.formId = 'tanaorosiExport';
      exportJs.suggestColumns = @json($columns);
      exportJs.urls.masterSuggestion = '{!! route('master-suggestion') !!}';
      exportJs.urls.validate = '{!! route('tanaorosi.exp.filterValidate') !!}';
      exportJs.documentReady();
    });

    function expSuggestionKeyup(e, fieldCd, fieldNm){
      exportJs.expSuggestionKeyup(e, fieldCd, fieldNm);
    }

  </script>
@endsection
