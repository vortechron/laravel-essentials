@declareFalse('isRequired')

<div class="form-group">
  <label for="{{ $name }}">{{ $label }}</label>
  @foreach ($options as $key => $option)
  <div class="form-check">
    <label class="form-check-label">
      <input type="checkbox" @if($isRequired==1 ) required @endif class="form-check-input" name="{{ $name }}" value="{{ $key }}" {{ in_array($key, $value) ? 'checked' : '' }}>
      {{ $option }}
    </label>
  </div>
  @endforeach
</div>
