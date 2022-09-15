<template>
  <div class="columns">
    <div class="column">
      <b-field
        :message="monthDaysErrors"
        :type="monthDaysErrors ? 'is-danger' : null"
        label="Select publishing and unpublishing day of month">
        <div class="months-list">
          <ul v-for="week in days">
            <li v-for="day, index in week">
              <button
                class="button is-kubic is-white"
                :class="{
                  'is-active': isActive(day),
                  'is-selected': isSelected(day),
                  'is-start': startDate === day,
                  'is-deadline': deadlineDate === day
                }"
                @click="selectDay(day)">
                <div>{{ day }}</div>
                <div class="title" v-if="startDate === day">Start</div>
                <div class="title" v-else-if="deadlineDate === day">End</div>
              </button>
            </li>
          </ul>
        </div>
      </b-field>
      <b-field label="Choose winners at end of each month?">
        <b-checkbox v-model="deadlineEndOfMonth">
          Check it if you want your scholarship to finish at end of each month.
        </b-checkbox>
      </b-field>
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
      <!-- <b-field label="Exceptions" /> -->
      <!-- <monthly-exceptions v-model="exceptions" :config="config" /> -->
    </div>
    <div class="column">
      <div class="helper-right">
        <strong>Scholarship summary</strong>
        <template v-if="startDate == null || deadlineDate == null">
          <p class="help">Click the month day you want your scholarship to be publish and unpublished.</p>
        </template>
        <template v-else>
          <p class="help">
            <span>Your scholarship will be running </span>
            <strong>
              <span v-if="!occurrences">infinitely</span>
              <span v-else>{{ occurrences }} times</span>
              <span>each month, starting at {{ startDate | ordinal_suffix }} and finishing at {{ deadlineDate | ordinal_suffix }} day</span>
              <span v-if="startDate > deadlineDate"> next month</span>
              <span>.</span>
            </strong>
          </p>
          <p class="help">You can add an exception for each month, by clicking on edit button.</p>
        </template>
        <prediction class="mt-20" :config="config">
          <exceptions
            slot="edit"
            slot-scope="slotProps"
            :prediction="slotProps.prediction"
            v-model="exceptions"
          />
        </prediction>
      </div>
    </div>
  </div>
</template>
<script>
import Timezone from './Timezone';
import Prediction from './Prediction';
import Exceptions from './MonthlyConfigExceptions';

export default {
  components: {
    Timezone,
    Prediction,
    Exceptions
  },
  props: {
    value: {
      type: Object,
      default: () => ({
        startDate: null,
        deadlineDate: null,
        deadlineEndOfMonth: false,
        // startsAfterDeadline: true,
        exceptions: [],
        occurrences: null,
        timezone: null,
      })
    }
  },
  created() {
    this.$validator.attach({
      name: 'monthDays',
      rules: 'required',
      getter: () => (this.startDate && this.deadlineDate) ? 1 : null
    })
    this.$validator.extend('validateDaterange', (value) => {
      return !!(Array.isArray(value) && value.length >= 2 && value[0] && value[1]);
    })
  },
  data() {
    return {
      startDate: this.value.startDate || null,
      deadlineDate: this.value.deadlineDate || null,
      deadlineEndOfMonth: this.value.deadlineEndOfMonth || false,
      // startsAfterDeadline: ('startsAfterDeadline' in this.value) ? !!this.value.startsAfterDeadline : true,
      occurrences: this.value.occurrences || null,
      timezone: this.value.timezone || null,
      exceptions: this.value.exceptions || [],
    }
  },
  methods: {
    isActive(day) {
      // if (this.startsAfterDeadline) {
      //   return !!this.deadlineDate;
      // }
      //
      if (this.startDate !== null) {
        if (this.deadlineDate !== null) {
          if (this.startDate < this.deadlineDate) {
            return day >= this.startDate && day <= this.deadlineDate;
          } else {
            return day <= this.deadlineDate || day >= this.startDate;
          }
        }

        return day >= this.startDate;
      }

      return false;
    },
    isSelected(day) {
      return (this.startDate === day) || (this.deadlineDate === day);
    },
    selectDay(day) {
      // if (this.startsAfterDeadline && this.deadlineEndOfMonth) {
      //   return;
      // }
      if (this.deadlineEndOfMonth) {
        this.startDate = day;
        return;
      }

      // if (this.startsAfterDeadline) {
      //   if (this.deadlineDate === day) {
      //     this.deadlineDate = null;
      //     this.startDate = null;
      //   } else {
      //     this.deadlineDate = day;
      //     this.startDate = day < 30 ? day + 1 : 1
      //   }
      //   return;
      // }

      if (this.startDate == null) {
        this.startDate = day;
        return;
      }
      if (this.startDate === day) {
        this.startDate = null;
        this.deadlineDate = null;
        return;
      }
      this.deadlineDate = day;
    },
    triggerInput(override) {
      this.$emit('input', { ...this.config, override });
    },
  },
  computed: {
    config() {
      return {
        type: 'monthlyScholarship',
        startDate: this.startDate,
        deadlineDate: this.deadlineDate,
        timezone: this.timezone,
        exceptions: this.exceptions,
        occurrences: parseInt(this.occurrences, 10),
        deadlineEndOfMonth: this.deadlineEndOfMonth
      }
    },
    days: () => {
      let days = [];
      let num = 1;
      let week;

      while (num <= 31) {
        week = Math.floor((num-1) / 7);
        if (!Array.isArray(days[week])) {
          days[week] = [];
        }
        days[week].push(num);
        num++;
      }

      return days;
    },
    deadlineDateString() {
      return this.deadlineEndOfMonth ? 'last date of the month' :
        `<b>${this.deadlineDate}</b> of ${this.startDate > this.deadlineDate ? 'next month' : 'this month'}`;
    },
    monthDaysErrors() {
      if (this.errors.has('monthDays')) {
        return 'Please select deadline and start month dates.'
      }
      if (this.errors.has('startDate') || this.errors.has('deadlineDate')) {
        return [this.errors.first('startDate'), this.errors.first('deadlineDate')].join(' ');
      }
      return null;
    },
  },
  watch: {
    startDate(startDate) {
      this.triggerInput({ startDate });
    },
    deadlineDate(deadlineDate) {
      this.triggerInput({ deadlineDate });
    },
    deadlineEndOfMonth(deadlineEndOfMonth, oldValue) {
      if (deadlineEndOfMonth && deadlineEndOfMonth !== oldValue) {
        this.deadlineDate = 31;
        // if (this.startsAfterDeadline) {
        //   this.startDate = 1;
        // }
      }
      this.triggerInput({ deadlineEndOfMonth });
    },
    // startsAfterDeadline(startsAfterDeadline, oldValue) {
    //   if (startsAfterDeadline && startsAfterDeadline !== oldValue && this.deadlineDate) {
    //     this.startDate = this.deadlineDate < 30 ? this.deadlineDate + 1 : 1;
    //   }
    //   this.triggerInput({ startsAfterDeadline });
    // },
    exceptions: {
      handler(exceptions) {
        this.triggerInput({ exceptions });
      },
      deep: true
    },
    occurrences(occurrences) {
      this.triggerInput({ occurrences });
    },
    timezone(timezone) {
      this.triggerInput({ timezone });
    }
  }
}
</script>
<style lang="scss" scoped>
.months-list {
  > ul {
    display: flex;
    > li {
      display: flex;

      &:not(:first-child) {
        margin-left: 10px;
      }
    }
    &:not(:first-child) {
      margin-top: 10px;
    }
  }
  .button.is-kubic {
    font-size: 20px;
    font-weight: normal;
    position: relative;

    .title {
      position: absolute;
      display: inline-block;
      bottom: 6px;
      font-size: 10px;
      color: white;
    }
  }
}
</style>
