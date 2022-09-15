<template>
  <section :class="['dash-board-tab', 'flex', { active: isActive }]">
    <header class="dash-board-tab__header">
      <Icon :name="iconName" />
    </header>
    <footer class="dash-board-tab__footer">
      <p class="text-xl dash-board-tab__name">{{ name }}</p>
      <p class="dash-board-tab__amount">{{ amount }}</p>
    </footer>
  </section>
</template>

<script>
import Icon from "@/elements/Icon.vue"

export default {
  name: "DashBoardTab",
  status: "prototype",
  release: "1.0.0",
  components: {
    Icon,
  },
  props: {
    name: { type: String, required: true },
    amount: { type: String, required: true },
    iconName: { type: String, required: true },
    isActive: { type: Boolean, default: false },
  },
}
</script>

<style lang="scss">
.dash-board-tab {
  border-radius: $radius-small;
  font-family: $font-text;
  height: 84px;
  background-color: $color-onahau;
  align-items: center;
  cursor: pointer;

  p {
    @include reset;
  }

  &__header {
    min-width: 75px;
    text-align: center;
  }

  &__name {
    font-size: $size-16;
    font-weight: $weight-semi-bold;
    color: $color-blue-bayoux;

    @media #{$media-query-m} {
      font-size: $size-15;
    }

    @media #{$media-query-l} {
      font-size: $size-17;
    }
  }

  &__amount {
    color: $color-bali-hai;
    font-size: $size-16;

    margin-top: 5px !important;
  }

  &:hover {
    box-shadow: $shadow-l;
  }

  &.active {
    background-color: $color-havelock-blue;

    .dash-board-tab__amount,
    .dash-board-tab__name {
      color: $color-white;
    }

    @include fill-svg;
  }
}
</style>

<docs>
  ```jsx
  <div>
    <DashBoardTab
      icon-name="cursor-click"
      name="Scholarship matches"
      amount="1,280" />
    <DashBoardTab style="margin-top: 10px"
      icon-name="cursor-click"
      name="Picked scholarships"
      amount="54" />
  </div>
  ```
</docs>
