<template lang="html">
 <article class="inner-indent winners">
   <h1 class="title winners__title">ScholarshipOwl Success Stories</h1>
   <p class="winners__description description">Our mission at Scholarshipowl is to get students the scholarship they need. Since 2015,
   we have granted scholarship awards and connected with millions of scholarships.</p>
   <div class="winners__image image-fluid">
      <img v-if="xs || s || m" src="./Winners/winners-party.jpg">
      <img v-else src="./Winners/winners-party-2.jpg">
   </div>

   <div v-if="winners" class="winners__middle">
    <div class="winners__dropdown-set" v-if="xs || s">
      <drop-down class="winners__dropdown" :options="years" :value="selectedYear" :select="selectYear" />
      <drop-down class="winners__dropdown" :options="months" :value="selectedMonth" :select="selectMonth" />
    </div>
    <time-line v-else class="winners__time-line" :years="years" :months="months" :selected-year="selectedYear" :selected-month="selectedMonth"
                :select-year="selectYear" :select-month="selectMonth" />

    <winner v-if="selectedWinner && !(xs || s)" class="winners__winner" :winner="selectedWinner" />

    <carousel v-if="xs || s" class="winners-slider-s" :per-page="1" :autoplay="false" :navigation-enabled="selectedWinners.length > 1"
              navigationNextLabel="" navigationPrevLabel="" :paginationEnabled="true" :autoplayTimeout="5000"
              paginationColor="#000000" paginationActiveColor="#506fc6" :paginationSize="7">
      <slide v-for="winner in selectedWinners" :key="winner.id"><winner class="winners__winner" :winner="winner" /></slide>
    </carousel>
  </div>

  <section v-if="winners && !(xs || s)" class="winners__slider">
    <carousel v-if="shouldInitCarousel" class="winners-slider"
      :perPageCustom="[[480, 1],[768, 2],[1024, 3]]" :autoplay="false" :navigation-enabled="true"
      navigationNextLabel="" navigationPrevLabel="" :paginationEnabled="false" :autoplayTimeout="5000">

      <slide v-for="winner in selectedWinners" :key="winner.id">
        <testimonial class="winners__testimonial" :selected="winner.id === selectedWinner.id" :winner="winner" :select-winner="selectWinner" />
      </slide>
    </carousel>

    <div class="testimonial-wrapper" v-if="!shouldInitCarousel && selectedWinners.length > 1">
      <testimonial v-for="winner in selectedWinners" :key="winner.id" class="testimonial-wrapper__item"
      :selected="winner.id === selectedWinner.id" :select-winner="selectWinner" :winner="winner" />
    </div>
  </section>

  <p v-if="authenticated" class="call-to-join winners__call-to-join">Your {{ eligibleScholarships }} scholarship matches are waiting for you!</p>
  <p v-else class="call-to-join winners__call-to-join">Get started on the path to YOUR success!</p>

  <a :href="authenticated ? '/scholarships' : '/register'" class="call-to-join-button call-to-join-button-text winners__button">{{ authenticated ? 'APPLY NOW' : 'join us today' }}</a>
  <div class="image-fluid winners__background">
    <img v-if="xs || s || m || l" src="./Winners/winners-blue-waves-small.svg">
    <img v-else src="./Winners/winners-blue-waves-large.svg">
  </div>
  <LoginModal />
 </article>
</template>

<script>
import { mapGetters } from "vuex";
import { Carousel, Slide } from "vue-carousel";
import { WinnersResource } from "resource";
import { formatAmount } from "lib/utils/utils";

import Winner from "components/Pages/Winners/Winner.vue";
import Testimonial from "components/Pages/Winners/Testimonial.vue";
import TimeLine from "components/Pages/Winners/TimeLine.vue";
import DropDown from "components/Pages/Winners/DropDown.vue";
import LoginModal from "components/Layout/LoginRegister.vue";

const monthSequence = ["January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"];

function listOfYears(winners) {
  let years = [];
  const YEAR_LANGTH = 4;

  winners.map(winner => {
    return Number(winner.wonAt.date.substring(0, YEAR_LANGTH));
  }).sort().reverse().forEach(year => {
    if(years.indexOf(year) === -1) {
      years.push(year);
    }
  });

  return years;
}

function months() {
  let months = [];
  const YEAR_LANGTH = 4;
  const SPACE_LANGTH = 1;
  const MONTH_STEP = 2;

  this.winners.forEach(winner => {
    if(winner.wonAt.date.indexOf(this.selectedYear + "") !== -1) {
      let month = monthSequence[Number(winner.wonAt.date.substr(YEAR_LANGTH + SPACE_LANGTH, MONTH_STEP)) - 1];

      if(months.indexOf(month) === -1) {
        months.push(month);
      }
    }
  });

  months.sort(function(a, b){
    return monthSequence.indexOf(b) - monthSequence.indexOf(a);
  });

  return months;
}

export default {
  components: {
    Winner,
    Testimonial,
    TimeLine,
    DropDown,
    Carousel,
    Slide,
    LoginModal
  },
  created() {
    WinnersResource.winners()
      .then(response => {
        if(response.status === 200 && response.body.data) {
          this.winners = response.body.data.map(item => {
            item.amountWon = formatAmount(item.amountWon.toString(), true);
            return item;
          });
          this.years = listOfYears(this.winners);
          this.selectYear(this.years[0]);
          this.$emit('loaded');
        }
      })
  },
  data() {
    return {
      winners: null,
      years: null,
      selectedYear: null,
      selectedMonth: null,
      selectedWinners: null,
      selectedWinner: null,
    };
  },
  computed: {
    ...mapGetters("screen", ["xs", "s", "m", "l", "xl", "xxl"]),
    ...mapGetters("account", ["authenticated", "eligibleScholarships"]),
    months,
    shouldInitCarousel() {
      let carouselWinnerCount = this.selectedWinners.slice(1).length;

      return ((this.xs || this.s || this.m) && carouselWinnerCount > 1)
            || (this.l && carouselWinnerCount > 2)
            || ((this.xl || this.xxl) && carouselWinnerCount > 3);
    },
  },
  methods: {
    selectYear(year) {
      this.selectedYear = year;
      this.selectMonth(this.months[0]);
    },
    selectMonth(month) {
      this.selectedMonth = month;
      this.selectWinners();
      this.selectWinner(this.selectedWinners[0]);
    },
    selectWinners() {
      let numericMonth = monthSequence.indexOf(this.selectedMonth) + 1;
      numericMonth = numericMonth + '';

      if(numericMonth.length < 2) {
        numericMonth = 0 + numericMonth;
      }

      let yearMonth = this.selectedYear + '-' + numericMonth;

      this.selectedWinners = this.winners.filter(winner =>
        winner.wonAt.date.indexOf(yearMonth) !== -1)
    },
    selectWinner(winner) {
      this.selectedWinner = winner;
    }
  }
};

</script>

<style lang="scss">
@import 'main/meta/reset';

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';
@import 'style-gide/mixins';
@import 'main/meta/helpers';
@import 'scholarships/shapes-icons';

  $black: #4a4a4a;
  $dark: #2f2f2f;
  $white: #ffffff;
  $pinkish-orange-two: #fe774a;
  $pinkish-grey: #c9c9c9;
  $pale-sky-blue: #cee2ff;
  $warm-grey: #9b9b9b;
  $iris: #506fc6;
  $pinkish-orange: #ff6633;
  $dark-sky-blue: #4181ed;
  $tealish: #2fd9b3;
  $dark-grey-blue: #354c6d;

  $main-font: 'Open Sans';

  %reset-focus-text {
    &:focus {
      color: $white;
      text-decoration: none;
    }
  }

  body {
    font-family: $main-font;
  }

  .title {
    font-family: $main-font;
    font-size: 28px;
    color: $dark;

    @include breakpoint($s) {
      font-size: 34px;
    }

    @include breakpoint($m) {
      font-size: 44px;
    }

    @include breakpoint($l) {
      font-size: 46px;
    }
  }

.description {
  font-family: $main-font;
  font-size: 16px;
  font-weight: 300;
  letter-spacing: 0.1px;
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

.image-fluid {
  width: 100%;

  img {
    display: block;
    width: 100%;
  }
}

.call-to-join {
  font-family: $open-sans;
  font-size: 24px;
  line-height: 1.375em;
  color: $white;

  @include breakpoint($s) {
    font-size: 32px;
  }

  @include breakpoint($m) {
    font-size: 36px;
  }
}

.call-to-join-button-text {
  font-family: $open-sans;
  font-size: 16px;
  text-transform: uppercase;
  font-weight: bold;
  color: $white;

  @extend %reset-focus-text;

  @include breakpoint($s) {
    font-size: 18px;
  }
}

.call-to-join-button {
  display: block;
  width: 160px;
  height: 40px;
  line-height: 40px;
  border-radius: 2px;
  background-color: $pinkish-orange-two;
  text-align: center;

  @include breakpoint($s) {
    width: 200px;
    line-height: 52px;
    height: 52px;
  }

  &:hover {
    background-color: darken($pinkish-orange-two, 5);
    color: $white;
    text-decoration: none;
  }
}

.winners-slider-s {
  margin-top: 30px;

  .VueCarousel-dot-container {
    > li {
      margin-top: 0 !important;
      padding-top: 20 !important;
      padding-bottom: 0 !important;
    }
  }

  .VueCarousel-navigation {
    position: relative;

    > button:first-child {
      top: -11px !important;
      left: 22% !important;
      margin-right: 0 !important;
      padding: 0 !important;
      transform: none !important;

      &:before {
        @include angle-bracket(left, $size: 12px, $weight: 1px, $color: $black);
        content: '';
        position: absolute;
      }
    }

    > button:first-child + button {
      top: -11px !important;
      right: 27% !important;
      margin-right: 0 !important;
      padding: 0 !important;
      transform: none !important;

      &:before {
        @include angle-bracket(right, $size: 12px, $weight: 1px, $color: $black);
        content: '';
        position: absolute;
      }
    }
  }
}

.winners-slider {
  .VueCarousel {
    position: static !important;
  }

  .VueCarousel-navigation {
    > button {
      width: 36px;
      height: 36px;

      &:before {
        top: 6px;
      }
    }

    > button:first-child {
      transform: translateX(0);
      left: 0;

      @include breakpoint($m) {
        left: -64px;
      }

      &:before {
        @include angle-bracket(left, $size: 25px, $weight: 1px, $color: $black);
        content: '';
        position: absolute;
        right: 0;
      }
    }

    > button:first-child + button {
      transform: translateX(0);
      right: 0;

      @include breakpoint($m) {
        right: -64px;
      }

      &:before {
        @include angle-bracket(right, $size: 25px, $weight: 1px, $color: $black);
        content: '';
        position: absolute;
        left: 0;
      }
    }
  }
}

.testimonial-wrapper {
  @include flexbox();
  @include justify-content(center);

  &__item {
    max-width: 430px;
  }

  &__item + &__item {
    margin-left: 3%;
  }
}

#main:focus {
  outline: none;
}

.winners {
  position: relative;
  padding-bottom: 1px;

  &__title {
    margin-top: 20px;
    text-align: center;
    line-height: 1.3em;

    @include breakpoint($s) {
      margin-top: 30px;
    }

    @include breakpoint($m) {
      margin-top: 60px;
    }
  }

  &__description {
    margin-top: 19px;
    text-align: center;
    line-height: 1.4em;

    @include breakpoint($s) {
      margin-top: 15px;
    }

    @include breakpoint($m) {
      margin-top: 18px;
      max-width: 530px;
      margin-left: auto;
      margin-right: auto;
    }

    @include breakpoint($l) {
      margin-top: 19px;
      max-width: 636px;
    }

    @include breakpoint($xl) {
      max-width: 886px;
    }
  }

  &__image {
    margin-top: 20px;
    margin-bottom: 30px;

    @include breakpoint($s) {
      margin-top: 24px;
      margin-bottom: 40px;
    }

    @include breakpoint($m) {
      margin-top: 30px;
      margin-bottom: 60px;
      max-width: 530px;
      margin-left: auto;
      margin-right: auto;
    }

    @include breakpoint($l) {
      margin-top: 40px;
      max-width: 640px;
    }

    @include breakpoint($xl) {
      margin-top: 50px;
      margin-bottom: 70px;
      max-width: 768px;
    }
  }

  &__dropdown-set {
    @include flexbox();
    margin-top: 30px;
  }

  &__dropdown {
    width: 47%;

    & + & {
      margin-left: 6%;
    }
  }

  &__middle {
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;

    @include breakpoint($s) {
      @include flexbox();
    }
  }

  &__time-line {
    @include breakpoint($s) {
      margin-right: 9%;
      width: 25%;
      height: 100%;
      margin-left: 3%;
      max-width: 120px;
    }

    @include breakpoint($m) {
      margin-right: 6.5%;
      margin-left: 5%;
      max-width: 120px;
      padding-top: 10px;
    }

    @include breakpoint($xl) {
      max-width: 140px;
      margin-left: 3%;
      margin-right: 10%;
    }
  }

  &__winner {
    @include breakpoint($s) {
      width: 66%;
    }

    @include breakpoint($m) {
      width: 69%;
    }

    @include breakpoint($xl) {
      width: 74%;
    }
  }

  &__call-to-join {
    max-width: 446px;
    margin-left: auto;
    margin-right: auto;
    margin-top: 30%;
    text-align: center;

    @include breakpoint($l) {
      max-width: 700px;
      margin-top: 250px;
    }

    @include breakpoint($xl) {
      margin-top: 300px;
    }
  }

  &__button {
    margin-left: auto;
    margin-right: auto;
    margin-bottom: 25px;
    margin-top: 15px;

    @include breakpoint($s) {
      margin-top: 25px;
    }

    @include breakpoint($m) {
      margin-top: 40px;
      margin-bottom: 40px;
    }

    @include breakpoint($l) {
      margin-bottom: 60px;
    }

    @include breakpoint($xl) {
      margin-top: 54px;
      margin-bottom: 75px;
    }
  }

  &__background {
    position: absolute;
    left: 0;
    right: 0;
    bottom: -2px;
    z-index: -1;

    @include breakpoint($m) {
      height: 466px;
      overflow: hidden;
    }

    @include breakpoint($l) {
      height: 540px;
      height: auto;
      max-height: 650px;
    }
  }

  &__slider {
    margin-top: 25px;
    margin-left: auto;
    margin-right: auto;
    position: relative;

    @include breakpoint($m) {
      margin-top: 50px;
      max-width: 590px;
    }

    @include breakpoint($l) {
      max-width: 860px;
    }

    @include breakpoint($xl) {
      max-width: 1150px;
    }
  }

  &__testimonial {
    max-width: 280px;
    margin-left: auto;
    margin-right: auto;

    @include breakpoint($l) {
      max-width: 250px
    }

    @include breakpoint($l) {
      max-width: 340px;
    }
  }
}
</style>