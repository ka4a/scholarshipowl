<template>
  <section class="profile-completeness-indicator">
    <progress-indicator class="profile-completeness-indicator__line" :completeness="completeness" />
    <p class="profile-completeness-indicator__value">{{completeness.toString()}}% <span v-if="l || xl || xxl">complete</span></p>
  </section>
</template>

<script>
  import { mapGetters } from "vuex";
  import ProgressIndicator from "components/Pages/Own/ProgressIndicator.vue";

  export default {
    components: {
      ProgressIndicator
    },
    props: {
      completeness: {type: Number, requred: true }
    },
    computed: {
      ...mapGetters({
        l: "screen/l",
        xl: "screen/xl",
        xxl: "screen/xxl",
      }),
    }
  }
</script>

<style lang="scss">

@import 'style-gide/breakpoints';

  $open-sans: 'Open Sans';

  $dark: #2f2f2f;
  $green: #2fd9b3;

  .profile-completeness-indicator {
    display: flex;
    align-items: center;

    &__line {
      height: 8px;
      border-radius: 5px;
      background-color: rgba(155, 176, 210, 0.27);

      .progress-indicator-line {
        height: 8px;
        background-color: $green;
        border-radius: 5px;
      }
    }

    &__value {
      font-family: $open-sans;
      font-weight: 600;
      font-size: 14px;
      color: $dark;
      margin-left: 15px;
      flex: 1 0 auto;

      @include breakpoint($l) {
        margin-left: 24px;
      }

      span {
        font-size: 12px;
      }
    }
  }
</style>