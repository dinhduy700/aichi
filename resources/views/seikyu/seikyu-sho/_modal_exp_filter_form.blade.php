@php
    $labelClass = ["col-12", "col-lg-2", "col-form-label"];
    $inputClass = ["col-12", "col-lg-10", "formCol", "px-0", "input-group", "form-inline"];
    $errorClass = ["error_message", "mb-0"];
  @endphp
<div class="modal fade" id="modalExpFilterSeikyu" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">請求書</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body form-custom">
        <div class="card form-custom">
          <div class="card-body">
            <form id="seikyuExport" method="post">
              @csrf
              <input type="hidden" name="list_ninusi_cd" value="">
              <input type="hidden" name="seikyu_sime_dt" value="">
              <div class="form-group row">
                <div class="col">
                  <button class="btn btn-primary min-wid-110 btn-xls-export" type="button"
                          data-href="{{ route('seikyu.seikyu_sho.exp.xls') }}" onclick="expFile(this)">EXCEL出力
                  </button>
                  <button class="btn btn-primary min-wid-110 btn-xls-export" type="button"
                          data-href="{{ route('seikyu.seikyu_sho.exp.pdf') }}" onclick="previewPdf(this)">プレビュー
                  </button>
                  <button class="btn btn-primary min-wid-110 btn-csv-export" type="button"
                          data-href="{{ route('seikyu.seikyu_sho.exp.csv') }}" onclick="expFile(this)">ファイル出力
                  </button>
                </div>
              </div>
      
      
              <div class="form-group row">
                <div class="col-12 col-md-12">
                  <div class="form-group row">
                    <label @class($labelClass)>見出指定</label>
                    <div class="col-12 col-lg-4 formCol px-0 input-group form-inline">
                      @foreach($midasisiteiOpts as $key => $opt)
                        <div class="col-auto pl-0">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="radio" class="form-check-input" name="exp[midasisitei]"
                                     value="{{ $key }}" @checked($key=='1')>
                              {{ $opt['text'] }}
                            </label>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>
      
              <div class="form-group row">
                <div class="col-12 col-md-12">
                  <div class="form-group row">
                    <label @class($labelClass)>請求書書式</label>
                    <div class="col-12 col-lg-4 formCol px-0 input-group form-inline">
                      @foreach($seikyusskOpts as $key => $opt)
                        <div class="col-auto pl-0">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="radio" class="form-check-input" name="exp[seikyussk]"
                                     value="{{ $key }}" @checked($key=='1')>
                              {{ $opt['text'] }}
                            </label>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-12 col-md-12">
                  <div class="form-group row">
                    <label @class($labelClass)>発行日付</label>
                    <div class="col-12 col-lg-4 formCol px-0 input-group form-inline">
                      <div class="group-input">
                        <input type="text" class="form-control size-L datepicker" name="exp[hakkou_dt]">
                        <div @class($errorClass)><span class=" text-danger" id="error-hakkou_dt"></span></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
        
      
              <div class="form-group row">
                <div class="col-12 col-md-12">
                  <div class="form-group row">
                    <label @class($labelClass)>オプション</label>
                    <div class="col-12 col-lg-4 formCol px-0 input-group form-inline">
                      @foreach($printOtherOpts as $key => $opt)
                          <div class="form-check" style="margin-right: 30px">
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
        
      </div>
    </div>
  </div>
</div>