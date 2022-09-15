<template>
  <div v-if="value && value.requirement" class="card requirement">
    <div class="card-header">
      <p class="card-header-title">
        <template v-if="value && value.requirement">
          <requirement-icon :type="value.requirement.type" />
        </template>
        <span>Requirement #{{ number }}</span>
      </p>
      <a href="#" class="card-header-icon" aria-label="more options" @click="$emit('delete')">
        <c-icon :icon="value.id ? 'trash' : 'cancel'" />
      </a>
    </div>
    <div class="card-content">
      <c-field
        label="Requirement type"
        tip="Select what type of information you want to get from the student."
        :message="errors.first('requirement')"
        :type="errors.has('requirement') ? 'is-danger' : null"
      >
        <search-select
          class="requirement-select"
          :clearable="false"
          :options="requirementOptions"
          :value="requirementOptions.find(o => o.value === this.value.requirement.id)"
          @input="value.requirement = requirementTypes.find(r => r.id === $event.value)"
        >
          <template slot="selected-option" slot-scope="option">
            <requirement-icon :type="option.type" />
            <span>{{ option.label }}</span>
          </template>
          <template slot="option" slot-scope="option">
            <requirement-icon :type="option.type" />
            <span>{{ option.label }}</span>
          </template>
        </search-select>
      </c-field>

      <c-field
        label="Requirement title"
        tip="Summary of the requirement data."
        :message="errors.first('title')"
        :type="errors.has('title') ? 'is-danger' : null"
      >
        <b-input
          type="text"
          name="title"
          v-model="value.title"
          v-validate="'required'"
        />
      </c-field>
      <!-- <text-config
        v-if="value.requirement && value.requirement.type === 'text'"
        :value="config"
        @input="value.config = $event"
      />
      <text-config
        v-if="value.requirement && value.requirement.type === 'input'"
        :allow-words="false"
        :value="config"
        @input="value.config = $event"
      />
      <file-config
        v-if="value.requirement && value.requirement.type === 'file'"
        :value="config"
        @input="value.config = $event"
      />
      <file-config
        v-if="value.requirement && value.requirement.type === 'image'"
        :value="config"
        @input="value.config = $event"
      />
      <image-config
        v-if="value.requirement && value.requirement.type === 'image'"
        :value="config"
        @input="value.config = $event"
      /> -->
      <c-field
        label="Requirement description"
        tip="Describe what required from the student."
        :message="errors.first('description')"
        :type="errors.has('description') ? 'is-danger' : null"
      >
        <b-input
          type="textarea"
          name="description"
          v-model="value.description"
          v-validate="'required'"
        />
      </c-field>
    </div>
  </div>
</template>
<script>
import RequirementIcon from 'components/scholarship/requirements/RequirementIcon';
import TextConfig from 'components/scholarship/requirements/TextConfig';
import FileConfig from 'components/scholarship/requirements/FileConfig';
import ImageConfig from 'components/scholarship/requirements/ImageConfig';
import SearchSelect from 'vue-select';

export default {
  components: {
    TextConfig,
    FileConfig,
    ImageConfig,
    SearchSelect,
    RequirementIcon
  },
  props: {
    number: Number,
    value: Object,
  },
  created() {
    if (!this.$store.getters['requirements/loaded']) {
      this.$store.dispatch('requirements/load');
    }

    if (this.value.config && this.value.config.length === 0) {
      this.value.config = {};
    }
  },
  computed: {
    requirementTypes: ({ $store }) => $store.getters['requirements/collection'],
    requirementOptions: ({ $store }) => $store.getters['requirements/collection']
      .map(requirement => ({ label: requirement.name, value: requirement.id, type: requirement.type })),
    config() {
      return Array.isArray(this.value.config) ? {} : this.value.config;
    },
  },
  watch: {
    value: {
      deep: true,
      handler(v) {
        this.$emit('input', v)
      }
    }
  }
}
</script>
<style lang="scss" scoped>
.requirement {
  /deep/ .v-select {
    width: 100%;
    .dropdown-toggle {
      width: 100%;
    }
  }
  .card {
    border-radius: 5px;
    .card-header-title {
      color: white;
      > .icon {
        margin-top: -5px;
        margin-right: 5px;
      }
    }
    .card-header {
      background: #CCD6E6;
      font-size: 18px;
    }
  }
  &:not(:first-child) {
    margin-top: 30px;
  }
}
</style>
