<template>
  <div class="scholarshipWinner">

    <b-field class="has-centered-text">
      <figure class="image is-300x300">
        <img v-if="scholarshipWinnerImagePreview" :src="scholarshipWinnerImagePreview" />
        <img v-else-if="scholarshipWinner.image_url" :src="scholarshipWinner.image_url" />
        <div v-else class="image__empty">
          <icon-camera class="icon is-64x64"/>
        </div>
        <b-loading :is-full-page="false" :active="loadingFace" />
      </figure>
    </b-field>

    <b-field>
      <b-notification :active.sync="loadingFaceFailed" type="is-warning" has-icon>
        <h5 class="title is-5">Sorry we can't detect winner's face on photo.</h5>
      </b-notification>
    </b-field>

    <b-field class="file" label="Image"
      :type="this.error['data.relationships.image.data'] ? 'is-danger' : null"
      :message="this.error['data.relationships.image.data'] ? this.error['data.relationships.image.data'] : null">
      <b-upload v-model="scholarshipWinnerImage" @input="previewUploaded">
        <a class="button">
          <icon-upload />
          <span>Upload</span>
        </a>
      </b-upload>
      <span class="file-name" v-if="scholarshipWinnerImage && scholarshipWinnerImage.length">
        {{ scholarshipWinnerImage[0].name }}
      </span>
      <span class="file-name" v-else-if="scholarshipWinner.image && scholarshipWinner.image.name">
        {{ scholarshipWinner.image.name }}
      </span>
    </b-field>

    <b-field label="Name"
      :type="this.error['data.attributes.name'] ? 'is-danger' : null"
      :message="this.error['data.attributes.name'] ? this.error['data.attributes.name'] : null">
      <b-input type="text" v-model="scholarshipWinnerStore.state.item.name" />
    </b-field>

    <b-field label="Testimonial"
      :type="this.error['data.attributes.testimonial'] ? 'is-danger' : null"
      :message="this.error['data.attributes.testimonial'] ? this.error['data.attributes.testimonial'] : null">
      <b-input type="textarea" v-model="scholarshipWinnerStore.state.item.testimonial" />
    </b-field>

    <b-field class="has-text-centered">
      <button @click="saveScholarshipWinner" class="button is-primary" :class="{ 'is-loading': loading }" :disabled="loading || loadingFace">
        <span v-if="!scholarshipWinner.id">
          Publish
        </span>
        <span v-else>
          Save
        </span>
        <span class="">
        </span>
      </button>
    </b-field>

  </div>
</template>
<script>
import Vuex from 'vuex';
import { ItemStore } from 'lib/store/factory';
import { JsonaModel } from 'lib/jsona';
import { b64toFile } from 'lib/utils';

import IconUpload from 'icon/upload.vue';
import IconCamera from 'icon/photo-camera.vue';

export default {
  components: {
    IconUpload,
    IconCamera,
  },
  props: {
    winner: Object,
    image: Object
  },
  data: function() {
    return {
      error: {},
      loading: false,
      loadingFace: false,
      loadingFaceFailed: false,

      scholarshipWinnerImage: [],
      scholarshipWinnerImagePreview: null,
      scholarshipWinnerStore: new Vuex.Store(
        ItemStore('scholarship_winner', {
          updateMethod: 'POST',
          item: this.winner.scholarship_winner ?
            this.winner.scholarship_winner :
            JsonaModel.new(
              'scholarship_winner',
              {
                name: this.winner.name.replace(/^(.+\s[A-Z])(.*)$/gi, '$1.'),
                testimonial: this.winner.testimonial,
              },
              {
                image: null,
                applicationWinner: this.winner
              }
            ),
        })
      ),
    }
  },
  created() {
    if (!this.scholarshipWinner.image) {
      this.loadingFace = true;
      this.$http.get(`/api/application_winner/${this.winner.id}/face`)
        .finally(() => this.loadingFace = false)
        .then(({ data }) => data)
        // .then(({ mime, base64 }) => this.scholarshipWinnerImagePreview = `data:${mime};base64,${base64}`)
        .then(({ mime, base64, name }) => this.scholarshipWinnerImage.push(b64toFile(base64, mime, name)))
        .then(() => this.previewUploaded())
        .catch(() => this.loadingFaceFailed = true);
    }
  },
  computed: {
    scholarshipWinner: ({ scholarshipWinnerStore }) => scholarshipWinnerStore.state.item,
  },
  methods: {
    previewUploaded() {
      const files = this.scholarshipWinnerImage;
      this.loadingFaceFailed = false;
      if (files && files[0]) {
        const reader = new FileReader();
        reader.onload = () => this.scholarshipWinnerImagePreview = reader.result;
        reader.readAsDataURL(files[0]);
      }
    },
    saveScholarshipWinner() {
      const sw = this.scholarshipWinner;

      this.loading = true;
      this.loadingFaceFailed = false;
      this.error = {};

      var form = new FormData();
      form.append('data[attributes][name]', sw.name);
      form.append('data[attributes][testimonial]', sw.testimonial || '');

      if (this.scholarshipWinnerImage && this.scholarshipWinnerImage.length) {
        form.append('data[relationships][image][data]', this.scholarshipWinnerImage[0]);
      } else if (sw.image && sw.image.id) {
        form.append('data[relationships][image][data][id]', sw.image.id);
        form.append('data[relationships][image][data][type]', 'scholarship_file');
      }

      form.append('data[relationships][applicationWinner][data][id]', this.winner.id);
      form.append('data[relationships][applicationWinner][data][type]', 'application_winner');

      this.scholarshipWinnerStore.dispatch('save', { form })
        .then(() => {
          this.loading = false;
          this.scholarshipWinnerImagePreview = null;
          this.$store.dispatch('winners/winnerPage/reload');
          this.$toast.open({
            message: 'Winner testimonial published!',
            type: 'is-success',
          });
        })
        .catch(response => {
          this.loading = false;
          if (response && response.status === 422) {
            if (response.data && response.data.errors) {
              response.data.  errors.forEach((err) => {
                if (err.source && err.source.pointer) {
                  Vue.set(this.error, err.source.pointer, err.detail);
                }
              })
            }
            return;
          }

          throw response;
        })
    },
  },
}
</script>
<style lang="scss" scoped>
.scholarshipWinner {
  .label {
    color: #828282;
    font-weight: normal;
  }
  label.label {
    margin: auto 0;
  }
  .image {
    margin: auto;
    width: 300px;
    height: 300px;

    > img {
      width: 300px;
      height: 300px;
      border-radius: 50%;
    }
    &__empty {
      margin: auto;
      background-color: #C4C4C4;
      width: 300px;
      height: 300px;
      border-radius: 50%;
      text-align: center;
      .icon {
        width: 48px;
        height: 100%;
      }
    }
  }
  /deep/ .field {
    .label {
      margin-bottom: 8px  ;
    }
    &.file {
      .button {
        margin-left: 24px;
      }
    }
    &.is-horizontal {
      margin-bottom: 0;
    }
  }
}
</style>
