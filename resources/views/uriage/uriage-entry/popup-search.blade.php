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
    margin-bottom: 0;
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
          <input type="checkbox" class="form-check-input" name="chk[bumon]" value="1">
          部門
          <i class="input-helper"></i>
          <i class="input-helper"></i>
        </label>
    </div>
    <div class="col-md-10 group-s-input">
      <div class="form-group row">
        <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">部門</label> -->
        <div class="col-sm form-inline">
          <div class="">
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni input1" name="bumon_cd_from" style="" onkeyup="suggestionForm(this, 'bumon_cd', ['bumon_cd', 'bumon_nm', 'kana'], {bumon_cd: 'bumon_cd_from', bumon_nm: 'bumon_nm_from'}, $(this).parent() )">

              {{-- <input class="form-control input2" name="bumon_nm_from" style="" onkeyup="suggestionForm(this, 'bumon_nm', ['bumon_cd', 'bumon_nm', 'kana'], {bumon_cd: 'bumon_cd_from', bumon_nm: 'bumon_nm_from'}, $(this).parent() )" > --}}
              <ul class="suggestion"></ul>
            </div>
          </div>
          <span class="px-2"> ～ </span>
          <div class="">
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni input1" name="bumon_cd_to"  onkeyup="suggestionForm(this, 'bumon_cd', ['bumon_cd', 'bumon_nm', 'kana'], {bumon_cd: 'bumon_cd_to', bumon_nm: 'bumon_nm_to'}, $(this).parent() )">

              {{-- <input class="form-control input2" name="bumon_nm_to"  onkeyup="suggestionForm(this, 'bumon_nm', ['bumon_cd', 'bumon_nm', 'kana'], {bumon_cd: 'bumon_cd_to', bumon_nm: 'bumon_nm_to'}, $(this).parent() )"> --}}
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
          <input type="checkbox" class="form-check-input" name="chk[hatuti]" value="1">
          発地
          <i class="input-helper"></i>
          <i class="input-helper"></i>
        </label>
    </div>
    <div class="col-md-10 group-s-input">
      <div class="form-group row">
        <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">発地</label> -->
        <div class="col-sm form-inline">
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni input1" name="hatuti_cd_from" style="" onkeyup="suggestionForm(this, 'hatuti_cd', ['hatuti_cd', 'hatuti_nm', 'kana'], {hatuti_cd: 'hatuti_cd_from', hatuti_nm: 'hatuti_nm_from'}, $(this).parent() )">
              {{--
              <input class="form-control input2" name="hatuti_nm_from" style="" onkeyup="suggestionForm(this, 'hatuti_nm', ['hatuti_cd', 'hatuti_nm', 'kana'], {hatuti_cd: 'hatuti_cd_from', hatuti_nm: 'hatuti_nm_from'}, $(this).parent() )" > --}}
              <ul class="suggestion"></ul>
            </div>
          </div>
          <span class="px-2"> ～ </span>
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni input1" name="hatuti_cd_to"  onkeyup="suggestionForm(this, 'hatuti_cd', ['hatuti_cd', 'hatuti_nm', 'kana'], {hatuti_cd: 'hatuti_cd_to', hatuti_nm: 'hatuti_nm_to'}, $(this).parent() )">
              {{--
              <input class="form-control input2" name="hatuti_nm_to"  onkeyup="suggestionForm(this, 'hatuti_nm', ['hatuti_cd', 'hatuti_nm', 'kana'], {hatuti_cd: 'hatuti_cd_to', hatuti_nm: 'hatuti_nm_to'}, $(this).parent() )"> --}}
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
          <input type="checkbox" class="form-check-input" name="chk[genkin]" value="1">
          現金
          <i class="input-helper"></i>
          <i class="input-helper"></i>
        </label>
    </div>
    <div class="col-md-10 group-s-input " style="align-self: center;">
      <div class="form-group row">
        <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">現金</label> -->
        <div class="col-sm form-inline ">
          @foreach($dataGenkin as $key => $genkin)
          <div class="form-check form-check-flat form-check-primary" style="margin-left: 5px; margin-top: 0; margin-bottom: 0">
            <label class="form-check-label">
              <input type="checkbox" class="form-check-input form-search" name="genkin_cd[]" value="{{ $genkin->genkin_cd }}">
              {{ $genkin->genkin_nm }}
              <i class="input-helper"></i>
            </label>
          </div>
          @endforeach
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
          <input type="checkbox" class="form-check-input " name="chk[syuka_dt]" value="1">
          集荷日
          <i class="input-helper"></i>
          <i class="input-helper"></i>
        </label>
    </div>
    <div class="col-md-10 group-s-input">
      <div class="form-group row">
        <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">集荷日</label> -->
        <div class="col-sm form-inline">
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni" style="width: 100%" name="syuka_dt_from" style="" onchange="autoFillDate(this)">
            </div>
          </div>
          <span class="px-2"> ～ </span>
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni"  style="width: 100%" name="syuka_dt_to" onchange="autoFillDate(this)">
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
          <input type="checkbox" class="form-check-input" name="chk[chaku]" value="1">
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
          <input type="checkbox" class="form-check-input" name="chk[syubetu]" value="1">
          種別
          <i class="input-helper"></i>
          <i class="input-helper"></i>
        </label>
    </div>
    <div class="col-md-10 group-s-input">
      <div class="form-group row">
        <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">種別</label> -->
        <div class="col-sm form-inline">
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni input1" name="syubetu_cd_from" style="" onkeyup="suggestionForm(this, 'syubetu_cd', ['syubetu_cd', 'syubetu_nm', 'kana'], {syubetu_cd: 'syubetu_cd_from', syubetu_nm: 'syubetu_nm_from'}, $(this).parent() )">
              {{--
              <input class="form-control input2" name="syubetu_nm_from" style="" onkeyup="suggestionForm(this, 'syubetu_nm', ['syubetu_cd', 'syubetu_nm', 'kana'], {syubetu_cd: 'syubetu_cd_from', syubetu_nm: 'syubetu_nm_from'}, $(this).parent() )" > --}}
              <ul class="suggestion"></ul>
            </div>
          </div>
          <span class="px-2"> ～ </span>
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni input1" name="syubetu_cd_to"  onkeyup="suggestionForm(this, 'syubetu_cd', ['syubetu_cd', 'syubetu_nm', 'kana'], {syubetu_cd: 'syubetu_cd_to', syubetu_nm: 'syubetu_nm_to'}, $(this).parent() )">
              {{--
              <input class="form-control input2" name="syubetu_nm_to"  onkeyup="suggestionForm(this, 'syubetu_nm', ['syubetu_cd', 'syubetu_nm', 'kana'], {syubetu_cd: 'syubetu_cd_to', syubetu_nm: 'syubetu_nm_to'}, $(this).parent() )"> --}}
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
              {{--
              <input type="text" class="form-control size-L" style="width: 100px; border-radius: 0" name="hinmoku_nm_from">

              <input class="form-control input2" name="hinmei_nm_from" style="" onkeyup="suggestionForm(this, 'hinmei_nm', ['hinmei_cd', 'hinmei_nm', 'kana'], {hinmei_cd: 'hinmei_cd_from', hinmei_nm: 'hinmei_nm_from', hinmoku_nm: 'hinmoku_nm_from'}, $(this).parent() )" > --}}
              <ul class="suggestion"></ul>
            </div>
          </div>
          <span class="px-2"> ～ </span>
          <div >
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni input1" name="hinmei_cd_to"  onkeyup="suggestionForm(this, 'hinmei_cd', ['hinmei_cd', 'hinmei_nm', 'kana'], {hinmei_cd: 'hinmei_cd_to', hinmei_nm: 'hinmei_nm_to',  hinmoku_nm: 'hinmoku_nm_to'}, $(this).parent() )">
              {{--
              <input type="text" class="form-control size-L" style="width: 100px; border-radius: 0" name="hinmoku_nm_to">
              <input class="form-control input2" name="hinmei_nm_to"  onkeyup="suggestionForm(this, 'hinmei_nm', ['hinmei_cd', 'hinmei_nm', 'kana'], {hinmei_cd: 'hinmei_cd_to', hinmei_nm: 'hinmei_nm_to', hinmoku_nm: 'hinmoku_nm_to'}, $(this).parent() )"> --}}
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
          <!-- <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni input1" name="tani_cd_from" style="" onkeyup="suggestionForm(this, 'tani_cd', ['tani_cd', 'tani_nm', 'kana'], {tani_cd: 'tani_cd_from', tani_nm: 'tani_nm_from'}, $(this).parent() )">
              {{--
              <input class="form-control input2" name="tani_nm_from" style="" onkeyup="suggestionForm(this, 'tani_nm', ['tani_cd', 'tani_nm', 'kana'], {tani_cd: 'tani_cd_from', tani_nm: 'tani_nm_from'}, $(this).parent() )" > --}}
              <ul class="suggestion"></ul>
            </div>
          </div>
          <span class="px-2"> ～ </span>
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni input1" name="tani_cd_to"  onkeyup="suggestionForm(this, 'tani_cd', ['tani_cd', 'tani_nm', 'kana'], {tani_cd: 'tani_cd_to', tani_nm: 'tani_nm_to'}, $(this).parent() )">
              {{--
              <input class="form-control input2" name="tani_nm_to"  onkeyup="suggestionForm(this, 'tani_nm', ['tani_cd', 'tani_nm', 'kana'], {tani_cd: 'tani_cd_to', tani_nm: 'tani_nm_to'}, $(this).parent() )"> --}}
              <ul class="suggestion"></ul>
            </div>
          </div> -->
        </div>
      </div>
      <div class="error-message-row"></div>
    </div>
  </div>


  <div class="row row-s" style="">
    <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
        <label class="form-check-label text-nowrap">
          <input type="checkbox" class="form-check-input" name="chk[unso]" value="1">
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
              <input type="text" class="form-control size-L-uni"  name="unso_dt_to" onchange="autoFillDate(this)">
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
          <input type="checkbox" class="form-check-input" name="chk[jyotai]" value="1">
          状態
          <i class="input-helper"></i>
          <i class="input-helper"></i>
        </label>
    </div>
    <div class="col-md-10 group-s-input">
      <div class="form-group row">
        <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">状態</label> -->
        <div class="col-sm form-inline">
          <input type="text" class="form-control" name="jyotai">
          <!-- <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni" name="jyotai_from" style="">
            </div>
          </div>
          <span class="px-2"> ～ </span>
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni" name="jyotai_to">
            </div>
          </div> -->
        </div>
      </div>
      <div class="error-message-row"></div>
    </div>
  </div>

  <div class="row row-s" style="">
    <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
        <label class="form-check-label text-nowrap">
          <input type="checkbox" class="form-check-input" name="chk[sitadori]" value="1">
          下取
          <i class="input-helper"></i>
          <i class="input-helper"></i>
        </label>
    </div>
    <div class="col-md-10 group-s-input">
      <div class="form-group row">
        <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">下取</label> -->
        <div class="col-sm form-inline">
          <input type="text" class="form-control" name="sitadori">
          <!-- <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni" name="sitadori_from" style="">
            </div>
          </div>
          <span class="px-2"> ～ </span>
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni"  style="width: 100%" name="sitadori_to">
            </div>
          </div> -->
        </div>
      </div>
      <div class="error-message-row"></div>
    </div>
  </div>

  <div class="row row-s" style="">
    <div class="col-2 form-check form-check-flat form-check-primary" style="margin-top: 7px;">
        <label class="form-check-label text-nowrap">
          <input type="checkbox" class="form-check-input" name="chk[gyosya]" value="1">
          業者
          <i class="input-helper"></i>
          <i class="input-helper"></i>
        </label>
    </div>
    <div class="col-md-10 group-s-input">
      <div class="form-group row">
        <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">業者</label> -->
        <div class="col-sm form-inline">
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni input1" name="gyosya_cd_from" style="" onkeyup="suggestionForm(this, 'gyosya_cd', ['gyosya_cd', 'gyosya_nm', 'kana'], {gyosya_cd: 'gyosya_cd_from', gyosya_nm: 'gyosya_nm_from'}, $(this).parent() )">
              {{--
              <input class="form-control input2" name="gyosya_nm_from" style="" onkeyup="suggestionForm(this, 'gyosya_nm', ['gyosya_cd', 'gyosya_nm', 'kana'], {gyosya_cd: 'gyosya_cd_from', gyosya_nm: 'gyosya_nm_from'}, $(this).parent() )" > --}}
              <ul class="suggestion"></ul>
            </div>
          </div>
          <span class="px-2"> ～ </span>
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni input1" name="gyosya_cd_to"  onkeyup="suggestionForm(this, 'gyosya_cd', ['gyosya_cd', 'gyosya_nm', 'kana'], {gyosya_cd: 'gyosya_cd_to', gyosya_nm: 'gyosya_nm_to'}, $(this).parent() )">
              {{--
              <input class="form-control input2" name="gyosya_nm_to"  onkeyup="suggestionForm(this, 'gyosya_nm', ['gyosya_cd', 'gyosya_nm', 'kana'], {gyosya_cd: 'gyosya_cd_to', gyosya_nm: 'gyosya_nm_to'}, $(this).parent() )"> --}}
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
          <input type="checkbox" class="form-check-input" name="chk[unchin_kin]" value="1">
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
              <input type="text" class="form-control size-L-uni" name="tyukei_kin_from" style="" onkeypress="onlyNumber(event)">
            </div>
          </div>
          <span class="px-2"> ～ </span>
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni" name="tyukei_kin_to" onkeypress="onlyNumber(event)">
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
          <input type="checkbox" class="form-check-input" name="chk[tukoryo_kin]" value="1">
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
              <input type="text" class="form-control size-L-uni" name="tukoryo_kin_from" style="" onkeypress="onlyNumber(event)">
            </div>
          </div>
          <span class="px-2"> ～ </span>
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni" name="tukoryo_kin_to" onkeypress="onlyNumber(event)">
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
              <input type="text" class="form-control size-L-uni" name="syuka_kin_from" style="" onkeypress="onlyNumber(event)">
            </div>
          </div>
          <span class="px-2"> ～ </span>
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni"  name="syuka_kin_to" onkeypress="onlyNumber(event)">
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
          <input type="checkbox" class="form-check-input" name="chk[tesuryo_kin]" value="1">
          手数料
          <i class="input-helper"></i>
          <i class="input-helper"></i>
        </label>
    </div>
    <div class="col-md-10 group-s-input">
      <div class="form-group row">
        <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">手数料</label> -->
        <div class="col-sm form-inline">
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni" name="tesuryo_kin_from" style="" onkeypress="onlyNumber(event)">
            </div>
          </div>
          <span class="px-2"> ～ </span>
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni"  name="tesuryo_kin_to" onkeypress="onlyNumber(event)">
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
          <!-- <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni input1" name="biko_cd_from" style="" onkeyup="suggestionForm(this, 'biko_cd', ['biko_cd', 'biko', 'kana'], {biko_cd: 'biko_cd_from', biko: 'biko_from'}, $(this).parent() )">
              {{--
              <input class="form-control input2" name="biko_from" style="" onkeyup="suggestionForm(this, 'biko', ['biko_cd', 'biko', 'kana'], {biko_cd: 'biko_cd_from', biko: 'biko_from'}, $(this).parent() )" > --}}
              <ul class="suggestion"></ul>
            </div>
          </div>
          <span class="px-2"> ～ </span>
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni input1" name="biko_cd_to"  onkeyup="suggestionForm(this, 'biko_cd', ['biko_cd', 'biko', 'kana'], {biko_cd: 'biko_cd_to', biko: 'biko_to'}, $(this).parent() )">
              {{--
              <input class="form-control input2" name="biko_to"  onkeyup="suggestionForm(this, 'biko', ['biko_cd', 'biko', 'kana'], {biko_cd: 'biko_cd_to', biko: 'biko_to'}, $(this).parent() )"> --}}
              <ul class="suggestion"></ul>
            </div>
          </div> -->
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
              <input type="text" class="form-control size-L-uni" name="syaryo_kin_from" style="" onkeypress="onlyNumber(event)">
            </div>
          </div>
          <span class="px-2"> ～ </span>
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni"  name="syaryo_kin_to" onkeypress="onlyNumber(event)">
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
              <input type="text" class="form-control size-L-uni" name="unten_kin_from" style="" onkeypress="onlyNumber(event)">
            </div>
          </div>
          <span class="px-2"> ～ </span>
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni" name="unten_kin_to" onkeypress="onlyNumber(event)">
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
          <input type="checkbox" class="form-check-input" name="chk[bunchin_mikakutei]" value="1">
          運賃確定区分
          <i class="input-helper"></i>
          <i class="input-helper"></i>
        </label>
    </div>
    <div class="col-md-10 group-s-input" style="align-self: center;">
      <div class="form-group row">
        <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">運賃確定区分</label> -->
        <div class="col-sm form-inline" style="margin-left: 30px; ">
          @foreach($dataUnchinMikakutei as $key => $unchin)
          <div class="form-check form-check-flat form-check-primary" style="margin-left: 5px; margin-top: 0; margin-bottom: 0">
            <label class="form-check-label">
              <input type="checkbox" class="form-check-input form-search" name="unchin_mikakutei_kbn[]" value="{{ $unchin->unchin_mikakutei_kbn }}">
              {{ $unchin->unchin_mikakutei_nm }}
              <i class="input-helper"></i>
            </label>
          </div>
          @endforeach
        </div>
      </div>
      <div class="error-message-row"></div>
    </div>
  </div>

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
        <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">車番</label> -->
        <div class="col-sm form-inline">
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni" name="syaban_from" style="">
            </div>
          </div>
          <span class="px-2"> ～ </span>
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni" name="syaban_to">
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
        <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">運転者</label> -->
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
              {{--
              <input class="form-control input2" name="yousya_nm_from" style="" onkeyup="suggestionForm(this, 'yousya_nm', ['yousya_cd', 'yousya_nm', 'kana'], {yousya_cd: 'yousya_cd_from', yousya_nm: 'yousya_nm_from'}, $(this).parent() )" > --}}
              <ul class="suggestion"></ul>
            </div>
          </div>
          <span class="px-2"> ～ </span>
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni input1" name="yousya_cd_to"  onkeyup="suggestionForm(this, 'yousya_cd', ['yousya_cd', 'yousya_nm', 'kana'], {yousya_cd: 'yousya_cd_to', yousya_nm: 'yousya_nm_to'}, $(this).parent() )">
              {{--
              <input class="form-control input2" name="yousya_nm_to"  onkeyup="suggestionForm(this, 'yousya_nm', ['yousya_cd', 'yousya_nm', 'kana'], {yousya_cd: 'yousya_cd_to', yousya_nm: 'yousya_nm_to'}, $(this).parent() )"> --}}
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
          <input type="checkbox" class="form-check-input" name="chk[yosya_kin]" value="1" onkeypress="onlyNumber(event)">
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
              <input type="text" class="form-control size-L-uni" name="yosya_tyukei_kin_from" style="" onkeypress="onlyNumber(event)">
            </div>
          </div>
          <span class="px-2"> ～ </span>
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni" name="yosya_tyukei_kin_to" onkeypress="onlyNumber(event)">
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
          <input type="checkbox" class="form-check-input" name="chk[yosya_tukoryo_kin]" value="1" >
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
              <input type="text" class="form-control size-L-uni" name="yosya_tukoryo_kin_from" style="" onkeypress="onlyNumber(event)">
            </div>
          </div>
          <span class="px-2"> ～ </span>
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni" name="yosya_tukoryo_kin_to" onkeypress="onlyNumber(event)">
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
          <input type="checkbox" class="form-check-input" name="chk[yosya_kin_tax]" value="1">
          消費税
          <i class="input-helper"></i>
          <i class="input-helper"></i>
        </label>
    </div>
    <div class="col-md-10 group-s-input">
      <div class="form-group row">
        <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">消費税</label> -->
        <div class="col-sm form-inline">
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni" name="yosya_kin_tax_from" onkeypress="onlyNumber(event)" >
            </div>
          </div>
          <span class="px-2"> ～ </span>
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni" name="yosya_kin_tax_to" onkeypress="onlyNumber(event)">
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
          <input type="checkbox" class="form-check-input" name="chk[denpyo_send_dt]" value="1">
          伝票送付日
          <i class="input-helper"></i>
          <i class="input-helper"></i>
        </label>
    </div>
    <div class="col-md-10 group-s-input">
      <div class="form-group row">
        <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">伝票送付日</label> -->
        <div class="col-sm form-inline">
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni" name="denpyo_send_dt_from" style="" onchange="autoFillDate(this)">
            </div>
          </div>
          <span class="px-2"> ～ </span>
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni"  name="denpyo_send_dt_to" onchange="autoFillDate(this)">
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
          <input type="checkbox" class="form-check-input" name="chk[denpyo_sofu_dt]" value="1">
          日報日
          <i class="input-helper"></i>
          <i class="input-helper"></i>
        </label>
    </div>
    <div class="col-md-10 group-s-input">
      <div class="form-group row">
        <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">日報日</label> -->
        <div class="col-sm form-inline">
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni" name="nipou_dt_from" style="" onchange="autoFillDate(this)">
            </div>
          </div>
          <span class="px-2"> ～ </span>
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni" name="nipou_dt_to" onchange="autoFillDate(this)">
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
          <input type="checkbox" class="form-check-input" name="chk[nipou_no]" value="1">
          日報NO
          <i class="input-helper"></i>
          <i class="input-helper"></i>
        </label>
    </div>
    <div class="col-md-10 group-s-input">
      <div class="form-group row">
        <!-- <label for="exampleInputUsername2" class="col-sm-2 col-form-label">日報NO</label> -->
        <div class="col-sm form-inline">
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni" name="nipou_no_from" onkeypress="onlyNumber(event)">
            </div>
          </div>
          <span class="px-2"> ～ </span>
          <div>
            <div class="group-flex">
              <input type="text" class="form-control size-L-uni" name="nipou_no_to" onkeypress="onlyNumber(event)">
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