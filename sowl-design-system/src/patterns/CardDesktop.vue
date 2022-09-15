<template>
  <section class="bg-gray-4 shadow-m rounded-sm text-gray-1">
    <header class="flex min-h-80 pl-30 pr-40 pt-15 flex">
      <p class="text-gray-1 text-18 font-semibold w-14per">{{ amount }}</p>
      <p class="text-gray-1 text-14 w-14per">{{ deadline }}</p>
      <p class="w-50per" style="line-height: 1.12em">
        <span class="text-15 font-semibold block">{{ name }}</span>
        <span class="text-14 text-oth-6 block">{{ requirements }}</span>
      </p>
      <p class="text-14 w-11per text-center">{{ requirementNumber }}</p>
      <p class="ml-auto">
        <IconInShape
          shape-type="circle"
          shape-size="25px"
          shape-color="#ED3041"
          icon-name="heart"
        />
      </p>
    </header>
    <footer style="border-top: 1px solid #F3F3F3" class="h-55 pl-30 pr-40"></footer>
  </section>
</template>

<script>
import IconInShape from "../elements/IconInShape.vue"

export default {
  name: "CardDesktop",
  status: "review",
  release: "1.0.0",
  component: {
    IconInShape,
  },
  props: {
    amount: { type: String, required: true },
    deadline: { type: String, required: true },
    name: { type: String, required: true },
    requirements: { type: String, required: true },
    isPicked: { type: Boolean, default: false },
    requirementNumber: { type: Number, required: true },
  },
}
</script>

<docs>
  ```jsx
  <div>
    <CardDesktop
      amount="$500"
      deadline="02/12/2020"
      name="Worgul, Sarna & Ness, Criminal Defense Attorneys,  Scholarship"
      requirements="Requirements: Essay, Video, Document"
      :requirementNumber="3" />
  </div>
  ```
</docs>
