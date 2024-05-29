@extends('layouts.master')
@section('css')
  <style>
    .btn.btn-lg {
      padding: 1.375rem 2rem;
      text-align: left;
    }
    .btn-lv2 {
      background-color: #4747A1;
    }
    .btn-lv2:hover {
      background-color: #5050b2;
    }
    .btn-lv3 {
      background-color: #F3797E;
    }
    .btn-lv3:hover {
      background-color: #f59095;
    }
  </style>
@endsection
@section('page-content')
  @php
  $col2 = [];
  $col3 = [];
  @endphp
  <div class="card menu">
    <div class="card-body">
      <div class="row mt-2">
        <div class="col-lg-4 col-xl-3">
          <div class="col-12">
            @foreach($menus as $lv1 => $itemLv1)
              @php
                $col2[$lv1] = [];
                foreach ($itemLv1['sub'] ?? [] as $lv2 => $itemLv2) {
                    $col2[$lv1][$lv2] = $itemLv2;
                    $col3["{$lv1}-{$lv2}"] = [];
                    foreach ($itemLv2['sub'] ?? [] as $lv3 => $itemLv3) {
                        $col3["{$lv1}-{$lv2}"][$lv3] = $itemLv3;
                    }
                }
              @endphp
              <x-menu-item :label="$itemLv1['label']"
                           :href="$itemLv1['href'] ?? null"
                           :sub="$itemLv1['sub'] ?? null"
                           :subId="$lv1"
                           class="btn-facebook"
              />
            @endforeach
          </div>
        </div>
        <div class="col-lg-4 col-xl-3 row">
          @php
            @endphp
          @foreach($col2 as $k => $items)
            <div class="col-12 lv2" id="{{ $k }}" lvl="2" style="display: none">
              @foreach($items ?? [] as $subId => $item)
                <x-menu-item :label="@$item['label']"
                             :href="$item['href'] ?? null"
                             :sub="$item['sub'] ?? null"
                             :subId="$k.'-'.$subId"
                             class="btn-primary btn-lv2 col-auto"
                />
              @endforeach
            </div>
          @endforeach
        </div>

        <div class="col-lg-4 col-xl-3">
          @foreach($col3 as $k => $items)
            <div class="col-12" id="{{ $k }}" lvl="3" style="display: none">
              @foreach($items ?? [] as $item)
                <x-menu-item :label="@$item['label']"
                             :href="$item['href'] ?? null"
                             :sub="$item['sub'] ?? null"
                             class="btn-google btn-lv3"
                />
              @endforeach
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
@endsection
@section('js')
  <script>
    $(function() {
      $('.toggleSub').click(function() {
        lvl = parseInt($('#' + $(this).attr('subId')).attr('lvl'));
        $('div[lvl=' + ((lvl || 0) + 1) + ']').hide();
        $('#' + $(this).attr('subId')).siblings().hide();
        $('#' + $(this).attr('subId')).toggle();
      });
    });
  </script>
@endsection
