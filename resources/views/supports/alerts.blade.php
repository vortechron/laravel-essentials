@if ($flash = session('successFlash'))
<div class="bg-green-lightest border-t-4 border-green rounded-b text-green-darkest px-4 py-3 shadow-md" role="alert">
        <div>
            <p class="font-bold">{!! $flash['title'] !!}</p>
        </div>
</div>
@endif

@if ($flash = session('warningFlash'))
<div class="bg-orange-lightest border-t-4 border-orange rounded-b text-orange-darkest px-4 py-3 shadow-md" role="alert">
        <div>
            <p class="font-bold">{!! $flash['title'] !!}</p>
        </div>
</div>
@endif

@if ($flash = session('dangerFlash'))
<div class="bg-red-lightest border-t-4 border-red rounded-b text-red-darkest px-4 py-3 shadow-md" role="alert">
        <div>
            <p class="font-bold">{!! $flash['title'] !!}</p>
        </div>
</div>
@endif
