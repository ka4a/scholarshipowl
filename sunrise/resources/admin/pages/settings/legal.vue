<template>
  <section class="page">
    <breadcrumbs :breadcrumbs="breadcrumbs" />
    <div class="container">
      <b-loading v-if="$store.getters['settings/loading']" :is-full-page="false" active />
      <ul v-else>
        <li class="block" v-for="{ id, name, updatedAt } in settings">
          <router-link class="button is-grey is-round is-pulled-right"
            :to="{ name: 'settings.legal.edit', params: { id } }">
            <c-icon icon="pencil" />
          </router-link>
          <h3 class="title is-3">{{ name }}</h3>
          <h4 class="subtitle is-4">
            <span>Last updated {{ updatedAt | moment('MM/DD/YYYY, h:mm a') }}</span>
          </h4>
        </li>
      </ul>
    </div>
  </section>
</template>
<script>
import {
  CONFIG_LEGAL_AFFIDAVIT,
  CONFIG_LEGAL_PRIVACY_POLICY,
  CONFIG_LEGAL_TERMS_OF_USE,
} from 'store/settings';

export default {
  name: 'LegalContent',
  created() {
    if (!this.$store.getters['settings/loaded']) {
      this.$store.dispatch('settings/load');
    }
  },
  computed: {
    settings() {
      return [
        CONFIG_LEGAL_TERMS_OF_USE,
        CONFIG_LEGAL_PRIVACY_POLICY,
        CONFIG_LEGAL_AFFIDAVIT,
      ].map((id) => this.$store.getters['settings/find'](id));
    },
    breadcrumbs: () => ({
      'Settings': { name: 'settings', },
      'Legal Content': { name: 'settings.legal' },
    })
  }
}
</script>
