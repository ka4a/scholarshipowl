<template>
  <div class="columns">
    <div class="column">
      <daterange
        @input="onChange"
        :firstDate="start"
        :secondDate="deadline"
        label="Select start and deadline dates for first scholarship instance."

        :message="daterangeErrors"
        :type="daterangeErrors ? 'is-danger' : null"
      />
      <c-field label="Next scholarship should be started after:"
        :message="[errors.first('periodType'), errors.first('periodValue')]"
        :type="errors.has('periodType') || errors.has('periodValue') ? 'is-danger' : null">
        <div>
          <div class="period-inputs">
            <b-input
              class="period-value"
              name="periodValue"
              v-model="periodValue"
              v-validate="'required|min_value:1|numeric'"
              data-vv-as="period value"
              length="5"
              type="number"
            />
            <b-select
              v-model="periodType"
              class="period-type"
              v-validate="'required'"
              data-vv-as="period type"
              name="periodType"
            >
              <option v-for="name, type in periodTypes" :value="type">
                {{ name }}
              </option>
            </b-select>
          </div>
        </div>
      </c-field>
      <c-field
        label="Amount of scholarship repetitions"
        tip="How many times scholarship will repeat itself."
        :message="errors.first('occurrences')"
        :type="errors.has('occurrences') ? 'is-danger' : null"
      >
        <b-input
          name="occurrences"
          type="number"
          v-model="occurrences"
          placeholder="Leave field empty to repeat infinitely"
          v-validate="'min_value:2|numeric'"
        />
      </c-field>
      <timezone v-model="timezone" />
      <!-- <p class="help">
        <u>Example:</u> First instance start date is Janurary 12 and it ends at March 16. Next instance need to start on April 12 and run for another 2 months and 4 days and finish at June 16.
For this configurations we need to select start date as January 12, deadline date as March 16, and set recurrence period of 3 months.
      </p> -->
    </div>
    <div class="column">
      <div class="helper-right">
        <strong>Scholarship summary</strong>
        <p class="help">
          This is an advanced publishing modifier, which will help you to set your scholarship publishing/unpublishing dates flexibly.
        </p>
        <p v-if="start && deadline" class="help">Your scholarship will run <strong>from {{ value.start | moment('MM/DD/YYYY') }} till {{ value.deadline | moment('MM/DD/YYYY') }}</strong>.</p>
        <prediction class="mt-20" :config="config" />
      </div>
    </div>
  </div>
</template>
<script>
import Daterange from 'components/common/daterange';
import Timezone from './Timezone';
import Prediction from './Prediction';

export default {
  components: {
    Daterange,
    Timezone,
    Prediction
  },
  props: {
    value: {
      type: Object,
      default: () => ({
        start: null,
        deadline: null,
        periodValue: null,
        periodType: null,
        occurrences: null,
        timezone: null
      })
    }
  },
  created() {
    //do something after creating vue instance
    this.$validator.attach({
      name: 'daterange',
      rules: 'required',
      getter: () => (this.start && this.deadline) ? 1 : null
    })
  },
  data() {
    return {
      start: this.value.start ? new Date(this.value.start) : null,
      deadline: this.value.deadline ? new Date(this.value.deadline) : null,
      periodValue: this.value.periodValue || 1,
      periodType: this.value.periodType || 'month',
      occurrences: this.value.occurrences || null,
      timezone: this.value.timezone,
    }
  },
  computed: {
    periodTypes: () => ({
      day: 'Day',
      week: 'Week',
      month: 'Month',
      year: 'Year',
    }),
    daterangeErrors() {
      if (this.errors.has('daterange')) {
        return 'Please select start and deadline dates'
      }
      if (this.errors.has('start') || this.errors.has('deadline')) {
        return [this.errors.first('start'), this.errors.first('deadline')].join(' ');
      }
      return null;
    },
    config() {
      const format = (date, d) => date ? date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate() : d;

      return {
        type: 'advanced',
        start: format(this.start, null),
        deadline: format(this.deadline, null),
        periodValue: this.periodValue,
        periodType: this.periodType,
        occurrences: this.occurrences,
        timezone: this.timezone,
      }
    }
  },
  methods: {
    onChange([start, end]) {
      this.start = start;
      this.deadline = end;
    },
    triggerInput(ovverride) {
      this.$emit('input', { ...this.config, ...ovverride });
    }
  },
  watch: {
    deadline(deadline) {
      this.triggerInput({ deadline });
    },
    start(start) {
      this.triggerInput({ start });
    },
    periodType(periodType) {
      this.triggerInput({ periodType });
    },
    periodValue(periodValue) {
      this.triggerInput({ periodValue });
    },
    occurrences(occurrences) {
      this.triggerInput({ occurrences });
    },
    timezone(timezone) {
      this.triggerInput({ timezone });
    },
  }
}
</script>
<style lang="scss" scoped>
.period-inputs {
  display: flex;
  max-width: 420px;
  .period-value {
    flex: 1;
    margin-right: 5px;
  //   width: 80px;
  //   margin-right: 12px;
  }
  .period-type {
    flex: 1;
    margin-left: 5px;
  //   width: 100%;
  }
}
</style>
