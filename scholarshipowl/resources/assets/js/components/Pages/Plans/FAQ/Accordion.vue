<template>
  <section class="accordion-vue">
    <div v-for="(item, i) in items">
      <h4 @click="selectItem(i)">
        <slot name="controller"
          v-bind="{ctrl: item[controllerName], isActive: i === indexSelected}" />
      </h4>
      <p v-if="indexSelected === i" :class="{
        'accordion-vue__repres': true,
        'accordion-vue_close': indexSelected !== i,
        'accordion-vue_open': indexSelected === i}">
        <slot name="representation"
          v-bind="{repres: item[representationName]}" />
      </p>
    </div>
  </section>
</template>

<script>
  export default {
    props: {
      items: {type: Array, default: []},
      controllerName: {type: String, default: 'key'},
      representationName: {type: String, default: 'value'}
    },
    data() {
      return {
        indexSelected: 0
      }
    },
    methods: {
      selectItem(index) {
        const value = index !== this.indexSelected
          ? index : undefined;

        this.indexSelected = value;
      }
    }
  }
</script>

<style lang="scss">
  .accordion-vue {
    &__repres {
      overflow: hidden;
    }

    &_close {
      // max-height: 0;
      // transition: max-height .3s ease;
    }

    &_open {
      // max-height: 150px;
      // transition: max-height .9s ease;
    }
  }
</style>