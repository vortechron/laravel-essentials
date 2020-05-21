@if ($flash = session('successFlash'))
<div class="bg-green-100 border-green-600 border-t-4 mb-4 px-4 py-3 rounded-b shadow text-green-700" role="alert">
        <div>
            <p class="font-bold">{!! $flash['title'] !!}</p>
        </div>
</div>
@endif

@if ($flash = session('warningFlash'))
<div class="mb-4 bg-orange-100 border-t-4 border-orange-600 rounded-b text-orange-800 px-4 py-3 shadow" role="alert">
        <div>
            <p class="font-bold">{!! $flash['title'] !!}</p>
        </div>
</div>
@endif

@if ($flash = session('dangerFlash'))
<div class="mb-4 bg-red-100 border-t-4 border-red-600 rounded-b text-red-800 px-4 py-3 shadow" role="alert">
        <div>
            <p class="font-bold">{!! $flash['title'] !!}</p>
        </div>
</div>
@endif
