@foreach ($errors->all() as $message)
<b-alert show dismissible variant="danger">
    {{ $message }}
</b-alert>
@endforeach