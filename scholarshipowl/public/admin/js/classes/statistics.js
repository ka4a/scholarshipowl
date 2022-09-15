var StatisticsConversionGraph = Element.extend({
  _init: function($this) {
    var that = this;

    this._super($this);
    this.$startInput = $this.find('input[name=start]');
    this.$endInput = $this.find('input[name=end]');
    this.$button = $this.find('button.btn-send');
    this.$canvas = $this.find('canvas');

    this.$button.click(this.loadGraph.bind(this));
    this.loadGraph();
  },

  loadGraph: function() {
    var that = this;
    var data = {};

    if (this.$startInput.val()) data['start'] = this.$startInput.val();
    if (this.$endInput.val()) data['end'] = this.$endInput.val();

    $.ajax('/admin/statistics/api/conversion', {data: data})
      .done(function(response) {
        if (response.status === 200) {
          that.$startInput.val(response.meta.start);
          that.$endInput.val(response.meta.end);
          that.displayGraph(response.data);
        } else {
          alert('Failed load chart data');
        }
      });
  },

  displayGraph: function(data) {
    if (this.graph) {
      this.graph.destroy();
    }

    this.graph = new Chart(this.$canvas, {
      type: 'line',
      data: {
        labels: Object.keys(data),
        datasets: [{
          label: 'Only New',
          data: Object.keys(data).map(function(date) {
              return data[date]['conversion'];
          }),
          borderColor: 'rgb(191, 242, 162)',
          backgroundColor: 'rgb(131, 165, 111)',
          fill: false
        },{
          label: 'Transaction',
          data: Object.keys(data).map(function(date) {
            return data[date]['conversion_t'];
          }),
          borderColor: 'rgb(161, 186, 221)',
          backgroundColor: 'rgb(25, 104, 214)',
          fill: false
        },{
          label: 'Subscription',
          data: Object.keys(data).map(function(date) {
            return data[date]['conversion_s'];
          }),
          borderColor: 'rgb(255, 145, 145)',
          backgroundColor: 'rgb(244, 66, 66)',
          fill: false
        }]
      },
      options: {
        responsive: true,
        tooltips: {
          custom: function(tooltip) {
            if (!tooltip || !tooltip.opacity) {
              return;
            }

            if (tooltip.title && typeof data[tooltip.title] !== 'undefined') {
              tooltip.afterBody.push('Registrations: ' + data[tooltip.title]['registered']);
              tooltip.afterBody.push('Subscriptions: ' + data[tooltip.title]['subscription']);
              tooltip.afterBody.push('Transactions: ' + data[tooltip.title]['transaction']);
              tooltip.afterBody.push('Only new: ' + data[tooltip.title]['new_transactions']);
            }
          }
        },
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true
            }
          }]
        }
      }
    });

    return this;
  }
});
