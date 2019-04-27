@declareNull('placeholder')
@declareNull('value')
@declareTrue('isRequired')
@php
$value = $value ?? key($options);    
@endphp

<div class="form-group">
  <label for="{{ $name }}">{{ $label }}</label>
  <select class="form-control" @if (isset($name)) name="{{ $name }}" @endif id="{{ $name }}" @if($isRequired==1 ) required @endif {!! isset($extra) ? $extra : '' !!}>
    @foreach ($options as $key => $option)
    <option value="{{ $key }}" @if (isset($value)){{ $value == $key ? 'selected' : '' }}@endif>{{ $option }}</option>
    @endforeach
  </select>
</div>