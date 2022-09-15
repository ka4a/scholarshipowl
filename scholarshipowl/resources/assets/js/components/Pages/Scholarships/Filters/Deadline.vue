<template lang="html">
  <div class="deadline">
    <div class="deadline__wrp first">
      <datepicker
        :selectedDate="deadline.from"
        @selected="(date) => { setParam('from', date) }"
        :calendar-button="true"
        calendar-button-icon="icon icon-calendar"
        input-class="date-input"
        wrapper-class="date-wrapper"
        calendar-class="date-calendar from">
      </datepicker>
      <p>
        <span v-if="!deadline.from" class="deadline__label">Date From:</span>
        <span v-else class="deadline__value">{{ formatFrom }}</span>
      </p>
    </div>
    <div class="deadline__wrp second">
      <datepicker
        :selectedDate="deadline.to"
        @selected="(date) => { setParam('to', date) }"
        :calendar-button="true"
        calendar-button-icon="icon icon-calendar"
        input-class="date-input"
        wrapper-class="date-wrapper"
        calendar-class="date-calendar until">
      </datepicker>
      <p>
        <span v-if="!deadline.to" class="deadline__label">Date Until:</span>
        <span v-else class="deadline__value">{{ formatUntil }}</span>
      </p>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";

import moment from "moment";
import Datepicker from "vuejs-datepicker";

function format(date) {
  return date ? moment(date).format("L") : "";
}

export default {
  model: {
    prop: "deadline",
    event: "updateDeadline"
  },
  components: {
    Datepicker,
  },
  computed: {
    ...mapState({
      deadline: state => state.list.scholarships.filter.deadline
    }),
    formatFrom() {
      return format(this.deadline.from);

    },
    formatUntil() {
      return format(this.deadline.to);
    }
  },
  methods: {
    ...mapActions({
      setFilterParam: "list/setFilterParam"
    }),
    setParam(marker, date) {
      let value = {
        from: "",
        to: ""
      }

      value = Object.assign({}, this.deadline, {[marker]: date});

      this.setFilterParam({
        nameSpace: 'scholarships',
        filterBy: 'deadline',
        parameter: value });

      this.$emit('filter');
    }
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';
@import 'style-gide/mixins';

$black: #2f2f2f;
$white: #fff;
$blue-light: #F2F7FE;
$blue: #708FE7;
$blue-dark: #ACCAF6;
$blue-darker: #CDDFF9;

// deadline
.deadline {
  width: 100%;
  @include flexbox();
  @include align-items(center);

  &__wrp {
    @include flexbox();
    @include align-items(center);
    @include justify-content(center);
    position: relative;
    padding-left: 30px;
    width: 70px;

    & + & {
      margin-left: 20px;
    }

    &.first    {
      .date-calendar {
        top: 29px !important;
        left: -77px !important;

        &.from:before {
          left: 75px !important;
        }
      }
    }

    &.second {
      .date-calendar {
          top: 29px !important;
          right: -7px;
          left: auto !important;

          @include breakpoint($s) {
            right: -11px !important;
          }

        &.until:before {
          right: 84px !important;

          @include breakpoint($s) {
            right: 89px !important;
          }
        }
      }
    }
  }

  &__label {
    font-size: 12px;
    text-transform: capitalize;
    color: $black;
    display: block;

    @include breakpoint($s) {
      font-size: 13px;
    }
  }

  &__value {
    color: #333;
    font-size: 11px;
    display: block;
    min-width: 60px;
  }

  .icon {
    font-size: 20px;
    color: #accaf6;
    margin-right: 10px;
    margin-top: 5px;
    display: inline-block;
    cursor: pointer;
  }

  // assets vue date picker styles rewrite
  .vdp-datepicker {
    position: static;

    .date-calendar {
      top: 0;
      left: 0;
      width: 252px;
      background-color: $blue-light;
      border: none;
      border-top: 11px solid $blue-dark;
      top: 106px;
      box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.15);
      box-sizing: border-box;

      &:before {
        content: "";
        position: absolute;
        top: -21px;

        width: 0; height: 0;
        border-style: solid;
        border-color: transparent;
        border-width: 0 12px 12px 12px;
        border-bottom-color: $blue-dark;
        z-index: 1;
      }

      > header {
        overflow: hidden;
      }

      > div {
        background-color: $blue-light;
        padding: 0 14px 13px 14px;
        box-sizing: border-box;
      }

      .prev {
        background-color: $white;
        height: 43px;
        position: relative;

        &:hover {
          background-color: $white !important;
        }

        &:after {
          border: none;
          @include angle-bracket(left, 12px, 2px, $blue);

          display: block;
          content: '';
          position: absolute;
          top: 0; left: 0;
          bottom: 0; right: 0;
          margin: auto;
        }
      }

      .prev + span {
        color: $black;
        font-size: 12px;
        line-height: 43px;
        text-align: center;
        font-weight: 700;

        background-color: $white;
        height: 43px;

        &:hover {
          background-color: $white !important;
        }
      }
      .next {
        background-color: $white;
        height: 43px;
        position: relative;

        &:hover {
          background-color: $white !important;
        }

        &:after {
          border: none;
          @include angle-bracket(right, 12px, 2px, $blue);

          display: block;
          content: '';
          position: absolute;
          top: 0; left: 0;
          bottom: 0; right: 0;
          margin: auto;
        }
      }

      .cell {
        height: 33px;
        width: 33px;
        text-align: center;
        line-height: 32px;
        border: 1px solid $blue-light !important;
        margin-top: -1px;
        margin-left: -1px;
        background-color: $white;
        font-size: 10px;
        color: $black;

        &.day-header {
          background-color: transparent;
          border-color: transparent;
          color: $black;
          font-size: 10px;
          text-align: center;
          text-transform: uppercase;
          cursor: pointer;
          height: 39px;
          line-height: 39px;

          &:hover {
            background-color: transparent;
          }
        }

        &.blank {
          background-color: $blue-dark;
          &:hover {
            background-color: $blue-dark;
          }
        }

        &.today {
          background-color: $blue-dark;
          color: $white;
        }

        &.selected {
          background: $blue;
          color: $white;
          font-weight: 700;

          &:hover {
            background: $blue;
          }
        }

        &.month {
          overflow: hidden;
          text-overflow: ellipsis;
          width: 43px;
        }

        &.year {
          width: 51.4px;
        }
      }

      // modificators
      &.from {
        &:before {
          left: 112px;
        }
      }

      &.until {
        &:before {
          right: 98px;
        }
      }
    }

    .date-input {
      display: none;
    }

    .date-wrapper {
      position: static;
      width: 28px;
      height: 28px;

      .vdp-datepicker__calendar-button {
        position: absolute;
        width: 70px;
      }
    }

    .vdp-datepicker__calendar-button {
      position: absolute;
      width: 100px;
      top: -10px;
      left: 1px;
    }
  }
}
</style>