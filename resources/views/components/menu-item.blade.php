@props(['label' => '', 'href', 'sub', 'subId' => null, 'value' => null])

  @if(empty($sub))
    <a type="button" @class(array_merge(['btn', 'btn-block', 'btn-lg', 'item-drag-menu'], [$attributes['class']]))
       href="{{ $href ?? '#' }}" target="_blank" subId="{{ $subId }}" @if(!empty($value)) value="{{$value}}" @endif">
      {{ $label }}
    </a>
  @else
    <button type="button"
            @class(array_merge(['btn', 'btn-block', 'btn-lg', 'toggleSub'], [$attributes['class']]))
            subId="{{ $subId }}">
      {{ $label }}
      <i class="ti-angle-double-right float-right"></i>
    </button>
  @endif

