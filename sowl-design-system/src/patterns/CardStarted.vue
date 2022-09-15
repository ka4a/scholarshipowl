<template>
  <section
    :class="[
      'bg-gray-4 shadow-m rounded-sm text-gray-1 p-20 lg:px-30 lg:flex lg:justify-between',
      { 'lg:items-end': !isReady },
    ]"
  >
    <header>
      <p class="text-15 lg:text-18 font-semibold">{{ name }}</p>
      <div class="mt-10 lg:flex">
        <CardAttribute name="award">
          <span class="text-14 font-bold">{{ amount }}</span>
        </CardAttribute>
        <CardAttribute class="lg:ml-20" name="deadline">
          <span class="text-14 font-bold">{{ deadline }}</span>
        </CardAttribute>
      </div>
    </header>
    <footer class="mt-20 lg:mt-0 md:flex md:justify-between md:items-center lg:block">
      <CardButton
        class="md:max-w-240"
        :text="isReady ? 'APPLY FOR SCHOLARSHIP' : 'COMPLETE APPLICATION'"
        :type="isReady ? 'fill' : 'stroke'"
        :color="isReady ? 'green' : 'orange'"
        :wide="true"
      />
      <p class="text-center mt-15 md:mt-0 lg:mt-10 cursor-pointer" v-if="isReady">
        <Icon name="pen-stroke" size="13px" />
        <span class="text-15 md:text-14 text-oth-6">Edit application</span>
      </p>
    </footer>
  </section>
</template>

<script>
import CardAttribute from "./../elements/CardAttribute.vue"
import CardButton from "./../elements/CardButton.vue"
import Icon from "./../elements/Icon.vue"

export default {
  name: "CardStatedMobile",
  components: {
    CardAttribute,
    CardButton,
    Icon,
  },
  props: {
    name: { type: String, required: true },
    amount: { type: String, required: true },
    deadline: { type: String, required: true },
    isReady: { type: Boolean, required: true },
  },
}
</script>

<docs>
  ```jsx
  <div>
    <div style="margin-top: 10px;">
      <CardStatedMobile name="Worgul, Sarna & Ness, Criminal Defense Attorneys, Scholarship"
        amount="$1,000" deadline="04/30/2019"/>
    </div>
    <div style="margin-top: 10px;">
      <CardStatedMobile name="Worgul, Sarna & Ness, Criminal Defense Attorneys, Scholarship"
        amount="$1,000" deadline="04/30/2019" :is-ready="true" />
    </div>
  </div>
  ```
</docs>
