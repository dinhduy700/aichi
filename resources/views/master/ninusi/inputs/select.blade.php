<select class="form-control size-{{ $value['size'] }}"
        name="{{ $value['name'] }}" >
  @if(data_get($value, 'prompt', true))
    <option value=""></option>
  @endif
  @foreach ($listOption[$value['name']] as $key => $name)
    <option value="{{ $key }}"
            @selected(data_get($ninusi, $value['name'], isset($value['default']) ? $value['default'] : '') == $key)
    >
    {{ $name }}
    </option>
  @endforeach
</select>
