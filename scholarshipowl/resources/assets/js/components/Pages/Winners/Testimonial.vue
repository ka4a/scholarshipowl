<template lang="html">
  <section class="testimonial" @click="selectWinner(winner)">
    <h2 :class="['testimonial__scholarship-name', {'testimonial__scholarship-name_selected': selected }]">{{ winner.scholarshipTitle }}</h2>

    <div class="testimonial__top">
      <lazy-component>
        <user-scholarship-avatar class="testimonial__avatar" :path="winner.winnerPhoto" :alt="winner.winnerName" />
      </lazy-component>
      <div class="testimonial__top-right">
        <h3 class="testimonial__name">{{ winner.winnerName }}</h3>
        <p class="testimonial__amount">{{ winner.amountWon }}</p>
      </div>
    </div>
    <div class="testimonial__description" v-html="trancate(winner.testimonialText, 200)"></div>
  </section>
</template>

<script>
import WinnerStory from "vue-truncate-collapsed";
import UserScholarshipAvatar from "components/Pages/Winners/Winner/UserScholarshipAvatar.vue";

export default {
  props: {
    winner: {type: Object, required: true},
    selectWinner: {type: Function, required: true},
    selected: {type: Boolean, default: false}
  },
  components: {
    UserScholarshipAvatar,
    WinnerStory,
  },
  methods: {
    trancate(string, maxLength) {
      return string.length > maxLength ? string.substring(0, maxLength) + " (...)" : string;
    }
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';

  $black: #2f2f2f;
  $warm-grey: #9b9b9b;
  $iris: #506fc6;

  $open-sans: 'Open Sans';

  .winning-amount {
    font-family: $open-sans;
    font-size: 18px;
    letter-spacing: 0.1px;
    line-height: 1.3em;
    color: $black;

    @include breakpoint($m) {
      font-size: 24px;
    }

    @include breakpoint($l) {
      font-size: 26px;
    }
  }

  .testimonial {
    cursor: pointer;

    &__avatar {
      width: 60px;
      height: 60px;
    }

    &__scholarship-name {
      font-family: $open-sans;
      font-size: 16px;
      line-height: 1.35em;
      font-weight: 800;
      color: $black;

      @include breakpoint($m) {
        font-size: 18px;
      }

      &_selected {
        color: $iris;
      }
    }

    &__top {
      @include flexbox();
      @include align-items(center);
      margin-top: 12px;

      @include breakpoint($m) {
        margin-top: 21px;
      }
    }

    &__top-right {
      margin-left: 20px;
    }

    &__name {
      font-family: $open-sans;
      font-size: 16px;
      font-weight: bold;
      letter-spacing: 0.1px;
      color: $black;

      @include breakpoint($m) {
        font-size: 18px;
      }
    }

    &__amount {
      font-family: $open-sans;
      font-size: 16px;
      letter-spacing: 0.1px;
      line-height: 1.3em;
      color: $black;

      margin-top: 7px;

      @include breakpoint($m) {
        font-size: 18px;
      }
    }

    &__description {
      font-family: $open-sans;
      font-size: 14px;
      line-height: 1.35em;
      letter-spacing: 0.1px;
      color: $black;

      margin-top: 12px;

      @include breakpoint($m) {
        font-size: 16px;
      }
    }
  }
</style>
