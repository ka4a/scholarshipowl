<template>
  <div class="grid">
    <c-table
      :data="rows"
      :striped="striped"
      :narrowed="narrowed"
      :columns="newColumns.slice(0)"
      :rowActions="rowActions"
      :rowActionsLabel="rowActionsLabel"

      @click="rowClick"
      :rowCursorPointer="rowCursorPointer"

      backend-pagination
      :loading="loading"
      :paginated="paginate && pagination.total_pages > 1"
      :total="pagination.total"
      :per-page="pagination.per_page"
      :current-page="pagination.current_page"
      @page-change="page"

      @filter="filter"

      backend-sorting
      :default-sort="sorting.field"
      :default-sort-direction="sorting.direction"
      @sort="sort">

      <template slot="before">
        <div class="level">

          <div class="level-left">
            <div v-if="title || $slots.title" class="level-item">
              <slot name="title">
                <h4 v-if="title" class="title is-4">
                  {{ title }}
                </h4>
              </slot>
            </div>

            <div v-if="searchDisplay" class="search level-item">
              <b-input :value="searchInput"
                @input="search"
                type="search"
                icon="magnify"
                :placeholder="searchPlaceholder" />
            </div>
          </div>

          <div class="level-right">

            <span v-if="store.state.query.filter" class="toolbar-action has-text-primary level-item" @click="clearFilters">
              <b-icon icon="delete-outline" />
              <span>Clear filters</span>
            </span>

            <span v-if="showPerPage" class="per-page is-vcentered toolbar-action level-item">
              <b-dropdown>
                <span slot="trigger">Rows: {{ pagination.per_page }}</span>
                <b-dropdown-item v-for="(len, index) in pagelen" :key="index" @click="setPerPage(len)">{{ len }}</b-dropdown-item>
              </b-dropdown>
            </span>

            <span v-if="customizableColumns" @click="openCustomizableColumns" class="toolbar-action level-item">
              <c-icon icon="gear" />
              <span>Customize Columns</span>
            </span>

            <span v-if="exportable" @click="download" class="toolbar-action level-item">
              <c-icon icon="download" />
              <span>Export</span>
            </span>

            <span v-if="showRefresh" @click="_dispatch('load')" class="toolbar-action level-item">
              <b-icon icon="refresh" />
              <span>Refresh</span>
            </span>

          </div>

        </div>
      </template>

      <template v-for="slot in Object.keys($scopedSlots)" :slot="slot" slot-scope="props">
        <slot :name="slot" v-bind="props" />
      </template>

      <!-- <template slot="footer">
        <slot name="footer">
          <p class="is-pulled-right">Total {{pagination.total}} records</p>
        </slot>
      </template> -->

    </c-table>
    <b-modal :active.sync="customizableModalOpen">
      <div class="box">
        <i class="boxclose" @click="customizableModalOpen = false" />
        <div class="customize-columns">
          <h4 class="title is-4 has-text-bold">Cutomize table</h4>
          <h5 class="subtitle is-5">Choose info you want to be shown in the table</h5>
          <ul class="customize-columns__list">
            <li>
              <b-field v-for="(column, i) in newColumns" :key="i">
                <b-checkbox
                  :value="column.visible || column.visible === undefined"
                  @input="toggleColumn(column)">
                  <span>{{ column.label }}</span>
                </b-checkbox>
              </b-field>
            </li>
          </ul>
        </div>
      </div>
    </b-modal>
  </div>
</template>
<script type="javascript">
import jsona from 'lib/jsona';

export default {
  name: 'CommonGrid',
  props: {
    customizableColumns: Boolean,
    striped: {
      type: Boolean,
      default: true,
    },
    store: {
      required: true,
    },
    title: String,
    columns: {
      type: Array,
      required: true,
    },
    loaded: Boolean,
    rowActions: Boolean,
    rowActionsLabel: String,
    rowCursorPointer: Boolean,
    searchDisplay: Boolean,
    paginate: {
      default: true
    },
    exportable: Boolean,
    narrowed: {
      type: Boolean,
      default: true,
    },
    pagelen: {
      type: Array,
      default: () => [1, 5, 10, 20, 50],
    },
    perPage: {
      type: String,
      default: '10',
    },
    pageNumber: 1,
    defaultSortField: {
      type: String,
      default: 'id'
    },
    defaultSortDirection: {
      type: String,
      default: 'asc',
    },
    searchPlaceholder: {
      default: 'Search',
    },
    showPerPage: {
      type: Boolean,
      default: true
    },
    showRefresh: {
      type: Boolean,
      default: false
    },
  },
  data: function () {
    return {
      customizableModalOpen: false,

      newColumns: this.columns,
      exporting: false,
      searchInput: '',
      filters: {},
    };
  },
  computed: {
    sorting() {
      return this._getter('sorting');
    },
    pagination() {
      return this._getter('pagination');
    },
    rows() {
      return this._getter('collection');
    },
    loading() {
      return this._getter('loading');
    },
  },
  created() {
    this.firstLoad();
  },
  methods: {
    clearFilters() {
      this.store.dispatch('clearFilters');
      this.searchInput = null;
    },
    toggleColumn(column) {
      Vue.set(
        this.newColumns,
        this.newColumns.indexOf(column),
        { ...column, visible: (column.visible === undefined || column.visible) ? false : true }
      );
    },
    openCustomizableColumns() {
      this.customizableModalOpen = true;
    },
    firstLoad() {
      const size = this.perPage;
      const number = this.pageNumber;
      const field = this.defaultSortField;
      const direction = this.defaultSortDirection;
      const load = !this.loaded;

      this._dispatch('page', { number, size, load });
      this._dispatch('sort', { field, direction, load });
    },
    rowClick(row) {
      this.$emit('click', row);
    },
    closeFilters(index) {
      return () => this.$set(this.columns[index], 'filtersOpen', false)
    },
    openFilters(event, index) {
      if (!this.columns[index].filterable) {
        return;
      }

      event.cancelBubble = true;
      this.$set(this.columns[index], 'filtersOpen', true)
    },
    download() {
      this.exporting = true;
      this._dispatch('download')
        .then(() => {
          this.exporting = false;
        })
        .catch(() => {
          this.exporting = false;
        });
    },
    search(q) {
      this.searchInput = q;
      if (q !== null) {
        this._dispatch('search', q);
      }
    } ,
    filter( column, filter) {
      this._dispatch('filter', { [column.field]: filter });
    },
    sort(field, direction) {
      if (field.indexOf('.') !== -1) {
        field = field.substring(0, field.indexOf('.'));
      }

      return this._dispatch('sort',  { field, direction });
    },
    page(number) {
      const size = this.pagination.per_page;
      return this._dispatch('page', { number, size });
    },
    setPerPage(size) {
      const { total, per_page, current_page } = this.pagination;
      // Get new page number relative to previouse result shown.
      const offset = per_page * (current_page - 1);
      const number = Math.floor(offset / size) + 1;
      return this._dispatch('page', { number, size });
    },
    _dispatch(action, option) {
      return (typeof this.store === 'object') ?
        this.store.dispatch(action, option) :
        this.$store.dispatch(`${this.store}/${action}`, option);
    },
    _getter(item) {
      return (typeof this.store === 'object') ?
        this.store.getters[item] :
        this.$store.getters[`${this.store}/${item}`];
    },
  },
  watch: {
    store: function() {
      this.firstLoad()
    },
    columns: function(columns) {
      this.newColumns = columns;
    },
  },
}
</script>
<style lang="scss">
.grid {
  /deep/ .b-table {
    .level {
      margin-bottom: 0;
    }
  }
  .table {
    background-color: #FAFCFD;
    // background-color: #F5F7FA;
    min-height: 100px;
    color: #61676F;

    tr:hover {
      background-color: #FAFCFD;
    }

    tbody {
      tr:last-child {
        td:first-child {
          border-bottom-left-radius: 5px;
        }
        td:last-child {
          border-bottom-right-radius: 5px;
        }
      }
    }

    td, th {
      border: none;
      // border-top: 0;
      // border-bottom: 0.75px solid #DBDBDB
    }

    thead {
      th {
        color: #B6BECA;
        font-weight: normal;
        background: #F5F7FA;
        font-weight: bold;
        font-size: 15px;
        /deep/ .filters {
          margin-left: auto;
        }
        &.is-current-sort {
          color: #61676F;
        }
        &:first-child {
          border-top-left-radius: 5px;
        }
        &:last-child {
          border-top-right-radius: 5px;
        }
        &:hover {
          color: #61676F;
          &.is-sortable {
            background: #B6BECA;
          }
          .icon-sort {
            path {
              fill: #61676F;
              stroke: #61676F;
            }
          }
        }
      }
    }
    &.is-striped {
      // .table.is-striped tbody tr:not(.is-selected):nth-child(even)
      tbody {
        tr:not(.is-selected):nth-child(even) {
          background-color: #F5F7FA;
          // background-color: #FAFCFD;
        }
      }
      // tbody tr:not(.is-selected):nth-child(even) {
      //   background-color: red;
      // }
    }
  }
  .toolbar-action {
    cursor: pointer;
    display: flex;
    color: #61676F;
    &:hover {
      text-decoration: underline;
    }
  }
  .customize-columns {
    padding: 23px 38px;
    margin-right: 40px;
    max-height: 500px;
    &__list {
      // -moz-column-count: 4;
      // -moz-column-gap: 20px;
      // -webkit-column-count: 4;
      // -webkit-column-gap: 20px;
      column-count: 2;
      column-gap: 20px;
    }
  }
}
</style>
