<template>
  <div class="columns">
    <div class="column">
      <daterange
        @input="triggerInput"
        :firstDate="start"
        :secondDate="deadline"
        label="Start and deadline dates"

        :message="daterangeErrors"
        :type="daterangeErrors ? 'is-danger' : null"
        v-validate.disable="'required|validateDaterange'"
        data-vv-name="dates"
      />
      <timezone v-model="timezone" />
    </div>
    <div class="column">
      <div class="helper-right">
        <strong>Scholarship summary</strong>
        <p v-if="!value.start || !value.deadline" class="help">Click the exact date you want your scholarship to be published and unpublished.</p>
        <p v-else class="help">Your scholarship will run <strong>from {{ value.start | moment('MM/DD/YYYY') }} till {{ value.deadline | moment('MM/DD/YYYY') }}</strong>.</p>
      </div>
    </div>
  </div>
</template>
<script>
import Daterange from 'components/common/daterange';
import Timezone from './Timezone';

export default {
  components: {
    Daterange,
    Timezone
  },
  props: {
    value: Object,
    default: () => {
      return {
        start: null,
        deadline: null,
        timezone: null
      }
    }
  },
  created() {
    this.$validator.extend('validateDaterange', (value) => {
      return !!(Array.isArray(value) && value.length >= 2 && value[0] && value[1]);
    })
  },
  data() {
    return {
      start: this.value.start ? this.UTCDate(this.value.start) : null,
      deadline: this.value.deadline ? this.UTCDate(this.value.deadline) : null,
      timezone: this.value.timezone,
    };
  },
  methods: {
    triggerInput(v) {
      const start = v ? v[0] : this.value.start;
      const deadline = v ? v[1] : this.value.deadline;
      const timezone = this.timezone;
      this.$emit('input', { start, deadline, timezone });
    }
  },
  computed: {
    daterangeErrors() {
      let errors = [];
      if (this.errors.has('dates')) {
        errors.push('Please select start and deadline dates.');
      }
      if (this.errors.has('start')) {
        errors.push(this.errors.first('start'));
      }
      if (this.errors.has('deadline')) {
        errors.push(this.errors.first('deadline'));
      }
      return errors.join(' ', errors);
    },
  },
  watch: {
    timezone(timezone) {
      this.triggerInput();
    }
  }
}
</script>
