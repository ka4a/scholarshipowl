<template>
  <div class="page">
    <b-loading :active="loading" :isFullPage="false" />
    <div class="container">
      <h2 class="title is-2">Scholarships ({{ scholarships.length }})</h2>

      <scholarships-list v-if="scholarships.length" />
      <div class="empty-state" v-else-if="!loading">
        <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M25 0C11.2335 0 0 11.2354 0 25C0 38.7665 11.2354 50 25 50C38.7665 50 50 38.7646 50 25C50 11.2331 38.7646 0 25 0ZM25 47.0703C12.8304 47.0703 2.92969 37.1696 2.92969 25C2.92969 12.8304 12.8304 2.92969 25 2.92969C37.1696 2.92969 47.0703 12.8304 47.0703 25C47.0703 37.1696 37.1696 47.0703 25 47.0703Z" fill="#828282"/>
          <path d="M23.5352 11.8164H26.4648V26.4648H23.5352V11.8164Z" fill="#828282"/>
          <path d="M31.5929 13.5811L30.1258 16.1171C33.289 17.9466 35.2539 21.3504 35.2539 25C35.2539 30.6541 30.6541 35.2539 25 35.2539C19.3459 35.2539 14.7461 30.6541 14.7461 25C14.7461 21.3504 16.711 17.9466 19.8742 16.1171L18.4071 13.5811C14.3417 15.9328 11.8164 20.3083 11.8164 25C11.8164 32.2693 17.7307 38.1836 25 38.1836C32.2693 38.1836 38.1836 32.2693 38.1836 25C38.1836 20.3083 35.6583 15.9328 31.5929 13.5811Z" fill="#828282"/>
        </svg>
        <h4 class="title is-4 has-text-grey-lighter">Start by adding a new scholarship item.</h4>
      </div>
    </div>
  </div>
</template>
<script>
import ScholarshipsList from 'components/scholarship/DashboardList';

export default {
  name: 'OrgScholarshipsList',
  components: {
    ScholarshipsList,
  },
  created() {
    this.$store.dispatch('organisation/scholarships/load');
  },
  computed: {
    scholarships: ({ $store }) => $store.state.organisation.scholarships.collection,
    loading: ({ $store }) => $store.state.organisation.scholarships.loading,
    loaded: ({ $store }) => $store.state.organisation.scholarships.loaded,
  },
}
</script>
<style lang="scss" scoped>
.page {
  .container {
    padding: 50px;
    display: flex;
    flex-direction: column;
  }

  .empty-state {
    display: flex;
    text-align: center;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 100%;

    .title {
      font-weight: normal;
      margin-top: 30px;
    }
  }
}
</style>
