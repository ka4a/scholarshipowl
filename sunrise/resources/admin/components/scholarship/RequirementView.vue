<template>
  <div class="card">
    <div class="card-header">
      <div class="card-header-title">
        <requirement-icon :type="type" />
        <span>{{ title }}</span>
      </div>
      <div v-if="type === 'image'" class="card-header-icon" @click="previewFile = true">
        <c-icon icon="view" />
      </div>
      <b-modal v-if="type === 'image'" :active.sync="previewFile">
        <figure class="image is-4by3"><img :src="images[file.id]" /></figure>
        <button class="modal-close is-large" aria-label="close"></button>
      </b-modal>
      <div v-if="type === 'image' || type === 'file'" class="card-header-icon" @click="downloadApplicationFile(file.id, file.name)">
        <c-icon icon="download" />
      </div>
    </div>
    <div v-if="hasContent" class="card-content">
      <div class="content">
        <p v-if="type === 'text'" v-html="requirement.value" />
        <p v-else-if="type === 'input'" v-html="requirement.value" />
        <a v-else-if="type === 'link'" target="_blank" :href="requirement.value">
          <span>{{ requirement.value }}</span>
        </a>
        <!-- <p v-if="type === 'file'">
          <a clas="link" v-for="f in requirement.files" @click="downloadApplicationFile(f.id, f.name)">
            {{ f.name }}
          </a>
        </p>
        <p v-if="type === 'image'">
          <a clas="link" v-for="f in requirement.files" @click="downloadApplicationFile(f.id, f.name)">
            {{ f.name }}
          </a>
        </p> -->
      </div>
    </div>
  </div>
</template>
<script>
import Vuex from 'vuex';
import RequirementIcon from 'components/scholarship/requirements/RequirementIcon';
export default {
  components: {
    RequirementIcon
  },
  props: {
    requirement: Object,
  },
  created() {
    //do something after creating vue instance
    if (this.type === 'image') {
      this.requirement.files.forEach(f => this.loadApplicationFile(f.id).then(i => this.images[f.id] = i));
    }
  },
  data() {
    return {
      images: {},
      previewFile: null
    }
  },
  computed: {
    file: ({ requirement }) => requirement.files[0],
    image: () => (file) => imagesStore.state.images[file.id],
    title: ({ requirement }) => requirement.requirement.requirement.name,
    type: ({ requirement }) => requirement.requirement.requirement.type,
    hasContent: ({ type }) => ['text', 'input', 'link'].indexOf(type) !== -1,
  }
}
</script>
<style lang="scss" scoped>
.card {
  .card-header-title {
    font-size: 15px;
    font-weight: 500;
  }
}
</style>
