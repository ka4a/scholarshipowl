<template>
  <div>

    <c-field
      horizontal
      label="Name"
      :message="errors.first('name')"
      :type="errors.has('name') ? 'is-danger' : null">
      <b-input
        name="name"
        placeholder="Full name"
        v-model="item.name"
        v-validate="'max:255'"
      />
    </c-field>

    <c-field
      horizontal
      label="E-mail"
      :message="errors.first('email')"
      :type="errors.has('email') ? 'is-danger' : null">
      <b-input
        name="email"
        v-model="item.email"
        v-validate="'required'"
      />
    </c-field>

    <c-field
      horizontal
      label="Avatar photo"
      :message="errors.first('picture')"
      :type="errors.has('picture') ? 'is-danger' : null">
      <div class="field-picture">
        <b-upload
          drag-drop
          name="picture"
          v-model="fileUpload"
          data-vv-validate-on="input"
          v-validate="'image|size:2000'">
          <div class="content has-text-centered">
            <p>
              <c-icon icon="upload" />
            </p>
            <p>Drop your files here or click to upload</p>
          </div>
        </b-upload>
        <div class="field-picture--image">
          <user-picture :picture="filePreview || item.picture" is-large />
        </div>
      </div>
    </c-field>

  </div>
</template>
<script>
import { createModelStore } from 'lib/store/factory';
import { parseErrors } from 'lib/utils';

import UserPicture from './UserPicture';

export default {
  name: 'UserForm',

  components: {
    UserPicture
  },

  props: {
    user: {
      type: Object,
      required: true,
    }
  },

  data() {
    return {

      fileUpload: null,
      filePreview: null,

      store: createModelStore(this.user.copy(), { updateMethod: 'post' }),
    }
  },

  computed: {
    item: ({ store }) => store.state.item,
  },

  methods: {
    save() {
      return new Promise((resolve, reject) => {
        this.$validator.validateAll()
          .then((result) => {
            if (!result) return reject();

            const form = new FormData();
            let changed = false;

            if (this.fields.name.changed) {
              form.append('data[attributes][name]', this.item.name);
              changed = true;
            }

            if (this.fields.email.changed) {
              form.append('data[attributes][email]', this.item.email);
              changed = true;
            }

            if (this.fields.picture.dirty && this.fileUpload.length) {
              form.append('data[attributes][picture]', this.fileUpload[0]);
              changed = true;
            }

            if (!changed) return;

            this.store.dispatch('save', { form })
              .then(resolve)
              .catch(error => {
                if (error.response && error.response.status === 422) {
                  parseErrors(error.response.data, this.$validator);
                }

                reject(error);
              })
          })
          .catch(reject);
      });
    }
  },

  watch: {
    user(user) {
      this.store.dispatch('setItem', user.copy());
      this.$validator.reset();
    },

    fileUpload(files) {
      if (files.length) {
        this.$validator.validate('picture', files)
          .then((result) => {
            if (result) {
              const reader = new FileReader();
              reader.onload = (e) => this.filePreview = e.target.result;
              reader.readAsDataURL(files[0]);
            } else {
              this.filePreview = null;
            }
          });
      }
    }

  },

}
</script>
<style lang="scss" scoped>
.field-picture {
  display: flex;

  &--image {
    flex: 1;
    align-self: center;
    margin-left: 20px;
  }
}
</style>
