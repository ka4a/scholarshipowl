<template lang="html">
  <section class="banner-adapter">
    <large-banner v-if="xxl" class="banner-adapter__item" />
    <medium-banner v-if="l || xl" class="banner-adapter__item" />
    <small-banner v-if="xs || s || m" class="banner-adapter__item" />
  </section>
</template>

<script>
import { mapGetters } from "vuex";
import SmallBanner    from "banners/FreemiumScholarshipsApplicationDetails1.vue";
import MediumBanner   from "banners/FreemiumScholarshipsApplicationDetails2.vue";
import LargeBanner    from "banners/FreemiumScholarshipsApplicationDetails3.vue";

export default {
  name: "BannerAdapter",
  components: {
    SmallBanner,
    MediumBanner,
    LargeBanner
  },
  computed: {
    ...mapGetters({
      xs: "screen/xs",
      s: "screen/s",
      m: "screen/m",
      l: "screen/l",
      xl: "screen/xl",
      xxl: "screen/xxl"
    })
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';
@import 'style-gide/mixins';
@import 'main/meta/palette';

$blue: #B9D7FA;

.banner-adapter {
  overflow: hidden;

  &__item {
    display: block;
    margin-left: auto;
    margin-right: auto;
  }
}

.banner-top {
  margin-top: 15px;

  @include breakpoint($s) {
    margin-top: 20px;
  }

  @include breakpoint($m) {
    margin-top: 25px;
  }

  @include breakpoint(max-width $s - 1px) {
    position: relative;
    overflow: hidden;
  }
}

.banner-bottom {
  margin-top: 25px;
  margin-bottom: -5px;

  @include breakpoint($m) {
    margin-top: 30px;
    margin-bottom: -10px;
  }
}
</style>
