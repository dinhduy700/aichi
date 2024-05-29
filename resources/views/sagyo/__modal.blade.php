<div class="modal fade" id="{{ $idModal }}" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ $title }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body form-custom">
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">自社欄 上段</label>
          <div class="col-sm-7">
            <input type="text" class="form-control" name="exp[tyohyokbn_modal][{{ $key }}][upper]">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">自社欄 中段</label>
          <div class="col-sm-7">
            <input type="text" class="form-control" name="exp[tyohyokbn_modal][{{ $key }}][middle]">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">自社欄 下段</label>
          <div class="col-sm-7">
            <input type="text" class="form-control" name="exp[tyohyokbn_modal][{{ $key }}][bottom]">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">タイトル下コメント</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name="exp[tyohyokbn_modal][{{ $key }}][under_title]">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
        <button type="button" class="btn btn-primary" data-href="{{ route('sagyo.exp.handleMUserPg') }}" onclick="{{ $onClick }}">保存</button>
      </div>
    </div>
  </div>
</div>