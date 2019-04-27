@foreach ($images as $image)
    <img src="{{ $image->getFullUrl() }}" alt="" srcset="" class="img-thumbnail">
@endforeach