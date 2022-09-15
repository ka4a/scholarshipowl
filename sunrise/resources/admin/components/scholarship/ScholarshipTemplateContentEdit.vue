<template>
  <div class="template-content-editor">
    <b-loading v-if="loading" active :is-full-page="false" />
    <h4 class="title is-4">
      <span>{{ label }}</span>
      <span class="current-version is-pulled-right" v-if="currentVersion">
        <span>Current version</span>
        <span>{{ currentVersion.loggedAt | moment('MM/DD/YYYY') }}</span>
      </span>
    </h4>
    <div class="description">
      <div>
        <p><strong>Sunrise saves all previous versions of content</strong></p>
        <p>You can always go back to any of it, selecting needed one from the list</p>
      </div>
      <b-field class="version-select">
        <b-select v-model="logSelected">
          <option :value="index" v-for="(log, index) in item.log">
            <span>Version </span>
            <span>{{ log.loggedAt | moment('MM/DD/YYYY') }}</span>
            <span>({{ log.version }})</span>
          </option>
        </b-select>
      </b-field>
    </div>
    <content-editor
      :is-pdf="isPdf"
      v-model="item.content"
      @input="changed = true"
    />
    <div class="bottom-actions">
      <span class="current-version" v-if="currentVersion">
        <span>Current version</span>
        <span>{{ currentVersion.loggedAt | moment('MM/DD/YYYY') }}</span>
      </span>
      <b-field class="is-pulled-right">
        <form class="preview-form" v-if="isAffidavit" method="post" :action="previewURL" target="preview-document">
          <input name="html" type="hidden" :value="item.content" />
          <button class="button is-white is-rounded" type="submit">
            <c-icon icon="preview" />
            <strong>Preview File</strong>
          </button>
        </form>
        <button class="button is-rounded is-grey" :disabled="!changed" @click="reset">
          <c-icon icon="discard" />
          <span>Discard changes</span>
        </button>
        <button class="button is-rounded is-primary" :class="{ 'is-loading': saving }" :disabled="!changed || saving" @click="save()">
          <c-icon icon="check-circle" />
          <span>Save</span>
        </button>
      </b-field>
    </div>
  </div>
</template>
<script>
import { createStore } from 'lib/store/item-store';
import ContentEditor from 'components/settings/LegalContentEditor';

export default {
  name: 'LegalContentEdit',
  props: {
    contentId: String,
    isPdf: Boolean,
    label: String,
  },
  components: {
    ContentEditor
  },
  created() {
    this.store.dispatch('load', this.contentId)
      .then(() => this.loaded = true)
      .then(() => this.$nextTick(() => this.changed = false));
  },
  data() {
    return {

      store: createStore('scholarship_template_content', null, {
        include: ['log'],
        readOnlyAttributes: ['type', 'createdAt', 'updatedAt'],
        readOnlyRelationships: ['log'],
      }),

      loaded: false,
      saving: false,
      logSelected: 0,
      changed: false,
    };
  },
  methods: {
    save() {
      this.saving = true;
      this.store.dispatch('save')
        .finally(() => this.saving = false)
        .then(() => this.logSelected = 0)
        .then(() => this.changed = false)
        .then(() => this.$toast.open({ type: 'is-success', message: 'Content updated.' }) );
    },
    reset() {
      this.logSelected = 0;
      this.store.dispatch('resetLoaded')
        .then(() => this.$nextTick(() => this.changed = false));
    },
  },
  computed: {
    item: ({ store }) => store.state.item,
    loading: ({ store, saving }) => store.state.loading && !saving,

    isAffidavit: ({ item }) => item.type === 'affidavit',
    previewURL: ({ $route }) => `/api/preview.pdf`,

    currentVersion: ({ item }) => item && item.log ? item.log[0] : null,
  },
  watch: {
    logSelected(n, o) {
      if (n !== o) {
        this.item.content = this.item.log[n].data.content;
        this.$nextTick(() => this.changed = n !== 0);
      }
    }
  }
}
</script>
<style lang="scss" scoped>
.current-version {
  font-size: 13px;
  font-weight: normal;
  color: #878787;
}
.template-content-editor {
  > .title {
    padding: 28px 80px 28px 38px;
    margin-bottom: 0;
    > .current-version {
      line-height: 27px;
    }
  }

  > .description {
    display: flex;
    margin-bottom: 18px;
    justify-content: space-between;
    padding: 10px 38px;
    background: #F2F2F2;
    font-size: 14px;
  }

  .bottom-actions {
    display: flex;
    justify-content: space-between;
    padding: 0 38px;
    padding-bottom: 28px;
    > .current-version {
      line-height: 36px;
    }
    form .button, .button:not(:last-child) {
      margin-right: 18px;
    }
    .preview-form .button {
      color: #5C5C5C;
    }
  }

  /deep/ .version-select {
    .select select {
      height: 40px;
    }
  }

  /deep/ .quill-editor {
    padding: 0 38px;
    margin-bottom: 20px;
  }
}
</style>
