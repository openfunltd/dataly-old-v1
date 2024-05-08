// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTable').DataTable({
    initComplete: function () {
      this.api()
        .columns()
        .every(function () {
          let column = this;
          if (this.index() === 0) {
            return;
          }
          let title = this.footer().textContent;
          let input = document.createElement('input');
          input.placeholder = title;
          column.footer().replaceChildren(input);
          input.addEventListener('keyup', () => {
            if (column.search() !== this.value) {
              column.search(input.value).draw();
            }
          });
        });
    },
    keys: true,
    columnDefs: [
        { orderable: false, targets: 'nosort' },
        { className: 'dt-body-center', targets: [1, 2, 3, 4]},
    ],
    order: [2, 'desc'],
  });
});
