<template>
  <div class="columns">
    <div class="column">
      <h3 class="title is-4">APPLICATIONS RECEIVED</h3>
      <div class="deadline-info">
        <span>deadline</span>
        <span>{{ scholarship.deadline | moment('from', 'now') }}</span>
      </div>
      <div class="columns">
        <div class="column applications-number">
          <strong class="title is-2">{{ stats.total }}</strong>
        </div>
        <div class="column">
          <p v-if="stats.sources.sowl">{{ stats.sources.sowl }} <span class="has-text-grey">form SOWL</span></p>
          <p v-if="stats.sources.barn">{{ stats.sources.barn }} <span class="has-text-grey">from landing page</span></p>
          <p v-if="stats.sources.none">{{ stats.sources.none }} <span class="has-text-grey">from unknown</span></p>
          <!-- <p>{{ stats.rejected }} <span class="has-text-grey">rejected</span></p>
          <p>{{ stats.accepted }} <span class="has-text-grey">accepted</span></p> -->
          <p class="has-text-grey">{{ stats.unreviewed }} unreviewed</p>
        </div>
      </div>
      <!-- <div class="box-footer is-clearfix">
        <router-link class="mt-10 is-pulled-right" :to="{ name: 'scholarships.published.review', params: { id: $route.params.id }}">
          REVIEW
        </router-link>
      </div> -->
      <h3 class="title is-4">INFO</h3>
      <p>Id: <strong>{{ scholarship.id }}</strong>
      <p><strong>{{ scholarship.title }}</strong></p>
      <p class="small">{{ scholarship.description }}</p>
    </div>
    <div class="column">
      <h3 class="title is-4">WINNERS</h3>
      <div class="columns">
        <div class="column">
          <strong class="title is-2">
            <span>{{ stats.winners.total }}</span>
          </strong>
          <span class="small">(${{ scholarship.amount }})</span>
        </div>
        <div class="column">
          <p v-if="scholarship.awards === 1">{{ scholarship.awards }} <span class="has-text-grey">award</span></p>
          <p v-else>{{ scholarship.awards }} <span class="has-text-grey">awards</span></p>
          <p>{{ stats.winners.awarded }} <span class="has-text-grey">awarded</span></p>
          <p>{{ stats.winners.disqualified }} <span class="has-text-grey">disqualified</span></p>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    scholarship: Object,
  },
  computed: {
    stats: ({ scholarship }) => scholarship.stats,
  }
}
</script>
