<template>
  <button
    @click="handleClick"
    :class="['rounded-50 h-45 border-2 border-solid w-100 px-15', buttonStyles]"
  >
    <span :class="['text-15 font-bold', textStyles]">{{ text }}</span>
  </button>
</template>

<script>
/**
 * Buttons are generally used for interface actions. Suitable for all-purpose use.
 * Primary style should be used only once per view for main call-to-action.
 */

export default {
  name: "CardButton",
  status: "review",
  version: "0.0.1",
  props: {
    /**
     * Text which shows in button
     */
    text: { type: String, required: true },
    color: {
      type: String,
      default: "orange",
      validator: value => {
        return value.match(/(orange|green)/)
      },
    },
    /**
     * Appearing props which defines button appering style.
     * Could be 'stroke' or 'fill'
     */
    type: {
      type: Boolean,
      default: "stroke",
      validator: value => {
        return value.match(/(stroke|fill)/)
      },
    },
    /**
     * Props says to button expand to parent with or no.
     * Takes Boolean value. Default value is false.
     */
    wide: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    buttonStyles() {
      const styles = []

      switch (this.color) {
        case "orange":
          styles.push("border-prim-1")
          break
        case "green":
          styles.push("border-sec-1")
          break
      }

      if (this.type === "fill") {
        styles.push(this.color === "orange" ? "bg-prim-1" : "bg-sec-1")
      }

      if (this.wide) {
        styles.push("w-100per")
      }

      return styles
    },
    textStyles() {
      const styles = []

      if (this.type === "stroke") {
        switch (this.color) {
          case "orange":
            styles.push("text-prim-1")
            break
          case "green":
            styles.push("text-sec-1")
            break
        }
      } else {
        styles.push("text-gray-4")
      }

      return styles
    },
  },
  methods: {
    handleClick() {
      alert("click occured")
    },
  },
}
</script>

<docs>
  ```jsx
  <div>
    <CardButton text="COMPLETE APPLICATION" type="stroke" color="orange" />
    <CardButton text="COMPLETE APPLICATION" type="stroke" color="green" />
    <CardButton text="COMPLETE APPLICATION" type="fill" color="orange" />
    <CardButton text="COMPLETE APPLICATION" type="fill" color="green"/>
    <CardButton style="margin-top: 5px" text="COMPLETE APPLICATION" type="stroke" color="orange" :wide="true" />
  </div>
  ```
</docs>
