<template lang="html">
  <section class="scholarship-winner">
    <div class="scholarship-winner__top">
      <lazy-component>
        <user-scholarship-avatar class="scholarship-winner__avatar" :path="winner.winnerPhoto" :alt="winner.winnerName" />
      </lazy-component>
      <div class="scholarship-winner__top-right">
        <h3 class="winner-name scholarship-winner__name">{{ winner.winnerName }}</h3>
        <p class="winning-amount scholarship-winner__amount">{{ winner.amountWon }}</p>
      </div>
    </div>
    <div>
      <h2 class="winning-scholarship-name scholarship-winner__winning-scholarship">{{ winner.scholarshipTitle }}</h2>
      <p class="winning-date scholarship-winner__date">{{ date }}</p>
      <winner-story v-if="xs || s" class="winner-story scholarship-winner__story" type="html"
          :text="winner.testimonialText" clamp="(...)" :length="220" />
      <p v-else class="winner-story scholarship-winner__story" v-html="winner.testimonialText"></p>
      <lazy-component v-if="winner.testimonialVideo">
        <iframe class="scholarship-winner__video-link" width="100%" :src="winner.testimonialVideo" frameborder="0" allowfullscreen></iframe>
      </lazy-component>
    </div>
  </section>
</template>

<script>
import { mapGetters } from "vuex";
import WinnerStory from "vue-truncate-collapsed";
import UserScholarshipAvatar from "components/Pages/Winners/Winner/UserScholarshipAvatar.vue";

export default {
  props: {
    winner: {type: Object, required: true}
  },
  components: {
    UserScholarshipAvatar,
    WinnerStory,
  },
  computed: {
    date() {
      const monthSequence = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"];
      let date = this.winner.wonAt.date;

      return monthSequence[Number(date.substr(5, 2)) - 1] + ' ' + date.substr(0, 4);
    },
    ...mapGetters("screen", [
      "xs",
      "s"
    ])
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

  .winner-name {
    font-family: $open-sans;
    font-size: 18px;
    font-weight: bold;
    line-height: 1.3em;
    letter-spacing: 0.1px;
    color: $black;

    @include breakpoint($m) {
      font-size: 24px;
    }

    @include breakpoint($l) {
      font-size: 26px;
    }
  }

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

  .winning-scholarship-name {
    font-family: $open-sans;
    font-size: 18px;
    line-height: 1.3em;
    font-weight: 800;
    color: $iris;

    @include breakpoint($s) {
      font-size: 20px;
    }

    @include breakpoint($m) {
      font-size: 24px;
    }

    @include breakpoint($l) {
      font-size: 26px;
    }
  }

  .winning-date {
    font-family: $open-sans;
    font-size: 12px;
    line-height: 1.4em;
    font-style: italic;
    letter-spacing: 0.1px;
    color: $warm-grey;

    @include breakpoint($m) {
      font-size: 14px;
    }
  }

  .winner-story {
    font-family: $open-sans;
    font-size: 14px;
    line-height: 1.35em;
    letter-spacing: 0.1px;
    color: #4a4a4a;

    @include breakpoint($m) {
      font-size: 16px;
    }
  }

  .scholarship-winner {
    &__top {
      @include flexbox();
      @include align-items(center);
    }

    &__top-right {
      margin-left: 25px;

      @include breakpoint($m) {
        margin-left: 40px;
      }
    }

    &__avatar {
      width: 100px;
      width: 100px;

      @include breakpoint($m) {
        width: 144px;
        height: 144px;
      }

      @include breakpoint($xl) {
        width: 160px;
        height: 160px;
      }
    }

    &__amount {
      margin-top: 7px;
    }

    &__winning-scholarship {
      margin-top: 17px;

      @include breakpoint($s) {
        margin-top: 15px;
      }

      @include breakpoint($m) {
        margin-top: 24px;
      }
    }

    &__date {
      margin-top: 13px;
    }

    &__story {
      margin-top: 14px;

      @include breakpoint($m) {
        margin-top: 19px;
      }
    }

    &__video-link {
      margin-top: 20px;
      min-height: 175px;

      @include breakpoint($s) {
        max-width: 280px;
        min-height: 170px;
      }

      @include breakpoint($m) {
        max-width: 344px;
        min-height: 208px;
        margin-top: 30px;
      }

      @include breakpoint($l) {
        max-width: 476px;
        min-height: 288px;
      }

      @include breakpoint($xl) {
        max-width: 582px;
        min-height: 350px;
      }
    }
  }
</style>
