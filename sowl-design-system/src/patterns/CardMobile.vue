<template>
  <section
    class="card-mobile rounded-sm bg-gray-4 text-gray-1 flex flex-col justify-between shadow-m pt-15"
  >
    <header class="flex justify-between items-center px-20">
      <h5 class="text-gray-1 text-18 font-semibold">{{ amount }}</h5>
      <p class="text-gray-1 text-14">Due on: {{ deadline }}</p>
    </header>
    <main class="px-20">
      <p class="text-15 font-semibold">{{ name }}</p>
      <p class="text-14 text-oth-6 mt-8">{{ requirements }}</p>
    </main>
    <footer
      style="border-top: 1px solid #F3F3F3"
      class="flex justify-end h-45 px-20 flex items-center"
    >
      <IconInShape shape-type="circle" shape-size="25px" shape-color="#ED3041" icon-name="heart" />
    </footer>
  </section>
</template>

<script>
import IconInShape from "../elements/IconInShape.vue"

export default {
  name: "CardMobile",
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
  },
}
</script>

<style lang="scss">
.card-mobile {
  min-height: 228px;
}
</style>

<docs>
  ```jsx
  <div>
    <CardMobile style="width: 280px"
      amount="$15,000"
      deadline="02/21/2021"
      name="Worgul, Sarna & Ness, Criminal Defense Attorneys,  Scholarship"
      requirements="3 Requirements: Essay, Video, Document" />
  </div>
  ```
</docs>
