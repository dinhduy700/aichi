@extends('layouts.master')
@section('css')
  <style>


  </style>
@endsection
@section('page-content')
@php
  $formSearchNyukinIndex = null;
  if(!empty($request->NyukinIndex))
  {
    $formSearchNyukinIndex = $request->NyukinIndex;
  }
@endphp
<form method="post" action="{{ !empty($nyukin) ? route('nyukin.update', ['nyukinNo' => $nyukin->nyukin_no]) :   route('nyukin.store') }}">
  <div class="card form-custom">
    <div>
      <div class="card-body">
        <h4 class="card-title mb-0">@if(!empty($nyukin)){{ trans('app.screen.master.meisyo.edit') }}@else{{ trans('app.screen.master.meisyo.create') }}@endif</h4>
        <div class="button-form">
          <button class="btn btn-back min-wid-110" id="backButton" data-href="{{route('nyukin.index')}}" onclick="redirectBack(this, 'NyukinIndex')" type="button">{{ trans('app.labels.btn-back') }}</button>
          @if(!empty($nyukin)) 
            @if($nyukin->sime_kakutei_kbn != 1) 
              <button class="btn btn-insert min-wid-110" type="button" onclick="submitForm(this)">{{trans('app.labels.btn-update')}}
              </button>
            @endif
          @else 
            <button class="btn btn-insert min-wid-110" type="button" onclick="submitForm(this)">
              {{trans('app.labels.btn-insert')}} 
            </button>
          @endif
          @if(!empty($nyukin) && $nyukin->sime_kakutei_kbn != 1)
            <button class="btn btn-delete min-wid-110" type="button" onclick="deleteData(this)">{{trans('app.labels.btn-delete')}}</button>
          @endif
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-8">
            <div class="" style="position: relative;">
              {{csrf_field()}}
              @if(!empty($formSearchNyukinIndex))
                @foreach($formSearchNyukinIndex as $key => $value)
                  <input type="hidden" name="NyukinIndex[{{ $key }}]" value="{{ $value }}">
                @endforeach
              @endif
              <div class="form-group">
                <div class="row">
                  <label class="col-12 col-md-1 col-form-label text-nowrap ">入金NO</label>
                  <div class="col-12 col-md-8 group-input">
                    <input type="text" name="nyukin_no" readonly class="form-control size-L" value="{{ old('nyukin_no', !empty($nyukin) ? $nyukin->nyukin_no : (!empty($maxNyukinNo) ? $maxNyukinNo : '') ) }}">
                    <div class="error_message">
                      <span class=" text-danger" id="error-nyukin_no"></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-12 col-md-12">
                    <div class="row group-input group-s-input">
                      <label class="col-form-label col-md-1" >荷主</label>
                      <div  class="col-md-8">
                        <div style="flex-wrap: wrap; display: flex; flex: 1; position: relative; ">
                          <input type="text" class="form-control size-M" name="ninusi_cd" style="width: 100px; border-bottom-right-radius: 0; border-top-right-radius: 0" onkeyup="suggestionForm(this, 'ninusi_cd', ['ninusi_cd', 'kana', 'ninusi_nm'], {'ninusi_cd': 'ninusi_cd', 'ninusi_nm': 'ninusi_nm'}, $(this).parent())" style="" autocomplete="off" value="{{ old('ninusi_cd', !empty($nyukin) ? $nyukin->ninusi_cd : '' ) }}" onchange="autoFillSeikyuSimeDt(), onchangeNinusiLoadSeikuSel()">

                          <input class="form-control size-3L" name="ninusi_nm" style="border-bottom-left-radius: 0; border-top-left-radius: 0" onkeyup="suggestionForm(this, 'ninusi_nm', ['ninusi_cd', 'kana', 'ninusi_nm'], {'ninusi_cd': 'ninusi_cd', 'ninusi_nm': 'ninusi_nm'}, $(this).parent())" autocomplete="off"  value="{{ old('ninusi_nm', !empty($nyukin) ? $nyukin->ninusi_nm : '' ) }}" onchange="autoFillSeikyuSimeDt(), onchangeNinusiLoadSeikuSel()">
                          <ul class="suggestion"></ul>
                          <div style="width: 100%;"><span class="error-message-row"></span></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-12 col-md-6">
                    <div class="row group-input" >
                      <label class="col-12 col-md-2 col-form-label text-nowrap ">入金日</label>
                      <div class="col-12 col-md-4 group-input">
                        <input type="text" onchange="autoFillDate(this), autoFillSeikyuSimeDt()" class="form-control size-L datePicker" name="nyukin_dt" value="{{ old('nyukin_dt', !empty($nyukin) ? \App\Helpers\Formatter::date($nyukin->nyukin_dt) : '' ) }}">
                        <div class="error_message">
                          <span class=" text-danger" id="error-nyukin_dt"></span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-md-6">
                    <div class="row group-input">
                      <label class="col-12 col-md-2 col-form-label text-nowrap ">締　日</label>
                      <div class="col-12 col-md-4 group-input">
                        <input type="text" onchange="autoFillDate(this)" class="form-control size-L datePicker" name="seikyu_sime_dt" value="{{ old('seikyu_sime_dt', !empty($nyukin) ? \App\Helpers\Formatter::date($nyukin->seikyu_sime_dt) : '' ) }}">
                        <div class="error_message">
                          <span class=" text-danger" id="error-seikyu_sime_dt"></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-12 col-md-6">
                    <div class="row group-input">
                      <label class="col-12 col-md-2 col-form-label text-nowrap ">現　金</label>
                      <div class="col-12 col-md-4 group-input">
                        <input type="text" class="form-control size-L text-right" name="genkin_kin" onblur="formatNumberOnBlur(this)" onfocus="removeFormatOnFocus(this)" onkeypress="return onlyNumber(event)" onkeyup="totalForInput($('#nyukin_sum'), ['genkin_kin', 'furikomi_kin', 'furikomi_tesuryo_kin', 'tegata_kin', 'sousai_kin', 'nebiki_kin', 'sonota_nyu_kin'])" maxlength="10" value="{{ old('genkin_kin', !empty($nyukin) ? numberFormat($nyukin->genkin_kin) : '' ) }}">
                        <div class="error_message">
                          <span class=" text-danger" id="error-genkin_kin"></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <label class="col-12 col-md-1 col-form-label text-nowrap">振　込</label>
                  <div class="col-12 col-md-2">
                    <div class=" group-input">
                      <input type="text" class="form-control size-L text-right" name="furikomi_kin" onblur="formatNumberOnBlur(this)" onfocus="removeFormatOnFocus(this)" onkeypress="return onlyNumber(event)" onkeyup="totalForInput($('#nyukin_sum'), ['genkin_kin', 'furikomi_kin', 'furikomi_tesuryo_kin', 'tegata_kin', 'sousai_kin', 'nebiki_kin', 'sonota_nyu_kin'])" value="{{ old('furikomi_kin', !empty($nyukin) ? numberFormat($nyukin->furikomi_kin) : '' ) }}" maxlength="10">
                      <div class="error_message">
                        <span class=" text-danger" id="error-furikomi_kin"></span>
                      </div>
                    </div>
                  </div>
                  <label class="col-12 col-md-1 col-form-label text-nowrap">振込手数料</label>
                  <div class="col-12 col-md-2">
                    <div class=" group-input">
                      <input type="text" class="form-control size-L text-right" name="furikomi_tesuryo_kin" onblur="formatNumberOnBlur(this)" onfocus="removeFormatOnFocus(this)" onkeypress="return onlyNumber(event)" onkeyup="totalForInput($('#nyukin_sum'), ['genkin_kin', 'furikomi_kin', 'furikomi_tesuryo_kin', 'tegata_kin', 'sousai_kin', 'nebiki_kin', 'sonota_nyu_kin'])" value="{{ old('furikomi_tesuryo_kin', !empty($nyukin) ? numberFormat($nyukin->furikomi_tesuryo_kin) : '' ) }}" maxlength="10">
                      <div class="error_message">
                        <span class=" text-danger" id="error-furikomi_tesuryo_kin"></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row ">
                  <label class="col-12 col-md-1 col-form-label text-nowrap">手　形</label>
                  <div class="col-12 col-md-2">
                    <div class="group-input">
                      <input type="text" class="form-control size-L text-right" name="tegata_kin" onblur="formatNumberOnBlur(this)" onfocus="removeFormatOnFocus(this)" onkeypress="return onlyNumber(event)" onkeyup="totalForInput($('#nyukin_sum'), ['genkin_kin', 'furikomi_kin', 'furikomi_tesuryo_kin', 'tegata_kin', 'sousai_kin', 'nebiki_kin', 'sonota_nyu_kin'])" value="{{ old('tegata_kin', !empty($nyukin) ? numberFormat($nyukin->tegata_kin) : '' ) }}" maxlength="10">
                      <div class="error_message">
                        <span class=" text-danger" id="error-tegata_kin"></span>
                      </div>
                    </div>
                  </div>
                  <label class="col-12 col-md-1 col-form-label text-nowrap">手形期日</label>
                  <div class="col-12 col-md-2">
                    <div class="group-input">
                      <input type="text" onchange="autoFillDate(this)" class="form-control size-L datePicker" name="tegata_kijitu_kin" value="{{ old('tegata_kijitu_kin', !empty($nyukin) ? \App\Helpers\Formatter::date($nyukin->tegata_kijitu_kin) : '' ) }}">
                      <div class="error_message">
                        <span class=" text-danger" id="error-tegata_kijitu_kin"></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <label class="col-12 col-md-1 col-form-label text-nowrap">相　殺</label>
                  <div class="col-12 col-md-2">
                    <div>
                      <input type="text" class="form-control size-L text-right" name="sousai_kin" onblur="formatNumberOnBlur(this)" onfocus="removeFormatOnFocus(this)" onkeypress="return onlyNumber(event)" onkeyup="totalForInput($('#nyukin_sum'), ['genkin_kin', 'furikomi_kin', 'furikomi_tesuryo_kin', 'tegata_kin', 'sousai_kin', 'nebiki_kin', 'sonota_nyu_kin'])" value="{{ old('sousai_kin', !empty($nyukin) ? numberFormat($nyukin->sousai_kin) : '' ) }}" maxlength="10">
                      <div class="error_message">
                        <span class=" text-danger" id="error-sousai_kin"></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <label class="col-12 col-md-1 col-form-label text-nowrap">値　引</label>
                  <div class="col-12 col-md-2">
                    <div class="group-input">
                      <input type="text" class="form-control size-L text-right" name="nebiki_kin" onblur="formatNumberOnBlur(this)" onfocus="removeFormatOnFocus(this)" onkeypress="return onlyNumber(event)" onkeyup="totalForInput($('#nyukin_sum'), ['genkin_kin', 'furikomi_kin', 'furikomi_tesuryo_kin', 'tegata_kin', 'sousai_kin', 'nebiki_kin', 'sonota_nyu_kin'])" value="{{ old('nebiki_kin', !empty($nyukin) ? numberFormat($nyukin->nebiki_kin) : '' ) }}" maxlength="10">
                      <div class="error_message">
                        <span class=" text-danger" id="error-nebiki_kin"></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <label class="col-12 col-md-1 col-form-label text-nowrap">その他</label>
                  <div class="col-12 col-md-2">
                    <div class="group-input">
                      <input type="text" class="form-control size-L text-right" name="sonota_nyu_kin" onblur="formatNumberOnBlur(this)" onfocus="removeFormatOnFocus(this)" onkeypress="return onlyNumber(event)" onkeyup="totalForInput($('#nyukin_sum'), ['genkin_kin', 'furikomi_kin', 'furikomi_tesuryo_kin', 'tegata_kin', 'sousai_kin', 'nebiki_kin', 'sonota_nyu_kin'])" value="{{ old('sonota_nyu_kin', !empty($nyukin) ? numberFormat($nyukin->sonota_nyu_kin) : '' ) }}" maxlength="10">
                      <div class="error_message">
                        <span class=" text-danger" id="error-sonota_nyu_kin"></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <label class="col-12 col-md-1 col-form-label text-nowrap">備考</label>
                  <div class="col-12 col-md-10">
                    <div class="group-input">
                      <input type="text" class="form-control size-3L" name="biko" value="{{ old('biko', !empty($nyukin) ? $nyukin->biko : '' ) }}">
                      <div class="error_message">
                        <span class=" text-danger" id="error-biko"></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mt-3">
                <table class="hansontable table table-bordered table-hover" style="max-width: 500px;">
                  <thead>
                    <tr>
                      <th>合計入金額</th>
                      <th>請求残高</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="text-right" id="nyukin_sum">
                        {{ !empty($nyukin)? numberFormat($nyukin->genkin_kin + $nyukin->furikomi_kin + $nyukin->furikomi_tesuryo_kin + $nyukin->tegata_kin + $nyukin->sousai_kin + $nyukin->sonota_nyu_kin + $nyukin->nebiki_kin)  : '' }}
                      </td>
                      <td class="text-right" id="nyukin_seikyu">@if( empty($seikyuSel) ||  $seikyuSel->isEmpty()) {{ 0 }} @else  {{ numberFormat($seikyuSel->sum('konkai_torihiki_kin'), -1) }} @endif </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              
            </div>
          
        </div>
        <div class="col-4">
          <div style="padding: 0 80px;">
            <div class="hansontable table table-bordered table-hover" >
              <table class="" id="seikyuSelTop" style="width: 100%;">
                <thead>
                  <th colspan="2">未回収請求書</th>
                </thead>
                <tbody>
                  @if(!empty($hikiSeikyu) && !$seikyuSel->isEmpty())
                    @foreach($seikyuSel as $sel)
                    <tr>
                       <td class="text-center" style="width: 50%">
                        {{ $sel->seikyu_sime_dt }}
                        <input type="hidden" name="seikyu_sime_dt_tmp" value="{{ $sel->seikyu_sime_dt }}">
                        <input type="hidden" name="seikyu_no_tmp" value="{{$sel->seikyu_no}}">
                        <button class="btn btn-primary" onclick="seikyuSelDown(this)" style="position: absolute; right: 105%; top: 0">選択</button>
                      </td>
                      <td class="text-right"> <input type="hidden" name="konkai_torihiki_kin_tmp" value="{{ $sel->konkai_torihiki_kin }}"> {{ numberFormat($sel->konkai_torihiki_kin, -1) }} </td>
                    </tr>
                    @endforeach
                  @else 
                    <tr>
                      <td colspan="2" class="text-center no-record">指定の条件に一致するデータが見つかりません</td>
                    </tr>
                  @endif
                </tbody>
              </table>
            </div>
          </div>
          <div style="padding: 0 80px; margin-top: 30px;">
            <div class="hansontable table table-bordered table-hover">
              <table class="" style="width: 100%;" id="seikyuSelBot">
                <thead>
                  <th colspan="2">引当請求書</th>
                </thead>
                <tbody>
                  @if(!empty($hikiSeikyu) && !$hikiSeikyu->isEmpty())
                    @foreach($hikiSeikyu as $sel)
                    <tr>
                      <td class="text-center" style="width: 50%">
                        {{ $sel->seikyu_sime_dt }}
                        <input type="hidden" name="seikyu_no_bot[]" value="{{ $sel->seikyu_no }}">
                        <input type="hidden" name="seikyu_sime_dt_bot[]" value="{{ $sel->seikyu_sime_dt }}">
                      </td>
                      <td class="text-right" style="width: 50%">
                        {{ numberFormat($sel->konkai_torihiki_kin, -1) }} 
                        <input type="hidden" name="konkai_torihiki_kin_bot[]" value="{{ $sel->konkai_torihiki_kin }}">
                        <button class="btn btn-warning" style="position: absolute; left: 105%; top: 0;" onclick="seikyuSelUp(this)">削除</button>
                      </td>
                    </tr>
                    @endforeach
                  @else
                    <tr>
                      <td colspan="2" class="text-center no-record">指定の条件に一致するデータが見つかりません</td>
                    </tr>
                  @endif
                </tbody>
              </table>
            </div>
          </div>
          <div style="padding: 0 80px; margin-top: 30px">
            <div class=" table table-bordered table-hover" >
              <table  style="width: 100%;">
                <tbody>
                  <tr>
                    <td class="text-center" style="width: 50%; background: #355687; color: #FFF; font-weight: 600; font-size: 0.875rem;">合計引当額</td>
                    <td class="text-right" style="background: #FFF"><span id="nyukin_seikyu_bot">@if( empty($hikiSeikyu) ||  $hikiSeikyu->isEmpty()) {{ 0 }} @else  {{ numberFormat($hikiSeikyu->sum('konkai_torihiki_kin'), -1) }} @endif</span></td>
                  </tr>
                  <tr>
                    <td class="text-center" style="width: 50%; background: #355687; color: #FFF; font-weight: 600; font-size: 0.875rem">未引当額</td>
                    <td class="text-right" style="background: #FFF">
                      <span id="mi_hikiate_kin">
                      {{ !empty($nyukin)? numberFormat( (empty($hikiSeikyu) ||  $hikiSeikyu->isEmpty() ? 0 : $hikiSeikyu->sum('konkai_torihiki_kin') ) - ($nyukin->genkin_kin + $nyukin->furikomi_kin + $nyukin->furikomi_tesuryo_kin + $nyukin->tegata_kin + $nyukin->sousai_kin + $nyukin->sonota_nyu_kin + $nyukin->nebiki_kin) ) : 0 }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form> 
  <div class="popup-confirm"></div>
  <div>
    <table id="table"></table>
  </div>
@endsection

@section('js')
<script>
  var columns = @json([]);
  $('#table').customTable({
    columns: columns,
    urlSearchSuggestion: '{{route('master-suggestion')}}',
  })
  @if(!empty($nyukin))
    function handleDelete() {
      $.ajax({
        url: '{{route('nyukin.destroy', ['nyukinNo' => $nyukin->nyukin_no])}}',
        method: 'DELETE',
        data: {},
        success: function(res) {
          if(res.status == 200) {
            $('#backButton').click();
          } else {
            Swal.fire({
              title: res.message,
              icon: "error"
            });
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.error('AJAX Error:', textStatus, errorThrown);
        },
        complete: function() {

        }
      })
    }
  @endif

  function totalForInput(elementTo, listFieldInputFrom) {
    let total = 0;
    listFieldInputFrom.forEach(fieldName => {
      const fieldValue = parseFloat(document.querySelector(`[name="${fieldName}"]`).value.replace(/,/g, '')) || 0;
      total += fieldValue;
    });
    elementTo.html(numberFormat(total, -1));
    calculatorMiHikiateKin();
  }

  function autoFillSeikyuSimeDt() {
    var ninusiCd = $('input[name="ninusi_cd"]').val();
    var nyukinDt = $('input[name="nyukin_dt"]').val();

    if(ninusiCd && nyukinDt) {
      $.ajax({
        url: '{{route('nyukin.auto_fill_seikyu_sime_dt')}}',
        method: 'POST',
        data: {
          ninusi_cd: ninusiCd,
          nyukin_dt: nyukinDt
        },
        success: function(res) {
          if(res.seikyu_sime_dt) {
            $('input[name="seikyu_sime_dt"]').val(res.seikyu_sime_dt);
          }
        }
      })
    }
  }

  function seikyuSelDown(e) {
    var seikyoNo = $(e).parents('tr').find('input[name="seikyu_no_tmp"]').val();
    var seikyuSimeDt = $(e).parents('tr').find('input[name="seikyu_sime_dt_tmp"]').val();
    var konkaiTorihikiKin = $(e).parents('tr').find('input[name="konkai_torihiki_kin_tmp"]').val();
    $(e).parents('tr').remove();
    if($('#seikyuSelTop').find('tbody tr').length == 0 ) {
      $('#seikyuSelTop tbody').html('<tr><td colspan="2" class="text-center no-record">指定の条件に一致するデータが見つかりません</td></tr>');
    };
    var htmlSeikyuSelBot = '<tr><td style="width: 50%" class="text-center">'+convertDateFormat(seikyuSimeDt)+'<input type="hidden" name="seikyu_sime_dt_bot[]" value="'+(seikyuSimeDt)+'"> <input type="hidden" name="seikyu_no_bot[]" value="'+seikyoNo+'" > </td><td class="text-right">'+numberFormat(konkaiTorihikiKin, -1)+'<input type="hidden" name="konkai_torihiki_kin_bot[]" value="'+konkaiTorihikiKin+'" ><button type="button" class="btn btn-warning" style="position: absolute; left: 105%; top: 0" onclick="seikyuSelUp(this)">削除</button></td></tr>';
    if($('#seikyuSelBot').find('.no-record').length == 1) {
      $('#seikyuSelBot tbody').html(htmlSeikyuSelBot);
    } else {
      $('#seikyuSelBot tbody').append(htmlSeikyuSelBot);
    }
    calculatorSeikyuSelTop();
    calculatorSeikyuSelBot();
  }

  function seikyuSelUp(e) {
    var seikyoNo = $(e).parents('tr').find('input[name="seikyu_no_bot[]"]').val();
    var seikyuSimeDt = $(e).parents('tr').find('input[name="seikyu_sime_dt_bot[]"]').val();
    var konkaiTorihikiKin = $(e).parents('tr').find('input[name="konkai_torihiki_kin_bot[]"]').val();
    $(e).parents('tr').remove();
    if($('#seikyuSelBot').find('tbody tr').length == 0 ) {
      $('#seikyuSelBot tbody').html('<tr><td colspan="2" class="text-center no-record">指定の条件に一致するデータが見つかりません</td></tr>');
    };

    var htmlSeikyuSelTop = '<tr>' +
      '<td class="text-center" style="width: 50%">' +
        convertDateFormat(seikyuSimeDt) +
        '<input type="hidden" name="seikyu_sime_dt_tmp" value="'+convertDateFormat(seikyuSimeDt)+'">' +
        '<input type="hidden" name="seikyu_no_tmp" value="'+seikyoNo+'">' +
        '<button class="btn btn-primary" onclick="seikyuSelDown(this)" style="position: absolute; right: 105%; top: 0">選択</button>'+
      '</td>' +
      '<td class="text-right"> <input type="hidden" name="konkai_torihiki_kin_tmp" value="'+konkaiTorihikiKin+'"> '+numberFormat(konkaiTorihikiKin, -1)+' </td>' +
    '</tr>';
    if($('#seikyuSelTop').find('.no-record').length == 1) {
      $('#seikyuSelTop tbody').html(htmlSeikyuSelTop);
    } else {
      $('#seikyuSelTop tbody').append(htmlSeikyuSelTop);
    }
    calculatorSeikyuSelTop();
    calculatorSeikyuSelBot();
  }

  function calculatorSeikyuSelTop() {
    var total = 0;
    $('#seikyuSelTop input[name="konkai_torihiki_kin_tmp"]').each(function() {
      var value = parseFloat($(this).val());
      total += value;
    });
    $('#nyukin_seikyu').html(numberFormat(total, -1));
    calculatorMiHikiateKin();
  }

  function calculatorSeikyuSelBot() {
    var total = 0;
    $('#seikyuSelBot input[name="konkai_torihiki_kin_bot[]"]').each(function() {
      var value = parseFloat($(this).val()) || 0;
      total += value;
    });
    $('#nyukin_seikyu_bot').html(numberFormat(total, -1));

    calculatorMiHikiateKin();
  }

  function calculatorMiHikiateKin() {
    var totalSelBot = 0; 
    $('#seikyuSelBot input[name="konkai_torihiki_kin_bot[]"]').each(function() {
      var value = parseFloat($(this).val()) || 0;
      totalSelBot += value;
    });
    var listInput = ['genkin_kin', 'furikomi_kin', 'furikomi_tesuryo_kin', 'tegata_kin', 'sousai_kin', 'nebiki_kin', 'sonota_nyu_kin'];
    var nyukinSum = 0;
    listInput.forEach(fieldName => {
      const fieldValue = parseFloat(document.querySelector(`[name="${fieldName}"]`).value.replace(/,/g, '')) || 0;
      nyukinSum += fieldValue;
    });
    $('#mi_hikiate_kin').html(numberFormat(totalSelBot - nyukinSum, -1));
  }

  function onchangeNinusiLoadSeikuSel() {
    $('#seikyuSelBot tbody').html('<tr><td colspan="2" class="text-center no-record">指定の条件に一致するデータが見つかりません</td></tr>');
    var ninusiCd = $('input[name="ninusi_cd"]').val();
    if(ninusiCd) {
      $.ajax({
        url: '{{ route('nyukin.get_list_nyukin_seikyu') }}',
        data: {
          ninusi_cd: ninusiCd
        },
        method: 'POST',
        success: function(res) {
          if(res.data) {
            var html = '';
            var totalNyukinSeikyu = 0;
            for(let i = 0; i < res.data.length; i++)
            {
              var seikyuSimeDt = res.data[i].seikyu_sime_dt;
              var seikyoNo = res.data[i].seikyu_no;
              var konkaiTorihikiKin = res.data[i].konkai_torihiki_kin;
              totalNyukinSeikyu += parseFloat(konkaiTorihikiKin);
              html += '<tr>' +
                '<td class="text-center" style="width: 50%">' +
                  convertDateFormat(seikyuSimeDt) +
                  '<input type="hidden" name="seikyu_sime_dt_tmp" value="'+convertDateFormat(seikyuSimeDt)+'">' +
                  '<input type="hidden" name="seikyu_no_tmp" value="'+seikyoNo+'">' +
                  '<button class="btn btn-primary" onclick="seikyuSelDown(this)" style="position: absolute; right: 105%;">選択</button>'+
                '</td>' +
                '<td class="text-right"> <input type="hidden" name="konkai_torihiki_kin_tmp" value="'+konkaiTorihikiKin+'"> '+numberFormat(konkaiTorihikiKin, -1)+' </td>' +
              '</tr>';
            }
            if(res.data.length > 0) {
              $('#seikyuSelTop tbody').html(html);
              $('#nyukin_seikyu').html(numberFormat(totalNyukinSeikyu, -1));
            } else {
              $('#seikyuSelTop tbody').html('<tr><td colspan="2" class="text-center no-record">指定の条件に一致するデータが見つかりません</td></tr>');
              $('#nyukin_seikyu').html(0);
            }
          }
        },
        error: function() {

        }
      })
    } else {
      $('#seikyuSelTop tbody').html('<tr><td colspan="2" class="text-center no-record">指定の条件に一致するデータが見つかりません</td></tr>');
    }
  }

</script>
@endsection
