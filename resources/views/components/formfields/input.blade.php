
@declareWithDefault('type', 'text')
@declareNull('placeholder')
@declareTrue('isRequired')

<div class="form-group">
    <label for="{{ $name }}">{{ $label }}</label>
    <input id="{{ $name }}" @if($isRequired==1 ) required @endif type="{{ $type }}" class="form-control" @if (isset($name)) name="{{ $name }}" @endif placeholder="{{ $placeholder }}"
       @if (isset($value))
       value="{{ old($name, $value) }}"
       @endif {!! isset($extra) ? $extra : '' !!}>
</div>