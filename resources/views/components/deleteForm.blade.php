<form ref="deleteForm" action="{{ $_deleteAction }}" method="POST">
    @csrf
    @method('delete')
</form>