@php
    $labelClass = ["col-12", "col-lg-2", "col-form-label"];
    $inputClass = ["col-12", "col-lg-10", "formCol", "px-0", "input-group", "form-inline"];
    $errorClass = ["error_message", "mb-0"];
  @endphp
<div class="modal fade" id="modalPreviewPdfSeikyu" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button onclick="printPDF()" class="btn btn-primary mr-1">印刷</button>
        <button data-href="{{ route('seikyu.seikyu_sho.exp.downloadPdf') }}" onclick="downloadPDF(this)" class="btn btn-primary">ダウンロード</button>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body form-custom">
        <div class="card form-custom">
          <div class="card-body">
            <div id="pdfContainer" class="text-center" style="height: 400px"></div>
          </div>
        </div>
        
      </div>
    </div>
  </div>
</div>
