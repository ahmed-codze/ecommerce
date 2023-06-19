$(document).ready(function() {
    $('product-list').DataTable();
    // Basic table example
    $('#basic-1').DataTable({
        order: [[3, 'desc']],
    })
    $('#basic-2').DataTable({
        order: [[3, 'desc']],
    })
    
});
