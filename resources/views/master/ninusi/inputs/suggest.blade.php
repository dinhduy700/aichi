<div class="row p-15">
  <input type="text" class="form-control size-{{ data_get($value, "size.cd", 'M') }}" autocomplete="off"
         name="{{ $value['name'] }}"
         id="{{ $value['name'] }}" maxlength="255"
         value="{{ old($value['name'], data_get($ninusi, $value['name'], '')) }}"
         onkeyup="eSuggestionKeyup(this)">
  <input type="text" class='form-control size-{{ data_get($value, "size.nm", '2L') }}' readonly
         value="{{ data_get($ninusi, $value['suggest'], '') }}"
         id='{{ $value['suggest'] }}'></input>
  <ul class="suggestion modify-position-suggest"></ul>
</div>
