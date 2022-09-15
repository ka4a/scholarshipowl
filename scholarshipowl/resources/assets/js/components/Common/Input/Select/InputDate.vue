<template>
  <div class="input-date">
    <input type="hidden" :name="name" :value="value" />
    <input-select
      :class="`input-date__${item.name}`"
      v-for="item, index in periods"
      :key="item.id"
      autocomplete="off"
      :value="data[item.name]"
      @input="val => update(val, item.name)"
      @close="(val, id) => closeHolder(val, id)"
      @open="id => openHolder(item, id)"
      :name="`${item.name}-of-birth`"
      :placeholder="item.placeholder"
      :error="error"
      :options="instance[`${item.name}s`]" />
  </div>
</template>

<script>
import InputSelect from "components/Common/Input/Select/InputSelect.vue";

const isDate = date => (date instanceof Date && !isNaN(date.valueOf()))

const dateObject = date => {
  return {
    day: date ? date.getDate() : null,
    month: date ? date.getMonth() + 1 : null,
    year: date ? date.getFullYear() : null,
  }
};

const daysInMonth = (year, month) => {
  return new Date(year, month, 0).getDate();
};

export default {
  name: "input-date",
  components: {
    InputSelect
  },
  props: {
    value:         { required: true },
    name:          { required: true },
    showDay:       { type: Boolean, default: true},
    yearStart:     { default: 1900  },
    yearStop:      { default: (new Date()).getUTCFullYear() },
    type:          { type: String },
    error:         { type: Boolean, default: false },
  },
  created() {
    if(!this.showDay) {
      this.periods = this.periods.filter(item => item.name !== 'day');
    }
  },
  watch: {
    value(val) {
      if(!val) return;

      this.data = dateObject(this.value);
    }
  },
  data: function() {
    return {
      data: dateObject(this.value),
      periods: [
        {id: 1, name: "month", placeholder: "MM",    pristine: true},
        {id: 2, name: "day",   placeholder: "DD",    pristine: true},
        {id: 3, name: "year",  placeholder: "YYYY",  pristine: true}
      ]
    }
  },
  methods: {
    openHolder(item, id) {
      this.periods.find(i => item.id === i.id).pristine = false;

      this.$emit("open", id);
    },
    closeHolder(val, id) {
      this.emitDirtyState();

      this.$emit("close", val, id);
    },
    emitDirtyState() {
      if(!this.periods.filter(item => item.pristine && !this.data[item.name]).length) {
        this.$emit("dirty");
      }
    },
    update (val, name) {
      this.data[name] = val;

      if (this.data.year && this.data.month && (!this.showDay || this.data.day)) {
        let date = new Date(
          Number(this.data.year),
          Number(this.data.month) - 1,
          this.showDay ? Number(this.data.day) : 1,
          0, 0, 0, 0
        );

        this.$emit("input", date);
        return;
      }

      this.$emit("input", null);
    }
  },
  computed: {
    instance() {
      return this;
    },
    days ({ month, year }) {
      const days = month ? daysInMonth(year ? year : 1999, month) : 31;

      let array = [];
      for (let i = 1; i <= days; i++) {
        array.push(i);
      }
      return array;
    },
    months () {
      let array = [];
      for (let i = 1; i <= 12; i++) {
        array.push(i);
      }
      return array;
    },
    years () {
      let array = [];
      for (let i = this.yearStart; i <= this.yearStop; i++) {
        array.push(i);
      }

      return array.reverse();
    }
  }
}
</script>

<style lang="scss">
  .input-date {
    display: flex;

    &:after {
      content: "";
      clear: both;
      display: table;
    }

    @include breakpoint($xs $s - 1px) {
      .multiselect__select {
        width: 24px;
        padding: 4px;
      }
    }

    @include breakpoint($xs 350px) {
      &__month {
        padding-right: 6px;
      }
    }

    &__month,
    &__day {
      flex: 1 1 27%;
      min-width: 27.3%;
    }

    &__day,
    &__year {
      margin-left: 5px;
    }
  }
</style>
