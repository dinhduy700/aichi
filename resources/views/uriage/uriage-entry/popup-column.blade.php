<form id="initColumn">
  <style>
    #initColumn .form-check 
    {
      margin-bottom: 0 !important; margin-top: 0 !important;
    }
    #initColumn .form-check label 
    {
      margin-bottom: 0 !important;
    }
  </style>
  <div class="modal-body" id="columnInitModalBody" style="padding: 0;">
  <div class="form-check form-check-flat form-check-primary" style="margin-bottom: 5px !important;">
    <label class="form-check-label text-nowrap text-center">
    <input type="checkbox" class="form-check-input" id="checkAllColumn">
    <i class="input-helper"></i>
    <span style="margin-left: -60px; font-weight: bold">表示列選択</span>
    </label>
  </div>
  <div style="display: grid; grid-template-columns: repeat(2, 1fr); grid-gap: 5px;">
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="bumon_cd" value="1" onclick="initColumnCheckHidden(this, ['bumon_nm'])">部門CD/受注部門名
      <i class="input-helper"></i>
      </label>
      <input type="hidden" class="form-check-input" name="bumon_nm" value="1">
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="hatuti_cd" value="1" onclick="initColumnCheckHidden(this, ['hatuti_hachaku_nm'])">発地CD/発地名
      <i class="input-helper"></i>
      </label>
      <input type="hidden" class="form-check-input" name="hatuti_hachaku_nm" value="1">
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="genkin_cd" value="1" onclick="initColumnCheckHidden(this, ['genkin_nm'])">現金CD/現金名
      <i class="input-helper"></i>
      </label>
      <input type="hidden" class="form-check-input" name="genkin_nm" value="1">
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="ninusi_cd" value="1" onclick="initColumnCheckHidden(this, ['ninusi_nm'])">荷主CD/荷主名
      <i class="input-helper"></i>
      </label>
      <input type="hidden" class="form-check-input" name="ninusi_nm" value="1" >
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="syuka_dt" value="1" >集荷日
      <i class="input-helper"></i>
      </label>
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="haitatu_dt" value="1" >配達日
      <i class="input-helper"></i>
      </label>
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="hachaku_cd" value="1" onclick="initColumnCheckHidden(this, ['hachaku_nm'])">着地CD/着地名
      <i class="input-helper"></i>
      </label>
      <input type="hidden" class="form-check-input" name="hachaku_nm" value="1" >
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="syubetu_cd" value="1" onclick="initColumnCheckHidden(this, ['syubetu_nm'])">種別CD/種別名
      <i class="input-helper"></i>
      </label>
      <input type="hidden" class="form-check-input" name="syubetu_nm" value="1" >
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="hinmei_cd" value="1" onclick="initColumnCheckHidden(this, ['hinmoku_nm', 'hinmei_nm'])">品名CD/品目名/品名
      <i class="input-helper"></i>
      </label>
      <input type="hidden" class="form-check-input" name="hinmoku_nm" value="1">
      <input type="hidden" class="form-check-input" name="hinmei_nm" value="1">
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="su" value="1" >数量
      <i class="input-helper"></i>
      </label>
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="tani_cd" value="1" onclick="initColumnCheckHidden(this, ['tani_nm'])">単位CD/単位名
      <i class="input-helper"></i>
      </label>
      <input type="hidden" class="form-check-input" name="tani_nm" value="1" >
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="unso_dt" value="1" >運送日
      <i class="input-helper"></i>
      </label>
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="jyotai" value="1" >状態
      <i class="input-helper"></i>
      </label>
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="sitadori" value="1" >下取
      <i class="input-helper"></i>
      </label>
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="gyosya_cd" value="1" onclick="initColumnCheckHidden(this, ['gyosya_nm'])">業者CD/業者名
      <i class="input-helper"></i>
      </label>
      <input type="hidden" class="form-check-input" name="gyosya_nm" value="1" >
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="unchin_kin" value="1" >基本運賃
      <i class="input-helper"></i>
      </label>
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="tyukei_kin" value="1" >中継料
      <i class="input-helper"></i>
      </label>
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="tukoryo_kin" value="1" >通行料等
      <i class="input-helper"></i>
      </label>
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="syuka_kin"  value="1" >集荷料
      <i class="input-helper"></i>
      </label>
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="tesuryo_kin" value="1" >手数料
      <i class="input-helper"></i>
      </label>
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="biko_cd" value="1" onclick="initColumnCheckHidden(this, ['biko'])">備考CD/備考名
      <i class="input-helper"></i>
      </label>
      <input type="hidden" class="form-check-input" name="biko" value="1" >
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="unten_kin" value="1" >運転者金額
      <i class="input-helper"></i>
      </label>
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="unchin_mikakutei_kbn" value="1" onclick="initColumnCheckHidden(this, ['unchin_mikakutei_nm'])" >運賃確定区分/運賃確定名
      <i class="input-helper"></i>
      </label>
      <input type="hidden" class="form-check-input" name="unchin_mikakutei_nm" value="1" >
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="syaban" value="1" >車番
      <i class="input-helper"></i>
      </label>
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="jyomuin_cd" value="1" onclick="initColumnCheckHidden(this, ['jyomuin_nm'])">運転者CD/運転者名
      <i class="input-helper"></i>
      </label>
      <input type="hidden" class="form-check-input" name="jyomuin_nm" value="1" >
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="yousya_cd" value="1" onclick="initColumnCheckHidden(this, ['yousya_nm'])">庸車先CD/庸車先名
      <i class="input-helper"></i>
      </label>
      <input type="hidden" class="form-check-input" name="yousya_nm" value="1" >
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="yosya_tyukei_kin" value="1" >庸車料
      <i class="input-helper"></i>
      </label>
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="yosya_tukoryo_kin" value="1" >通行料等
      <i class="input-helper"></i>
      </label>
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="yosya_kin_tax" value="1" >消費税
      <i class="input-helper"></i>
      </label>
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="denpyo_send_dt" value="1" >伝票送付日
      <i class="input-helper"></i>
      </label>
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="nipou_dt" value="1" >日報日
      <i class="input-helper"></i>
      </label>
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="nipou_no" value="1" >日報NO
      <i class="input-helper"></i>
      </label>
    </div>
    <div class="form-check form-check-flat form-check-primary">
      <label class="form-check-label text-nowrap">
      <input type="checkbox" class="form-check-input" name="uriage_den_no" value="1" >売上番号
      <i class="input-helper"></i>
      </label>
    </div>
  </div>
  </div>
</form>

<style>
  #columnInitModalBody .form-check
  {
    border-bottom: 1px solid #eee;
  }
  #columnInitModalBody .form-check .form-check-label
  {
    height: 25px;
    padding-left: 50px;
    align-items: center;
  }
  #columnInitModalBody .form-check .form-check-label input[type="checkbox"] + .input-helper::before, #columnInitModalBody .form-check .form-check-label input[type="checkbox"] + .input-helper::after {
    left: 35px !important;
  }
  .key-focusing {
      background-color: #b3e0ff;
  }
</style>