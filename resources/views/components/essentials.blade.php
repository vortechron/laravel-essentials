<script>

    window._data = @json(isset($_data) ? $_data : 'null');
    window.countries = {!! Storage::get('countries') !!}

</script>