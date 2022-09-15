<template>
  <iframe :id='id' :name='id' :width='width' :height='height' :src="iframeSource" frameborder='0' scrolling='no'>
    <a :href="iframeLink" target='_blank'>
      <img :src="iframeImage" border='0' alt='' />
    </a>
  </iframe>
</template>

<script>
import { httpQuery }    from "lib/utils/http";
import { randomString } from "lib/utils/utils";

export default {
  props: {
    id:             { type: String, required: true },
    width:          { type: Number, required: true },
    height:         { type: Number, required: true },
    iframeSrc:      { type: String, required: true },
    iframeLinkHref: { type: String, required: true },
    iframeImgSrc:   { type: String, required: true }
  },
  computed: {
    iframeSource () {
      return this.iframeSrc + "&cb=" + this.randomString() + "&" + this.siteVariables();
    },
    iframeLink () {
      return this.iframeLinkHref + "&cb=" + this.randomString();
    },
    iframeImage () {
      return this.iframeImgSrc + "&cb=" + this.randomString();
    },
  },
  methods: {
    siteVariables () {
      const profile = this.$store.getters["account/profile"];
      let data = [];

      if (profile) {
        data["age"] = profile.age;
        data["enrolled"] = profile.enrolled;
        data["gender"] = profile.gender;
        data["country"] = profile.country ? profile.country.name : null;
        data["state"] = profile.state ? profile.state.abbreviation : null;
      }

      return httpQuery(data);
    },
    randomString
  }
};
</script>
