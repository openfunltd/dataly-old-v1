// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTable').DataTable({
    ordering: false,
    scrollX: true,
    keys: true,
    columnDefs: [{ width: '3%', targets: 0 }]
  });
});
