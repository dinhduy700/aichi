<style>
  .flex1 
  {
    flex: 1;
  }
  .group-flex
  {
    display: flex;
    flex: 1;
    position: relative;
    flex-wrap: nowrap;
  }
  /*.group-flex .input1
  {
    width: 100px; border-bottom-right-radius: 0; border-top-right-radius: 0
  }

  .group-flex .input2
  {
    width: 100%; border-bottom-left-radius: 0; border-top-left-radius: 0
  }*/
  #popupSearchModal label 
  {
    margin-bottom: 0;
  }

  #popupSearchModal .form-group
  {
    margin-bottom: 0;
  }
  #popupSearchModal .row-s
  {
    margin-bottom: 0.8rem
  }
  .group-s-input 
  {
    display: none;
  }
  .group-s-input.active
  {
    display: block;
  }
  .error-message-row
  {
    color: red; font-size: 12px;
    display: none;
  }
  .error-message-row.active
  {
    display: block;
  }
  .error-input 
  {
    border-color: red !important;
  }
</style>
<div style="display: grid; grid-template-columns: repeat(3, 1fr);">
    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[syaban]" value="1">
              車番
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni input1" name="syaban_from" style="" onkeypress="onlyNumber(event)">
                  <ul class="suggestion"></ul>
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni input1" name="syaban_to" onkeypress="onlyNumber(event)">
                  <ul class="suggestion"></ul>
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[jyomuin]" value="1">
              運転者
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni input1" name="jyomuin_cd_from" style="" onkeyup="suggestionForm(this, 'jyomuin_cd', ['jyomuin_cd', 'jyomuin_nm', 'kana'], {jyomuin_cd: 'jyomuin_cd_from', jyomuin_nm: 'jyomuin_nm_from'}, $(this).parent() )">
                  {{--
                  <input class="form-control input2" name="jyomuin_nm_from" style="" onkeyup="suggestionForm(this, 'jyomuin_nm', ['jyomuin_cd', 'jyomuin_nm', 'kana'], {jyomuin_cd: 'jyomuin_cd_from', jyomuin_nm: 'jyomuin_nm_from'}, $(this).parent() )" > --}}
                  <ul class="suggestion"></ul>
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni input1" name="jyomuin_cd_to"  onkeyup="suggestionForm(this, 'jyomuin_cd', ['jyomuin_cd', 'jyomuin_nm', 'kana'], {jyomuin_cd: 'jyomuin_cd_to', jyomuin_nm: 'jyomuin_nm_to'}, $(this).parent() )">
                  {{--
                  <input class="form-control input2" name="jyomuin_nm_to"  onkeyup="suggestionForm(this, 'jyomuin_nm', ['jyomuin_cd', 'jyomuin_nm', 'kana'], {jyomuin_cd: 'jyomuin_cd_to', jyomuin_nm: 'jyomuin_nm_to'}, $(this).parent() )"> --}}
                  <ul class="suggestion"></ul>
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[yousya]" value="1">
              庸車先
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">庸車先</label> -->
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni input1" name="yousya_cd_from" style="" onkeyup="suggestionForm(this, 'yousya_cd', ['yousya_cd', 'yousya_nm', 'kana'], {yousya_cd: 'yousya_cd_from', yousya_nm: 'yousya_nm_from'}, $(this).parent() )">
                  <ul class="suggestion"></ul>
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni input1" name="yousya_cd_to"  onkeyup="suggestionForm(this, 'yousya_cd', ['yousya_cd', 'yousya_nm', 'kana'], {yousya_cd: 'yousya_cd_to', yousya_nm: 'yousya_nm_to'}, $(this).parent() )">
                  <ul class="suggestion"></ul>
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[ninusi]" value="1">
              荷主
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">荷主</label> -->
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni input1" name="ninusi_cd_from" style="" onkeyup="suggestionForm(this, 'ninusi_cd', ['ninusi_cd', 'ninusi_ryaku_nm', 'kana'], {ninusi_cd: 'ninusi_cd_from', ninusi_ryaku_nm: 'ninusi_nm_from'}, $(this).parent() )">
                  {{--
                  <input class="form-control input2" name="ninusi_nm_from" style="" onkeyup="suggestionForm(this, 'ninusi_nm', ['ninusi_cd', 'ninusi_ryaku_nm', 'kana'], {ninusi_cd: 'ninusi_cd_from', ninusi_ryaku_nm: 'ninusi_nm_from'}, $(this).parent() )" > --}}
                  <ul class="suggestion"></ul>
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni input1" name="ninusi_cd_to"  onkeyup="suggestionForm(this, 'ninusi_cd', ['ninusi_cd', 'ninusi_nm', 'kana'], {ninusi_cd: 'ninusi_cd_to', ninusi_nm: 'ninusi_nm_to'}, $(this).parent() )">
                  {{--
                  <input class="form-control input2" name="ninusi_nm_to"  onkeyup="suggestionForm(this, 'ninusi_nm', ['ninusi_cd', 'ninusi_nm', 'kana'], {ninusi_cd: 'ninusi_cd_to', ninusi_nm: 'ninusi_nm_to'}, $(this).parent() )"> --}}
                  <ul class="suggestion"></ul>
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[hachaku]" value="1">
              着地
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">着地</label> -->
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni input1" name="hachaku_cd_from" style="" onkeyup="suggestionForm(this, 'hachaku_cd', ['hachaku_cd', 'hachaku_nm', 'kana'], {hachaku_cd: 'hachaku_cd_from', hachaku_nm: 'hachaku_nm_from'}, $(this).parent() )">
                  {{--
                  <input class="form-control input2" name="hachaku_nm_from" style="" onkeyup="suggestionForm(this, 'hachaku_nm', ['hachaku_cd', 'hachaku_nm', 'kana'], {hachaku_cd: 'hachaku_cd_from', hachaku_nm: 'hachaku_nm_from'}, $(this).parent() )" > --}}
                  <ul class="suggestion"></ul>
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni input1" name="hachaku_cd_to"  onkeyup="suggestionForm(this, 'hachaku_cd', ['hachaku_cd', 'hachaku_nm', 'kana'], {hachaku_cd: 'hachaku_cd_to', hachaku_nm: 'hachaku_nm_to'}, $(this).parent() )">
                  {{--
                  <input class="form-control input2" name="hachaku_nm_to"  onkeyup="suggestionForm(this, 'hachaku_nm', ['hachaku_cd', 'hachaku_nm', 'kana'], {hachaku_cd: 'hachaku_cd_to', hachaku_nm: 'hachaku_nm_to'}, $(this).parent() )"> --}}
                  <ul class="suggestion"></ul>
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[haitatu_dt]" value="1">
              配達日
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">配達日</label> -->
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="haitatu_dt_from" style="" onchange="autoFillDate(this)">
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="haitatu_dt_to" onchange="autoFillDate(this)">
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[jikoku]" value="1">
              配達時刻
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni input1" name="jikoku_from" style="" onkeyup="">
                  <ul class="suggestion"></ul>
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni input1" name="jikoku_to"  onkeyup="">
                  <ul class="suggestion"></ul>
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[tyuki]" value="1">
              配達注記
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">配達注記</label> -->
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni input1" name="tyuki_from" style="">
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni input1" name="tyuki_to">
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[haitatu_jyusyo1]" value="1">
              配達住所1
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">配達住所1</label> -->
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni input1" name="haitatu_jyusyo1_from" style="">
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni input1" name="haitatu_jyusyo1_to">
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[haitatu_jyusyo2]" value="1">
              配達住所2
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">配達住所2</label> -->
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni input1" name="haitatu_jyusyo2_from" style="">
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni input1" name="haitatu_jyusyo2_to">
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[haitatu_atena]" value="1">
              配達宛名
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">配達宛名</label> -->
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni input1" name="haitatu_atena_from" style="">
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni input1" name="haitatu_atena_to">
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[haitatu_tel]" value="1">
              配達TEL
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">配達TEL</label> -->
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="haitatu_tel_from">
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="haitatu_tel_to">
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[haitatu_fax]" value="1">
              配達FAX
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">配達FAX</label> -->
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="haitatu_fax_from">
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="haitatu_fax_to">
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[unso_dt]" value="1">
              運送日
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">運送日</label> -->
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="unso_dt_from" style="" onchange="autoFillDate(this)">
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="unso_dt_to" onchange="autoFillDate(this)">
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[jisya_km]" value="1">
              実車Km
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">実車Km</label> -->
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="jisya_km_from">
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="jisya_km_to">
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[hinmei]" value="1">
              品名
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">品名</label> -->
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni input1" name="hinmei_cd_from" style="" onkeyup="suggestionForm(this, 'hinmei_cd', ['hinmei_cd', 'hinmei_nm', 'kana'], {hinmei_cd: 'hinmei_cd_from', hinmei_nm: 'hinmei_nm_from', hinmoku_nm: 'hinmoku_nm_from' }, $(this).parent() )">
                  <ul class="suggestion"></ul>
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div >
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni input1" name="hinmei_cd_to"  onkeyup="suggestionForm(this, 'hinmei_cd', ['hinmei_cd', 'hinmei_nm', 'kana'], {hinmei_cd: 'hinmei_cd_to', hinmei_nm: 'hinmei_nm_to',  hinmoku_nm: 'hinmoku_nm_to'}, $(this).parent() )">
                  <ul class="suggestion"></ul>
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[su]" value="1">
              数量
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">数量</label> -->
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="su_from" style="">
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="su_to">
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[tani]" value="1">
              単位
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">単位</label> -->
            <div class="col-sm form-inline">
              <div class="group-flex">
                <input type="text" class="form-control" name="tani"  onkeyup="suggestionForm(this, 'tani_cd', ['tani_cd', 'tani_nm', 'kana'], {tani_cd: 'tani'}, $(this).parent() )">
                <ul class="suggestion"></ul>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[unchin_kin]" value="1" >
              基本運賃
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">基本運賃</label> -->
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="unchin_kin_from" style="" onkeypress="onlyNumber(event)">
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="unchin_kin_to" onkeypress="onlyNumber(event)">
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[tyukei_kin]" value="1">
              中継料
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">中継料</label> -->
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="tyukei_kin_from" style=""  onkeypress="onlyNumber(event)">
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="tyukei_kin_to"  onkeypress="onlyNumber(event)">
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[tukoryo_kin]" value="1"  onkeypress="onlyNumber(event)">
              通行料等
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">通行料等</label> -->
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="tukoryo_kin_from" style=""  onkeypress="onlyNumber(event)">
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="tukoryo_kin_to"  onkeypress="onlyNumber(event)">
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[syuka_kin]" value="1">
              集荷料
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">集荷料</label> -->
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="syuka_kin_from" style=""  onkeypress="onlyNumber(event)">
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni"  name="syuka_kin_to"  onkeypress="onlyNumber(event)">
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[syaryo_kin]" value="1">
              車両金額
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">車両金額</label> -->
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="syaryo_kin_from" style=""  onkeypress="onlyNumber(event)">
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni"  name="syaryo_kin_to"  onkeypress="onlyNumber(event)">
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[unten_kin]" value="1">
              運転者金額
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">運転者金額</label> -->
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="unten_kin_from" style=""  onkeypress="onlyNumber(event)">
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="unten_kin_to"  onkeypress="onlyNumber(event)">
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[yosya_tyukei_kin]" value="1">
              庸車料
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">庸車料</label> -->
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="yosya_tyukei_kin_from" style=""  onkeypress="onlyNumber(event)">
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="yosya_tyukei_kin_to"  onkeypress="onlyNumber(event)">
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[yosya_tukoryo_kin]" value="1">
              庸車通行料等
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="yosya_tukoryo_kin_from" style=""  onkeypress="onlyNumber(event)">
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="yosya_tukoryo_kin_to"  onkeypress="onlyNumber(event)">
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[biko]" value="1">
              備考
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">備考</label> -->
            <div class="col-sm form-inline">
              <input type="text" class="form-control" name="biko">
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[add_tanto_cd]" value="1">
              入力担当CD
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">入力担当</label> -->
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni input1" name="add_tanto_cd_from" style="" onkeyup="suggestionForm(this, 'jyomuin_cd', ['jyomuin_cd', 'jyomuin_nm', 'kana'], {jyomuin_cd: 'add_tanto_cd_from', jyomuin_nm: 'add_tanto_nm_from'}, $(this).parent() )">
                  {{--
                  <input class="form-control input2" name="add_tanto_nm_from" style="" onkeyup="suggestionForm(this, 'jyomuin_nm', ['jyomuin_cd', 'jyomuin_nm', 'kana'], {jyomuin_cd: 'add_tanto_cd_from', jyomuin_nm: 'add_tanto_nm_from'}, $(this).parent() )" > --}}
                  <ul class="suggestion"></ul>
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni input1" name="add_tanto_cd_to"  onkeyup="suggestionForm(this, 'jyomuin_cd', ['jyomuin_cd', 'jyomuin_nm', 'kana'], {jyomuin_cd: 'add_tanto_cd_to', jyomuin_nm: 'add_tanto_nm_to'}, $(this).parent() )">
                  {{--
                  <input class="form-control input2" name="add_tanto_nm_to"  onkeyup="suggestionForm(this, 'jyomuin_nm', ['jyomuin_cd', 'jyomuin_nm', 'kana'], {jyomuin_cd: 'add_tanto_cd_to', jyomuin_nm: 'add_tanto_nm_to'}, $(this).parent() )"> --}}
                  <ul class="suggestion"></ul>
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>

    <div class="row row-s" style="">
        <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
            <label class="form-check-label text-nowrap">
              <input type="checkbox" class="form-check-input" name="chk[uriage_den_no]" value="1">
              売上番号
              <i class="input-helper"></i>
              <i class="input-helper"></i>
            </label>
        </div>
        <div class="col-md-10 group-s-input">
          <div class="form-group row">
            <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">売上番号</label> -->
            <div class="col-sm form-inline">
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="uriage_den_no_from" onkeypress="onlyNumber(event)">
                </div>
              </div>
              <span class="px-2"> ～ </span>
              <div>
                <div class="group-flex">
                  <input type="text" class="form-control size-L-uni" name="uriage_den_no_to" onkeypress="onlyNumber(event)">
                </div>
              </div>
            </div>
          </div>
          <div class="error-message-row"></div>
        </div>
    </div>
</div>