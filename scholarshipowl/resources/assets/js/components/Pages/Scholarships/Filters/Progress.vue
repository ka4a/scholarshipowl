<template lang="html">
  <ul class="status-filter">
    <li @click="setStatus(SCHOLARSHIP_STATUS.INCOMPLETE)"
        :class="['status-filter__item', {active: status.includes(SCHOLARSHIP_STATUS.INCOMPLETE)}]">
      <a class="status-filter__ctrl" href="#"><i class="status-indicator s1"></i><span>new</span></a>
    </li>
    <li @click="setStatus(SCHOLARSHIP_STATUS.IN_PROGRESS)"
        :class="['status-filter__item', {active: status.includes(SCHOLARSHIP_STATUS.IN_PROGRESS)}]">
      <a class="status-filter__ctrl" href="#"><i class="status-indicator s2"></i><span>started</span></a>
    </li>
    <li @click="setStatus(SCHOLARSHIP_STATUS.READY_TO_SUBMIT)"
        :class="['status-filter__item', {active: status.includes(SCHOLARSHIP_STATUS.READY_TO_SUBMIT)}]">
      <a class="status-filter__ctrl" href="#"><i class="status-indicator s3"></i><span>ready</span></a>
    </li>
  </ul>
</template>

<script>
import { SCHOLARSHIP_STATUS } from "lib/utils/filter";

import { mapState, mapActions } from "vuex";

export default {
  data: function() {
    return {
      SCHOLARSHIP_STATUS
    };
  },
  computed: {
    ...mapState({
      status: state => state.list.scholarships.filter.status,
    })
  },
  methods: {
    ...mapActions({
      setFilterParam: "list/setFilterParam"
    }),
    setStatus(currentStatus) {
      if(!currentStatus) return;

      let index = this.status.indexOf(currentStatus);
      index > -1 ? this.status.splice(index, 1) : this.status.push(currentStatus);

      this.setFilterParam({
        nameSpace: 'scholarships',
        filterBy: 'status',
        parameter: this.status
      });

      this.$emit('filter');
    }
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';
@import 'style-gide/mixins';

.status-filter {
  $color1: #F2F7FE;
  $color2: #E2E9FF;
  $color3: #2f2f2f;
  $color4: white;

  border: 1px solid $color2;
  border-radius: 2px;
  background-color: $color4;
  @include flexbox();
  @include justify-content(space-around);
  width: 100%;
  max-width: 276px;

  &__item {
    width: 33.333%;

    & + & {
      border-left: 1px solid $color2;
    }

    &.active {
      background-color: $color1;
    }
  }

  &__ctrl {
    color: $color3;
    font-size: 12px;
    text-transform: capitalize;
    text-align: center;
    display: block;
    padding: 7px 0;

    > span {
      display: block;
      line-height: 1.3em;
      margin-top: 4px;
    }

    @include breakpoint($s) {
      padding: 9px 0;
      line-height: 1.38em;
      font-size: 13px;
    }

    .status-indicator {
      @include breakpoint(max-width $s - 1px) {
        width: 10px;
        height: 10px;
      }
    }
  }
}
</style>
