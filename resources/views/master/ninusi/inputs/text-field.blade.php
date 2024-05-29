<input type="text" class="form-control size-{{ $value['size'] }}"
       name="{{ $value['name'] }}" maxlength="{{ $value['maxlength'] ?? 255 }}"
       value="{{ old($value['name'], empty($ninusi) ? @$value['default'] : data_get($ninusi, $value['name'], '')) }}"
       @if (isset($value['key'])) required @if (!empty($ninusi)) readonly @endif
  @endif>
