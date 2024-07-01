<form id="initColumn">
  <div class="modal-body" id="columnInitModalBody" style="padding: 0">
      <div class="form-check form-check-flat form-check-primary">
        <label class="form-check-label text-nowrap text-center">
        <input type="checkbox" class="form-check-input" id="checkAllColumn">
        <i class="input-helper"></i>
        <span style="margin-left: -60px; font-weight: bold">表示列選択</span>
        </label>
      </div>

    <div class="row">
        <div class="col-md-6">
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
                <input type="checkbox" class="form-check-input" name="ninusi_cd" value="1" onclick="initColumnCheckHidden(this, ['ninusi_nm'])">荷主CD/荷主名
                <i class="input-helper"></i>
                </label>
                <input type="hidden" class="form-check-input" name="ninusi_nm" value="1" >
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
                <input type="checkbox" class="form-check-input" name="jikoku" value="1" >配達時刻
                <i class="input-helper"></i>
                </label>
              </div>
              <div class="form-check form-check-flat form-check-primary">
                <label class="form-check-label text-nowrap">
                <input type="checkbox" class="form-check-input" name="tyuki" value="1" >配達注記
                <i class="input-helper"></i>
                </label>
              </div>
              <div class="form-check form-check-flat form-check-primary">
                <label class="form-check-label text-nowrap">
                <input type="checkbox" class="form-check-input" name="haitatu_jyusyo1" value="1" >配達住所１
                <i class="input-helper"></i>
                </label>
              </div>
              <div class="form-check form-check-flat form-check-primary">
                <label class="form-check-label text-nowrap">
                <input type="checkbox" class="form-check-input" name="haitatu_jyusyo2" value="1" >配達住所2
                <i class="input-helper"></i>
                </label>
              </div>
              <div class="form-check form-check-flat form-check-primary">
                <label class="form-check-label text-nowrap">
                <input type="checkbox" class="form-check-input" name="haitatu_atena" value="1" >配達宛名
                <i class="input-helper"></i>
                </label>
              </div>
              <div class="form-check form-check-flat form-check-primary">
                <label class="form-check-label text-nowrap">
                <input type="checkbox" class="form-check-input" name="haitatu_tel" value="1" >配達TEL
                <i class="input-helper"></i>
                </label>
              </div>
              <div class="form-check form-check-flat form-check-primary">
                <label class="form-check-label text-nowrap">
                <input type="checkbox" class="form-check-input" name="haitatu_fax" value="1" >配達FAX
                <i class="input-helper"></i>
                </label>
              </div>
              <div class="form-check form-check-flat form-check-primary">
                <label class="form-check-label text-nowrap">
                <input type="checkbox" class="form-check-input" name="unso_dt" value="1" >運送日
                <i class="input-helper"></i>
                </label>
              </div>
        </div>
        <div class="col-md-6">
              <div class="form-check form-check-flat form-check-primary">
                <label class="form-check-label text-nowrap">
                <input type="checkbox" class="form-check-input" name="jisya_km" value="1" >実車km
                <i class="input-helper"></i>
                </label>
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
                <input type="checkbox" class="form-check-input" name="syaryo_kin"  value="1" >車両金額
                <i class="input-helper"></i>
                </label>
              </div>
              <div class="form-check form-check-flat form-check-primary">
                <label class="form-check-label text-nowrap">
                <input type="checkbox" class="form-check-input" name="unten_kin" value="1" >運転者金額
                <i class="input-helper"></i>
                </label>
              </div>
              <div class="form-check form-check-flat form-check-primary">
                <label class="form-check-label text-nowrap">
                <input type="checkbox" class="form-check-input" name="yosya_tyukei_kin" value="1" >庸車料
                <i class="input-helper"></i>
                </label>
              </div>
              <div class="form-check form-check-flat form-check-primary">
                <label class="form-check-label text-nowrap">
                <input type="checkbox" class="form-check-input" name="yosya_tukoryo_kin" value="1" >庸車通行料等
                <i class="input-helper"></i>
                </label>
              </div>
              <div class="form-check form-check-flat form-check-primary">
                <label class="form-check-label text-nowrap">
                <input type="checkbox" class="form-check-input" name="biko_cd" value="1" onclick="initColumnCheckHidden(this, ['syubetu_nm'])">備考CD/備考名
                <i class="input-helper"></i>
                </label>
                <input type="hidden" class="form-check-input" name="biko" value="1" >
              </div>
              <div class="form-check form-check-flat form-check-primary">
                <label class="form-check-label text-nowrap">
                <input type="checkbox" class="form-check-input" name="add_tanto_cd" value="1" onclick="initColumnCheckHidden(this, ['jyomuin_nm'])">入力担当CD/入力担当名
                <i class="input-helper"></i>
                </label>
                <input type="hidden" class="form-check-input" name="add_tanto_nm" value="1" >
              </div>
              <div class="form-check form-check-flat form-check-primary">
                <label class="form-check-label text-nowrap">
                <input type="checkbox" class="form-check-input" name="uriage_den_no" value="1" >売上番号
                <i class="input-helper"></i>
                </label>
              </div>
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
    height: 30px;
    padding-left: 50px;
  }
  #columnInitModalBody .form-check .form-check-label input[type="checkbox"] + .input-helper::before, #columnInitModalBody .form-check .form-check-label input[type="checkbox"] + .input-helper::after {
    left: 35px !important;
  }
  .key-focusing {
      background-color: #b3e0ff;
  }
</style>