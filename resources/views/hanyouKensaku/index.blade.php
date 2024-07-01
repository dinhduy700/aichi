@extends('layouts.master')
@section('css')
  <style>
    .row-condition + .row-condition
    {
      margin-top: 10px;
    }
  </style>
@endsection
@section('page-content')
  <div class="card">
    <div class="card-body">
      <form method="post" onsubmit="return false;" class="form-custom">
        <div class="row">
          <div class="col-4">
            <div class="row">
              <label class="col-4 align-self-center">機能</label>
              <div class="col-8">
                <select class="form-control" name="kinou" onchange="onchangeKinou(this)">
                  @foreach(configParam('HANYOU_KENSAKU.kinou') as $key => $value)
                  <option value="{{$key}}" data-option="{{ !empty($value['options']) ? json_encode($value['options']) : ''  }}"> {{ $value['title'] }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="col-8">
            <div style="display: flex; flex-wrap: wrap; grid-gap: 5px;">
              <button type="button" class="btn btn-primary"
                      data-extra="{{ json_encode([
                        'tyohyokbn' => \App\Http\Repositories\SagyoRepository::EXP_TYOHYO_KBN_YOUSYA_CD,
                        'inji_group' => \App\Http\Repositories\SagyoRepository::EXP_PRINT_INJI_HAITATU_DT
                        ]) }}"
                      onclick="submitToUrl(this, '{{route('sagyo.exp.filterForm')}}')">作業指示書（庸車）</button>
              <button type="button" class="btn btn-primary"
                      data-extra="{{ json_encode([
                        'bumon_cd_from' => configParam('BUMON_CD_HOKURIKU'),//21北陸一般
                        'bumon_cd_to' => configParam('BUMON_CD_HOKURIKU'),//21北陸一般
                        'tyohyokbn' => \App\Http\Repositories\SagyoRepository::EXP_TYOHYO_KBN_ALL,
                        'inji_group' => \App\Http\Repositories\SagyoRepository::EXP_PRINT_INJI_HAITATU_DT
                      ]) }}"
                      onclick="submitToUrl(this, '{{route('sagyo.exp.filterForm')}}')">作業指示書（北陸）</button>
              <button type="button" class="btn btn-primary"
                      data-extra="{{ json_encode([
                        'tyohyokbn' => \App\Http\Repositories\SagyoRepository::EXP_TYOHYO_KBN_JYOMUIN_CD,
                        'inji_group' => \App\Http\Repositories\SagyoRepository::EXP_PRINT_INJI_HAITATU_DT
                      ]) }}"
                      onclick="submitToUrl(this, '{{route('sagyo.exp.filterForm')}}')">作業指示書（自社）</button>
              <button type="button" class="btn btn-primary"
                      data-extra="{{ json_encode([
                        'bumon_cd_from' => configParam('BUMON_CD_KANTO'),//22北関東
                        'bumon_cd_to' => configParam('BUMON_CD_KANTO'),//22北関東
                        'tyohyokbn' => \App\Http\Repositories\SagyoRepository::EXP_TYOHYO_KBN_ALL,
                        'inji_group' => \App\Http\Repositories\SagyoRepository::EXP_PRINT_INJI_HAITATU_DT
                      ]) }}"
                      onclick="submitToUrl(this, '{{route('sagyo.exp.filterForm')}}')">作業指示書（関東）</button>
              <button type="button" class="btn btn-primary"
                      onclick="submitToUrl(this, '{{route('jyutyu.exp.filterForm')}}')">受注リスト</button>
              <button type="button" class="btn btn-primary"
                      data-extra="{{ json_encode([
                        'bumon_cd_from' => configParam('BUMON_CD_HONTEN'),//10遊技台
                        'bumon_cd_to' => configParam('BUMON_CD_HONTEN'),//10遊技台
                      ]) }}"
                      onclick="submitToUrl(this, '{{route('uriage.exp.filterForm')}}')">売上リスト（遊技台）</button>
              <button type="button" class="btn btn-primary"
                      data-extra="{{ json_encode([
                        'bumon_cd_from' => configParam('BUMON_CD_HOKURIKU_YUGI'),
                        'bumon_cd_to' => configParam('BUMON_CD_HOKURIKU_YUGI'),
                      ]) }}"
                      onclick="submitToUrl(this, '{{route('uriage.exp.filterForm')}}')">売上リスト</button>
              <button type="button" class="btn btn-primary"
                      onclick="submitToUrl(this, '{{route('uriage.exp.filterForm')}}')">売上リスト（ALL）</button>
              <button type="button" class="btn btn-primary"
                      data-extra="{{ json_encode([
                        'bumon_cd_from' => configParam('BUMON_CD_HOKURIKU'),
                        'bumon_cd_to' => configParam('BUMON_CD_HOKURIKU'),
                      ]) }}"
                      onclick="submitToUrl(this, '{{route('uriage.nouhinsyo.filterForm')}}')">納品書（北陸）</button>
              <button type="button" class="btn btn-primary"
                      data-extra="{{ json_encode([
                        'bumon_cd_from' => configParam('BUMON_CD_HONTEN'),
                        'bumon_cd_to' => configParam('BUMON_CD_HONTEN'),
                      ]) }}"
                      onclick="submitToUrl(this, '{{route('uriage.nouhinsyo.filterForm')}}')">納品書（本社）</button>
              <button type="button" class="btn btn-primary"
                      data-extra="{{ json_encode([
                        'bumon_cd_from' => configParam('BUMON_CD_KANTO'),
                        'bumon_cd_to' => configParam('BUMON_CD_KANTO'),
                      ]) }}"
                      onclick="submitToUrl(this, '{{route('uriage.nouhinsyo.filterForm')}}')">納品書（関東）</button>
            </div>
          </div>
        </div>

        <div class="row mt-2">
          <div class="col-8">
            <div class="row">
              <div class="col-10" id="contentCondition">
                <div class="row mb-2" id="firstCondition">
                  <label class="col-2">検索条件1</label>
                  <div class=" col-10" >
                    <div class="row row-condition" >
                      <div class="col-2">
                        <select class="form-control" name="logical_operator[0]" data-name="logical_operator" style="display: none;">
                          @foreach(configParam('HANYOU_KENSAKU.logical_operator') as $key => $value)
                          <option value="{{$key}}" > {{ $value }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-4">
                        <select class="form-control" name="field[0]" data-name="field" onchange="onchangeField(this)">
                          @foreach(configParam('HANYOU_KENSAKU.field') as $key => $value)
                          <option value="{{$key}}" > {{ $value }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-2">
                        <select class="form-control" name="operator[0]" data-name="operator">
                          @foreach(configParam('HANYOU_KENSAKU.operator') as $key => $value)
                          <option value="{{$key}}" > {{ $value }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-4">
                        <input type="text" name="value[0]" data-name="value" class="form-control">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-2">
                <button type="button" id="btnAddCondition" class="btn btn-primary" onclick="addCondition(this)">条件追加</button>
              </div>
            </div>
          </div>
          <div class="col-4 text-right">
            <button class="btn btn-clear min-wid-110" type="button" onclick="clearForm()">条件クリア</button>
            <button class="btn btn-search min-wid-110" onclick="searchList(this)">検索</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="card mt-2">
    <div class="card-body">
      <div class="form-custom ">
        <div class="table-pagi-top">
          <table id="table" class="hansontable" data-sticky-columns="['id']">
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('js')

<script>
  var nowServer = '{{date('Y/m/d')}}';
  var urlCsv = '';
  $(document).ready(function() {
    $('select[name="kinou"]').trigger('change');
  })
  function onchangeKinou(e) {
    $('#contentCondition > div:nth-child(n+2)').remove();
    $('[name="value[0]"]').val('');
    var html = '<option></option>';
    var value = $(e).val();
      if(value) {
      var dataOptions = $(e).find('option[value="'+value+'"]').attr('data-option');
      if(dataOptions) {
        dataOptions = JSON.parse(dataOptions);

        Object.keys(dataOptions).forEach(function(key) {
          html += '<option value="'+key+'">'+dataOptions[key]+'</option>';
        });
      }
    }
    $('select[data-name="field"]').html(html);
    switch (value) {
      case 'suitocho_nyuryoku_list':
        $('#btnAddCondition').click();
        $('select[name="field[0]"]').val('kaisyu_dt').trigger('change');
        $('select[name="operator[0]"]').val('>=');
        $('select[name="field[1]"]').val('kaisyu_dt').trigger('change');
        $('select[name="operator[1]"]').val('<=');
        $('input[name="value[0]"]').val(nowServer);
        $('input[name="value[1]"]').val(nowServer);
        break;
      case 'nichibetsu_uriage_kingaku':
        $('#btnAddCondition').click();
        $('select[name="field[0]"]').val('haitatu_dt').trigger('change');
        $('select[name="operator[0]"]').val('>=');
        $('select[name="field[1]"]').val('haitatu_dt').trigger('change');
        $('select[name="operator[1]"]').val('<=');
        $('input[name="value[0]"]').val(nowServer);
        $('input[name="value[1]"]').val(nowServer);
        break;
      case 'mikakutei_unchin_list':
        $('select[name="field[1]"]').val('seikyu_sime_dt').trigger('change');
        $('select[name="operator[1]"]').val('=');
        $('input[name="value[1]"]').val('{!! \Illuminate\Support\Carbon::now()->lastOfMonth()->format(\App\Helpers\Formatter::DF_DATE) !!}');
        break;
      case 'ryoshusho_honten': selRyoshusho('honten'); break;
      case 'ryoshusho_hokuriku': selRyoshusho('hokuriku'); break;
      case 'ryoshusho_kanto': selRyoshusho('kanto'); break;
    }
  }

  function selRyoshusho(mode) {
    var bumonCd = '';
    if (mode=='honten') bumonCd = '{!! configParam('BUMON_CD_HONTEN') !!}';
    else if (mode=='hokuriku') bumonCd = '{!! configParam('BUMON_CD_HOKURIKU') !!}';
    else if (mode=='kanto') bumonCd = '{!! configParam('BUMON_CD_KANTO') !!}';
    $('#btnAddCondition').click();
    $('#btnAddCondition').click();
    $('#btnAddCondition').click();
    $('select[name="field[0]"]').val('haitatu_dt').trigger('change');
    $('select[name="operator[0]"]').val('>=');
    $('input[name="value[0]"]').val(nowServer);
    $('select[name="field[1]"]').val('haitatu_dt').trigger('change');
    $('select[name="operator[1]"]').val('<=');
    $('input[name="value[1]"]').val(nowServer);
    $('select[name="field[2]"]').val('bumon_cd');
    $('select[name="operator[2]"]').val('>=');
    $('input[name="value[2]"]').val(bumonCd);
    $('select[name="field[3]"]').val('bumon_cd');
    $('select[name="operator[3]"]').val('<=');
    $('input[name="value[3]"]').val(bumonCd);
  }

  function onchangeField(e) {
    var value = $(e).val();
    if(value == 'konkai_torihiki_kin_flg') {
      $(e).parents('.row-condition').find('select[data-name="operator"]').html('<option value="="> ＝</option>');
    } else {
      $(e).parents('.row-condition').find('select[data-name="operator"]').html(`<option value="="> ＝</option>
        <option value=">="> ＞＝</option>
        <option value="<="> ＜＝</option>
        <option value=">"> ＞</option>
        <option value="<"> ＜</option>
        <option value="<>"> ＜＞</option>`);
    }
    if(value.endsWith('_dt')) {
      $(e).parents('.row-condition').find('input[data-name="value"]').attr('onchange', 'autoFillDate(this)');
    } else {
      $(e).parents('.row-condition').find('input[data-name="value"]').removeAttr('onchange');

    }
  }

  function submitToUrl(e, url) {
    var form = $('<form>', {
      'action': url,
      'method': 'POST',
      'target' : '_blank'
    });
    var $formSearch = $('#formSearch');
    if ($formSearch) {
      $formSearch.find('input, select').each(function () {
        form.append($('<input>', {
          'type': 'hidden',
          'name': $(this).attr('name'),
          'value': $(this).val()
        }));
      });
    }

    if ($(e).data('extra')) {
      $.each( $(e).data('extra'), function( key, value ) {
        form.append($('<input>', {
          'type': 'hidden',
          'name': key,
          'value': value
        }));
      });

    }

    form.append($('<input>', {
      'type': 'hidden',
      'name': '_token',
      'value': $('meta[name="csrf-token"]').attr('content')
    }));
    form.appendTo('body').submit().remove();
  }

  function clearForm() {
    $('.row-condition:nth-child(n+2)').remove();
    $('#btnAddCondition').css('display', 'unset');
  }

  function addCondition(e) {
    var html = $('#firstCondition').clone();
    $('#contentCondition').append(html);
    $('[id="firstCondition"]:last-of-type input[data-name="value"]').val('');
    var now = 0;
    $('#contentCondition .row-condition').each(function() {

      $(this).find('select, input').each(function() {
        $(this).attr('name', $(this).attr('data-name')+'['+now+']');
      });
      if(now == 0) {
        $('[name="logical_operator[0]"]').prop('disabled', true).css('display', 'none');
      } else {
        $('[name="logical_operator['+now+']"]').prop('disabled', false).css('display', 'block');
      }
      now +=1;
      $(this).parents('#firstCondition').find('label').html('検索条件' + now);
    });
    var keyLogical = 0;
    $('#contentCondition [data-name="logical_operator"]').each(function() {
      $(this).attr('name', 'logical_operator['+(keyLogical - 1) +']');
      keyLogical += 1;
    });
    
    if($('.row-condition').length >= 13) {
      $(e).css('display', 'none');
    }
  }

  function searchList(e) {
    var kinou = $('[name="kinou"]').val();
    $.ajax({
      url: '{{route('hanyou_kensaku.get_setting_mode')}}',
      data: {
        mode: kinou
      },
      method: 'POST',
      success: function(res) {
        if(res.status == 200) {
          if(res.data.setting) {
            createGrid(res.data.setting, res.data.url_excel, res.data.url_data);
            if(res.data.url_csv) {
              urlCsv = res.data.url_csv;
            } else {
              urlCsv = '';
            }
          }
        }
      }
    });
  }
  var listButtonToolBar = '';
  // var useAddFormFooter = true;
  // var useCopyButton = false;
  var urlUpdateDataRecord = false;
  var urlSearchSuggestion = false;
  var pageNumber = {{ request() -> get('page') ?? 1 }};
  var searchDatas = @json(request() -> query());

  var dataSuggestion = {};
  function createGrid(setting, urlExcel, urlData) {
    if($('.bootstrap-table').length > 0) {
      $.fn.customTable.destroy();
    }
    if($('select[name="kinou"]').val() == 'keiri_soft_renkei') {
      listButtonToolBar = '<div class="columns columns-right btn-group float-right"><button type="button" onclick="exportCsvKeiriSoftRenkei(this)" class="btn btn-success">CSV出力</button></div>';
    } else {
      listButtonToolBar = '';
    }
    $('#table').customTable({
        urlData: urlData,
        showColumns: false,
        columns: setting,
        listButtonToolBar: listButtonToolBar,
        pageNumber: pageNumber,
        urlInsertDataRecord: '',
        urlUpdateDataRecord: '',
        formSearch: $('#contentCondition'),
        pageSize: {{ config()->get('params.PAGE_SIZE') }},
        textButtonExportExcel: '{{ trans('app.labels.btn-xls-export') }}',
        urlExportExcelDataTable: urlExcel,
        isShow: true,
        isShowBtnExcel: true,
        sortName:''
    });
  }

  function formatSu(value, row, index, field) {
    return numberFormat(value || '', 3);
  }

  function exportCsvKeiriSoftRenkei(e) {
    $table = $('#table');
    var form = $('<form>', {
      'action': urlCsv,
      'method': 'POST',
      'target': '_blank'
    });
    if ($table) {
      var paramsTable = $('#table').customTable.getQueryParams();
      $.each(paramsTable, function (key, value) {
        form.append($('<input>', {
          'type': 'hidden',
          'name': key,
          'value': value
        }));
      });
    }

    form.append($('<input>', {
      'type': 'hidden',
      'name': '_token',
      'value': $('meta[name="csrf-token"]').attr('content')
    }));
    form.appendTo('body').submit().remove();
  }
</script>
@endsection

