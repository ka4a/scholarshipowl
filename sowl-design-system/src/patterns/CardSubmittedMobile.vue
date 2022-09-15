<template>
  <section class="shadow-m rounded-sm text-gray-1 p-20 leading-14 bg-gray-4">
    <p class="text-18 font-semibold">{{ amount }}</p>
    <p class="text-15 mt-5">{{ name }}</p>
    <CardAttribute class="mt-10" name="status">
      <span class="text-14 font-bold">{{ status }}</span>
    </CardAttribute>
    <CardAttribute name="submit"
      ><span class="text-14">{{ submitDate }}</span></CardAttribute
    >
  </section>
</template>

<script>
import CardAttribute from "./../elements/CardAttribute.vue"

export default {
  name: "CardSubmittedMobile",
  status: "review",
  release: "0.0.1",
  props: {
    amount: { type: String, required: true },
    name: { type: String, required: true },
    status: { type: String, required: true },
    submitDate: { type: String, required: true },
  },
}
</script>

<docs>
  ```jsx
  <div>
    <CardSubmittedMobile style="max-width: 420px"
      amount="$500"
      name="Worgul, Sarna & Ness, Criminal Defense Attorneys,  Scholarship"
      status="Missed"
      submitDate="02/12/2020" />
  </div>
  ```
</docs>
