<template lang="html">
  <ul class="links-footer">
    <li v-for="link in links" :key="link.id" class="links-footer__item"><a class="links-footer__link" :href="link.href">{{ link.text }}</a></li>
  </ul>
</template>

<script>
export default {
  props: {
    links: {type: Array, required: true}
  }
};
</script>

<style lang="scss">
@import 'main/meta/flex-box';

  $footer-layout-color: #8094B0;

  .links-footer {
    // ul reset
    list-style: none;
    padding-top: 0;
    padding-bottom: 0;

    &__item {
      display: inline;

      & + & {
        margin-left: 25px;
      }
    }

    &__link {
      color: $footer-layout-color;
      &:focus {
        color: $footer-layout-color;
        text-decoration: none;
      }

      &:hover {
        color: darken($footer-layout-color, 10);
        text-decoration: none;
      }


    }
  }
</style>
