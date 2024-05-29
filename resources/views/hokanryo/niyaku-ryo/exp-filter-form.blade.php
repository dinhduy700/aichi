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
    $formId = 'nyusyukoExport';
    $rowGroupClass = ["col-12", "row", "ml-0"];
    //$labelClass = ["col-12", "col-lg-1", "col-form-label"];
    //$inputClass = ["col-12", "col-lg-10", "formCol", "px-0", "input-group", "form-inline"];
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
                    href="{{ route('hokanryo.niyakuryo.xls') }}">EXCEL出力
            </button>
            <button class="btn btn-primary min-wid-110 btn-pdf-export" type="button"
                    href="{{ route('hokanryo.niyakuryo.pdf') }}">プレビュー
            </button>
            <button class="btn btn-primary min-wid-110 btn-csv-export" type="button"
                    href="{{ route('hokanryo.niyakuryo.csv') }}">ファイル出力
            </button>
          </div>
        </div>
        <div class="form-group row mb-0">
          <div @class($rowGroupClass)>
            <div class="blockquote col-12 col-lg-12">
              <div class="row">
                <label class="col-form-label col-12 col-lg-1">売上部門</label>
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
                <label class="col-form-label col-12 col-lg-1">請求締日</label>
                <div class="col-auto d-flex flex-column">
                  <div class="group-input">
                    <div class="input-group flex-nowrap">
                      <div class="input-group-prepend">
                        <span class="input-group-text">年月</span>
                      </div>
                      <input type="text" class="form-control size-M" name="exp[seikyu_sime_yymm]" />
                    </div>
                    <div @class($errorClass)><span class=" text-danger" id="error-seikyu_sime_yymm"></span></div>
                  </div>
                  <div class="group-input mt-3">
                    <div class="input-group flex-nowrap">
                      <div class="input-group-prepend">
                        <span class="input-group-text">日　</span>
                      </div>
                      <input type="text" class="form-control size-S" name="exp[seikyu_sime_dd]" />
                    </div>
                    <div @class($errorClass)><span class=" text-danger" id="error-seikyu_sime_dd"></span></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div @class($rowGroupClass)>
            <div class="blockquote col-12 col-lg-12">
              <div class="row">
                <label class="col-form-label col-12 col-lg-1">発行日</label>
                <div class="col-auto d-flex flex-column">
                  <div class="group-input">
                    <input type="text" class="form-control size-L datepicker" name="exp[hakko_dt]" value="">
                    <div @class($errorClass)><span class=" text-danger" id="error-hakko_dt"></span></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div @class($rowGroupClass)>
            <div class="blockquote col-12 col-lg-12">
              <div class="row">
                <label class="col-form-label col-12 col-lg-1">請求先</label>
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
                <label class="col-form-label col-12 col-lg-1 mb-0">出力区分</label>
                <div class="col-auto col-lg-11 form-inline">
                  <select class="form-control size-L" name="exp[export_kbn]">
                  @foreach($printOpts as $value => $opt)
                    <option value="{{ $value }}">{{ $opt['text'] }}</option>
                  @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>

          <div @class($rowGroupClass)>
            <div class="blockquote col-12 col-lg-12">
              <div class="row">
                <label class="col-form-label col-12 col-lg-1 mb-0">オプション</label>
                <div class="col-auto col-lg-11">
                  @foreach($optionOpts as $value => $opt)
                    <div class="form-check col-auto justify-content-start align-self-center">
                      <label class="form-check-label mb-0">
                        <input type="checkbox" class="form-check-input" name="exp[option][]" value="{{ $value }}"
                               @checked(in_array($value, $initValues['exp.option'] ?? [] )) >
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
      exportJs.formId = 'nyusyukoExport';
      exportJs.suggestColumns = @json($columns);
      exportJs.urls.masterSuggestion = '{!! route('master-suggestion') !!}';
      exportJs.urls.validate = '{!! route('hokanryo.niyakuryo.filterValidate') !!}';
      exportJs.documentReady();
    });
    function expSuggestionKeyup(e, fieldCd, fieldNm){
      exportJs.expSuggestionKeyup(e, fieldCd, fieldNm);
    }
  </script>
@endsection
