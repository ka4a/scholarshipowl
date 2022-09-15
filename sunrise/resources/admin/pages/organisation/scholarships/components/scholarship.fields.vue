<template>
  <div class="scholarship-fields">
    <p class="info-block">
      Select fields that must be filled out by students during application.</br>
      <span class="is-size-7">* Name, e-mail, phone and state information is required.</span>
    </p>

    <ul class="fields-list">
      <li class="fields-list--item-container" v-for="field in allFields">
        <div class="fields-list--item" :class="{ 'is-opened-conditions': openedConditions[field.id] }">
          <b-field class="field-switch">
            <b-switch
              :value="enabledFields[field.id]"
              :disabled="canRemoveField(field)"
              @input="toggleScholarshipField($event, field)"
            >
              <span>{{ field.name }}</span>
            </b-switch>
          </b-field>
          <field-conditions-view v-if="scholarshipFieldHasConditions(field)" :scholarship-field="getScholarshipField(field.id)" />
          <template v-if="enabledFields[field.id]">
            <add-button
              label="Add condition"
              v-if="canHaveConditions(field) && !scholarshipFieldHasConditions(field)"
              @click="openConditions(field)"
            />
            <div class="conditions-actions" v-if="scholarshipFieldHasConditions(field) && !openedConditions[field.id]">
              <button class="button is-grey is-round" @click="clearConditions(field)">
                <b-icon icon="close" />
              </button>
              <button class="button is-grey is-round" @click="openConditions(field)">
                <c-icon icon="pencil" />
              </button>
            </div>
            <b-field v-if="!canRemoveField(field)" class="is-field-optional">
              <b-switch size="is-small"
                        :value="scholarshipFieldIsOptional(field)"
                        @input="setFieldOptional(field, $event)">
                <span>Optional</span>
              </b-switch>
            </b-field>
          </template>
        </div>
        <div v-show="enabledFields[field.id] && openedConditions[field.id]"
          class="fields-list--item-conditions animated slideInDown">
          <field-conditions
            v-if="!!getScholarshipField(field.id) && canHaveConditions(field)"
            :scholarshipField="getScholarshipField(field.id)"
            @input="changedConditions"
            @close="closedConditions"
          />
        </div>
      </li>
    </ul>

    <b-field class="fields-actions is-pulled-right">
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
</template>
<script>
import Grid from 'components/grid';
import FieldConditions from 'components/scholarship/FieldConditions';
import FieldConditionsView from 'components/scholarship/FieldConditionsView';

import AddButton from 'components/buttons/AddButton';

import { JsonaModel } from 'lib/jsona';
import { createStore } from 'lib/store/grid-store';

export default {
  name: 'ScholarshipSettingsFields',
  components: {
    Grid,
    AddButton,
    FieldConditions,
    FieldConditionsView,
  },
  created() {
    this.store.dispatch('load')
      .then((fields) => {
        this.enabledFields = fields.reduce((acc, curr) => {
          return { ...acc, [curr.field.id]: true };
        }, {});
      });

    if (!this.$store.state.fields.loaded) {
      this.$store.dispatch('fields/load');
    }
  },
  data() {
    const store = createStore('scholarship_template_field', {
      path: () => `scholarship_template/${this.$route.params.id}/fields`
    });

    return {
      store,
      enabledFields: {},
      openedConditions: {},
      showNewField: false,
    }
  },
  methods: {
    setFieldOptional(field, value) {
      const scholarshipField = this.getScholarshipField(field.id);
      Vue.set(scholarshipField, 'optional', !!value);
    },
    openConditions(field) {
      Vue.set(this.openedConditions, field.id, true);
    },
    closedConditions({ field }) {
      Vue.set(this.openedConditions, field.id, false);
    },
    changedConditions({ eligibilityType, eligibilityValue, field }) {
      const scholarshipField = this.getScholarshipField(field.id);
      Vue.set(scholarshipField, 'eligibilityType', eligibilityType);
      Vue.set(scholarshipField, 'eligibilityValue', eligibilityValue);
      Vue.set(this.openedConditions, field.id, false);
    },
    clearConditions({ id }) {
      const scholarshipField = this.getScholarshipField(id);
      Vue.set(scholarshipField, 'eligibilityType', null);
      Vue.set(scholarshipField, 'eligibilityValue', null);
    },
    toggleScholarshipField(toggle, field) {
      Vue.set(this.enabledFields, field.id, toggle);

      if (toggle && !this.getScholarshipField(field.id)) {
        this.store.dispatch('addItem', JsonaModel.new('scholarship_template_field', null, { field }));
      }
    },
    save() {
      const data = Object.keys(this.enabledFields)
        .filter(id => this.enabledFields[id])
        .map(id => this.getScholarshipField(id));

      this.store.dispatch('save', data)
        .then((fields) => {
          this.enabledFields = fields.reduce((acc, curr) => ({ ...acc, [curr.field.id]: true }), {});
          this.$toast.open({ type: 'is-success', message: 'Scholarship fields settings updated.' });
          if (this.$route.params.isNewScholarship) {
            this.$router.push({
              name: 'scholarships.settings.requirements',
              params: { id: this.scholarship.id, isNewScholarship: true }
            });
          }
        })
    }
  },
  computed: {

    loading: ({ $store }) => $store.getters['organisation/scholarshipSettings/loading'],
    scholarship: ({ $store }) => $store.getters['organisation/scholarshipSettings/item'],

    canHaveConditions: ({ $store }) => ({ id }) => $store.getters['fields/hasEligibilityRule'](id),

    canRemoveField: () => ({ id }) => ['name', 'email', 'phone', 'state'].indexOf(id) !== -1,

    scholarshipFieldHasConditions: ({ getScholarshipField }) => ({ id }) => {
      const field = getScholarshipField(id);
      return !!(field && field.eligibilityValue && field.eligibilityType);
    },

    scholarshipFieldIsOptional: ({ getScholarshipField }) => ({ id }) => {
      const field = getScholarshipField(id);
      return !!field.optional;
    },

    scholarshipFields: ({ store }) => store.state.collection,

    getScholarshipField: ({ scholarshipFields }) => (id) =>
      scholarshipFields.filter(({ field }) => field.id === id)[0],

    allFields: ({ $store }) => {
      const sorting = [
        'name',
        'email',
        'phone',
        'state'
      ].reverse();

      return $store.getters['fields/collection']
        .sort((a, b) => {
          const ia = sorting.indexOf(a.id);
          const ib = sorting.indexOf(b.id);
          return ia === ib ? 0 : (ia > ib ? -1 : 1);
        });
    },
  },
}
</script>
<style lang="scss" scoped>
@import "~scss/variables.scss";

.fields-list {
  &--item {
    background: #FFFFFF;
    position: relative;
    display: flex;
    align-items: center;
    z-index: 2;
    height: 64px;
    border: 1px solid #BBBBBB;
    border-radius: 3px;
    padding: 15px 12px;
    > .field {
      &.field-switch {
        min-width: 256px;
        margin: 0;
      }
      &.is-field-optional {
        margin-left: auto;
      }
    }
    &.is-opened-conditions {
      border-bottom-left-radius: 0;
      border-bottom-right-radius: 0;
    }
  }
  &--item-container {
    overflow: hidden;
    &:not(:first-child) {
      margin-top: 10px;
    }
  }
  &--item-conditions {
    position: relative;
    background: #F5F7FA;
    padding: 15px 12px;
    border: 1px solid #D7D7D7;
    border-radius: 3px;
    z-index: 1;
    border-top: none;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
  }
}

.conditions-actions {
  flex: 1;
  display: flex;
  flex-direction: row-reverse;
  .button:not(:last-child) {
    margin-left: 7px;
  }
  & + .field.is-field-optional {
    margin-left: 15px;
  }
}

.button {
  &.is-grey {
    background: #8C8C8C;
  }
}
.fields-actions {
  margin-top: 20px;
}
.fields-table {
  /deep/ tfoot tr:hover {
    background: none;
  }
}
</style>
