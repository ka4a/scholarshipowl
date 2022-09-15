<template>
  <div class="columns">
    <div class="column">
      <table class="table-calendar">
        <thead>
          <tr>
            <th v-for="day in weekdays">{{ day }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="week in days">
            <td v-for="date, index in week">
              <button
                class="button is-kubic is-white"
                :class="{
                  'is-active': isActive(date),
                  'is-selected': isSelected(date),
                  'is-disabled': isNotCurrentMonth(date),
                }"
                :disabled="isDisabled(date)"
                @click="clickDate(date)">
                <div>{{ date.getDate() }}</div>
                <div class="number-label" v-if="firstLabel && first && first.getTime() === date.getTime()">{{ firstLabel }}</div>
                <div class="number-label" v-else-if="secondLabel && second && second.getTime() === date.getTime()">{{ secondLabel }}</div>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- <div v-if="isRange" class="column">
      <table class="table-calendar">
        <thead>
          <tr>
            <th v-for="day in weekdays">{{ day }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="week in nextMonthDays">
            <td v-for="date, index in week">
              <button
                v-if="date"
                class="button is-kubic is-white"
                :class="{ 'is-active': isActive(date), 'is-selected': isSelected(date) }"
                :disabled="(minDate && date < minDate)"
                @click="clickDate(date)">
                {{ date.getDate() }}
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div> -->
  </div>
</template>
<script>

const addMonth = (date) => {
  return date.getMonth() === 11 ?
    new Date(date.getFullYear() + 1, 0, date.getDate()) :
    new Date(date.getFullYear(), date.getMonth() + 1, date.getDate())
}

const prepareDays = (forDate) => {
  const firstMonthDay = new Date(Date.UTC(forDate.getUTCFullYear(), forDate.getUTCMonth()));
  let days = [];
  let week;

  let date = (new Date(Date.UTC(
    firstMonthDay.getFullYear(), firstMonthDay.getMonth(), firstMonthDay.getDate() - firstMonthDay.getDay()
  )));

  for(week = 1; week <= 6; week++) {
    if (!days[week]) {
      days[week] = [];
    }
    while (days[week].length < 7) {
      days[week].push((new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate(), 0, 0, 0))));
      date.setDate(date.getDate() + 1);
    }
  }

  return days;
};

export default {
  name: 'DaterangeCalendar',
  props: {
    isRange: Boolean,
    currentDate: Date,
    minDate: Date,
    maxDate: Date,
    second: Date,
    first: Date,
    firstLabel: String,
    secondLabel: String,
  },
  methods: {
    isActive(date) {
      if (date && this.isRange) {
        return (this.first !== null && date >= this.first ) &&
          (this.second === null || date <= this.second);
      }

      return false;
    },
    isSelected(date) {
      if (this.first) {
        if (date && this.first.getTime() === date.getTime()) {
          return true;
        }
      }

      if (this.second) {
        if (date && this.second.getTime() === date.getTime()) {
          return true;
        }
      }

      return false;
    },
    isNotCurrentMonth(date) {
      return !date || date.getMonth() !== this.currentDate.getMonth();
    },
    isDisabled(date) {
      if (!date) {
        return true;
      }

      if (this.first && this.first.getTime() === date.getTime()) {
        return false;
      }

      if (this.second && this.second.getTime() === date.getTime()) {
        return false;
      }

      return (this.minDate && date < this.minDate) || (this.maxDate && date > this.maxDate);
    },
    clickDate(date) {
      this.$emit('input', date);
    },
  },
  computed: {
    days: ({ currentDate }) => prepareDays(currentDate),
    nextMonthDays: ({ currentDate }) => prepareDays(addMonth(currentDate)),
    weekdays: () => {
      return (new Array(7)).fill(null).map((v,i) => {
        let date = new Date();
        date.setDate(date.getDate() - date.getDay() + (i));
        return date.toLocaleString('en-us', { weekday: 'short' });
      })
    },
    weekdaysFull: () => {
      return (new Array(7)).fill(null).map((v,i) => {
        let date = new Date();
        date.setDate(date.getDate() - date.getDay() + (i));
        return date.toLocaleString('en-us', { weekday: 'long' });
      })
    },
    monthNames: () => ([
      "January", "February", "March", "April", "May", "June",
      "July", "August", "September", "October", "November", "December"
    ]),
  }
}
</script>
<style lang="scss" scoped>
.weekdays {
  display: flex;
  > li:not(:first-child) {
    margin-left: 10px;
  }
}
.days {
  > ul {
    display: flex;
    margin-top: 10px;
    > li:not(:first-child) {
      margin-left: 10px;
    }
  }
}
.button.is-kubic {
  position: relative;
  width: 30px;
  height: 30px;
  font-size: 14px;
  .number-label {
    position: absolute;
    font-size: 12px;
    bottom: 0;
  }
}
.table-calendar {
  thead th {
    color: #CCD6E6;
    font-size: 13px;
    font-weight: bold;
    text-transform: uppercase;
    text-align: center;
    padding: 4px 0;
  }
  tbody {
    tr td {
      &:not(:first-child) {
        padding-left: 8px;
      }
      &:not(:last-child) {
        padding-bottom: 8px;
      }
    }
    tr td {
      .button {
        width: 50px;
        height: 50px;
      }
    }
  }
}
</style>
