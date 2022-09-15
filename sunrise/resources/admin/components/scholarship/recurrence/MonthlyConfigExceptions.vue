<template>
  <div class="monthly-exceptions is-clearfix">
    <daterange
      ref="daterange"
      :first-date="first"
      :second-date="second"
      :min-date="minDate"
      :max-date="maxDate"
      :min-date-second="minDateSecond"
      :max-date-second="maxDateSecond"
      first-label="Start"
      second-label="End"
      v-validate.disable="'required|validateDaterange'"
      data-vv-name="dates"
      data-vv-scope="exceptions-daterange"
      >
      <button slot="trigger" class="button is-round is-edit"
        :class="{
          'is-primary': !!getPredictionExceptions(prediction).length,// getException(UTCDate(prediction.start).getMonth()).start,
          'is-grey': !getPredictionExceptions(prediction).length
        }">
        <c-icon icon="pencil" />
      </button>
      <div slot="after-calendar">
        <p class="help has-text-danger mb-10" v-if="errors.has('dates', 'exceptions-daterange')">Please select start and deadline dates.</p>
        <div class="is-clearfix is-pulled-right">
          <button class="button is-primary is-rounded mr-10" @click="saveException(prediction)">
            <c-icon icon="check-circle" />
            <span>Save</span>
          </button>
          <button v-if="!!getPredictionExceptions(prediction).length" class="button is-grey is-rounded" @click="deleteException(prediction)">
            <c-icon icon="cancel" />
            <span>Remove</span>
          </button>
        </div>
      </div>
    </daterange>
    <!-- <button v-if="true" class="button is-edit is-grey is-round"
      :class="{ 'is-primary': true }">
      <c-icon icon="pencil" />
    </button>
    <button v-else class="button is-edit is-grey is-round"
      :class="{ 'is-primary': true }">
      <c-icon icon="cancel" />
    </button> -->
    <!-- <button class="button is-primary is-rounded is-pulled-right" @click="saveException">
      <c-icon icon="check-circle" />
      <span>Save</span>
    </button> -->
  </div>
</template>
<script>
import Daterange from 'components/common/daterange';

export default {
  components: {
    Daterange
  },
  props: {
    prediction: Object,
    value: {
      type: Array,
      default: () => [],
    }
  },
  methods: {
    getException(month) {
      return this.value.filter((exc) => exc.month === (month+1))[0];
    },
    saveException(prediction) {
      this.$validator.validateAll('exceptions-daterange')
        .then((result) => {
          if (result) {
            const daterange = this.$refs.daterange;
            const startDate = daterange.first;
            const startMonth = startDate.getMonth() + 1;
            const deadlineDate = daterange.second;
            const deadlineMonth = deadlineDate.getMonth() + 1;
            let exceptions = this.value;
            let exception;

            exception = exceptions.filter(({ month }) => month === startMonth)[0] ||
              { month: startMonth, deadlineDate: null };
            exception.startDate = startDate.getDate();
            exceptions = exceptions.filter(({ month }) => month !== exception.month);
            exceptions.push(exception);

            exception = exceptions.filter(({ month }) => month === deadlineMonth)[0] ||
              { month: deadlineMonth, startDate: null };
            exception.deadlineDate = deadlineDate.getDate()
            exceptions = exceptions.filter(({ month }) => month !== exception.month);
            exceptions.push(exception);

            daterange.closeDropdown();
            this.$emit('input', exceptions);
          }
        })
    },
    deleteException(prediction) {
      const start = this.UTCDate(prediction.start);
      const deadline = this.UTCDate(prediction.deadline);

      const newValue = this.value
        .map((e) => {
          if ((e.month === start.getMonth() + 1) && e.startDate === start.getDate()) {
            e.startDate = null;
          }
          if ((e.month === deadline.getMonth() + 1) && e.deadlineDate === deadline.getDate()) {
            e.deadlineDate = null;
          }
          return e;
        })
        .filter((e) => e.startDate != null || e.deadlineDate != null)

      this.$emit('input', newValue);
      this.$refs.daterange.closeDropdown();
    },
    getPredictionExceptions(prediction) {
      const start = this.UTCDate(prediction.start);
      const deadline = this.UTCDate(prediction.deadline);
      return  this.value
        .filter(({ month, startDate, deadlineDate }) => {
          return ((month === start.getMonth() + 1) && startDate === start.getDate()) ||
            ((month === deadline.getMonth() + 1) && deadlineDate === deadline.getDate());
        });
    }
  },
  computed: {
    first() {
      return this.UTCDate(this.prediction.start);
    },
    second() {
      return this.UTCDate(this.prediction.deadline);
    },
    minDate() {
      const start = this.UTCDate(this.prediction.start);
      return new Date(Date.UTC(start.getUTCFullYear(), start.getUTCMonth(), 1));
    },
    maxDate() {
      const start = this.UTCDate(this.prediction.start);
      return new Date(Date.UTC(start.getUTCFullYear(), start.getUTCMonth() + 1, 0));
    },
    minDateSecond() {
      const deadline = this.UTCDate(this.prediction.deadline);
      return new Date(Date.UTC(deadline.getUTCFullYear(), deadline.getUTCMonth(), 1));
    },
    maxDateSecond() {
      const deadline = this.UTCDate(this.prediction.deadline);
      return new Date(Date.UTC(deadline.getUTCFullYear(), deadline.getUTCMonth() + 1, 0));
    }
  }
}
</script>
<style lang="scss" scoped>
.monthly-exceptions {
  position: absolute;
  top: 7px;
  right: 11px;

  .button {
    &.is-edit {
    }
  }
  /deep/ .daterange-dropdown {
    top: 48px;
    left: -423px;
  }
}
</style>
