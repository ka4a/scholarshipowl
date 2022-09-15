<template>
  <div class="scholarship-published-list">
    <!-- <b-loading :active="loading" :isFullPage="false" /> -->

    <div class="toolbar">
      <router-link v-if="waiting.length > 0"
        :to="{ name: 'scholarships.published.review.application', params: { id: $route.params.id, application: waiting[0].id }}">
        <button class="button is-primary">REVIEW APPLICATIONS</button>
      </router-link>
      <button v-else-if="!allWinnersPicked" class="button is-success" @click="pickWinners">
        Pick winners
      </button>
    </div>

    <grid class="grid-cholarship-winners"
      rowCursorPointer
      defaultSortDirection="desc"
      @click="openWinner"
      :columns="columns"
      :store="store">
    </grid>
  </div>
</template>
<script>
import { Store } from 'vuex';
import { GridStore } from 'lib/store/factory';

import Grid from 'components/grid';

export default {
  components: {
    Grid
  },
  created() {
    this.applicationStore.dispatch('filter', {
      status: { operator: 'in', value: ['received','review'] }
    })
  },
  data: function() {
    return {
      store: new Store(
        GridStore('winner', {
          baseURL: () => `/api/scholarship/${this.$route.params.id}/`,
        }),
      ),
      applicationStore: new Store(
        GridStore('application', {
          baseURL: () => `/api/scholarship/${this.$route.params.id}/`,
        })
      ),
      columns: [
        {
          label: 'Id',
          field: 'id',
          sortable: true,
        },
        {
          label: 'Name',
          field: 'name',
          sortable: true,
        },
        {
          label: 'Email',
          field: 'email',
          sortable: true,
        },
        {
          label: 'Phone',
          field: 'phone',
          sortable: true,
        },
        {
          label: 'State',
          field: 'state.name',
          sortable: true,
        },
        {
          label: 'Selected at',
          field: 'createdAt',
          sortable: true,
          width: 200,
          date: true,
        },
        {
          label: 'Disqualified at',
          field: 'disqualifiedAt',
          sortable: true,
          width: 200,
          date: true,
        }
      ]
    }
  },
  methods: {
    openWinner({ id }) {
      this.$router.push({ name: 'winner', params: { id } });
    },
    pickWinners() {
      this.$http.post(`/api/scholarship/${this.$route.params.id}/chooseWinners`)
        .then(() => this.$toast.open({ type: 'is-success', message: 'Winners picked.' }))
        .then(() => this.store.dispatch('load'))
    }
  },
  computed: {
    waiting: ({ applicationStore }) => {
      return applicationStore.state.collection
    },
    winners() {
      return this.store.state.collection;
    },
    scholarship() {
      return this.$store.state.organisation.scholarshipsPublishedPage.item;
    },
    allWinnersPicked() {
      return this.scholarship.awards <= this.winners.length;
    }
  }
}
</script>
<style lang="scss" scoped>
.toolbar {
  display: flex;
  flex-direction: row-reverse;
  margin-bottom: 20px;
}
.scholarship-published-list {

}
</style>
