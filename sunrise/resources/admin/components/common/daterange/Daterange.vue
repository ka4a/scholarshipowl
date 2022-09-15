<template>
  <div class="field daterange" v-click-outside="closeDropdown">
    <div v-if="$slots.trigger" @click="openDropdown()">
      <slot name="trigger"/>
    </div>
    <c-field
      v-else
      class="daterange-inputs"
      :type="type"
      :message="message"
      :label="label"
    >
      <div class="daterange-inputs-container">
        <div class="date-info" :class="{ 'is-range': isRange }" @click.stop="openDropdown(first)">
          <span class="label">From <span class="date">{{ firstDateString }}</span></span>
          <!-- <span class="date">{{ first }}</span> -->
          <c-icon icon="calendar" />
        </div>
        <div v-if="isRange" class="date-info" :class="{ 'is-range': isRange }" @click.stop="openDropdown(second)">
          <span class="label">Until <span class="date">{{ secondDateString }}</span></span>
          <c-icon icon="calendar" />
        </div>
      </div>
    </c-field>
    <div v-if="dropdownOpen" class="daterange-dropdown">
      <div class="daterange-header">
        <div class="daterange-header_date">
          <span>{{ monthNames[currentDate.getMonth()] }}</span>,
          <span>{{ currentDate.getFullYear() }}</span>
        </div>
        <c-icon class="icon-month is-left" icon="arrow-point-right" @click="prevMonth" />
        <c-icon class="icon-month" icon="arrow-point-right" @click="nextMonth" />
      </div>
      <!-- <div class="daterange-header">
        <div @click="prevMonth">
          <c-icon class="icon-month is-left" icon="arrow-point-right" />
        </div>
        <b-field>
          <b-select :value="currentDate.()" @input="selectMonth">
            <option v-for="name, month in monthNames" :value="month" :key="month">
              {{ name }}
            </option>
          </b-select>
          <b-select :value="currentDate.getUTCFullYear()" @input="selectYear">
            <option v-for="year in years" :value="year" :key="year">
              {{ year }}
            </option>
          </b-select>
        </b-field>
        <div @click="nextMonth">
          <c-icon class="icon-month" icon="arrow-point-right" />
        </div>
      </div> -->
      <calendar
        :is-range="isRange"
        :currentDate="currentDate"
        :minDate="minDateCalendar"
        :maxDate="maxDateCalendar"
        :first="first"
        :first-label="firstLabel"
        :second="second"
        :second-label="secondLabel"
        @input="isRange ? selectDateRange($event) : selectDate($event)"
      />
      <slot name="after-calendar" />
    </div>
  </div>
</template>
<script>
import Calendar from './Calendar';

export default {
  name: 'Daterange',
  $_veeValidate: {
    value() {
      return this.isRange ? [this.first, this.second] : this.first;
    }
  },
  components: {
    Calendar
  },
  props: {
    type: String,
    message: String,
    isRange: {
      type: Boolean,
      default: true,
    },
    columns: {
      type: Number,
      default: 2
    },
    minDate: {
      type: Date,
      default: () => new Date(),
    },
    maxDate: Date,
    minDateSecond: Date,
    maxDateSecond: Date,
    open: Boolean,
    label: String,
    firstDate: Date,
    secondDate: Date,
    firstLabel: String,
    secondLabel: String,
  },
  data() {
    return {
      dropdownOpen: this.open,

      first: this.firstDate || null,
      second: this.secondDate || null,
      currentDate: this.firstDate ? this.firstDate : new Date(),
    };
  },
  methods: {
    openDropdown(date) {
      this.dropdownOpen = true;
      this.currentDate = date ? date : this.currentDate;
    },
    isActive(date) {
      if (this.isRange) {
        return (this.first !== null && date >= this.first ) &&
          (this.second === null || date <= this.second);
      }
      return false;
    },
    selectDate(date) {
      this.first = date;
    },
    selectDateRange(date) {
      if (this.first == null) {
        this.setFirstDate(date);
        return;
      }
      if (this.first.getTime() === date.getTime()) {
        this.setFirstDate(null);
        this.setSecondDate(null);
        return;
      }
      if (date > this.first) {
        this.setSecondDate((this.second && this.second.getTime() === date.getTime()) ? null : date);
      }
    },
    setFirstDate(date) {
      if (date == null ||
        ((!this.minDate || date >= this.minDate) && (!this.maxDate || date <= this.maxDate))
      ) {
        this.first = date;
      }
    },
    setSecondDate(date) {
      if (date == null ||
        ((!this.minDateSecond || date >= this.minDateSecond) && (!this.maxDateSecond || date <= this.maxDateSecond))
      ) {
        this.second = date;
      }
    },
    selectMonth(month) {
      this.currentDate = new Date(this.currentDate.getUTCFullYear(), month, this.currentDate.getUTCDate());
    },
    selectYear(year) {
      this.currentDate = new Date(year, this.currentDate.getUTCMonth(), this.currentDate.getUTCDate());
    },
    nextMonth() {
      const maxDate = this.maxDate ?
        new Date(Date.UTC(this.maxDate.getUTCFullYear(), this.maxDate.getUTCMonth() + 1, 0)) : null;
      const nextDate = this.currentDate.getUTCMonth() === 11 ?
        new Date(Date.UTC(this.currentDate.getUTCFullYear() + 1, 0, 1)) :
        new Date(Date.UTC(this.currentDate.getUTCFullYear(), this.currentDate.getUTCMonth() + 1, 1));

      if (!maxDate || nextDate <= maxDate || nextDate <= this.secondDate) {
        this.currentDate = nextDate;
      }
    },
    prevMonth() {
      const minDate = this.minDate ?
       new Date(Date.UTC(this.minDate.getUTCFullYear(), this.minDate.getUTCMonth(), 1)) : null;
      const nextDate = this.currentDate.getUTCMonth() === 0 ?
        new Date(Date.UTC(this.currentDate.getUTCFullYear() - 1, 11, 1)) :
        new Date(Date.UTC(this.currentDate.getUTCFullYear(), this.currentDate.getUTCMonth() - 1, 1))

      if (!minDate || nextDate >= minDate || nextDate >= this.firstDate) {
        this.currentDate = nextDate;
      }
    },
    closeDropdown() {
      this.dropdownOpen = false;
    }
  },
  computed: {
    years: ({ minDate }) => Array(30).fill(null).map((x,i) => minDate.getUTCFullYear() + i),
    weekdays: () => (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']),
    weekdaysFull: () => (['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']),
    monthNames: () => (["January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"]),
    firstDateString: ({ first }) => !first ? null : (
      (first.getUTCMonth() + 1) + '/' + first.getUTCDate() + '/' + first.getUTCFullYear()
    ),
    secondDateString: ({ second }) => !second ? null : (
      (second.getUTCMonth() + 1) + '/' + second.getUTCDate() + '/' + second.getUTCFullYear()
    ),
    minDateCalendar() {
      if (this.minDateSecond) {
        return this.minDate > this.minDateSecond ? this.minDateSecond : this.minDate;
      }
      return this.minDate;
    },
    maxDateCalendar() {
      if (this.maxDateSecond) {
        return this.maxDate > this.maxDateSecond ? this.maxDate : this.maxDateSecond;
      }
      return this.maxDate;
    }
  },
  watch: {
    first() {
      this.$emit('input', this.isRange ? [this.first, this.second] : this.first);
    },
    second() {
      this.$emit('input', this.isRange ? [this.first, this.second] : this.first);
    },
    firstDate(first) {
      this.first = first;
    },
    secondDate(second) {
      this.second = second;
    }
  }
}
</script>
<style lang="scss" scoped>
.daterange {
  position: relative;
  .icon-month {
    cursor: pointer;
    border-radius: 50%;
    background: #DDDDDE;
    &:hover {
      background: #B1B5BB;
    }
    &.is-left {
      transform: rotate(180deg)
    }
  }
}
.daterange-dropdown {
  position: absolute;
  top: 73px;

  border: 1px solid #CCD6E6;
  border-radius: 5px;
  padding: 30px;
  background-color: #ffffff;
  z-index: 20;
}
.daterange-inputs {
  &-container {
    display: flex !important;
  }
  .input {
    cursor: pointer !important;
  }
  .date-info {
    display: flex;
    justify-content: space-between;

    width: 212px;
    border: 1px solid #CCD6E6;
    border-radius: 3px;
    padding: 5px 12px 5px 15px;

    .label {
      margin-bottom: 0;
      color: #C7C7C7;
      font-weight: normal;
      .date {
        color: #656565;
      }
    }
    &:hover {
      cursor: pointer;
      background: #F7F9FD;
    }
    &.is-range {
      &:first-child {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
      }
      &:not(:first-child) {
        border-left: none;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
      }
    }
  }
}
.daterange-header {
  width: 100%;
  // display: flex;
  // justify-content: space-between;
  font-size: 20px;

  &_date {
    display: inline-block;
    width: 142px;
  }
}
</style>
