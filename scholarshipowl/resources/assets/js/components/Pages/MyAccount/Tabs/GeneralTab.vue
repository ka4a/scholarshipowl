<template>
  <section class="general-tab">
    <template v-for="info in infos">
      <a class="general-tab__info-block" :href="info.redirect">
        <information-block skin="combine" :title="info.title" :text="info.text" :img="info.img">
          <p slot="count" v-if="info.slot && info.slot.value"
            :class="['my-account__count', {'dark-c': !info.slot.new}, {'orange-c': info.slot.new}]">{{ info.slot.value }}</p>
        </information-block>
      </a>
    </template>
  </section>
</template>

<script>
  import InformationBlock from "components/Pages/MyAccount/InformationBlock.vue";

  export default {
    name: "general-tab",
    components: {
      InformationBlock
    },
    props: {
      infos: {type: Object, required: true}
    },
  }
</script>

<style lang="scss">
  .general-tab {
    max-width: 730px;

    @include breakpoint($m) {
      display: flex;
      justify-content: center;
    }

    &__info-block {
      display: block;
      cursor: pointer;
      box-shadow: 0 3px 6px 0 rgba(53, 76, 109, 0.15);

      @include breakpoint($m) {
        min-width: 198px;
        max-width: 227px;
        width: 100%;

        & + & {
          margin-left: 12px;
        }
      }

      @include breakpoint(max-width $m - 1px) {
        & + & {
          margin-top: 12px;
        }
      }
    }
  }
</style>

