<template lang="html">
  <div v-show="!hidden" class="coreg-basic">
    <checkbox :name="name" v-model="coregChecked" @input="emitCoregData">
      <template slot="label">
        <img v-if="name.toLowerCase() === 'cappex'"
             class="coreg__cappex-logo"
             style="width: 86px; margin-right: 5px; vertical-align: bottom;" src="./capex-logo.svg" />
        <span v-html="text"></span>
      </template>
    </checkbox>

    <checkbox class="coreg-basic__sms" v-if="sms" :name="'sms-' + name" v-model="shouldSendSMS" @input="emitCoregData">
      <span slot="label" v-html="sms.text"></span>
    </checkbox>

    <input v-if="js" ref="leadid_token" id="leadid_token" name="universal_leadid" type="hidden" />
    <script-injector v-if="js" :source="js" />

    <noscript v-if="noscript"><img :src="noscript" /></noscript>
  </div>
</template>

<script>
import ScriptInjector from "components/Common/ScriptInjector.vue";
import Checkbox       from "components/Common/CheckBoxes/CheckBoxBasic.vue";

export default {
  name: "coreg",
  components: {
    Checkbox,
    ScriptInjector,
  },
  props: {
    hidden:           { type: Boolean, default: false },
    id:               { type: Number, required: true },
    name:             { type: String, required: true },
    text:             { type: String, required: true },
    extra:            { type: Array,  default: null },
    js:               { type: String },
    noscript:         { type: String },
    sms:              { type: Object, default: null },
    checked:          { type: Boolean, default: false }
  },
  created() {
    if(this.hidden) {
      setTimeout(() => {
        this.coregChecked = true;
      }, 1500);
    }

    this.coregChecked = this.checked;

    if(this.sms && this.sms.checked) {
      this.shouldSendSMS = !!this.sms.checked;
    }

    this.emitCoregData();
  },
  data() {
    return {
      shouldSendSMS: false,
      coregChecked: false,
      data: {}
    }
  },
  methods: {
    emitCoregData() {
      let name = this.name.toLowerCase();

      if(!this.coregChecked) {
        this.$emit("coreg", name, null);
        return;
      }

      this.data["checked"] = 1;
      this.data["id"] = this.id;

      if("leadid_token" in this.$refs && this.$refs["leadid_token"].value) {
        this.data["extra"]["universal_leadid"] = this.$refs["leadid_token"].value;
      }

      if(this.extra) {
        this.data["extra"] = {};

        this.extra.forEach(field => {
          this.data["extra"][field.name] = field.value;
        });
      }

      if(this.sms) {
        this.data["extra"]["sms"] = Number(this.shouldSendSMS);
      }

      this.$emit("coreg", name, this.data);
    }
  },
};
</script>

<style lang="scss">
  .coreg-basic {
    &__sms {
      @include breakpoint($m) {
        margin-top: 2px;
      }
    }
  }
</style>
