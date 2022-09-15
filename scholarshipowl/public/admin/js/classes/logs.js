var AdminActivityLogDataTable = Element.extend({
  _init: function($this) {
    this._super($this);

    var table = $this.DataTable({
      sDom: 'lrtip',
      processing: true,
      serverSide: true,
      ajax: {
        url: $this.attr('data-url'),
        data: function (data) {
          var request = {
            start: data.start || 0,
            limit: data.length || 1000,
            sort: data.order.map(function(order) {
              return { property: data.columns[order.column].name, direction: order.dir };
            })
          };

          console.log('[REQUEST DATA]', data);
          return request;
        },
        dataFilter: function (json) {
          var data = $.parseJSON(json);

          if (data.status === 200) {
            data.recordsTotal = data.meta.count;
            data.recordsFiltered = data.meta.count;
          }

          return JSON.stringify(data); // return JSON string
        },
      },
      rowId: 'id',
      pageLength: 50,
      order: [[0, 'desc']],
      columns: [
        {
          data: "id",
          name: "id"
        },
        {
          data: "createdAt",
          name: "createdAt",
          render: function(obj) {
            return moment(obj.date).format('YYYY-MM-DD HH:mm:ss');
          }
        },
        {
          data: "adminId",
          name: "adminId"
        },
        {
          data: "adminName",
          name: "adminName"
        },
        {
          data: "route",
          name: "route"
        },
        {
          data: "data",
          name: "data",
          render: function(data) {
            return '<div class="text-ellipsis" style="width: 300px;">' +
                data +
            '</div>';
          }
        }
      ]
    });

    $this.on('click', 'tbody tr', function(event) {
      var row = table.row($(this));

      var formatData = function(rowData) {
        return '<pre>' +
          '<code>' +
          JSON.stringify(JSON.parse(rowData.data), null, 4) +
          '</code>' +
        '</pre>';
      };

      if (!row.child.isShown()) {
        row.child(formatData(row.data())).show();
      } else {
        row.child.hide();
      }
    });
  }
});
