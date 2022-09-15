<template>
  <grid
    class="grid-cholarship-templates"
    searchDisplay
    rowActions
    :columns="columns"
    :store="scholarshipsGrid"
    searchPlaceholder="Search"
    defaultSortDirection="desc"
  >
    <template slot="column-id" slot-scope="data">
      <router-link :to="{ name: 'scholarships.show', params: { id: data.row.id }}">
        {{ data.row.id }}
      </router-link>
    </template>
    <template slot="column-website" slot-scope="data">
      <a v-if="data.row.website" :href="data.row.website.meta.url" target="_blank">
        {{ data.row.website.meta.url }}
      </a>
      <router-link v-else class="button"
        :to="{ name: 'scholarships.settings.design', params: { id: data.row.id }}">
        Create Website
      </router-link>
    </template>
    <template slot="column-title" slot-scope="data">
      <router-link v-if="data.row.published" :to="{ name: 'scholarships.published.show', params: { id: data.row.published.id }}">
        {{ data.row.title }}
      </router-link>
      <p v-else>{{ data.row.title }}</p>
    </template>
    <template slot="column-status" slot-scope="data">
      <button v-if="data.row.published" class="button is-success">Published</button>
      <button v-else class="button is-grey">Not published</button>
    </template>
    <template slot="row-actions" slot-scope="data">
      <router-link class="button" :to="{ name: 'scholarships.settings', params: { id: data.row.id }}">
        <icon-edit class="icon" />
        <span>Edit</span>
      </router-link>
    </template>
  </grid>
</template>
<script>
import IconEdit from 'icon/edit.vue';
import Grid from 'components/grid.vue';
import { createGridStore } from 'lib/store/factory';

const createScholarshipsGridStore = ($store) => {
  return createGridStore('scholarships', {
    baseURL: () => `/api/organisation/${$store.getters['user/organisation'].id}/`,
    include: ['website', 'published'],
    sorting: {
      field: 'id',
      direction: 'desc',
    },
  })
}

export default {
  name: 'ScholarshipList',
  components: {
    Grid,
    IconEdit,
  },
  created() {
    /**
     * Refresh the page after working organisation changed.
     */
    this.$store.subscribe(({ type }, state) => {
      if (type === 'user/setWorkingOrganisation') {
        this.scholarshipsGrid = createScholarshipsGridStore(this.$store);
      }
    });
  },
  data: function() {
    return {
      selected: null,
      scholarshipsGrid: createScholarshipsGridStore(this.$store),
      columns: [
        {
          label: 'Id', // Column name
          field: 'id', // Field name from row
          // slot: 'column-id',
          numeric: true, // Affects sorting
          sortable: true,
          filterable: false,
          width: 50, //width of the column
          slot: 'column-id',
        }, {
          label: 'Website',
          field: 'website',
          slot: 'column-website',
        }, {
          label: 'Title',
          field: 'title',
          sortable: true,
          slot: 'column-title',
        }, {
          label: 'Status',
          field: 'status',
          centered: true,
          slot: 'column-status',
        }
      ]
    }
  },
}
</script>
<style lang="scss">
.grid-scholarship-templates {
  .table {
    width: 100%;
  }
}
</style>
