<script>

    window._data = @json(isset($_data) ? $_data : 'null');
    window.countries = {!! Storage::get('countries') !!}

</script>

<link rel="stylesheet" href="https://rsms.me/inter/inter.css">
<link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Two+Tone" rel="stylesheet">
<script src="https://use.fontawesome.com/releases/v5.13.0/js/all.js" data-mutate-approach="sync"></script>