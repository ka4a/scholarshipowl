<template>
  <div class="scholarship-webpage" :class="{ 'is-empty': hasWebsite === null }">

    <h5 class="title is-5">
      <span>Do you have website for the scholarship?</span>
      <button class="button is-small" :class="{ 'is-success': hasWebsite === true }" @click="hasWebsite = true">
        <span>Yes</span>
      </button>
      <button class="button is-small" :class="{ 'is-success': hasWebsite === false }" @click="hasWebsite = false">
        <span>No</span>
      </button>
    </h5>

    <design-config-website v-if="hasWebsite === true" @saved="saved" />
    <design-config v-if="hasWebsite === false" @saved="saved" />

  </div>
</template>
<script>
import store from 'store';
import DesignConfig from 'components/scholarship/DesignConfig';
import DesignConfigWebsite from 'components/scholarship/DesignConfigWebsite';
import { emptyScholarshipWebsite } from 'store/organisation/scholarshipSettings';

const routeBeforeHandler = (to, from, next) => {
  const { scholarshipSettings } = store.state.organisation;
  const website = scholarshipSettings.item.website ?
    scholarshipSettings.item.website : emptyScholarshipWebsite();

  store.dispatch('organisation/scholarshipSettings/website/setItem', website);
  next();
};

export default {
  components: {
    DesignConfig,
    DesignConfigWebsite,
  },
  beforeRouteEnter: routeBeforeHandler,
  beforeRouteUpdate: routeBeforeHandler,
  data() {
    return {
      hasWebsite: null,
    };
  },
  computed: {
    loading: ({ $store }) => $store.getters['organisation/scholarshipSettings/website/loading'],
    website: ({ $store }) => $store.getters['organisation/scholarshipSettings/website/item'],
    template: ({ $store }) => $store.getters['organisation/scholarshipSettings/item'],
  },
  created() {
    if (this.template.scholarshipUrl !== undefined || this.website.domain !== undefined) {
      this.hasWebsite = !!(this.template.scholarshipUrl && !this.website.domain);
    }
  },
  methods: {
    saved() {
      if (this.$route.params.isNewScholarship) {
        this.$router.push({
          name: 'scholarships.settings.legal',
          params: this.$route.params
        });
      }
    },
  },
}
</script>
<style lang="scss" scoped>
.scholarship-webpage {

}
</style>
