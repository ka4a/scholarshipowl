<template>
  <div class="accordion-item">
    <div @click="$emit('open')">
      <div class="accordion-item__top">
        <h3 v-if="title" class="accordion-item__title">{{ title }}</h3>
        <Indicator :state="isOpen" class="accordion-item__indicator" />
      </div>
      <template v-if="messages && messages.length && isValid !== null">
        <Message v-for="message in messages" :key="message" class="accordion-item__message"
        :message="capitalizeFirstChar(message)" :is-valid="isValid" />
      </template>
    </div>
    <slot v-if="isOpen" class="accordion-item__input" name="default" />
  </div>
</template>

<script>
  import { capitalizeFirstChar } from "lib/utils/utils";
  import Indicator from "components/Common/Indicators/StateIndicatorArrow.vue";
  import Message from "components/Common/Accordion/AccordionMessage.vue";

  export default {
    components: {
      Message,
      Indicator
    },
    props: {
      messages: {type: Array, default: null},
      isValid:  {type: Boolean, default: null},
      isOpen:   {type: Boolean, default: false},
      title:    {type: String, required: true},
    },
    computed: {
      notifications() {
        return Array.isArray(this.message)
          ? this.message : this.message && [this.message]
      }
    },
    methods: {
      capitalizeFirstChar
    }
  }
</script>

<style lang="scss">
  // global variables
  // assets/sass/style-gide/palette/_variables.scss
  // $blue-bayoux
  // $geyser

  .accordion-item-title {
    font-size: 16px;
    font-weight: bold;
    color: $blue-bayoux;
  }

  .accordion-item {
    padding: 16px 15px;
    background-color: white;

    & + & {
      border-top: 2px solid $geyser;
    }

    &__title {
      font-size: 16px;
      font-weight: bold;
      color: $blue-bayoux;
    }

    &__top {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    &__message {
      margin-top: 8px;
    }

    .input-date,
    .input-select-basic,
    .mdc-radio-list {
      margin-top: 12px;
    }
  }
</style>