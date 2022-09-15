<template>
  <iframe-banner :id="banner.id" :width="banner.width" :height="banner.height"
                 :iframe-src="banner.iframeSrc"
                 :iframe-link-href="banner.iframeLinkHref"
                 :iframe-img-src="banner.iframeImgSrc">
  </iframe-banner>
</template>

<script>
import { mapGetters } from "vuex";
import IframeBanner from "banners/IframeBanner.vue";

export default {
  components: {
    IframeBanner
  },
  props: {
    banners: {type: Object, required: true}
  },
  computed: {
    banner() {
      const breakpointName = this.$store.state.screen.screenResolution.breakpointNames[0];
      let banner = null;

      for(let key in this.banners) {
        if(key.split("|").includes(breakpointName)) {
          banner = this.banners[key];
          break;
        }
      }

      return banner;
    },
    ...mapGetters({
      xs: "screen/xs",
      s: "screen/s",
      m: "screen/m",
      l: "screen/l",
      xl: "screen/xl",
      xxl: "screen/xxl",
    }),
  }
};
</script>