<template>
  <header class="winner-header media">
    <div class="media-left">
      <slot name="left">
        <winner-photo :photo="winner.photoSmall || winner.photo" />
      </slot>
    </div>
    <div class="media-content">
      <router-link :to="{ name: 'scholarships.published.show', params: { id: winner.scholarship.id } }">
        {{ winner.scholarship.title }}
      </router-link>
      <router-link :to="{ name: 'winner', params: { id: winner.id }}">
        <h2 class="title">{{ winner.application.name }}</h2>
      </router-link>
      <span>Selected on: {{ winner.createdAt | moment('MM/DD/YYYY') }}</span>
      <span v-if="winner.application.source === 'sowl'" class="source">
        (from SOWL)
      </span>
      <span v-else-if="winner.application.source === 'barn'" class="source">
        (from landing page)
      </span>
      <span v-else class="source">
        (from unknown)
      </span>
    </div>
    <div class="media-right">
      <slot name="right" />
    </div>
  </header>
</template>
<script>
import moment from 'moment';
import WinnerPhoto from './winner-photo.vue';

export default {
  components: {
    WinnerPhoto
  },
  props: {
    winner: Object
  },
}
</script>
<style lang="scss">
.winner-header {

  a {

  }
  .title {
    font-size: 22px;
  }
  .media-left {
    margin-right: 25px;
  }
  .media-content {
    margin: auto 0;
  }
  .media-right {
    margin: auto 0;
  }
  .image.is-80x80 {
    width: 80px;
    height: 80px;
    img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
    }
  }
}
</style>
