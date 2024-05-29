@props(['label', 'nameInput', 'readOnly'])
<div class="row">
  <label class="col-12 col-md-2 col-form-label text-nowrap ">{{ @$label }}</label>
  <div class="col-12 col-md-10 group-input">
    <input type="text" name="{{ @$nameInput }}" {{ $attributes->merge(['class' => 'form-control']) }} {{ @$readOnly }}>
    <div class="error_message">
      <span class=" text-danger" id="error-{{ @$nameInput }}"></span>
    </div>
  </div>
</div>