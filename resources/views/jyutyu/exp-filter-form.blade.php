@extends('layouts.master')
@section('css')
  <style>
    .input-group .size-M {
      width: calc(5em + var(--padding-left) + var(--padding-right) + 4px) !important;
      max-width: calc(5em + var(--padding-left) + var(--padding-right) + 4px) !important;
    }
    .form-control.size-S {
      width: calc(3em + var(--padding-left) + var(--padding-right) + 4px) !important;
    }
    .rotate {
      transform: rotate(-90deg);
      margin-left: calc(5em) !important;
    }
    .formCol {
      min-width: calc(25em + var(--padding-left)*2 + 2*var(--padding-right) + 7px) !important;;
    }
    .print_order label{
      font-size: 13px;
    }
  </style>
@endsection
@section('page-content')
  @php
    $labelClass = ["col-12", "col-lg-2", "col-form-label"];
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
      <form id="jyutyuExport" target="_blank" method="post">
        @csrf
        <div class="form-group row">
          <div class="col">
            <button class="btn btn-primary min-wid-110 btn-xls-export" type="button"
                    href="{{ route('jyutyu.exp.xls') }}">EXCEL出力
            </button>
            <button class="btn btn-primary min-wid-110 btn-xls-export" type="button"
                    href="{{ route('jyutyu.exp.pdf') }}">プレビュー
            </button>
            <button class="btn btn-primary min-wid-110 btn-csv-export" type="button"
                    href="{{ route('jyutyu.exp.csv') }}">ファイル出力
            </button>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-12 col-md-6 ">
            <div class="form-group row">
              <label @class($labelClass)>部門</label>
              <div @class($inputClass)>
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
                      'value' => ''
                    ];
                  @endphp
                  <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs" />
                  <div @class($errorClass)><span class=" text-danger" id="error-bumon_cd_from"></span></div>
                </div>
                <div class="col-12 form-inline"><span class="rotate"> ～ </span></div>
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
                      'value' => ''
                    ];
                  @endphp
                  <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs" />
                  <div @class($errorClass)><span class=" text-danger" id="error-bumon_cd_to"></span></div>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label @class(array_merge($labelClass, ['text-left']))>出力方法</label>
              <div @class($inputClass)>
                @foreach($injiGroupOpts as $key => $opt)
                  <div class="col-auto pl-0">
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="exp[inji_group]"
                               value="{{ $key }}" @checked($key=='syuka_dt') >
                        {{ $opt['text'] }}
                        <i class="input-helper"></i></label>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>

            <div class="form-group row">
              <label @class(array_merge($labelClass))>集荷日</label>
              <div @class($inputClass)>
                <div class="group-input">
                  <input type="text" class="form-control size-L datepicker" name="exp[syuka_dt_from]">
                  <div @class($errorClass)><span class=" text-danger" id="error-syuka_dt_from"></span></div>
                </div>
                <span class="col-form-label px-2"> ～ </span>
                <div class="group-input">
                  <input type="text" class="form-control size-L datepicker" name="exp[syuka_dt_to]">
                  <div @class($errorClass)><span class=" text-danger" id="error-syuka_dt_to"></span></div>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label @class($labelClass)>出力区分</label>
              <div @class($inputClass)>
                @foreach($syuturyokuKbnOpts as $key => $opt)
                  <div class="col-auto pl-0">
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="exp[syuturyoku_kbn]"
                               value="{{ $key }}" @checked($key=='1')>
                        {{ $opt['text'] }}
                      </label>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>

            <div class="form-group row">
              <label @class($labelClass)>荷主</label>
              <div @class($inputClass)>
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
                      'value' => ''
                    ];
                  @endphp
                  <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs" />
                  <div @class($errorClass)><span class=" text-danger" id="error-ninusi_cd_from"></span></div>
                </div>
                <div class="col-12 form-inline"><span class="rotate"> ～ </span></div>
                <div class="group-input">
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
                  <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs" />
                  <div @class($errorClass)><span class=" text-danger" id="error-ninusi_cd_to"></span></div>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label @class($labelClass)>受注区分</label>
              <div @class($inputClass)>
                <div class="group-input">
                  @php
                    $cdAttrs = [
                      'name' => 'exp[jyutyu_kbn_from]', 'base' => 'jyutyu_kbn',
                      'onkeyup' => "expSuggestionKeyup(this, 'exp[jyutyu_kbn_from]', 'exp[jyutyu_kbn_nm_from]')",
                    ];
                    $nmAttrs = [
                      'name' => 'exp[jyutyu_kbn_nm_from]', 'base' => 'jyutyu_kbn_nm',
                      'onkeyup' => "expSuggestionKeyup(this, 'exp[jyutyu_kbn_from]', 'exp[jyutyu_kbn_nm_from]')",
                    ];
                  @endphp
                  <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs" />
                  <div @class($errorClass)><span class=" text-danger" id="error-jyutyu_kbn_from"></span></div>
                </div>
                <div class="col-12 form-inline"><span class="rotate"> ～ </span></div>
                <div class="group-input">
                  @php
                    $cdAttrs = [
                      'name' => 'exp[jyutyu_kbn_to]', 'base' => 'jyutyu_kbn',
                      'onkeyup' => "expSuggestionKeyup(this, 'exp[jyutyu_kbn_to]', 'exp[jyutyu_kbn_nm_to]')",
                    ];
                    $nmAttrs = [
                      'name' => 'exp[jyutyu_kbn_nm_to]', 'base' => 'jyutyu_kbn_nm',
                      'onkeyup' => "expSuggestionKeyup(this, 'exp[jyutyu_kbn_to]', 'exp[jyutyu_kbn_nm_to]')",
                    ];
                  @endphp
                  <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs" />
                  <div @class($errorClass)><span class=" text-danger" id="error-jyutyu_kbn_to"></span></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 formCol">
            <div class="form-group row">
              <label @class($labelClass)>発地</label>
              <div @class($inputClass)>
                <div class="group-input">
                  @php
                    $cdAttrs = [
                      'name' => 'exp[hatuti_cd_from]', 'base' => 'hachaku_cd',
                      'onkeyup' => "expSuggestionKeyup(this, 'exp[hatuti_cd_from]', 'exp[hatuti_nm_from]')",
                    ];
                    $nmAttrs = [
                      'name' => 'exp[hatuti_nm_from]', 'base' => 'hachaku_nm',
                      'onkeyup' => "expSuggestionKeyup(this, 'exp[hatuti_cd_from]', 'exp[hatuti_nm_from]')",
                    ];
                  @endphp
                  <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs" />
                  <div @class($errorClass)><span class=" text-danger" id="error-hatuti_cd_from"></span></div>
                </div>
                <div class="col-12 form-inline"><span class="rotate"> ～ </span></div>
                <div class="group-input">
                  @php
                    $cdAttrs = [
                      'name' => 'exp[hatuti_cd_to]', 'base' => 'hachaku_cd',
                      'onkeyup' => "expSuggestionKeyup(this, 'exp[hatuti_cd_to]', 'exp[hatuti_nm_to]')",
                    ];
                    $nmAttrs = [
                      'name' => 'exp[hatuti_nm_to]', 'base' => 'hachaku_nm',
                      'onkeyup' => "expSuggestionKeyup(this, 'exp[hatuti_cd_to]', 'exp[hatuti_nm_to]')",
                    ];
                  @endphp
                  <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs" />
                  <div @class($errorClass)><span class=" text-danger" id="error-hatuti_cd_to"></span></div>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <label @class($labelClass)>着地</label>
              <div @class($inputClass)>
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
                  <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs" />
                  <div @class($errorClass)><span class=" text-danger" id="error-hachaku_cd_from"></span></div>
                </div>
                <div class="col-12 form-inline"><span class="rotate"> ～ </span></div>
                <div class="group-input">
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
                  <x-suggestion-cd-name :input-cd-attrs="$cdAttrs" :input-nm-attrs="$nmAttrs" />
                  <div @class($errorClass)><span class=" text-danger" id="error-hachaku_cd_to"></span></div>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <label @class($labelClass)>印刷順</label>
              <div @class($inputClass)>
                @php
                $i = 0;
                @endphp
                @foreach($printOrderFields as $field => $text)
                  <div class="w-100 form-inline print_order">
                    <input class="form-control size-S mr-2" name="exp[print_order][{{$field}}]" value="{{ ++$i }}" />
                    <label class="m-0">{{ $text }}</label>
                  </div>
                @endforeach
              </div>
            </div>

            <div class="form-group row">
              <label @class($labelClass)>その他</label>
              <div class="col-sm-10 px-0">
                <div class="form-group">
                  @foreach($printOtherOpts as $key => $opt)
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="exp[print_other][]" value="{{ $key }}">
                        {{ $opt['text'] }}
                        <i class="input-helper"></i></label>
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
      exportJs.formId = 'jyutyuExport';
      exportJs.suggestColumns = @json($columns);
      exportJs.urls.masterSuggestion = '{!! route('master-suggestion') !!}';
      exportJs.urls.validate = '{!! route('jyutyu.exp.filterValidate') !!}';
      exportJs.documentReady();
    });
    function expSuggestionKeyup(e, fieldCd, fieldNm){
      exportJs.expSuggestionKeyup(e, fieldCd, fieldNm);
    }
  </script>
@endsection
