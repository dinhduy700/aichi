@props(['inputAttrs' => [], 'text' => ''])
@php
  $input = new \Illuminate\View\ComponentAttributeBag($inputAttrs ?? []);
@endphp
<div class="input-group">
  <input type="text" class="form-control size-M" {{$input}}>
  <div class="suggestion"></div>
  <div class="input-group-append">
    <span class="input-group-text size-2L" id="{{ $input->get('id') . '_text' }}">{{ $text }}</span>
  </div>
</div>
