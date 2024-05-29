@extends('layouts.master')
@section('css')
  <style>
    .btn-edit {
		  width: 80px;
		  height: 35px;
		  font-size: 9pt;
		  background-color: #4B49AC;
		  color: white;

	  }

    .btn-edit:hover {
		  background-color: #4B49AC;
	  }

    .box {
      border: 1px solid #a9a1a1 !important;
      max-width: 100%;
      height: 50px;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: auto;
      border-radius: inherit !important;
    }

    .empty {
      background-color: #e1e1e1;
    }

    .border-empty {
      border: 1px solid #7e7b7b;
      border-radius: 100%;
      font-size: 10px;
      width: 21px;
      height: 21px;
      text-align: center;
      line-height: 21px;
    }

    .btn-lv1, .btn-lv2, .btn-lv3 {
      padding: 1rem 1.25rem !important;
    }

    .box-container {
      position: relative;
    }

    .btn-clear {
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      position: absolute;
      top: 0;
      right: 0;
      transform: translate(50%, -50%);
      background-color: #fff !important;
      border: 1px solid black !important;
      height: 20px;
      width: 20px;
      border-radius: 100% !important;
      cursor: pointer !important;
      padding: 0 !important;
      color: #0e1014;
    }
  
  </style>
@endsection
@section('page-content')
  @php
    $col = 2;
    $maxBox = 20;
    $colClass = ["col-md-" . (12 / $col)];
    $col2 = [];
    $col3 = [];
  @endphp
  <div class="row">
    <div class="col-md-12 text-right">
      @if(!empty($mode) && $mode == 'edit')
        <a class="btn btn-secondary btn-edit mr-3" type="button" onclick="saveMenus()">更新</a>
        <a class="btn btn-secondary btn-edit" type="button" onclick="deleteMenu()">全削除</a>
      @else
        <a class="btn btn-secondary btn-edit" type="button"
           href="{{ route('my_menu.index', ['mode' => 'edit']) }}">設定</a>
      @endif
    </div>
  </div>
  <div class="row p-3">
    <div class="{{!empty($mode) && $mode == 'edit' ? 'col-md-7' : 'col-md-12'}}">
      <div class="row">
        @if(!empty($listMenu))
          @php
            $keys = range(1, count($listMenu));
            $listMenuValues = array_combine($keys, array_values($listMenu));
          @endphp
          @for($i = 1; $i <= $col; $i++)
            <div @class($colClass)>
              @for($j = $i; $j <= count($listMenuValues); $j += $col)
                <div class="box-container">
                  @if(!empty($listMenuValues[$j]) && !empty($defaultMenus))
                    @php
                      $classByLvl = '';
                      switch (filterMenu($defaultMenus, $listMenuValues[$j])) {
                        case 1:
                            $classByLvl = 'btn-facebook';
                          break;
                        case 2:
                            $classByLvl = 'btn-primary';
                          break;
                        case 3:
                            $classByLvl = 'btn-google';
                          break;
                      }
                    @endphp
                    @if(!empty($mode) && $mode == 'edit')
                      <div class="box mt-3 {{$classByLvl}}" pgid="{{$j}}" parent_id="{{$listMenuValues[$j]}}">
                        {{filterMenu($defaultMenus, $listMenuValues[$j], 'label')}}
                        <button class="btn btn-clear" onclick="clearContent({{$j}})" title="Clear Content">
                          &times;
                        </button>
                      </div>
                    @else
                      <a class="box mt-3 btn {{$classByLvl}}" target="_blank" pgid="{{$j}}"
                         parent_id="{{$listMenuValues[$j]}}"
                         href="{{filterMenu($defaultMenus, $listMenuValues[$j], 'href')}}">
                        {{filterMenu($defaultMenus, $listMenuValues[$j], 'label')}}
                      </a>
                    @endif
                  @else
                    <div class="box mt-3 empty" pgid="{{$j}}" parent_id="" value="">
                      <div class="border-empty">{{$j}}</div>
                    </div>
                  @endif
                </div>
              @endfor
            </div>
          @endfor
        @else
          @for($i = 1; $i <= $col; $i++)
            <div @class($colClass)>
              @for($j = $i; $j <= $maxBox; $j += $col)
                <div class="box-container">
                  <div class="box mt-3 empty" pgid="{{$j}}" parent_id="" value="">
                    <div class="border-empty">{{$j}}</div>
                  </div>
                </div>
              @endfor
            </div>
          @endfor
        @endif
      </div>
    </div>
    @if(!empty($mode) && $mode == 'edit')
      <div class="col-md-5 mt-3">
        <div class="row">
          <div class="col-md-4 pr-0">
            <div lvl="1">
              @foreach($defaultMenus as $lv1 => $itemLv1)
                @if($lv1 === 'my-menu')
                  @continue
                @endif
                @php
                  $col2[$lv1] = [];
                  foreach ($itemLv1['sub'] ?? [] as $lv2 => $itemLv2) {
                      $col2[$lv1][$lv2] = $itemLv2;
                      $col3["{$lv1}-sub-{$lv2}"] = [];
                      foreach ($itemLv2['sub'] ?? [] as $lv3 => $itemLv3) {
                          $col3["{$lv1}-sub-{$lv2}"][$lv3] = $itemLv3;
                      }
                  }
                @endphp
                <x-menu-item :label="$itemLv1['label']"
                             :href="$itemLv1['href'] ?? null"
                             :sub="$itemLv1['sub'] ?? null"
                             :subId="$lv1"
                             :value="$lv1"
                             class="btn-facebook btn-lv1"
                />
              @endforeach
            </div>
          </div>
          <div class="col-md-4 pr-0">
            @foreach($col2 as $k => $items)
              <div id="{{ $k }}" lvl="2" style="display: none">
                @foreach($items ?? [] as $lv2 => $item)
                  <x-menu-item :label="@$item['label']"
                               :href="$item['href'] ?? null"
                               :sub="$item['sub'] ?? null"
                               :subId="$k.'-sub-'.$lv2"
                               :value="$lv2"
                               class="btn-primary btn-lv2 col-auto"
                  />
                @endforeach
              </div>
            @endforeach
          </div>
          <div class="col-md-4 pr-0">
            @foreach($col3 as $k => $items)
              <div id="{{ $k }}" lvl="3" style="display: none">
                @foreach($items ?? [] as $lv3 => $item)
                  <x-menu-item :label="@$item['label']"
                               :href="$item['href'] ?? null"
                               :sub="$item['sub'] ?? null"
                               :subId="$k.'-sub-'.$lv3"
                               :value="$lv3"
                               class="btn-google btn-lv3"
                  />
                @endforeach
              </div>
            @endforeach
          </div>
        </div>
      </div>
    @endif
  </div>
@endsection
@section('js')
  <script>
    @if(!empty($mode) && $mode == 'edit')
    $(document).ready(function () {
      $(".item-drag-menu").draggable({
        helper: 'clone',
        revert: "invalid",
      });
      $(".box").droppable({
        drop: function (event, ui) {
          const itemDrop = $(this);
          if ($(ui.draggable).hasClass('item-drag-menu')) {
            let allBoxesHaveHtml = true;
            const parentID = ui.draggable.attr('subid')?.replace(/-/g, '.');
            $(".box").not('.empty').each(function (index, item) {
              if ($(item).attr('parent_id') === parentID) {
                allBoxesHaveHtml = false;
                return false;
              }
            });
            if (allBoxesHaveHtml) {
              const pgid = itemDrop.attr('pgid');
              const value = ui.draggable.text().trim();
              const levelMenu = ui.draggable.parent()?.attr('lvl');
              switch (levelMenu) {
                case "1":
                  itemDrop.addClass('btn-facebook')
                  break;
                case "2":
                  itemDrop.addClass('btn-primary')
                  break;
                case "3":
                  itemDrop.addClass('btn-google')
                  break;
              }
              itemDrop.attr({
                'value': value,
                'parent_id': parentID
              });
              const htmlButtonClear = `${value} <button class="btn btn-clear" onclick="clearContent(${pgid})" title="Clear Content">&times;</button>`
              itemDrop.html(htmlButtonClear);
              itemDrop.removeClass('empty');
              ui.draggable.draggable('option', 'revert', false);
            } else {
              ui.draggable.draggable('option', 'revert', true);
            }
          }
        }
      })
      $('.toggleSub').click(function () {
        lvl = parseInt($('#' + $(this).attr('subId')).attr('lvl'));
        $('div[lvl=' + ((lvl || 0) + 1) + ']').hide();
        $('#' + $(this).attr('subId')).siblings().hide();
        $('#' + $(this).attr('subId')).toggle();
      });
    });

    function clearContent(pgid) {
      $('[pgid="' + pgid + '"]').html(`<div class="border-empty">${pgid}</div>`).removeClass().addClass('box mt-3 ui-droppable empty').attr({
        'value': '',
        'parent_id': ''
      });
    }

    function saveMenus() {
      let formData = {};
      $('.box').each(function (index, item) {
        const pgid = $(item).attr('pgid');
        const value = $(item).attr('parent_id')?.replace(/-/g, '.');
        formData['pgid' + pgid] = value;
      });
      $.ajax({
        url: '{{ route('my_menu.save') }}',
        method: 'POST',
        data: formData,
        success: function (res) {
          if (res.status == 200) {
            window.location.href = "{{ route('my_menu.index') }}";
          } else {
            Swal.fire({
              title: res.message,
              icon: "error"
            });
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.error('AJAX Error:', textStatus, errorThrown);
        },
        complete: function () {

        }
      })
    }

    function deleteMenu() {
      const confirmDelete = confirm(@JSON(__('messages.E0023')));
      if (confirmDelete) {
        $.ajax({
          url: '{{ route('my_menu.delete') }}',
          method: 'POST',
          data: [],
          success: function (res) {
            if (res.status == 200) {
              window.location.href = "{{ route('my_menu.index') }}";
            } else {
              Swal.fire({
                title: res.message,
                icon: "error"
              });
            }
          },
          error: function (jqXHR, textStatus, errorThrown) {
            console.error('AJAX Error:', textStatus, errorThrown);
          },
          complete: function () {

          }
        })
      }
    }
    @endif
  </script>
@endsection