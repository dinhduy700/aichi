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
    $rowGroupClass = ["col-12", "row", "ml-0"];
    $labelClass = ["col-12", "col-lg-2", "col-form-label"];
    $inputClass = ["input-group", "form-inline"];
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
        'jyutyu_kbn' => $jyutyu = [
           'suggestion_show' => [
            'jyutyu_kbn',
            'kana',
            'jyutyu_kbn_nm',
          ],
        ],
        'jyutyu_kbn_nm' => $jyutyu,
        'hachaku_cd' => $hatuti = [
           'suggestion_show' => [
            'hachaku_cd',
            'kana',
            'hachaku_nm',
          ],
        ],
        'hachaku_nm' => $hatuti,
    ];
  @endphp
  <div class="card form-custom">
    <div class="card-body">
      <form id="nouhinsyoExport" target="_blank" method="post">
        @csrf
        <div class="form-group row">
          <div class="col">
            <button class="btn btn-primary min-wid-110 btn-xls-export" type="button"
                    href="{{ route('uriage.nouhinsyo.xls') }}">EXCEL出力
            </button>
            <button class="btn btn-primary min-wid-110 btn-xls-export" type="button"
                    href="{{ route('uriage.nouhinsyo.pdf') }}">プレビュー
            </button>
          </div>
        </div>
        <div class="form-group row">
          <div @class($rowGroupClass)>
            <div class="blockquote col-12 col-lg-12">
              <div class="row">
              <label class="col-form-label col-12 col-lg-1">配車部門</label>
              <div class="col-auto form-inline">
                <div class="group-input">
                  @php
                    $cdAttrs = [
                      'name' => 'exp[bumon_cd_from]', 'base' => 'bumon_cd', 'id' => 'bumon_cd_from',
                      'onkeyup' => "expSuggestionKeyup(this, 'exp[bumon_cd_from]', 'exp[bumon_nm_from]')",
                      'maxlength' => 4, 'value' => !empty($initValue['bumon_cd_from']) ? $initValue['bumon_cd_from'] : ""
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
                <span class="col-form-label px-2"> ～ </span>
                <div class="group-input">
                  @php
                    $cdAttrs = [
                      'name' => 'exp[bumon_cd_to]', 'base' => 'bumon_cd', 'id' => 'bumon_cd_to',
                      'onkeyup' => "expSuggestionKeyup(this, 'exp[bumon_cd_to]', 'exp[bumon_nm_to]')",
                      'maxlength' => 4, 'value' => !empty($initValue['bumon_cd_to']) ? $initValue['bumon_cd_to'] : ""
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
            <div class="blockquote col-12 col-lg-4">
              <div class="row form-inline">
                <label class="col-lg-3 justify-content-start mb-0">集荷日</label>
                <div class="col-auto form-inline">
                  <div class="group-input">
                    <input type="text" class="form-control size-L datepicker" name="exp[syuka_dt_from]" maxlength="10">
                    <div @class($errorClass)><span class=" text-danger" id="error-syuka_dt_from"></span></div>
                  </div>
                  <span class="col-form-label px-2"> ～ </span>
                  <div class="group-input">
                    <input type="text" class="form-control size-L datepicker" name="exp[syuka_dt_to]" maxlength="10">
                    <div @class($errorClass)><span class=" text-danger" id="error-syuka_dt_to"></span></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="blockquote col-12 col-lg-4">
              <div class="row form-inline">
              <label class="col-lg-3 justify-content-start mb-0">出力区分</label>
              <div class="form-group row">
                @foreach($syuturyokuOpts as $value => $opt)
                  <div class="col-sm-auto">
                    <div class="form-check">
                      <label class="form-check-label mb-0">
                        <input type="radio" class="form-check-input" name="exp[syuturyoku]" value="{{ $value }}"
                               @checked($value==1)>
                        {{ $opt['text'] }}
                      </label>
                    </div>
                  </div>
                @endforeach
              </div>
              </div>
            </div>
            <div class="blockquote col-12 col-lg-4">
              <div class="row form-inline">
                <label class="col-lg-3 justify-content-start mb-0">売上番号</label>
              <div @class($inputClass)>
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
          </div>

          <div @class($rowGroupClass)>
            <div class="blockquote col-12 col-lg-6">
              <div class="row">
                <label class="col-form-label col-12 col-lg-2">荷主</label>
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
            <div class="blockquote col-12 col-lg-6">
              <div class="row">
                <label class="col-form-label col-12 col-lg-2">受注区分</label>
                <div class="col-auto d-flex flex-column">
                  <div class="group-input">
                    @php
                      $cdAttrs = [
                        'name' => 'exp[jyutyu_kbn_from]', 'base' => 'jyutyu_kbn', 'id' => 'jyutyu_kbn_from',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[jyutyu_kbn_from]', 'exp[jyutyu_kbn_nm_from]')",
                        'maxlength' => 4, 'value' => ''
                      ];
                      $nmAttrs = [
                        'name' => 'exp[jyutyu_kbn_nm_from]', 'base' => 'jyutyu_kbn_nm', 'id' => 'jyutyu_kbn_nm_from',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[jyutyu_kbn_from]', 'exp[jyutyu_kbn_nm_from]')",
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
                    <div @class($errorClass)><span class=" text-danger" id="error-jyutyu_kbn_from"></span></div>
                    <div @class($errorClass)><span class=" text-danger" id="error-jyutyu_kbn_nm_from"></span></div>
                  </div>
                  <div class="group-input mt-3">
                    @php
                      $cdAttrs = [
                        'name' => 'exp[jyutyu_kbn_to]', 'base' => 'jyutyu_kbn', 'id' => 'jyutyu_kbn_to',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[jyutyu_kbn_to]', 'exp[jyutyu_kbn_nm_to]')",
                        'maxlength' => 4, 'value' => ''
                      ];
                      $nmAttrs = [
                        'name' => 'exp[jyutyu_kbn_nm_to]', 'base' => 'jyutyu_kbn_nm', 'id' => 'jyutyu_kbn_nm_to',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[jyutyu_kbn_to]', 'exp[jyutyu_kbn_nm_to]')",
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
                    <div @class($errorClass)><span class=" text-danger" id="error-jyutyu_kbn_to"></span></div>
                    <div @class($errorClass)><span class=" text-danger" id="error-jyutyu_kbn_nm_to"></span></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div @class($rowGroupClass)>
            <div class="blockquote col-12 col-lg-12">
              <div class="row">
                <label class="col-form-label col-12 col-lg-1">タイトル設定</label>
                <div class="col-12 col-lg-11 row">
                  @foreach($titleOpts as $subName => $opt)
                    <div class="form-inline col-12 row">
                      <div class="form-check justify-content-start align-self-center">
                        <label class="form-check-label mb-0">
                          <input type="checkbox" class="form-check-input" name="exp[{{$subName}}][render]" value="1">
                          帳票選択
                        </label>
                      </div>
                      <input type="text" class="form-control size-2L ml-2 mr-5" name="exp[{{$subName}}][title]"
                             value="{{ $opt['text'] }}">
                      <div class="form-check size-L justify-content-start align-self-center">
                        <label class="form-check-label mb-0">
                          <input type="checkbox" class="form-check-input" name="exp[{{$subName}}][stamp]" value="1">
                          受領印エリア有無
                        </label>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>

          <div @class($rowGroupClass)>
            <div class="blockquote col-12 col-lg-12">
              <label class="">オプション</label>
              <div class="form-inline col-12 row">
                @foreach($otherOpts as $value => $opt)
                  <div class="form-check size-L justify-content-start align-self-center">
                    <label class="form-check-label mb-0">
                      <input type="checkbox" class="form-check-input" name="exp[option][]" value="{{ $value }}"
                             @checked(data_get($opt, 'checked', false)) />
                      {{ $opt['text'] }}
                    </label>
                  </div>
                @endforeach
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
      exportJs.formId = 'nouhinsyoExport';
      exportJs.suggestColumns = @json($columns);
      exportJs.urls.masterSuggestion = '{!! route('master-suggestion') !!}';
      exportJs.urls.validate = '{!! route('uriage.nouhinsyo.filterValidate') !!}';
      exportJs.documentReady();
    });
    function expSuggestionKeyup(e, fieldCd, fieldNm){
      exportJs.expSuggestionKeyup(e, fieldCd, fieldNm);
    }
  </script>
@endsection
