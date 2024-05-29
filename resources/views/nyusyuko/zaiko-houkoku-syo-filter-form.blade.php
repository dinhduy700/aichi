@extends('layouts.master')
@section('css')
  <style>

  </style>
@endsection
@section('page-content')
  @php

    $labelClass = ["col-12", "col-lg-1", "col-form-label"];
    $inputClass = ["col-12", "col-lg-10", "formCol", "px-0", "input-group", "form-inline"];
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
      <form id="nyusyukoExport" target="_blank" method="post">
        @csrf
        <div class="form-group row">
          <div class="col">
            <button class="btn btn-primary min-wid-110 btn-xls-export" type="button"
                    href="{{ route('nyusyuko.exp.zaikoXls') }}">EXCEL出力
            </button>
            <button class="btn btn-primary min-wid-110 btn-xls-export" type="button"
                    href="{{ route('nyusyuko.exp.zaikoPdf') }}">プレビュー
            </button>
            <button class="btn btn-primary min-wid-110 btn-csv-export" type="button"
                    href="{{ route('nyusyuko.exp.zaikoCsv') }}">ファイル出力
            </button>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-12">
            <div class="form-group row">
              <label @class($labelClass)>部門</label>
              <div @class($inputClass)>
                <div class="form-inline align-items-start">
                  <div class="group-input">
                    @php
                      $cdAttrs = [
                        'name' => 'exp[bumon_cd_from]', 'base' => 'bumon_cd', 'id' => 'bumon_cd_from',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[bumon_cd_from]', 'exp[bumon_nm_from]')",
                        'value' => ''
                      ];
                      $nmAttrs = [
                        'name' => 'exp[bumon_nm_from]', 'base' => 'bumon_nm', 'id' => 'bumon_nm_from',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[bumon_cd_from]', 'exp[bumon_nm_from]')",
                        'value' => '', 'disabled' => 'disabled'
                      ];
                    @endphp
                    <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs"/>
                    <div @class($errorClass)><span class=" text-danger" id="error-bumon_cd_from"></span></div>
                  </div>
                  <span class="col-form-label px-2"> ～ </span>
                  <div class="group-input">
                    @php
                      $cdAttrs = [
                        'name' => 'exp[bumon_cd_to]', 'base' => 'bumon_cd', 'id' => 'bumon_cd_to',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[bumon_cd_to]', 'exp[bumon_nm_to]')",
                        'value' => ''
                      ];
                      $nmAttrs = [
                        'name' => 'exp[bumon_nm_to]', 'base' => 'bumon_nm', 'id' => 'bumon_nm_to',
                        'onkeyup' => "expSuggestionKeyup(this, 'exp[bumon_cd_to]', 'exp[bumon_nm_to]')",
                        'value' => '', 'disabled' => 'disabled'
                      ];
                    @endphp
                    <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs"/>
                    <div @class($errorClass)><span class=" text-danger" id="error-bumon_cd_to"></span></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label @class($labelClass)>荷主</label>
              <div @class($inputClass)>
                <div class="form-inline align-items-start">
                <div class="group-input">
                  @php
                    $cdAttrs = [
                      'name' => 'exp[ninusi_cd_from]', 'base' => 'ninusi_cd',
                      'onkeyup' => "expSuggestionKeyup(this, 'exp[ninusi_cd_from]', 'exp[ninusi_nm_from]')",
                      'value' => ''
                    ];
                    $nmAttrs = [
                      'name' => 'exp[ninusi_nm_from]', 'base' => 'ninusi_nm',
                      'onkeyup' => "expSuggestionKeyup(this, 'exp[ninusi_cd_from]', 'exp[ninusi_nm_from]')",
                      'value' => '', 'disabled' => 'disabled'
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
              <label @class(array_merge($labelClass))>基準日</label>
              <div @class($inputClass)>
                <div class="group-input">
                  <input type="text" class="form-control size-L datepicker" name="exp[kijyun_dt]" value="{{!empty($defaultKijyunDt) ? \App\Helpers\Formatter::date($defaultKijyunDt) : ''}}">
                  <div @class($errorClass)><span class=" text-danger" id="error-kijyun_dt"></span></div>
                </div>
                <span class="col-form-label pl-2">現在</span>
              </div>
            </div>

            <div class="form-group row">
              <label @class($labelClass)>商品</label>
              <div @class($inputClass)>
                <div class="form-inline align-items-start">
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
                    <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs" />
                    <div @class($errorClass)><span class=" text-danger" id="error-hinmei_cd_from"></span></div>
                  </div>
                  <span class="col-form-label px-2"> ～ </span>
                  <div class="group-input">
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
                    <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs" />
                    <div @class($errorClass)><span class=" text-danger" id="error-hinmei_cd_to"></span></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label @class($labelClass)>オプション</label>
              <div class="col-12 col-lg-10 px-0">
                <div class="form-group">
                  @foreach($optionOpts as $key => $opt)
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="exp[option][]" value="{{ $key }}" @if(!empty($initSearch) && isset($initSearch->{'choice'.$key.'_nm'}) && isset($initSearch->{'choice'.$key.'_char'}) && $initSearch->{'choice'.$key.'_char'} == $key) 
                          {{ 'checked' }} 
                        @endif >
                        {{ $opt['text'] }}
                        <i class="input-helper"></i></label>
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
      exportJs.urls.validate = '{!! route('nyusyuko.exp.zaikoFilterValidate') !!}';
      exportJs.documentReady();
    });
    function expSuggestionKeyup(e, fieldCd, fieldNm){
      exportJs.expSuggestionKeyup(e, fieldCd, fieldNm);
    }

  </script>
@endsection