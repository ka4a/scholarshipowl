<template>
  <div class="page">
    <breadcrumbs :breadcrumbs="breadcrumbs" />
    <div class="container">
      <div class="block">
        <winner :winner="winner" v-if="winner.id === this.$route.params.id" />
      </div>
    </div>
  </div>
</template>
<script>
import Winner from './winners/winner';

export default {
  components: {
    Winner
  },
  computed: {
    winner: ({ $store }) => $store.state.winners.winnerPage.item,
    breadcrumbs: ({ $route, winner }) => ({
      'Winners': { name: 'winners' },
      [ winner.id ? winner.name : '' ]: { name: 'winner', params: { id: $route.params.id } }
    })
  },
  created () {
    this.$store.dispatch('winners/winnerPage/load', this.$route.params.id)
  }
}
</script>
