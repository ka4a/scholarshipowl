<template>
  <div class="vue-accordion-item">
    <slot nama="title" />
    <p><slot name="text" /></p>
  </div>
</template>

<script>
  export default {
    props: {
      isOpen: {type: Boolean, default: false}
    },
    methods: {
      handleClick() {
        console.log('trigger');
      }
    }
  }
</script>