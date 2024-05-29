@extends('layouts.master')
@section('css')
  <style>

  </style>
@endsection
@section('page-content')
  <div class="form-custom">
    <form id="exportNyukin" target="_blank" method="post">
      @csrf
      <div class="card">
        <div class="card-body" style="padding: 10px">
          <div class="row">
            <div class="col-12 py-4">
              <button class="btn btn-primary min-wid-110 btn-xls-export" type="button"
                href="{{ route('nyukin.exp.excel') }}">EXCEL出力
              </button>
              <button class="btn btn-primary min-wid-110 btn-xls-export" type="button"
                      href="{{ route('nyukin.exp.pdf') }}">プレビュー
              </button>
              <button class="btn btn-primary min-wid-110 btn-csv-export" type="button"
                href="{{ route('nyukin.exp.csv') }}">ファイル出力
              </button>
            </div>
          </div>
          <div class="row">
            <div class="col-12 d-flex flex-column" style="">
              <div class="row mb-4" style="">
                <label class="col-md-1 col-form-label">担当部門</label>
                <div class="col-md-11">
                  <div class="row mb-4 px-3 group-input">
                    <label class="col-form-label size-M">開始</label>
                    <div class="d-flex flex-wrap position-relative">
                      <input type="text" class="form-control size-M" name="exp[bumon_cd_from]"
                        style="width: 100px; border-bottom-right-radius: 0; border-top-right-radius: 0"
                        onkeyup="suggestionForm(this, 'bumon_cd', ['bumon_cd', 'kana', 'bumon_nm'], {'bumon_cd': 'exp[bumon_cd_from]', 'bumon_nm': 'exp[bumon_nm_from]'}, $(this).parent())"
                        style="" autocomplete="off">
                      <input class="form-control size-L" name="exp[bumon_nm_from]"
                        style="border-bottom-left-radius: 0; border-top-left-radius: 0"
                        onkeyup="suggestionForm(this, 'bumon_nm', ['bumon_cd', 'kana', 'bumon_nm'], {'bumon_cd': 'exp[bumon_cd_from]', 'bumon_nm': 'exp[bumon_nm_from]'}, $(this).parent())"
                        autocomplete="off">
                      <ul class="suggestion"></ul>
                      <div class="error_message" style="width: 100%">
                        <span class=" text-danger" id="error-bumon_cd_from"></span>
                      </div>
                    </div>
                  </div>
                  <div class="row px-3 group-input">
                    <label class="col-form-label size-M">終了</label>
                    <div class="d-flex flex-wrap position-relative">
                      <input type="text" class="form-control size-M" name="exp[bumon_cd_to]"
                        style="width: 100px; border-bottom-right-radius: 0; border-top-right-radius: 0"
                        onkeyup="suggestionForm(this, 'bumon_cd', ['bumon_cd', 'kana', 'bumon_nm'], {'bumon_cd': 'exp[bumon_cd_to]', 'bumon_nm': 'exp[bumon_nm_to]'}, $(this).parent())"
                        style="" autocomplete="off">
                      <input class="form-control size-L" name="exp[bumon_nm_to]"
                        style="border-bottom-left-radius: 0; border-top-left-radius: 0"
                        onkeyup="suggestionForm(this, 'bumon_nm', ['bumon_cd', 'kana', 'bumon_nm'], {'bumon_cd': 'exp[bumon_cd_to]', 'bumon_nm': 'exp[bumon_nm_to]'}, $(this).parent())"
                        autocomplete="off">
                      <ul class="suggestion"></ul>
                      <div class="error_message" style="width: 100%">
                        <span class=" text-danger" id="error-bumon_cd_to"></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row mb-4">
                <label class="col-form-label col-md-1">入金日</label>
                <div class="form-inline col-md-11" style="flex: 1; display: flex">
                  <div class="group-input">
                    <input type="text" id="startDate" class="form-control size-L-uni form-datetime text-center"
                      name="exp[nyukin_dt_from]">
                    <div class="error_message mb-0" style="width: 100%">
                      <span class=" text-danger" id="error-nyukin_dt_from"></span>
                    </div>
                  </div>
                  <span class="col-form-label px-2"> ～ </span>
                  <div class="group-input">
                    <input type="text" id="endDate" class="form-control size-L-uni form-datetime text-center"
                    name="exp[nyukin_dt_to]">
                    <div class="error_message mb-0" style="width: 100%">
                      <span class=" text-danger" id="error-nyukin_dt_to"></span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row mb-4">
                <label class="col-form-label col-md-1">入金NO</label>
                <div class="form-inline col-md-11" style="flex: 1; display: flex">
                  <input type="text" class="form-control size-L-uni text-center" name="exp[nyukin_no_from]"/>
                  <span class="px-2"> ～ </span>
                  <input class="form-control size-L-uni text-center" name="exp[nyukin_no_to]"/>
                  <ul class="suggestion"></ul>
                </div>
              </div>

              <div class="row mb-4">
                <label class="col-form-label col-md-1">オプション</label>
                <div class="col-md-10 form-check ml-3">
                  <label class="form-check-label">
                    <input type="checkbox" maxlength="1" class="form-check-input" name="exp[header]" value="1">
                    CSV見出し出力（M）
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="popup-confirm"></div>
  <table id="table"></table>
@endsection

@section('js')
  <script src="{{ asset('assets/custom/export.js') }}"></script>
  <script>
    $('#table').customTable({
      columns: [],
      formSearch: $('#exportNyukin'),
      urlSearchSuggestion: "{{ route('master-suggestion') }}",
    });
    // btn-xls-export EXCEL出力
    // btn-csv-export CSV出力
    $(function() {
      exportJs.formId = 'exportNyukin';
      exportJs.urls.validate = '{!! route('nyukin.exp.filterValidate') !!}';
      $('.btn-xls-export, .btn-csv-export, .btn-pdf-export').click(function() {
        exportJs.elementClick(this);
      });
      $('.form-datetime').change(function() {
        autoFillDate(this);
      });
    });
  </script>
@endsection
