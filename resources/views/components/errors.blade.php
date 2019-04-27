@foreach ($errors->all() as $message)
<div class="mb-2 bg-red-lightest border border-red-light text-red-dark px-4 py-3 rounded relative" role="alert">
    <span class="block sm:inline">{{ $message }}</span>
</div>
@endforeach