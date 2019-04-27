<div class="card">
    <div class="card-body">
        <h4 class="card-title text-sm font-semibold text-grey-darker">{{ $title }}</h4>
        <p class="card-text">
            {{ $address->address_1 }}, <br>

            @if ($address->address_2)
                {{ $address->address_2 }},
            @endif

            {{ $address->postal }}, {{ $address->state }}, <br>

            {{ $address->country }}. <br>

            {{ $address->phone }}
        </p>
    </div>
</div>