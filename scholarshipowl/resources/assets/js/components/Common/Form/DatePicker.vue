<template>
  <div :class="$bem.block('multiselect-set-fm', {'three': showDay, 'two': !showDay})">
    <input type="hidden" :name="name" :value="date" />
    <multi-select
      ref="multiselect-1"
      :class="$bem.element('multiselect-set-fm', 'item', 'month')"
      autocomplete="off"
      v-model="month"
      @input="update()"
      name="month-of-birth"
      placeholder="MM"
      transition="expand"
      open-direction="bottom"
      :options="months"
      :allow-empty="false"
      :max-height="maxHeight"
      :show-labels="showLabels"
      :show-pointer="showPointer"
     >
     <span slot="noResult">No results</span>
    </multi-select>
    <multi-select
      ref="multiselect-2"
      autocomplete="off"
      v-if="showDay"
      :class="$bem.element('multiselect-set-fm', 'item', 'day')"
      v-model="day"
      @input="update()"
      name="day-of-birth"
      placeholder="DD"
      transition="expand"
      open-direction="bottom"
      :options="days"
      :allow-empty="false"
      :max-height="maxHeight"
      :show-labels="showLabels"
      :show-pointer="showPointer"
     >
     <span slot="noResult">No results</span>
    </multi-select>
    <multi-select
      ref="multiselect-3"
      autocomplete="off"
      :class="$bem.element('multiselect-set-fm', 'item', 'year')"
      v-model="year"
      @input="update()"
      name="year-of-birth"
      placeholder="YYYY"
      transition="expand"
      open-direction="bottom"
      :options="years.reverse()"
      :allow-empty="false"
      :max-height="maxHeight"
      :show-labels="showLabels"
      :show-pointer="showPointer"
     >
     <span slot="noResult">No results</span>
   </multi-select>
  </div>
</template>

<script>
import multiSelect from "vue-multiselect";

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
  name: "DatePicker",
  mounted: function() {
    if(this.type) {
      Object.keys(this.$refs).forEach(key => {
        if(key.indexOf("multiselect") !== -1) {
          this.$refs[key].$el.querySelector("input").type = this.type;
        }
      });
    }
  },
  components: {
    multiSelect
  },
  props: {
    value:         { required: true },
    name:          { required: true },
    showDay:       { type: Boolean, default: true},
    yearStart:     { default: 1900  },
    yearStop:      { default: (new Date()).getUTCFullYear() },
    maxHeight:     { type: Number,  default: 170 },
    type:          { type: String },
    selectLabel:   { type: String },
    deselectLabel: { type: String },
    selectedLabel: { type: String },
    showLabels:    { type: Boolean, default: false },
    showPointer:   { type: Boolean, default: false }
  },
  data: function() {
    return dateObject(this.value);
  },
  methods: {
    update () {
      if (this.date) this.$emit("input", this.date);
    }
  },
  watch: {
    value (value) {
      const date = dateObject(value);

      this.day = date ? date.day : null;
      this.month = date ? date.month : null;
      this.year = date ? date.year : null;
    }
  },
  computed: {
    date () {
      if (this.year && this.month && (!this.showDay || this.day)) {
        return new Date(
          Date.UTC(this.year, this.month - 1, this.day || 1, 0, 0, 0, 0)
        );
      }

      return null;
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
      return array;
    }
  }
};
</script>
