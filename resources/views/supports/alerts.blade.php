@if ($flash = session('successFlash'))
<b-alert show dismissible variant="success">
    {!! $flash['title'] !!}
</b-alert>
@endif

@if ($flash = session('warningFlash'))
<b-alert show dismissible variant="warning">
    {!! $flash['title'] !!}
</b-alert>
@endif

@if ($flash = session('dangerFlash'))
<b-alert show dismissible variant="danger">
    {!! $flash['title'] !!}
</b-alert>
@endif