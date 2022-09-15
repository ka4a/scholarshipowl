<template>
  <component :is="type">
    <span class="text-14 lg:text-15 text-oth-6"
      ><Icon size="12px" :name="content.iconName" /> {{ content.label }}:</span
    >
    <slot />
  </component>
</template>

<script>
import Icon from "./Icon.vue"

const nameByContent = {
  status: {
    iconName: "information-circle",
    label: "Status",
  },
  award: {
    iconName: "cup",
    label: "Award",
  },
  submit: {
    iconName: "clock",
    label: "Submitted",
  },
  deadline: {
    iconName: "clock",
    label: "Deadline",
  },
}

export default {
  name: "CardAttribute",
  components: {
    Icon,
  },
  props: {
    type: { type: String, default: "p" },
    name: {
      type: String,
      required: true,
      validator: value => {
        return value.match(/(status|award|deadline|submit)/)
      },
    },
  },
  computed: {
    content() {
      return nameByContent[this.name]
    },
  },
}
</script>

<docs>
  ```jsx
  <div>
    <CardAttribute name="status">
      <span class="text-14 font-semibold">Missed</span>
    </CardAttribute>
    <CardAttribute name="award">
      <span class="text-14 font-semibold">$2,500</span>
    </CardAttribute>
    <CardAttribute name="deadline">
      <span class="text-14">04/27/2019</span>
    </CardAttribute>
    <CardAttribute name="submit">
      <span class="text-14">04/27/2019</span>
    </CardAttribute>
  </div>
  ```
</docs>
