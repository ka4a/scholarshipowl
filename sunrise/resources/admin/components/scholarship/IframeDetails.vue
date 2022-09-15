<template>
  <div class="box is-clearfix">
    <p>
      <pre v-text="buildCode(iframe)" />
    </p>
    <b-field label="Width"
      :message="errors.first('width')"
      :type="errors.has('width') ? 'is-danger' : null">
      <b-input
        type="text"
        name="width"
        v-model="store.state.item.width"
        v-validate="'max:255'"
      />
    </b-field>
    <b-field label="Height"
      :message="errors.first('height')"
      :type="errors.has('height') ? 'is-danger' : null">
      <b-input
        type="text"
        name="height"
        v-model="store.state.item.height"
        v-validate="'max:255'"
      />
    </b-field>
    <b-field label="Source"
      :message="errors.first('source')"
      :type="errors.has('source') ? 'is-danger' : null">
      <b-input
        type="text"
        name="height"
        v-model="store.state.item.source"
        v-validate="'max:255'"
      />
    </b-field>
    <b-field class="is-pulled-right">
      <button class="button is-success" @click="save">
        <b-icon icon="content-save" />
        <span>Save</span>
      </button>
    </b-field>
  </div>
</template>
<script>
import { createModelStore } from 'lib/store/item-store';

export default {
  props: {
    iframe: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      store: createModelStore(this.iframe),
    }
  },
  methods: {
    buildCode(iframe) {
      return `<script id='${iframe.id}' src='${iframe.links.src}'><\/script>`;
    },
    save() {
      this.$validator.validateAll()
        .then(result => {
          if (result) {
            this.store.dispatch('save')
              .then(() => this.$toast.open({ message: `Iframe "${this.iframe.id}" updated!`, type: 'is-success'}))
              .then(() => this.$store.dispatch('organisation/scholarshipSettings/iframes/load'))
              .catch((err) => {
                if (err.response && err.response.status === 422) {
                  this.JSONAPIparseErrors(err.response.data, this.$validator);
                }
                this.$nextTick(() => this.$scrollTo(document.querySelector('.help.is-danger')));
              })
          }
        })
    }
  },
};
</script>
