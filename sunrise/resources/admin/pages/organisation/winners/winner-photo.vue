<template>
  <figure :class="`image is-${size}x${size}`">
    <img v-if="image" :src="image" />
    <img v-else-if="!photo" :src="`https://placehold.it/${size}x${size}`" />
    <b-loading v-else :is-full-page="false" :active="true"/>
  </figure>
</template>
<script>
export default {
  props: {
    photo: Object,
    size: {
      type: String,
      default: '80'
    }
  },
  computed: {
    image({ photo, $store }) {
      if (!photo || !photo.id) {
        return null;
      }
      return $store.state.winners.winnerImages.images[this.photo.id];
    },
  },
  created() {
    if (this.photo && this.photo.id && !this.image) {
      this.$store.dispatch('winners/winnerImages/load', this.photo.id)
    }
  }
}
</script>
<style lang="scss" scoped>
.image {
  img {
    border-radius: 50%;
  }

  &.is-80x80 {
    width: 80px;
    height: 80px;
    img {
      width: 80px;
      height: 80px;
    }
  }
  &.is-40x40 {
    width: 40px;
    height: 40px;
    img {
      width: 40px;
      height: 40px;
    }
    /deep/ .loading-icon::after {
      width: 30px;
      height: 30px;
      left: calc(50% - 15px);
      top: calc(50% - 15px);
    }
  }
}
</style>
