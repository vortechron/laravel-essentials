@php $model = is_object($model) ? $model : new $model; 
@endphp
<style>
/* dataTables CSS modification & positioning */
table.dataTable thead .sorting:before,
table.dataTable thead .sorting_asc:before,
table.dataTable thead .sorting_desc:before,
table.dataTable thead .sorting_asc_disabled:before,
table.dataTable thead .sorting_desc_disabled:before {
  right: 0 !important;
  content: "" !important;
}
table.dataTable thead .sorting:after,
table.dataTable thead .sorting_asc:after,
table.dataTable thead .sorting_desc:after,
table.dataTable thead .sorting_asc_disabled:after,
table.dataTable thead .sorting_desc_disabled:after {
  right: 0 !important;
  content: "" !important;
}
</style>
<div class="table-responsive">
    <table id="dataTable" class="table table-hover">
        <thead>
            <tr>
                @foreach ($model->indexerColumns as $column)
                <th class="capitalize">{{ str_replace('_', ' ', $column) }}</th>
                @endforeach
                <th class="capitalize"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($model->indexer() as $item)
            <tr>
                @foreach ($model->indexerColumns as $column)
                <td>{{ $item->getIndexerValue($column) }}</td>
                @endforeach
                <td>
                    <a name="" id="" href="{{ $item->indexerViewRoute() }}" role="button">
                        <i class="fa fa-eye"></i>
                    </a>
                </td>
            </tr>
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                @foreach ($model->indexerColumns as $column)
                <th class="capitalize">{{ str_replace('_', ' ', $column) }}</th>
                @endforeach
                <th class="capitalize"></th>
            </tr>
        </tfoot>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function(){ 
    // your code goes here
    $('#dataTable').DataTable();
}, false);

</script>
