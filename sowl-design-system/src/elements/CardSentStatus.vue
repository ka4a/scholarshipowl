<template>
  <component
    class="rounded-xs bg-sec-1 py-2 opacity-70 text-13 text-gray-1 capitalize px-10"
    :is="type"
  >
    {{ status }}
  </component>
</template>

<script>
export default {
  name: "CardSentStatus",
  status: "review",
  release: "0.0.1",
  props: {
    type: { type: String, default: "p" },
    status: { type: String, required: true },
  },
}
</script>

<docs>
  ```jsx
  <div>
    <CardSentStatus status="sent" />
  </div>
  ```
</docs>
