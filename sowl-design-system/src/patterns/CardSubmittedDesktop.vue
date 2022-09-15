<template>
  <CardWrpDesktop>
    <div slot="header" class="flex min-h-96 flex items-center">
      <p class="text-gray-1 text-18 font-semibold w-14per">{{ amount }}</p>
      <p class="text-15 font-semibold block">{{ name }}</p>
      <CardAttribute class="ml-auto" name="status">
        <span class="text-14">{{ submitDate }}</span>
      </CardAttribute>
    </div>
    <CardSentStatus slot="footer" status="sent" />
  </CardWrpDesktop>
</template>

<script>
import CardWrpDesktop from "./CardWrpDesktop.vue"
import CardAttribute from "./../elements/CardAttribute.vue"
import CardSentStatus from "./../elements/CardSentStatus.vue"

export default {
  name: "CardSubmittedDesktop",
  status: "review",
  release: "0.0.1",
  component: {
    CardWrpDesktop,
    CardAttribute,
    CardSentStatus,
  },
  props: {
    amount: { type: String, required: true },
    name: { type: String, required: true },
    submitDate: { type: String, required: true },
  },
}
</script>

<docs>
  ```jsx
  <div>
    <CardSubmittedDesktop
      amount="$500"
      name="Worgul, Sarna & Ness, Criminal Defense Attorneys,  Scholarship"
      submitDate="02/12/2020" />

    <CardSubmittedDesktop
      class="mt-15"
      amount="$1,200"
      name="You Deserve it weekly scholarship"
      submitDate="02/12/2020" />
  </div>
  ```
</docs>
