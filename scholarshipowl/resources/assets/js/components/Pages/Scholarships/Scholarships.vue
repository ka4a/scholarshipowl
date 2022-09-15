<template lang="html">
  <section class="main-container">
    <aside class="left-container">
      <tabs-list @state-changed="stateChangeHolder" :states="tabs" state="selectedTab" slot="first"
        :action="{moduleName: 'scholarships', actionName: 'setCurrentScholarships'}" />
      <div :class="['filters', {'filters_sorted': sorted && selectedTab !== 'SENT'}, 'left-container__filters']">
        <section class="filters__wrp">
          <filtering-bar @filter="filterHolder" name-space="scholarships" v-if="!(xs || s || m) && selectedTab !== 'SENT'" />
          <filters-holder @filter="filterHolder" v-if="(xs || s || m) && selectedTab !== 'SENT'"
            :items="[{label: 'filters', componentName: 'FilterPanel', options: {'name-space': 'scholarships'}},
                    {label: 'sort by', componentName: 'SortingPanel',
                      options: {'unit-list': sortingSettings, 'name-space': 'scholarships'}}]" />
          <div :class="['filters__counter', {'filters__counter_sent': selectedTab === 'SENT'}]">
            <filtered-counter :filtered="scholarships.length" :original="possibleScholarships.length" />
          </div>
          <sorting-bar class="filters__sorting-bar" name-space="scholarships" :sorting-settings="sortingSettings"
            v-if="!(xs || s || m) && selectedTab !== 'SENT'" />
        </section>
      </div>
      <scholarship-list @global="globalEvHolder" @selected="showDetails = true" :scholarships="scholarships" />
    </aside>

    <section ref="right-container" :class="['right-container', { closed: showDetails }]">
      <div :class="['right-container__inner', { freemium: isFreemium }]">
        <router-view @global="globalEvHolder"></router-view>
      </div>
    </section>
  </section>
</template>

<script>
import { sortBydeadline, SORT_PROGRESS, SORT_EXPIRATION, SORT_AMOUNT } from "lib/utils/sort";
import { mapState, mapGetters, mapActions } from "vuex";
import { CURRENT_SCHOLARSHIPS, NEW_SCHOLARSHIPS, FAVORITES_SCHOLARSHIPS, SENT_SCHOLARSHIPS } from "store/scholarships";
import { WON_STATUS, MISSED_STATUS, AWARDED_STATUS, WINNER_CHOSEN_STATUS } from "store/scholarships";
import notifications from "components/Pages/Scholarships/Notifications/notifications";
import { ROUTES } from "router.js";

import ScholarshipList    from "components/Pages/Scholarships/ScholarshipsList/List.vue";
import ScholarshipItem    from "components/Pages/Scholarships/ScholarshipsList/Item.vue";
import Details            from "components/Pages/Scholarships/Details.vue";
import Notes              from "components/Pages/Scholarships/Notes/Notes.vue";
import Notification       from "components/Pages/Scholarships/Notifications/Notification.vue";

import TabsList from "components/Pages/TabsList.vue";
import FilteredCounter from "components/Pages/Own/FilteredCounter.vue";
import SortingBar from "components/Pages/Own/SortingBar.vue";
import FilteringBar from "components/Pages/Own/FilteringBar.vue";
import FiltersHolder from "components/Pages/Own/FiltersHolder.vue";

const sortingSettings = [
  {state: SORT_PROGRESS,   iconClass: "status-indicator s1"},
  {state: SORT_EXPIRATION, iconClass: "icon icon-alarm"},
  {state: SORT_AMOUNT,     iconClass: "icon icon-amount"}
]

function applyNotifications() {
  if(!this.scholarships.length) {
    let path = this.selectedTab === FAVORITES_SCHOLARSHIPS
      ? ROUTES.NO_FAVOURITES
      : this.selectedTab === SENT_SCHOLARSHIPS
        ? ROUTES.NO_SENT
        : ROUTES.NO_NEW;

    this.$router.push(`${ROUTES.SCHOLARSHIPS}/${path}`);

    if(!this.xl || !this.xxl) {
      this.showDetails = true;

      setTimeout(() => {
        this.showDetails = false
      }, 3000)
    }

    return;
  }

  this.$router.push(ROUTES.SCHOLARSHIPS);
}

function applyNoMatchesNotificaton() {
  let route = ROUTES.SCHOLARSHIPS;

  if(!this.scholarships.length) {
    route = `${route}/${ROUTES.NO_MATCHES}`;

    if(!this.xl || !this.xxl) {
      this.showDetails = true;

      setTimeout(() => {
        this.showDetails = false
      }, 3000)
    }
  }

  this.$router.push(route);
}

export default {
  components: {
    ScholarshipList,
    ScholarshipItem,
    Details,
    Notes,
    Notification,

    TabsList,
    FilteredCounter,
    SortingBar,
    FilteringBar,
    FiltersHolder,
  },
  data: function() {
    return {
      showDetails: false,
      activePanel: null,
      showFilterSortingPanel: false,
      tabs: [
        NEW_SCHOLARSHIPS,
        FAVORITES_SCHOLARSHIPS,
        SENT_SCHOLARSHIPS
      ],
      sortingSettings
    };
  },
  watch: {
    selected() {
      this.applySentNotifications();
    }
  },
  computed: {
    ...mapState({
      possibleScholarships: state => state.scholarships[CURRENT_SCHOLARSHIPS],
      credits: state => state.account.account.credits,
      selectedTab: state => state.scholarships.selectedTab,
      selected: state => state.scholarships.selected
    }),
    ...mapGetters({
      xs: "screen/xs",
      s: "screen/s",
      m: "screen/m",
      l: "screen/l",
      xl: "screen/xl",
      xxl: "screen/xxl",
      isFreemium: "account/isFreemium",
      scholarships: "scholarships/scholarships",
    }),
    sorted() {
      return this.$store.state.list.scholarships.sorted
        || this.$store.state.list.scholarships.filtered;
    }
  },
  methods: {
    ...mapActions("scholarships", [
      "setScholarship",
      "selectScholarship"
    ]),
    closeAllPanels() {
      this.activePanel = null;
    },
    defineActivePanel(panelName) {
      if(!panelName || typeof panelName !== "string") {
        throw Error("Ohh no!!! Please provide correct panel name");
      }

      if(this.activePanel === panelName) {
        this.closeAllPanels();
        return;
      }

      this.activePanel = panelName;
    },
    applySentNotifications() {
      if(!this.selected) return;

      let status = this.selected.derivedStatus;

      const statusScreenStatuses = [
        WON_STATUS, MISSED_STATUS, AWARDED_STATUS, WINNER_CHOSEN_STATUS
      ]

      if(this.selectedTab !== "SENT"
        || statusScreenStatuses.indexOf(status) === -1) return;

      let routePath = status.toLowerCase().replace(' ', '-');

      if(status === WON_STATUS) {
        notifications["won"].controller.link = this.selected.winnerFormUrl;
      }

      const path = `${ROUTES.SCHOLARSHIPS }/${routePath}`;

      this.$router.push(path);
    },
    globalEvHolder(playload) {
       let { ev, value } = playload,
        filtered = this.$store.state.list.scholarships.filtered,
        sorted = this.$store.state.list.scholarships.sorted;

      if(ev === 'show-details') {
        this.showDetailsHolder(value);
      }

      if(ev === 'item-state-change') {
        this.itemStateChangeHolder();
      }

      if(ev === 'apply') {
        this.applyHolder(ev);
      }
    },
    stateChangeHolder() {
      this.$store.dispatch('list/reset', 'scholarships');
      this.$store.commit('scholarships/SET_SCHOLARSHIP', this.scholarships[0]);
      applyNotifications.call(this);
      this.applySentNotifications();
    },
    filterHolder(ev) {
      if(ev.ev === 'filter-reset') {
        applyNotifications.call(this);
        this.$store.dispatch('scholarships/setCurrentScholarship');
        return;
      }

      this.$store.commit('scholarships/SET_SCHOLARSHIP', this.scholarships[0]);

      applyNoMatchesNotificaton.call(this);
    },
    showDetailsHolder(value) {
      let filtered = this.$store.state.list.scholarships.filtered;

      this.showDetails = value;

      if(filtered) {
        applyNoMatchesNotificaton.call(this);
        return;
      }

      applyNotifications.call(this);
    },
    itemStateChangeHolder() {
      let filtered = this.$store.state.list.scholarships.filtered;

      if(filtered) {
        this.$store.dispatch('list/applyFilter', 'scholarships');

        if(this.xl || this.xxl) {
          applyNoMatchesNotificaton.call(this);
        }

        return;
      }

      applyNotifications.call(this);
    },
    applyHolder(ev) {
      let filtered = this.$store.state.list.scholarships.filtered,
          path = ROUTES.SCHOLARSHIPS;

      if(!this.scholarships.length) {
        path = path + '/' + (this.selectedTab === FAVORITES_SCHOLARSHIPS
          ? ROUTES.NO_FAVOURITES
          : this.selectedTab === SENT_SCHOLARSHIPS
            ? ROUTES.NO_SENT
            : ROUTES.NO_NEW);
      }

      if(filtered) {
        this.$store.dispatch('list/applyFilter', 'scholarships');

        path = path + ROUTES.NO_MATCHES;
      }

      if(ev === "apply") {
        this.$router.push(path);
        return;
      }

      if(!this.xl || !this.xxl) {
        this.showDetails = true;

        setTimeout(() => {
          this.showDetails = false
        }, 3000)
      }

      this.$router.push(path);
    }
  },
};
</script>

<style lang="scss">
@import 'main/meta/reset';

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';
@import 'style-gide/mixins';
@import 'main/meta/helpers';
@import 'scholarships/shapes-icons';

    // variables
    $blue: #708FE7;
    $blue-light: #ACCAF6;
    $dark: #2F2F2F;

// layout
.main-container {
  @include flexbox();
  width: 100%;

  .left-container {
    @include flexbox();
    @include flex-direction(column);
    width: 328px;
    border-right: 1px solid #d8d8d8;
    overflow: hidden;

    @include breakpoint(max-width $l - 1px) {
      width: 100%;
      box-sizing: border-box;
    }

    &__list-tabs {
      @include flex(0 0 58px);
    }

    &__filters {
      @include flex(0 0 50px);
    }
  }
}

.filters {
  // variables
  $dark: #333;
  $white: #fff;
  $grey: #797979;
  $grey-lighter: #7F7F7F;
  $grey-more-lighter: #D8D8D8;
  $blue: #4181ED;
  $blue-lighter: #ACCAF6;
  $blue-more-lighter: #B9D5FA;
  $blue-darker: #9AB4FF;

  padding: 0 15px;
  border-bottom: 1px solid #E0E0E0;
  position: relative;
  background-color: $white;

  @include breakpoint($s $l - 1px) {
    padding-left: 25px;
    padding-right: 25px;
  }

  // modificators
  &_sorted {
    background-color: $blue-darker;
    border-bottom-color: $blue-darker;

    .filter-sorting__ctrls {
      background-color: $blue-darker;
    }

    .icon-filter,
    .toggle__text,
    .filters__counter {
      color: $white !important;
    }

    .toggle__indicator {
      border-top-color: $white !important;
    }
  }

  // elements
  &__wrp {
    margin-top: 10px;
    height: 40px;
    @include flexbox();
    @include align-items(center);

    &::after {
      display: block;
      content: "";
      clear: both;
    }

    > div {
      width: 33.33%;
      box-sizing: border-box;
    }
  }

  &__sorting-bar {
    text-align: right;

    .sorting-panel {
      right: 0;
    }
  }

  &__filter-panel,
  &__sorting-panel {
    position: absolute;
    z-index: 10;
    top: 51px;
  }

  &__filter-panel {
    left: 0;
  }

  &__sorting-panel {
    right: 0;
  }

  &__counter {
    &_sent {
      position: absolute;
      padding: 18px 0;
      top: 0;
      width: 90% !important;
    }
  }
}

.filter-sorting-ctrl {
  font-size: 12px;
  color: $dark;

  &.active {
    color: $blue;
  }
}

.notif-list-wrp {
  @include flex(1 1 auto);
  overflow: auto;
  @include flexbox();
  @include flex-direction(column);
  @include justify-content(center);
}

.right-container {
  @include flexbox();
  @include flex(1);
  width: 100%;
  @include flex-direction(column);
  @include flexbox();
  margin-left: auto;
  margin-right: auto;
  background-color: white;
  overflow: auto;

  @include breakpoint($s) {
    padding: 25px;
    background-color: #F3F8FF;
  }

  @include breakpoint(max-width $l - 1px) {
    height: 100%;
    position: absolute;
    top: 0; left: 0; bottom: 0;
    z-index: 1;
    box-sizing: border-box;
    -webkit-transition: 0.3s ease-in-out;
    transition: 0.3s ease-in-out;
    -webkit-transform: translateX(200%);
    transform: translateX(200%);

    &.closed {
      -webkit-transform: translateX(0);
      transform: translateX(0);
    }
  }

  &__inner {
    background-color: white;
    padding-top: 15px;
    padding-bottom: 15px;
    position: relative;
    @include flexbox();
    @include flex(1 1 100%);
    @include flexbox();
    @include flex-direction(column);
    overflow: auto;


    @include breakpoint($s) {
      padding: 25px 20px;
      border: 1px solid rgba(154, 180, 255, 0.29);
      box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.07);
      padding: 25px 20px;
    }

    @include breakpoint($m) {
      padding: 30px 28px;
    }

    @include breakpoint($l) {
      @include flex(1 0 auto);
      min-height: auto;
      padding: 30px 33px;
    }

    @include breakpoint($xl) {
      padding: 60px 33px;
    }
  }
}

// toggle block
.toggle {
  & {

    &.right {
      text-align: right;
    }

    .toggle__ctrl {
      cursor: pointer;
    }

    .toggle__text {
      line-height: 40px;
      color: $dark;
      font-size: 14px;
      margin-right: 5px;
      display: inline-block;
      text-transform: capitalize;
    }
  }

  &.active {
    .toggle__text {
      color: $blue;
    }

    .toggle__indicator {
      @include angle(bottom, 5px, $blue);
      transform: rotate(180deg);
    }
  }
}
</style>
