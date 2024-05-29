@props(['inputCdAttrs' => [], 'inputNmAttrs' => [], 'prepend' => ''])
@php
  $inputCd = new \Illuminate\View\ComponentAttributeBag($inputCdAttrs ?? []);
  $inputNm = new \Illuminate\View\ComponentAttributeBag($inputNmAttrs ?? []);
@endphp

<div class="input-group flex-nowrap">
  {{ $prepend }}
  <input type="text" class="form-control size-M" {{$inputCd}} />
  <input type="text" class="form-control size-2L {{ @$inputNmAttrs['class'] }}" {{$inputNm}}
         style="border-top-right-radius: 4px; border-bottom-right-radius: 4px"/>
  <ul class=" suggestion"></ul>
</div>
