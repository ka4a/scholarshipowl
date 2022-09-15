<template>
  <iframe-banner id="a672bde3" width="320" height="50"
                 iframe-src="https://revive.scholarshipowl.com/delivery/afr.php?zoneid=34"
                 iframe-link-href="https://revive.scholarshipowl.com/delivery/ck.php?n=a672bde3"
                 iframe-img-src="https://revive.scholarshipowl.com/delivery/avw.php?zoneid=34&n=a672bde3">
  </iframe-banner>
</template>

<script>
const bannerSetting = [{
  id: "a672bde3",
  width: 320,
  height: 50,
  iframeSrc: "https://revive.scholarshipowl.com/delivery/afr.php?zoneid=34",
  iframeLinkHref: "https://revive.scholarshipowl.com/delivery/ck.php?n=a672bde3",
  iframeImgSrc: "https://revive.scholarshipowl.com/delivery/ck.php?n=a672bde3"
}]

import IframeBanner from "banners/IframeBanner.vue";

export default {
  props: { width: { required: true, type: Number }},
  components: {IframeBanner}
};
</script>
