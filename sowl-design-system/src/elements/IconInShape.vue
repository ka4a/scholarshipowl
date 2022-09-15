<template>
  <component
    :class="['shape-icon flex justify-center items-center cursor-pointer', className]"
    :is="type"
    :style="{ width: shapeSize, height: shapeSize, backgroundColor: shapeColor }"
  >
    <Icon class="block" :name="iconName" size="12px" />
  </component>
</template>

<script>
import Icon from "./Icon.vue"

export default {
  name: "IconInShape",
  status: "review",
  release: "1.0.0",
  components: {
    Icon,
  },
  props: {
    type: { type: String, default: "div" },
    shapeType: { type: String, required: true },
    shapeSize: { type: String, required: true },
    shapeColor: { type: String, required: true },
    iconName: { type: String, required: true },
    iconColor: { type: String, required: true },
    iconWidth: { type: String, required: true },
  },
  computed: {
    className() {
      if (this.shapeType === "circle") return "rounded-circle"
    },
  },
}
</script>

<style lang="scss">
.shape-icon {
  @include fill-svg;

  svg {
    width: 100%;
  }
}
</style>

<docs>
  ```jsx
  <div>
    <IconInShape
      shape-type="circle"
      shape-size="25px"
      shape-color="#ED3041"
      icon-name="heart" />
  </div>
  ```
</docs>
