<template>
  <div class="scholarship-attributes">

    <p class="info-block">
      Let’s start to define your scholarship! Insert and edit your scholarship details.
    </p>

    <h3 class="title has-barline">
      Create scholarship
    </h3>

    <c-field
      horizontal
      label="Scholarship title"
      tip="Insert a name for your scholarship (e.g., L&L Law Scholarship, Giving Back Scholarship)"
      :type="errors.has('title') ? 'is-danger' : null"
      :message="errors.first('title')">
      <b-input
        name="title"
        placeholder=""
        v-model="scholarship.title"
        data-vv-validate-on="blur"
        v-validate="'required|min:3|max:255'" />
    </c-field>

    <c-field
      horizontal
      label="Scholarship description"
      tip="Please provide details about your scholarship. (e.g., Jamie’s Studio seeks to help aspiring visual and performing artists. This is why we created the Future Artists Scholarship. Once a month, we will award a $200 scholarship to qualified students. To apply, simply fill out the registration form.)"
      :type="errors.has('description') ? 'is-danger' : null"
      :message="errors.first('description')">
      <b-input
        name="description"
        type="textarea"
        v-model="scholarship.description"
        v-validate="'required'" />
    </c-field>


    <h3 class="title has-barline">
      Award
    </h3>

    <div class="is-clearfix">

      <c-field
        class="is-half"
        horizontal
        label="Amount"
        tip="Specify award amount (e.g., $2,000)"
        :type="errors.has('amount') ? 'is-danger' : null"
        :message="errors.first('amount')">
        <b-input
          name="amount"
          type="number"
          icon="currency-usd"
          v-model="scholarship.amount"
          v-validate="'required'" />
      </c-field>

      <c-field
        class="is-half"
        horizontal
        label="Number of Awards"
        tip="Indicate how many awards you are providing (e.g., 4)"
        :type="errors.has('awards') ? 'is-danger' : null"
        :message="errors.first('awards')">
        <b-input
          name="awards"
          type="number"
          v-model="scholarship.awards"
          v-validate="'required'" />
      </c-field>

    </div>

    <b-field class="is-pulled-right">
      <button v-if="!scholarship.id" class="button is-primary is-go-to-design is-rounded" @click="save()">
        <span>Create & Continue</span>
        <c-icon icon="arrow-right" :class="{ 'is-loading': loading }" />
      </button>
      <template v-else>
        <button class="button is-rounded is-primary" @click="save()">
          <c-icon icon="check-circle" />
          <span>Save</span>
        </button>
      </template>
    </b-field>
  </div>
</template>
<script>
import Datepicker from 'components/datepicker.vue';
import timezones from 'timezones.json';

import { emptyScholarshipTemplate } from 'store/organisation/scholarshipSettings';

export default {
  components: {
    Datepicker,
  },
  data: function() {
    return {

      scholarship: this.$route.params.id ?
        this.$store.state.organisation.scholarshipSettings.item : emptyScholarshipTemplate(),

      recurrencePeriod: null,
      scholarshipStartDate: null,
      scholarshipDeadlineDate: null,
      scholarshipRecurrenceType: null,

    };
  },
  computed: {
    isNewScholarship: ({ $route }) => $route.name === 'scholarships.create',
    loading: ({ $store }) => $store.state.organisation.scholarshipSettings.loading,
    timezones: () => timezones,
    recurrenceTypes() {
      return {
        'Weekly': {
          type: 'week',
          value: 1,
        },
        'Monthly': {
          type: 'month',
          value: 1
        },
        'Quarterly': {
          type: 'month',
          value: 3
        },
      }
    }
  },
  watch: {
    scholarship: {
      immediate: true,
      handler: function({ start, deadline, recurringValue, recurringType }) {
        if (start) {
          this.scholarshipStartDate = new Date(start);
        }
        if (deadline) {
          this.scholarshipDeadlineDate = new Date(deadline);
        }
        if (recurringValue && recurringType) {
          this.recurrencePeriod = {
            type: recurringType,
            value: recurringValue,
          };
        }
      }
    }
  },
  methods: {
    onRecurringPeriodChange({ value, type }) {
      this.scholarship.recurringValue = value;
      this.scholarship.recurringType = type;
    },
    save() {
      this.$validator.validateAll()
        .then((result) => {
          if (result && !this.loading) {
            this.$store.dispatch('organisation/scholarshipSettings/save', { item: this.scholarship })
              .then((scholarship) => {
                if (this.isNewScholarship) {
                  this.$store.dispatch('organisation/scholarships/load');
                  this.$router.push({
                    name: 'scholarships.settings.deadline',
                    params: { id: scholarship.id, isNewScholarship: true }
                  })
                }
                this.$toast.open({
                  type: 'is-success',
                  message: this.isNewScholarship ? 'New scholarship created.' : 'Scholarship attributes updated.'
                })
              })
              .catch((response) => {
                if (response && response.status === 422) {
                  if (response.data && response.data.errors) {
                    response.data.errors.forEach((err) => {
                      if (err.source && err.source.pointer) {
                        const needle = /data\.(attributes|relationships)\./gi;
                        const name = err.source.pointer.replace(needle, '')
                        const field = this.$validator.fields.find(name);

                        if (!field) return;

                        this.$validator.errors.add({
                          id: field.id,
                          field: name,
                          msg: err.detail[0],
                          // scope: this.$options.scope
                        });

                        // field.setFlags({
                        //   invlalid: true,
                        //   valid: false,
                        //   validated: true,
                        // });
                      }
                    })
                  }
                }
              })
          }
        })
    }
  },
}
</script>
<style lang="scss">
@import "node_modules/bulma/sass/utilities/_all";

.scholarship-attributes {
  font-size: 16px;

  .field.is-horizontal {

    .field-label {
      width: 15%;
    }

    .control {
      .select {
        width: 100%;
        select {
          width: 100%;
        }
      }
    }

    &.is-half {

      @include widescreen {
        max-width: 50%;
        &.is-right-column {
          float: right;
        }
        .field-label {
          min-width: 30%;
        }
      }

      // .control {
      //   max-width: 220px;
      // }
      // .field-body {
      //   flex-grow: 1;
      // }
      // .control {
      //   max-width: 220px;
      // }
    }
  }

  .title {
    font-size: 18px;
    font-weight: normal;
  }

  .column.is-deadline {
    .control, .control select {
      width: 180px
    }
  }
  .button.is-go-to-design {
    margin-left: 5px;

    .icon {
      margin-left: 8px;
    }
  }
}
</style>
