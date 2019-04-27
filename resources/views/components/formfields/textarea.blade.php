<textarea @if($isRequired == 1) required @endif class="form-control" name="{{ $name }}" rows="{{ $rows ?? 5 }}">{{ old($name, $value) }}</textarea>
