<template>
  <grid class="grid-template-scholarships"
    title="Expired instances"
    defaultSortField="expiredAt"
    defaultSortDirection="desc"
    :columns="columns"
    :store="expiredStore">
    <template slot="column-id" slot-scope="data">
      <router-link :to="{ name: 'scholarships.published.show', params: { id: data.row.id }}">
        {{ data.row.id }}
      </router-link>
    </template>
    <template slot="column-expired" slot-scope="data">
      <p>{{ data.row.expiredAt | moment('utc') | moment('MM/DD/YYYY, h:mm a') }}</p>
      <p class="has-text-warning">{{ data.row.expiredAt | moment('utc') | moment('from', 'now') }}</p>
    </template>
    <template slot="column-start" slot-scope="data">
      <p>{{ data.row.start | moment('utc') | moment('MM/DD/YYYY, h:mm a') }}</p>
      <p class="has-text-success">{{ data.row.start | moment('utc') | moment('from', 'now') }}</p>
    </template>
    <template slot="column-deadline" slot-scope="data">
      <p>{{ data.row.deadline | moment('utc') | moment('MM/DD/YYYY, h:mm a') }}</p>
      <p class="has-text-danger">{{ data.row.deadline | moment('utc') | moment('from', 'now') }}</p>
    </template>
  </grid>
</template>
<script>
import { Store } from 'vuex';
import GridStore from 'lib/store/grid-store';
import Grid from 'components/grid';

export default {
  components: {
    Grid
  },
  props: {
    templateId: String
  },
  data: function() {
    return {
      expiredStore: new Store(
        GridStore('scholarship', {
          baseURL: () => `/api/scholarship_template/${this.templateId}/`,
          basicQuery: {
            filter: {
              expiredAt: { operator: 'neq', value: null }
            }
          }
        }),
      ),
      columns: [
        {
          label: 'Id',
          field: 'id',
          width: 300,
          slot: 'column-id'
        },
        {
          label: 'Title',
          field: 'title',
        },
        {
          label: 'Start',
          field: 'start',
          centered: true,
          sortable: true,
          width: 200,
          date: true,
          slot: 'column-start',
        },
        {
          label: 'Deadline',
          field: 'deadline',
          centered: true,
          sortable: true,
          width: 200,
          date: true,
          slot: 'column-deadline',
        },
        {
          label: 'Expired at',
          field: 'expiredAt',
          centered: true,
          sortable: true,
          width: 200,
          date: true,
          slot: 'column-expired',
        },
      ]
    }
  }
}
</script>
