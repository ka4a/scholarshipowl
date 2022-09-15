<template>
  <div class="urls-list">
    <p class="info-block">Please provide URL to your website.</p>
    <b-field label="Scholarship URL"
      :type="errors.has('url') ? 'is-danger' : null"
      :message="errors.first('url')">
      <b-input
        name="url"
        v-model="item.scholarshipUrl"
        v-validate="'required|url'"
        data-vv-as="Scholarship URL"
        placeholder="https://example.com"
      />
    </b-field>
    <b-field label="Scholarship privacy policy URL (Optional)"
      :type="errors.has('pp-url') ? 'is-danger' : null"
      :message="errors.first('pp-url')">
      <b-input
        name="pp-url"
        v-model="item.scholarshipPPUrl"
        v-validate="'url'"
        data-vv-as="Scholarship privacy policy URL"
        placeholder="https://example.com/privacy-policy"
      />
    </b-field>
    <b-field label="Scholarship terms of use URL (Optional)"
      :type="errors.has('tos-url') ? 'is-danger' : null"
      :message="errors.first('tos-url')">
      <b-input
        name="tos-url"
        v-model="item.scholarshipTOSUrl"
        v-validate="'url'"
        data-vv-as="Scholarship terms of use URL"
        placeholder="https://example.com/terms-of-use"
      />
    </b-field>
    <b-field class="is-pulled-right mt-20">
      <template v-if="$route.params.isNewScholarship">
        <button class="button is-rounded is-primary" @click="save">
          <span>Save & Continue</span>
          <c-icon icon="arrow-right" :class="{ 'is-loading': loading }" />
        </button>
      </template>
      <template v-else>
        <button class="button is-rounded is-primary" @click="save">
          <c-icon icon="check-circle" :class="{ 'is-loading': loading }" />
          <span>Save</span>
        </button>
      </template>
    </b-field>
  </div>
</template>
<script>
import { JsonaModel } from 'lib/jsona';

export default {
  data: function() {
    return {
      item: JsonaModel.instance(
        this.$route.params.id,
        'scholarship_template', {
          scholarshipUrl: null,
          scholarshipPPUrl: null,
          scholarshipTOSUrl: null,
        }
      ),
    };
  },
  computed: {
    loading: () => false,
    template: ({ $store }) => $store.getters['organisation/scholarshipSettings/item'],
  },
  created() {
    this.item.scholarshipUrl = this.template.scholarshipUrl;
    this.item.scholarshipPPUrl = this.template.scholarshipPPUrl;
    this.item.scholarshipTOSUrl = this.template.scholarshipTOSUrl;
  },
  methods: {
    save() {
      this.$validator.validateAll()
        .then(result => {
          if (result) {
            this.$store.dispatch('organisation/scholarshipSettings/save', { item: this.item })
              .then((template) => {
                this.$toast.open({ message: 'Web page details saved!', type: 'is-success' });
                this.$emit('saved', template);
              })
              .catch((rsp) => {
                this.$scrollTo(document.querySelector('.help.is-danger'));
                if (rsp && rsp.status === 422) {
                  this.JSONAPIparseErrors(rsp.data, this.$validator);
                }
              });
          }
        })
    },
  },
  watch: {
    template({ scholarshipUrl, scholarshipPPUrl, scholarshipTOSUrl }) {
      this.item.scholarshipUrl = scholarshipUrl;
      this.item.scholarshipPPUrl = scholarshipPPUrl;
      this.item.scholarshipTOSUrl = scholarshipTOSUrl;
    }
  }
};
</script>
