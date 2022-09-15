<template>
  <div class="filters">
    <i class="filters-trigger" @click.stop="filtersOpen = !filtersOpen">
      <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="9" cy="9" r="9" fill="white"/>
        <path d="M9 11.8L6.40192 8.19999H11.5981L9 11.8Z" fill="#61676F"/>
      </svg>
    </i>
    <!-- <b-icon
        slot="trigger"
        class="cursor-pointer"
        size="is-small"
        icon="filter" /> -->
    <div v-show="filtersOpen" class="filters-select" v-click-outside="closeFilters">
      <template v-if="column.type === undefined">
        <div class="filter">
          <b-field :label="`Filter by ${column.label}`">
            <b-input
              name="filter"
              v-model="filter.search"
              @input="onChange"
              :placeholder="`Insert ${column.label}`"
            />
          </b-field>
        </div>
      </template>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    column: Object,
  },
  data() {
    return {
      filtersOpen: false,
      filter: {
        search: null,
      },
    };
  },
  methods: {
    closeFilters() {
      this.filtersOpen = false;
    },
    onChange() {
      this.$emit('filter', this.filter);
    }
  },
};
</script>
<style lang="scss" scoped>
.filters {
  .filters-trigger {
    cursor: pointer;
  }
  .filters-select {
    position: absolute;
    top: 100%;
    left: 0;
    background: #ECEEF3;
    padding: 14px 18px;
  }
}
</style>
