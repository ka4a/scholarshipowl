<template lang="html">
  <div class="requirement-wrp">
    <Popup v-if="isInputModalOpen"
      @close="isInputModalOpen = false"
      :applyRequirement="applyRequirement"
      :saving="saving"
      :requirement="requirement" />
    <input type="file" ref="fileInput" @change="uploadFile" style="display: none" />
    <DeleteModal v-if="deleteModalOpen"
      :requirementName="requirement.name"
      @delete="deleteRequirement(); deleteModalOpen = false"
      @close="deleteModalOpen = false" />
    <component :is="requirementComponent"
      :requirement="requirement"
      :application="application"
      :req-set-name="reqSetName"
      :saving="saving"
      :errors="errors"
      @save="applyRequirement"
      @input="({text, cb}) => {applyRequirement(text); afterReqSuc = cb;}"
      @upload="$refs.fileInput.click()"
      @write="isInputModalOpen = true"
      @delete="cb => {deleteModalOpen = true; afterReqDel = cb;}" />
  </div>
</template>

<script>
import { REQ_TYPES }      from "store/scholarships";

import Popup              from "components/Pages/Scholarships/Requirement/TextInputPopup.vue";
import DeleteModal        from "components/Pages/Scholarships/Requirement/DeleteModal.vue";

import Upload from "components/Pages/Scholarships/Requirement/Upload.vue";
import Input from "components/Pages/Scholarships/Requirement/Input.vue";
import Poll from "components/Pages/Scholarships/Requirement/Poll.vue";

export default {
  components: {
    Popup,
    DeleteModal,

    Upload,
    Input,
    Poll
  },
  props: {
    requirement: {type: Object, required: true},
    reqSetName: {type: String}
  },
  data: function() {
    return {
      isInputModalOpen: false,
      deleteModalOpen: false,
      afterReqDel: null,
      afterReqSuc: null,
      saving: false,
      errors: [],
    };
  },
  mounted: function() {
    this.errors = [];
  },
  computed: {
    application() {
      return this.$store.getters["scholarships/getRequirementApplication"](this.requirement);
    },
    requirementComponent() {
      switch(this.requirement.type) {
        case REQ_TYPES.INPUT:
          return Input;
        case REQ_TYPES.SPECIAL_ELIGIBILITY:
        case REQ_TYPES.SURVEY:
          return Poll;
        default:
          return Upload;
      }
    }
  },
  methods: {
    uploadFile(ev) {
      const file = ev.target.files[0];

      return this.applyRequirement({ file }, () => {
        ev.target.value = "";
      });
    },
    applyRequirement(details, cb) {
      const data = { details, requirement: this.requirement };

      const onResponse = (response) => {
        this.handleErrors(response);
        this.saving = false;
        if (typeof cb === "function") {
          cb(response);
        }

        if(response.ok && this.afterReqSuc) {
          this.afterReqSuc();
          this.afterReqSuc = null;
        }

        this.$emit('global', {ev: 'item-state-change'});
      };

      this.saving = true;
      this.errors = [];

      return this.$store
        .dispatch("scholarships/applyRequirement", data)
        .then(onResponse)
        .catch(onResponse);
    },
    deleteRequirement() {
      this.errors = [];
      this.saving = true;

      if (this.application) {
        this.$store.dispatch("scholarships/deleteRequirement", this.requirement)
          .then(() => {
            this.saving = false;
            if(this.afterReqDel) {
              this.afterReqDel();
              this.afterReqDel = null;
            }
            this.$emit('global', {ev: 'item-state-change'}); })
          .catch(() => { this.saving = false; });
      }
    },
    handleErrors(response) {
      if (response.status === 400 && response.data.error) {
        const error = response.data.error;

        if (typeof error === "object") {
          Object.keys(error).map(field => {
            if (Array.isArray(error[field])) {
              error[field].map(item => this.errors.push(item));
            }
          });
        }
      }
    }
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';

$grey: #C2C2C2;

.requirement-wrp {
  margin-top: 20px;
}

.requirement-loader {
  border: 1px dashed #D8D8D8;
  border-radius: 2px;
  box-sizing: border-box;
  height: 50px;

  @include breakpoint($m) {
    height: 70px;
  }
}

.requirement-error {
  @include flexbox();
  @include align-items(top);
  margin-bottom: 15px;

  &__icon {
    margin-right: 10px;
    width: 16px;
    height: 16px;
    min-width: 16px;
    display: inline-block;
    background-color: $burnt-sienna;
    border-radius: 4px;
    text-align: center;
    line-height: 14px;

    &:before {
      content: '!';
      color: white;
      font-weight: 700;
      font-size: 13px;
    }

    @include breakpoint($s) {
      min-width: 20px;
      width: 20px;
      height: 20px;
      line-height: 20px;

      &:before {
        font-size: 16px;
      }
    }
  }

  &__text {
    color: $burnt-sienna;
    font-size: 14px;
    line-height: 1.4em;
  }

  .input-requirement {
    margin-top: 20px;

    @include breakpoint($s) {
      margin-top: 25px;
    }

    @include breakpoint($m) {
      margin-top: 30px;
    }
  }

  // modification near elements
  & + .input-requirement {
    .input-requirement__input,
    .input-requirement__btn {
      border: 1px solid $burnt-sienna;
    }
  }

  & + .content-requirement {
    border: 1px solid $burnt-sienna;
  }
}
</style>
