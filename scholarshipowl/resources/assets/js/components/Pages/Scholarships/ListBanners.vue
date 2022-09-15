<template lang="html">
  <section ref="adapter" class="master-list-banners">
    <div v-if="inViewport">
      <banner1 v-if="width <= 468" />
      <banner2 v-if="width > 468 && width <= 760" />
      <banner3 v-if="width > 760" />
    </div>
    <!-- <i v-else class="master-list-banners__loader"></i> -->
  </section>
</template>

<script>
import Banner1 from "banners/Freemium/Scholarships/List1.vue";
import Banner2 from "banners/Freemium/Scholarships/List2.vue";
import Banner3 from "banners/Freemium/Scholarships/List3.vue";

export default {
  name: "ListBanners",
  props: {
    width: { type: Number, required: true },
    inViewport: { type: Boolean, default: false }
  },
  components: {
    Banner1,
    Banner2,
    Banner3
  },
};
</script>

<style leng="scss">
  .master-list-banners {
    width: 100%;
    min-width: 320px;
    border-bottom: 1px solid #E4E4E4;
  }

  .master-list-banners div > iframe {
    display: block;
    margin: 0 auto;
  }
</style>
