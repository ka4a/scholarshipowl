<template>
  <div class="columns">
    <div class="column">
      <b-field
        :label="label"
        :message="daysErrorMessage"
        :type="daysErrorMessage ? 'is-danger' : null"
      >
        <ul class="weekdays-list">
          <li v-for="(day,index) in weekdays">
            <button
              class="button is-kubic is-white"
              :class="{
                'is-active': isActiveWeekday(index),
                'is-selected': isSelectedWeekday(index),
                'is-start': startDay === index,
                'is-deadline': deadlineDay === index
              }"
              @click="selectWeekday(index)">
              <p>{{ day }}</p></br>
            </button>
            <p v-if="startDay === index" class="timeline timeline__start">
              <span>start</span>
              <svg width="6" height="7" viewBox="0 0 6 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 3.5L0.75 6.53109L0.75 0.468911L6 3.5Z" fill="#EB6569"/>
              </svg>
            </p>
            <p v-else-if="deadlineDay === index" class="timeline timeline__deadline">
              <svg width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="2" cy="2" r="2" fill="#EB6569"/>
              </svg>
              <span>end</span>
            </p>
            <p v-else-if="isActiveWeekday(index)" class="timeline timeline__between"></p>
          </li>
        </ul>
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
    </div>
    <div class="column">
        <div class="helper-right">
          <strong>Scholarship summary</strong>
          <p class="help" v-if="startDay == null || deadlineDay == null">Click the week day you want your scholarship to be published and unpublished.</p>
          <p class="help" v-else>
            <span>Your scholarship will be running </span>
            <strong>
              <span v-if="!occurrences">infinitely</span>
              <span v-else>{{ occurrences }} times</span>
              <span>each week starting from {{ weekdaysFull[startDay] }} and finishing at {{ weekdaysFull[deadlineDay] }} day</span>
              <span v-if="startDay > deadlineDay"> next week</span>
              <span>.</span>
            </strong>
          </p>
          <!-- <p class="help" v-if="startDay === null">Select weekday when scholarship should start.</p>
          <p class="help" v-else-if="deadlineDay === null">Select weekday when scholarship ends.</p>
          <p class="help" v-else>You scholarship will start each <b>{{ weekdaysFull[startDay] }}</b> and with deadline <b>{{ weekdaysFull[deadlineDay] }}</b> each week.</p> -->
          <prediction class="mt-20" :config="config" />
        </div>
    </div>
  </div>
</template>
<script>
import Timezone from './Timezone';
import Prediction from './Prediction';

export default {
  components: {
    Timezone,
    Prediction
  },
  props: {
    value: {
      type: Object,
      default: () => ({
        startDay: null,
        deadlineDay: null,
        // startsAfterDeadline: true,
        occurrences: null,
        timezone: null
      })
    }
  },
  created() {
    //do something after creating vue instance
    this.$validator.attach({
      name: 'days',
      rules: 'required',
      getter: () => (this.startDay !== null && this.deadlineDay !== null) ? 1 : null
    })
  },
  data() {
    return {
      startDay: this.value.startDay ? this.value.startDay - 1 : null,
      deadlineDay: this.value.deadlineDay ? this.value.deadlineDay - 1 : null,
      occurrences: this.value.occurrences ? this.value.occurrences : null,
      // startsAfterDeadline: ('startsAfterDeadline' in this.value) ? !!this.value.startsAfterDeadline : true,
      timezone: this.value.timezone ? this.value.timezone : null,
    };
  },
  methods: {
    isSelectedWeekday(index) {
      return (this.deadlineDay === index) || (!this.startsAfterDeadline && this.startDay === index);
    },
    isActiveWeekday(index) {
      // if (this.startsAfterDeadline) {
        // return !!this.deadlineDay;
      // }

      if (this.startDay !== null) {
        if (this.deadlineDay !== null) {
          if (this.startDay < this.deadlineDay) {
            return index >= this.startDay && index <= this.deadlineDay;
          } else {
            return index <= this.deadlineDay || index >= this.startDay;
          }
        }

        return index >= this.startDay;
      }

      return false;
    },
    selectWeekday(index) {
      // if (this.startsAfterDeadline) {
      //   if (this.deadlineDay === index) {
      //     this.deadlineDay = null;
      //     this.startDay = null;
      //   } else {
      //     this.deadlineDay = index;
      //     this.startDay = index < 6 ? index + 1 : 0
      //   }
      //   return;
      // }
      if (this.startDay == null) {
        this.startDay = index;
        return;
      }
      if (this.startDay === index) {
        this.startDay = null;
        this.deadlineDay = null;
        return;
      }
      this.deadlineDay = index;
    }
  },
  computed: {
    config() {
      return {
        type: 'weeklyScholarship',
        startDay: this.startDay != null ? this.startDay + 1 : null,
        deadlineDay: this.deadlineDay != null ? this.deadlineDay + 1 : null,
        occurrences: this.occurrences,
        timezone: this.timezone,
      }
    },
    label: () => 'Select start and deadline weekday.',
    // label: ({ startsAfterDeadline }) => startsAfterDeadline ? 'Select deadline weekday' : 'Select start and deadline weekday.',
    weekdays: () => (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']),
    weekdaysFull: () => (['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']),
    daysErrorMessage() {
      if (this.errors.has('days')) {
        return 'Please select deadline and start date of the scholarship';
      }
      if (this.errors.has('startDay') || this.errors.has('deadlineDay')) {
        return [this.errors.first('startDay'), this.errors.first('deadlineDay')].join(' ');
      }
      return null;
    }
  },
  watch: {
    startDay(startDay) {
      startDay++;
      this.$emit('input', { ...this.config, startDay });
    },
    deadlineDay(deadlineDay) {
      deadlineDay++;
      this.$emit('input', { ...this.config, deadlineDay });
    },
    occurrences(occurrences) {
      this.$emit('input', { ...this.config, occurrences });
    },
    timezone(timezone) {
      this.$emit('input', { ...this.config, timezone });
    }
  }
}
</script>
<style lang="scss" scoped>
.weekdays-list {
  display: flex;
  > li {
    position: relative;
    .button {
      margin: 0 5px;
    }
  }
}
// .button {
  // &.is-start {
  //   background: green;
  //   color: white;
  // }
  // &.is-deadline {
  //   background: blue;
  //   color: white;
  // }
// }
.timeline {
  color: #EB6569;
  font-size: 13px;
  font-weight: 900;
  // display: inline-block;
  width: 100%;

  &__start {
    text-align: right;
    padding-right: 5px;
  }
  &__deadline {
    text-align: left;
    padding-left: 5px;
  }

  &__between {
    &::after {
      content: " ";
      width: 100%;
      height: 100%;
      // border-radius: 50%;
      border-top: 1px dashed rgba(217, 55, 76, 0.49);
      display: block;
      position: absolute;
      bottom: -60px;
    }
  }
}
</style>
