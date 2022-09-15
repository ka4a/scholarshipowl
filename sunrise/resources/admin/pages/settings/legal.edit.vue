<template>
  <section class="page">
    <breadcrumbs :breadcrumbs="breadcrumbs" />
    <div class="container">
      <b-loading v-if="loading" :is-full-page="false" active />
      <div v-else class="block is-fullheight">
        <b-field class="is-pulled-left">
          <form v-if="isAffidavit" method="post" :action="previewURL" target="preview-document">
            <input name="html" type="hidden" :value="item.config" />
            <button class="button is-primary is-pulled-left" type="submit">
              <b-icon icon="file-find" /><span>Preview File</span>
            </button>
          </form>
        </b-field>
        <b-field class="is-pulled-right">
          <button class="button" :disabled="!changed" @click="reset">
            <b-icon icon="cancel" /><span>Reset</span>
          </button>
          <button class="button is-success"
            :class="{ 'is-loading': saving }"
            :disabled="!changed || saving"
            @click="save">
            <b-icon icon="content-save" /><span>Save</span>
          </button>
        </b-field>
        <content-editor class="is-clearfix" :is-pdf="isAffidavit" v-model="item.config" @input="changed = true" />
        <b-field  horizontal label="Load version">
          <b-select v-model="logSelected">
            <option :value="index" v-for="(log, index) in item.log">
              <span>{{ log.loggedAt | moment('MM/DD/YYYY, h:mm a') }}</span>
              <span>({{ log.version }})</span>
            </option>
          </b-select>
        </b-field>
        <b-field class="is-pulled-right is-clearfix">
          <button class="button" :disabled="!changed" @click="reset">
            <b-icon icon="cancel" /><span>Reset</span>
          </button>
          <button class="button is-success"
            :class="{ 'is-loading': saving }"
            :disabled="!changed || saving"
            @click="save">
            <b-icon icon="content-save" /><span>Save</span>
          </button>
        </b-field>
        <div class="field is-clearfix"></div>
      </div>
    </div>
  </section>
</template>
<script>
import {
  CONFIG_LEGAL_AFFIDAVIT,
  CONFIG_LEGAL_RULES,
  CONFIG_LEGAL_PRIVACY_POLICY,
  CONFIG_LEGAL_TERMS_OF_USE,
} from 'store/settings';

import { createStore } from 'lib/store/item-store';
import ContentEditor from 'components/settings/LegalContentEditor';

import rootStore from 'store';

const settingStore = createStore('settings', null, {
  include: ['log'],
  readOnlyAttributes: ['name', 'createdAt', 'updatedAt'],
  readOnlyRelationships: ['log'],
});

export default {
  name: 'EditLegalSettings',
  components: {
    ContentEditor
  },
  created() {
    settingStore.dispatch('load', this.$route.params.id)
      .then(() => this.$nextTick(() => this.changed = false));
  },
  data() {
    return {
      saving: false,
      logSelected: 0,
      changed: false,
    };
  },
  methods: {
    save() {
      this.saving = true;
      settingStore.dispatch('save')
        .finally(() => this.saving = false)
        .then(() => this.logSelected = 0)
        .then(() => this.changed = false)
        .then(() => this.$store.dispatch('settings/load'));
    },
    reset() {
      this.logSelected = 0;
      settingStore.dispatch('resetLoaded')
        .then(() => this.$nextTick(() => this.changed = false));
    },
  },
  computed: {
    item: ({ store }) => settingStore.state.item,
    loading: ({ saving }) => settingStore.state.loading && !saving,
    breadcrumbs: ({ item, loading, $route }) => (loading ? null : {
      'Settings': { name: 'settings', },
      'Legal Content': { name: 'settings.legal' },
      [item.name]: { name: $route.name, params: { id: $route.params.id } }
    }),

    isAffidavit: ({ $route }) => $route.params.id === CONFIG_LEGAL_AFFIDAVIT,
    previewURL: ({ $route }) => `/api/preview.pdf`,
  },
  watch: {
    logSelected(n, o) {
      if (n !== o) {
        this.item.config = this.item.log[n].config;
        this.$nextTick(() => this.changed = n !== 0);
      }
    }
  }
}
</script>
<style lang="scss" scoped>
.page > .container > .block.is-fullheight {
  /deep/ .quill-editor {
    .ql-editor {
      height: calc(100vh - 420px);
    }
  }
}
.field {
  &.is-clearfix {
    clear: both;
  }
  .button + .button {
    margin-left: 18px;
  }
}
</style>
