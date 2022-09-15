<template lang="html">
  <div v-if="selectedTab !== SENT_SCHOLARSHIPS" class="scholarship-card">
    <transition name="card-loader">
      <card-loader class="scholarship-card__loader" v-if="pendingNotification" />
    </transition>
    <transition name="card-notif">
      <div v-if="!pendingNotification && finishNotification" class="scholarship-card__notif"> {{ finishNotification }}</div>
    </transition>
    <transition name="card-item">
      <card v-if="!pendingNotification && !finishNotification" :class="['scholarship-card__item', { 'selected': selected }]"
        :requirements-count="requirementsCount" :item="item" :amount="amount" :deadline-soon="deadlineSoon"
        :favorite="favorite" :expiration-date="expirationDateFormated"/>
    </transition>
  </div>
  <div v-else class="scholarship-card">
    <sent-card :class="['scholarship-card__item', { 'selected': selected }]"
      :item="item" :requirements-count="requirementsCount" :amount="amount" :applied-on="appliedOn" />
  </div>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import { SENT_SCHOLARSHIPS, NEW_SCHOLARSHIPS, FAVORITES_SCHOLARSHIPS } from "store/scholarships";
import { formatAmount } from "lib/utils/utils";
import { clientTime, dateFormat } from "lib/utils/format";

import CardLoader from "components/Pages/Scholarships/CardLoader/CardLoader.vue";
import Card from "components/Pages/Scholarships/ScholarshipsList/Card.vue";
import SentCard from "components/Pages/Scholarships/ScholarshipsList/SentCard.vue";

function applyCardNotification(message, callback) {
  setTimeout(() => {
    this.pendingNotification = "";
    this.finishNotification = message;
  }, 500);

  setTimeout(() => {
    this.finishNotification = "";
    if(callback) {
      callback();
    }
  }, 2000);
}

const DAY = 8.64e+7;
const WEEK = DAY * 7;

export default {
  components: {
    CardLoader,
    Card,
    SentCard
  },
  props: {
    item: { required: true, type: Object },
    selected: { required: true, type: Boolean, default: false }
  },
  data() {
    return {
      pendingNotification: "",
      finishNotification: "",
      SENT_SCHOLARSHIPS
    };
  },
  computed: {
    deadlineSoon() {
      return this.expirationDate
        .valueOf() < ((new Date().getTime()) + WEEK);
    },
    expirationDate() {
      return clientTime(this.item.expirationDate.date, this.item.timezone);
    },
    expirationDateFormated() {
      return dateFormat(this.expirationDate, "MM/DD/YY");
    },
    appliedOn() {
      const date = new Date(this.item.application.submitedDate * 1000);

      return dateFormat(date, "MM/DD/YY");
    },
    amount() {
      return formatAmount(this.item.amount);
    },
    requirementsCount() {
      const essaysCount = Object.values(this.item.requirements).reduce((acc, req) => (acc + req.length), 0);

      return (essaysCount === 0) ? "None" : `${essaysCount}`;
    },
    ...mapState({
      selectedTab: state => state.scholarships.selectedTab,
      sorted: state => state.filters.sorted
    })
  },
  methods: {
    favorite(item) {
      if(this.pendingNotification) return;
      let that = this;

      let messages = {
        pending: that.item.isFavorite ? "Moving to New Tab" : "Moving to Favorite Tab",
        finish: that.item.isFavorite ? "Moved to New Tab" : "Moved to Favorite Tab",
        failure: "Something went wrong"
      };

      this.pendingNotification = messages.pending;

      let toggle = item.isFavorite
        ? this.unmarkFavorite
        : this.markFavorite;

      toggle(item)
        .then(() => {
          applyCardNotification.apply(this, [messages.finish, function() {
            that.REMOVE_SCHOLARSHIP({
              scholarshipId: item.scholarshipId,
              storeName: item.isFavorite ? NEW_SCHOLARSHIPS : FAVORITES_SCHOLARSHIPS
            });

            that.$emit('global', {ev: 'item-state-change'});
          }]);
        }, (err) => {
          applyCardNotification.apply(this, [messages.failure]);
        });
    },
    ...mapActions("scholarships", [
      "markFavorite",
      "unmarkFavorite"
    ]),
    ...mapActions("filters", [
      "applyFilters"
    ]),
    ...mapMutations("scholarships", [
      "REMOVE_SCHOLARSHIP"
    ])
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';
@import 'style-gide/mixins';

  $blue-darker: #213286;

  .scholarship-card {
    height: 105px;
    border-bottom: 1px solid #E4E4E4;
    box-sizing: border-box;
    cursor: pointer;
    -webkit-tap-highlight-color: rgba(255,255,255,0);
    position: relative;

    &__loader {
      width: 100%;
      height: 100%;
    }

    &__notif {
      font-size: 13px;
      color: $blue-darker;
      text-align: center;
      line-height: 104px;
    }

    &__item {
      @include flexbox();
      width: 100%; height: 100%;
      box-sizing: border-box;
      position: absolute;
      z-index: 1;
      left: 0; top: 0;
    }
  }

  .scholarships-list-item {
    $blue: #708FE7;
    $blue-more-lighter: #e4eefe;
    $blue-darker: #213286;
    $red:  #EB5757;
    $white: #fff;

    padding: 22px 15px;
    cursor: pointer;
    -webkit-tap-highlight-color: rgba(255,255,255,0);
    background-color: white;

    @include breakpoint($s $m - 1px) {
      padding: 15px 25px;
    }

    &__title {
      margin-top: 3px;
      margin-bottom: 3px;
      overflow: hidden;
    }

    &__right {
      width: 210px;
      @include flex(auto 1 1);
      margin-left: 15px;
    }

    &__favorite {
      float: right;
      right: -1px;
    }

    &__status {
      margin-right: 11px;
    }

    &__attribute {
      &:first-child {
        margin-bottom: 4px;
      }

      & + & {
        @include flexbox();
        @include justify-content(space-between);
        @include align-items(baseline);
      }
    }

    &.selected {
      background-color: #F3F8FF;
    }

    //components
    .angle-bracket {
      @include angle-bracket(right, 8px, 2px, $blue);
      margin-top: 3px;
      margin-right: 5px;
    }

    // fonts
    .title {
      color: $blue;
      font-weight: 700;
      font-size: 14px;
      line-height: 1.35em;

      display: inline-block;
      white-space: nowrap;
      width: 83%;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .deadline-notification {
      color: $red;
    }

    .paragraph {
      font-size: 13.5px;
      font-weight: 400;
      float: left;
    }

    // master list banners
    .master-list-banners {
      border-bottom: 1px solid #d8d8d8;

      &__loader {
        @include loader-lines(
          $size: 6px,
          $color: #5998ef,
          $line-width: 0.5em,
          $line-indent: 1em
        );

        position: absolute !important;
        left: 0; right: 0;
        top: 15px;
        margin: auto;
      }
    }
  }

  // transition
  // card item
  .card-item-leave-to,
  .card-item-enter {
    transform: translateX(-50%);
    opacity: 0;
  }

  .card-item-enter-active {
    transition: all 400ms cubic-bezier(0.77, 0, 0.175, 1) 400ms;
  }

  .card-item-leave-active {
    transition: all 400ms cubic-bezier(0.77, 0, 0.175, 1);
  }

  // notification
  .card-notif-enter,
  .card-notif-leave-to {
    transform: translateY(-40%);
    opacity: 0;
  }

  .card-notif-enter-active {
    transition: all 400ms cubic-bezier(0.77, 0, 0.175, 1);
  }

  .card-notif-leave-active {
    transition: all 400ms cubic-bezier(0.77, 0, 0.175, 1);
  }
</style>
