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
    $formId = 'export';
    $rowGroupClass = ["col-12", "row", "ml-0"];
    $errorClass = ["error_message", "mb-0"];
    $columns = [];
  @endphp
  <div class="card form-custom">
    <div class="card-body">
      <form id="{{ $formId }}" target="_blank" method="post">
        @csrf
        <div class="form-group row">
          <div class="col">
            <button class="btn btn-primary min-wid-110 btn-xls-export" type="button"
                    href="{{ route('seikyu.list.xls') }}">EXCEL出力
            </button>
            <button class="btn btn-primary min-wid-110 btn-pdf-export" type="button"
                    href="{{ route('seikyu.list.pdf') }}">プレビュー
            </button>
            <button class="btn btn-primary min-wid-110 btn-csv-export" type="button"
                    href="{{ route('seikyu.list.csv') }}">ファイル出力
            </button>
          </div>
        </div>
        <div class="form-group row mb-0">
          <textarea style="display: none" name="exp[selected_items]"><?php echo json_encode($selectedItems) ?></textarea>
          <div @class($rowGroupClass)>
            <div class="blockquote col-12 col-lg-12">
              <label>オプション</label>
              <div class="col-12 row form-inline">
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
      exportJs.formId = '{!! $formId !!}';
      exportJs.suggestColumns = @json($columns);
      exportJs.urls.masterSuggestion = '{!! route('master-suggestion') !!}';
      exportJs.urls.validate = '{!! route('seikyu.list.filterValidate') !!}';
      exportJs.documentReady();
    });
    function expSuggestionKeyup(e, fieldCd, fieldNm){
      exportJs.expSuggestionKeyup(e, fieldCd, fieldNm);
    }
  </script>
@endsection
