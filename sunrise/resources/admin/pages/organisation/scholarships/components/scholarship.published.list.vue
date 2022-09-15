<template>
  <div class="scholarship-published-list">
    <grid class="grid-cholarship-templates"
      exportable
      customizableColumns
      searchDisplay
      defaultSortField="createdAt"
      defaultSortDirection="desc"
      searchPlaceholder="Search by email..."
      per-page="20"
      :columns="columns"
      :store="store">
      <template slot="column-id" slot-scope="data">
        <router-link
          :to="{name: 'scholarships.published.list.application', params: {
            id: $route.params.id,
            application: data.row.id
          }}"
        >
          <span>{{ data.row.id }}</span>
        </router-link>
      </template>
      <template slot="column-status" slot-scope="data">
        <status-view :status="data.row.status" />
        <!-- <button v-if="data.row.status.id === 'accepted'" class="button is-success">Accepted</button>
        <button v-else-if="data.row.status.id === 'rejected'" class="button is-warning">Rejected</button>
        <button v-else class="button is-grey">Review</button> -->
      </template>
      <template slot="title">
        <h3 class="title is-3">{{ total }} applications</h3>
      </template>
    </grid>
    <application-review
      v-if="$route.name === 'scholarships.published.list.application'"
      @close="onPreviewClose"
      @updated="store.dispatch('updateItem', { id: $route.params.application, item: $event })"
      :id="$route.params.application"
      :total="total"
      :active="true" />
  </div>
</template>
<script>
import { Store } from 'vuex';
import { GridStore } from 'lib/store/factory';

import Grid from 'components/grid';
import StatusView from 'components/application/StatusView.vue';
import ApplicationReview from './application.review.vue';

export default {
  components: {
    Grid,
    StatusView,
    ApplicationReview,
  },
  computed: {
    loading: ({ store }) => store.getters['loading'],
    total: ({ store }) => store.getters['pagination'].total,
    scholarship: ({ $store }) => $store.state.organisation.scholarshipsPublishedPage.item,
    columns() {
      const columns = [
        {
          label: 'Applied at',
          field: 'createdAt',
          centered: true,
          sortable: true,
          width: 200,
          date: true,
          // filterable: true,
        },
        {
          label: 'Source',
          field: 'source',
          width: 100,
          sortable: true,
          filterable: true,
          visible: true,
        },
        {
          label: 'ID',
          field: 'id',
          width: 300,
          slot: 'column-id',
          filterable: true,
          visible: true,
        },
        {
          label: 'Name',
          field: 'name',
          sortable: true,
          filterable: true,
        },
        {
          label: 'E-mail',
          field: 'email',
          sortable: true,
          filterable: true,
        },
      ];

      this.scholarship.fields.forEach(({ field }) => {
        if (field.id !== 'name' && field.id !== 'email') {
          const newColumn = {
            label: field.name,
            field: `data.${field.id}`,
            filterable: ['name', 'phone', 'email', 'text'].indexOf(field.type) !== -1,
            visible: false,
            width: 200,
          };

          if (field.type === 'option') {
            newColumn.fieldValue = ({ data }) => {
              if (!data[field.id]) {
                return null;
              }
              const option = field.options[data[field.id]];
              return option.name ? option.name : option;
            };
          }

          columns.push(newColumn);
        }
      });

      columns.push({
        label: 'Status',
        field: 'status.name',
        centered: true,
        sortable: true,
        slot: 'column-status'
      });

      return columns;
    },
  },
  created() {
    const scholarshipLoaded = this.$store.getters['organisation/scholarshipsPublishedPage/loaded'];

    if (!scholarshipLoaded) {
      this.$store.dispatch('organisation/scholarshipsPublishedPage/load', this.$route.params.id);
    }
  },
  data: function() {
    return {
      store: new Store(
        GridStore('application', {
          baseURL: () => `/api/scholarship/${this.$route.params.id}/`,
          include: ['status'],
        }),
      ),
    };
  },
  methods: {
    onPreviewClose() {
      this.store.dispatch('load');
      this.$router.push({
        name: 'scholarships.published.list',
        params: { id: this.$route.params.id }
      });
    },
  },
}
</script>
