<template>
  <div>
    <b-loading :active="loading" :is-full-page="false" />
    <p class="info-block">
      <span>Add your scholarship requirements, if needed.</span><br />
      <small>* Requirement must be reviewed before winner draw.</small>
    </p>
    <div class="columns">
      <div class="column">
        <div class="add-requirement" :class="{ 'is-empty': !requirements.length }" @click="addRequirement">
          <c-icon icon="plus" />
          <span>Add requirement</span>
        </div>
        <ul>
          <li v-for="(requirement,i) in requirements" class="requirement is-new">
            <requirement-form
              :number="i + 1"
              :ref="`requirement-${i}`"
              :value="requirements[i]"
              @delete="removeRequirement(requirement)"
            />
          </li>
        </ul>
      </div>
      <div class="column">
        <div class="helper-right">
          <strong>About requirements.</strong>
          <p>If you need any additional information from your applicants, you can add it as a requirement fields, by chosing field type (text or file upload) and adding a brief description.</p>
          <strong class="helper-right__title">Current requirements: {{ requirements.length }}</strong>
          <ul class="requirements-list">
            <li v-for="(requirement, i) in requirements" v-if="requirement.requirement" @click="scrollToRequirement(i)">
              <span>#{{ i + 1 }}</span>
              <span class="requirements-list__title">{{ requirement.requirement.name }}</span>
              <c-icon icon="close"  @click.stop="removeRequirement(requirement)"/>
            </li>
          </ul>
        </div>
        <b-field class="fields-actions is-pulled-right mt-10">
          <button class="button is-rounded is-primary" @click="save()">
            <template v-if="$route.params.isNewScholarship">
              <span>Save & Continue</span>
              <c-icon icon="arrow-right" :class="{ 'is-loading': loading }" />
            </template>
            <template v-else>
              <c-icon icon="check-circle" />
              <span>Save</span>
            </template>
          </button>
        </b-field>
      </div>
    </div>

  </div>
</template>
<script>
import RequirementForm from 'components/scholarship/RequirementForm';
import { createStore } from 'lib/store/grid-store';
import { JsonaModel } from 'lib/jsona';

export default {
  components: {
    RequirementForm
  },
  created() {
    this.$store.dispatch('requirements/load');
    this.store.dispatch('load');
  },
  data() {
    const store = createStore('scholarship_template_requirement', {
      path: () => `scholarship_template/${this.$route.params.id}/requirements`
    });

    return {
      store,
      newRequirement: { open: false }
    }
  },
  computed: {
    requirements: ({ store }) => store.state.collection,
    requirementTypes: ({ $store }) => $store.getters['requirements/collection'],
    loading: ({ store, $store }) => store.getters['loading'] || $store.getters['requirements/loading'],
  },
  methods: {
    addRequirement() {
      this.store.dispatch('addItem',
        JsonaModel.new(
          'scholarship_template_requirement', {
            title: null,
            description: null,
            config: {}
          }, {
            requirement: this.requirementTypes.find(r => r.id === 'essay'),
          }
        )
      )
    },
    removeRequirement(requirement) {
      this.store.dispatch('removeItem', requirement);
    },
    scrollToRequirement(i) {
      this.$scrollTo(this.$refs[`requirement-${i}`][0].$el.closest('.requirement'));
    },
    save() {
      Promise.all(this.requirements.map((v, i) => this.$refs[`requirement-${i}`][0].$validator.validateAll()))
        .then((result) => result.reduce((acc, curr) => acc && curr, true))
        .then((result) => {
          if (result) {
            this.store.dispatch('save')
              .catch(() => this.$scrollTo(document.querySelector('.help.is-danger').closest('.card')))
              .then(() => {
                this.$toast.open({ type: 'is-success', message: 'Scholarship requirements updated' })
                if (this.$route.params.isNewScholarship) {
                  this.$router.push({
                    name: 'scholarships.settings.design',
                    params: { id: this.$route.params.id, isNewScholarship: true }
                  });
                }
              })
          } else {
            this.$scrollTo(document.querySelector('.help.is-danger').closest('.card'))
          }
        });
    }
  }
}
</script>
<style lang="scss" scoped>
/deep/ .requirement {
  &:not(:first-child) {
    margin-top: 13px;
  }
}
.helper-right {
  background: #F5F7FA;
  border-radius: 5px;
  padding: 30px 40px;
  .requirements-list {
    > li {
      display: flex;
      justify-content: space-between;
      padding: 7px 11px;
      background: rgba(255, 255, 255, 0.5);
      border-radius: 5px;
      &:hover {
        cursor: pointer;
        background: white;
      }
      &:not(:first-child) {
        margin-top: 5px;
      }
    }
    &__title {
      text-overflow: ellipsis;
      overflow: hidden;
      white-space: nowrap;
    }
  }
  &__title {
    display: inline-block;
    margin: 6px 0;
  }
}
.add-requirement {
  display: flex;
  justify-content: center;
  padding: 13px 0;
  margin-bottom: 13px;
  text-align: center;
  border: 2px dashed #CCD6E6;
  box-sizing: border-box;
  border-radius: 5px;
  cursor: pointer;
  color: #CCD6E6;
  .icon {
    margin-right: 11px;
  }
  &:hover {
    cursor: pointer;
    background: #F7F9FB;
  }
  &.is-empty {
    padding: 60px 170px;
    flex-direction: column;
    .icon {
      margin: 6px auto 0 auto;
    }
  }
}
</style>
