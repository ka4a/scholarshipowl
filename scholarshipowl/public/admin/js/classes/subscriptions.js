var SubscriptionsIndexPage = Element.extend({
  _init: function($element) {
    this._super($element);

    var that = this,
      filter = [],
      $grid = $element.find('#subscription-grid'),
      $search = $element.find('#subscription-grid-search');

    var getFilters = function(data) {
      var filters = [], filtersMarketing = [],
        subscriptionStatus = $element.find('#search-subscription-status').val(),
        remoteStatus = $element.find('#search-remote-status').val(),
        freeTrial = parseInt($element.find('#search-free-trial').val()),
        isFreemium = parseInt($element.find('#search-is-freemium').val()),
        paidSubscription = parseInt($element.find('#search-paid').val()),
        accountFirstName = $element.find('#search-account-first-name').val(),
        accountLastName = $element.find('#search-account-last-name').val(),
        accountId = parseInt($element.find('#search-account-id').val()),
        accountEmail = $element.find('#search-account-email').val(),
        startDateFrom = $element.find('#search-start-date-from').val(),
        startDateUntil = $element.find('#search-start-date-until').val(),
        endDateFrom = $element.find('#search-end-date-from').val(),
        endDateUntil = $element.find('#search-end-date-until').val(),
        terminatedAtFrom = $element.find('#search-terminated-at-from').val(),
        terminatedAtUntil = $element.find('#search-terminated-at-until').val(),
        renewalDateFrom = $element.find('#search-renewal-date-from').val(),
        renewalDateUntil = $element.find('#search-renewal-date-until').val(),
        packageId = $element.find('#search-package').val(),
        affiliateId = $element.find('#search-affiliate-id').val(),
        packageFreeTrial = parseInt($element.find('#search-package-free-trial').val());

      if (subscriptionStatus) {
        filters.push({property: 'subscriptionStatus', operator: 'in', value: subscriptionStatus});
      }

      if (remoteStatus) {
        filters.push({property: 'remoteStatus', operator: 'in', value: remoteStatus});
      }

      if (paidSubscription) {
        if (paidSubscription === 1) {
          filters.push({property: 'transaction.transactionId', operator: 'countGt', value: 0});
        } else {
          filters.push({property: 'transaction.transactionId', operator: 'countEq', value: 0});
        }
      }

      if (accountFirstName) {
        filters.push({property: 'profile.firstName', operator: 'like', value: accountFirstName})
      }

      if (accountLastName) {
        filters.push({property: 'profile.lastName', operator: 'like', value: accountLastName})
      }

      if (accountId) {
        filters.push({property: 'account.accountId', operator: 'eq', value: accountId});
      }

      if (accountEmail) {
        filters.push({property: 'account.email', operator: 'like', value: accountEmail});
      }

      if (startDateFrom) {
        filters.push({property: 'startDate', operator: 'gt', value: startDateFrom});
      }

      if (startDateUntil) {
        filters.push({property: 'startDate', operator: 'lt', value: startDateUntil});
      }

      if (endDateFrom) {
        filters.push({property: 'endDate', operator: 'gt', value: endDateFrom});
      }

      if (endDateUntil) {
        filters.push({property: 'endDate', operator: 'lt', value: endDateUntil});
      }

      if (terminatedAtFrom) {
        filters.push({property: 'terminatedAt', operator: 'gt', value: terminatedAtFrom});
      }

      if (terminatedAtUntil) {
        filters.push({property: 'terminatedAt', operator: 'lt', value: terminatedAtUntil});
      }

      if (renewalDateFrom) {
        filters.push({property: 'renewalDate', operator: 'gt', value: renewalDateFrom});
      }

      if (renewalDateUntil) {
        filters.push({property: 'renewalDate', operator: 'lt', value: renewalDateUntil});
      }

      if (packageId) {
        filters.push({property: 'package', operator: 'in', value: packageId});
      }

      if (freeTrial) {
        filters.push({property: 'freeTrial', operator: 'eq', value: freeTrial === 1 ? 1 : 0});
      }
      if (isFreemium) {
        filters.push({property: 'isFreemium', operator: 'eq', value: isFreemium === 1 ? 1 : 0});
      }

      if (packageFreeTrial) {
        filters.push({property: 'package.freeTrial', operator: 'eq', value: packageFreeTrial === 1 ? 1 : 0});
      }

      if (affiliateId) {
        filtersMarketing.push({ name: 'affiliate_id', operator: 'eq', value: affiliateId })
      }

      return {
        filter: filters,
        filter_marketing: filtersMarketing
      };
    };

    $element.find('#search-form')
      .submit(function(event) {
        that.table.draw();
        $search.find('.box-content').collapse('hide');
        event.preventDefault();
        return false;
      });

    this.table = $grid.DataTable({
      sDom: 'Brtlip',
      processing: true,
      responsive: true,
      serverSide: true,
      buttons: [
        {
          text: 'Refresh',
          action: function(e, dt, node, config) {
            dt.ajax.reload();
          }
        },
        {
          text: 'Export',
          action: function(e, table, node, config) {
            window.location = $grid.attr('data-url-export') +'?'+ $.param(table.ajax.params());
          }
        }
      ],
      ajax: {
        url: $grid.attr('data-url'),
        data: function (data) {
          return Object.assign({
            start: data.start || 0,
            limit: data.length || 1000,
            sort: data.order.map(function(order) {
              return { property: data.columns[order.column].name, direction: order.dir };
            })
          }, getFilters(data));
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
      rowId: 'subscriptionId',
      pageLength: 10,
      order: [[0, 'desc']],
      columns: [
        {
          data: "subscriptionId",
          name: "subscriptionId"
        },
        {
          data: 'startDate',
          name: 'startDate',
          render: function(obj) {
            return (obj && obj.date) ? moment(obj.date).format('YYYY-MM-DD HH:mm:ss') : '';
          }
        },
        {
          data: 'subscriptionStatus',
          name: 'subscriptionStatus'
        },
        {
          data: 'remoteStatus',
          name: 'R. Status',
          orderable: false,
          render: function(remoteStatus) {
            return remoteStatus.charAt(0).toUpperCase() + remoteStatus.slice(1);
          }
        },
        {
          data: 'accountId',
          name: 'Account Id',
          orderable: false,
        },
        {
          data: 'accountName',
          name: 'Account Name',
          orderable: false,
          render: function(accountName, display, data) {
            return '<a href="' + '/admin/accounts/edit?id=' + data.accountId + '">' + accountName + '</a>';
          }
        },
        {
          data: 'name',
          name: 'name'
        },
        {
          data: 'price',
          name: 'price'
        },
        {
          data: 'renewalDate',
          name: 'renewalDate',
          render: function(obj) {
            return (obj && obj.date && obj.date != '-0001-11-30 00:00:00.000000') ? moment(obj.date).format('YYYY-MM-DD HH:mm:ss') : '';
          }
        },
        {
          data: 'freeTrial',
          name: 'freeTrial',
          render: function(freeTrail) {
            return freeTrail ? 'Yes' : 'No';
          }
        },
        {
          data: 'inFreemium',
          name: 'Freemium',
          orderable: false,
          render: function(inFreemium) {
            return inFreemium ? 'Yes' : 'No';
          }
        },
        {
          data: 'endDate',
          name: 'endDate',
          render: function(obj) {
            return (obj && obj.date && obj.date != '-0001-11-30 00:00:00.000000') ? moment(obj.date).format('YYYY-MM-DD HH:mm:ss') : '';
          }
        },
        {
          data: 'terminatedAt',
          name: 'terminatedAt',
          render: function(obj) {
            return (obj && obj.date) ? moment(obj.date).format('YYYY-MM-DD HH:mm:ss') : '';
          }
        },
        {
          data: 'affiliateId',
          name: 'affiliateId',
          orderable: false,
        }
      ]
    });
  }
});
