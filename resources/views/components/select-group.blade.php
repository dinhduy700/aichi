@props(['list' => [], 'data' => '', 'label' => '', 'nameInput' => '', 'prompt' => true])
<div class="row">
    <label class="col-12 col-md-2 col-form-label text-nowrap ">{{ @$label }}</label>
    <div class="col-12 col-md-6 group-input">
        <select name="{{ @$nameInput }}" {{ $attributes->merge(['class' => 'form-control']) }}>
            @if($prompt) <option value=""></option> @endif

            @foreach($list as $k => $v)
                <option value="{{ $k }}"
                    {!! strval($data) === strval($k) ? 'selected=selected' : '' !!}>{!! $v !!}</option>
            @endforeach
        </select>
        <div class="error_message">
            <span class=" text-danger" id="error-{{ @$nameInput }}"></span>
        </div>
    </div>
</div>
