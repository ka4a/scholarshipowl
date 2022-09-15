<template>
<base-container>
  <columns-container v-if="loaded" :show-right-column="showRightColumn">
    <left-container slot="first">
      <tabs-list @state-changed="stateChangeHolder" slot="first" :states="tabs"
        :action="{moduleName: 'mailbox', actionName: 'setMails'}" state="mailState" />

      <search-bar @filter="filterHolder" slot="second" module-name="list"
        name-space="mailbox" filter-by="query" />

      <!-- third block in LeftContainer.vue -->
      <sorting-bar class="mailbox-sort-bar" v-if="!(xs || s || m)" slot="third" name-space="mailbox"
        :is-disable="mails && !mails.length" :sorting-settings="sortingSettings"/>
      <filters-holder v-else slot="third" :is-disable="mails && !mails.length" :items="[{label: 'sort by',
        componentName: 'SortingPanel', options: {'unit-list': sortingSettings, 'name-space': 'mailbox'}}, null]"/>
      <filtered-counter v-if="stateMails && mails" slot="third" :filtered="mails.length"
        :original="stateMails.length" />

      <template slot="fourth" v-for="(mail, index) in mails">
        <!-- <banner-holder class="banner-list" v-if="shouldShowListBanner(index)" :banners="listBanners" /> -->
        <mail-list-item @click.native="showRightColumn = false; selectItem(mail)" :key="mail.emailId"
          :item="extendMail(mail)" :current-mail="currentMail"/>
      </template>
    </left-container>
    <details-area slot="second">
      <back-button v-if="!(xl || xxl) && !notifications.noMatches && !notifications.noMails"
        class="back-button" slot="back" @click.native="showRightColumn = true" />
      <banner-holder v-if="shouldShowBanner && false" slot="banner" class="banner-details-area" :banners="detailsAreaBanners"/>
      <mail-area v-if="currentMail && !notifications.noMatches && !notifications.noMails"
        slot="main" :current-mail="extendMail(currentMail)" />
      <notification v-if="notifications.noMatches" slot="main" name="no-matches" :notification="noMatches"/>
      <notification v-if="notifications.noMails && !notifications.noMatches" slot="main" name="no-mails" :notification="noMails" />
    </details-area>
  </columns-container>
  <pre-loader v-else />
</base-container>
</template>

<script>
import { mapState, mapGetters, mapMutations } from "vuex";
import { INBOX, SENT } from "store/mailbox";
import { SORT_NAME, SORT_DATE, SORT_SUBJECT } from "lib/utils/sort";
import { SEARCH_QUERY } from "lib/utils/filter";
import { mailboxListbannerZones, mailboxDetailsAreaBannerZonnes } from "banners/settings";
import { fetchWithDalay } from "lib/utils/utils";
import notifications from "components/Pages/Scholarships/Notifications/notifications";

import BaseContainer from "components/Pages/BaseContainer.vue";
import ColumnsContainer from "components/Pages/ColumnsContainer.vue";
import LeftContainer from "components/Pages/LeftContainer.vue";
import TabsList from "components/Pages/TabsList.vue";
import SearchBar from "components/Pages/MailBox/SearchBar.vue";
import FilteredCounter from "components/Pages/Own/FilteredCounter.vue";
import SortingBar from "components/Pages/Own/SortingBar.vue";
import FiltersHolder from "components/Pages/Own/FiltersHolder.vue";
import MailListItem from "components/Pages/MailBox/MailListItem.vue";
import DetailsArea from "components/Pages/MailBox/DetailsArea.vue";
import MailArea from "components/Pages/MailBox/MailArea.vue";
import BannerHolder from "banners/BannerHolder.vue";
import BackButton from "components/Pages/Own/BackButton.vue";
import PreLoader from "components/Pages/Own/PreLoader/PreLoader.vue";
import Notification from "components/Pages/Scholarships/Notifications/Notification.vue";

let triggerWidthDelay = (timerHolder => {
  return function(callback) {
    if (timerHolder) {
      clearTimeout(timerHolder);
    }

    timerHolder = setTimeout(() => {
      callback()
    }, 1000);
  }
})();

function applyNotification(name, condition, callback) {
  this.notifications[name] = condition

  if(!(this.xl || this.xxl)) {
    triggerWidthDelay(() => {
      if(this.notifications[name]) {
        this.showRightColumn = false;

        if(callback) callback();

        setTimeout(() => {
          this.showRightColumn = true;
        }, 3000)
      }
    })
  }
}

const sortingSettings = [
  {state: SORT_NAME, iconClass: 'icon icon-user-profile'},
  {state: SORT_DATE, iconClass: 'icon icon-calendar'},
  {state: SORT_SUBJECT, iconClass: 'icon icon-cursor'},
]

export default {
  components: {
    BaseContainer,
    ColumnsContainer,
    LeftContainer,
    TabsList,
    SearchBar,
    FilteredCounter,
    SortingBar,
    FiltersHolder,
    MailListItem,
    DetailsArea,
    MailArea,
    BannerHolder,
    BackButton,
    PreLoader,
    Notification
  },
  mounted() {
    setTimeout(() => {
      this.loaded = true;
    }, 1500)
  },
  data() {
    return {
      tabs: [INBOX, SENT],
      SEARCH_QUERY,
      sortingSettings,
      showRightColumn: true,
      loaded: false,
      notifications: {
        noMatches: false,
        noMails: false
      },
      noMatches: notifications['no-matches'],
      noMails: notifications['no-mails']
    };
  },
  computed: {
    ...mapState({
      freemium: state => state.account.account.isFreemium,
      mailState: state => state.mailbox.mailState,
      currentMail: state => state.mailbox.currentMail,
      }),
    ...mapGetters({
      xs: "screen/xs",
      s: "screen/s",
      m: "screen/m",
      l: "screen/l",
      xl: "screen/xl",
      xxl: "screen/xxl",
      stateMails: "mailbox/stateMails",
      mails: "mailbox/mails"
    }),
    listBanners() {
      return {
        'xs|s|xl|xxl': mailboxListbannerZones['320'],
        'm': mailboxListbannerZones['480'],
        'l': mailboxListbannerZones['760']
      }
    },
    detailsAreaBanners() {
      return {
        'xs|s|m': mailboxDetailsAreaBannerZonnes['320'],
        'l|xl': mailboxDetailsAreaBannerZonnes['468'],
        'xxl': mailboxDetailsAreaBannerZonnes['728']
      }
    },
  },
  methods: {
    ...mapMutations("list", [
      "setState"
    ]),
    extractEmail(email) {
      let targetField = email.folder === 'Inbox' ? 'sender' : 'recipient';
      let matches = email[targetField].match(/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]+)/gi);

      return matches ? matches[0] : email[targetField];
    },
    selectItem(mail) {
      this.$store.dispatch('mailbox/markAsRead', mail)
    },
    shouldShowListBanner(index) {
      if(typeof index !== 'number')
        throw Error('Please provide correct index!');

      const BANNER_PER_EMAILS = 2;

      return index >= BANNER_PER_EMAILS
          && index % BANNER_PER_EMAILS === 0
          && this.shouldShowBanner && false;
    },
    shouldShowBanner() {
      return this.freemium
          && !/^((?!chrome|android).)*safari/i.test(navigator.userAgent);
    },
    extendMail(mail) {
      if(!mail) return;

      let additionalProps = {
        email: this.extractEmail(mail)
      };

      return Object.assign(mail, additionalProps);
    },
    filterHolder(inputElement) {
      let condition = !this.mails.length && inputElement.value;

      applyNotification.apply(this, ['noMatches', condition, () => inputElement.blur()]);
    },
    stateChangeHolder() {
      this.$store.dispatch('list/resetSort', 'mailbox');
      this.$store.dispatch('list/resetFilter', 'mailbox');

      if(this.xl || this.xxl) {
        this.$store.dispatch('mailbox/markAsRead', this.mails[0]);
      }

      this.notifications.noMatches = false;

      applyNotification.apply(this, ['noMails', !this.mails.length])
    }
  },
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';

  $grey: #E4E4E4;
  $white: white;

  .banner-list {
    display: block;
    margin-left: auto;
    margin-right: auto;
    border-bottom: solid 1px $grey;
  }

  // notifications
  $prefix : 'scholarship-notif';

  .scholarship-notif-no-mails {
    .#{$prefix} {
      &__message {
        margin-top: 15px;

        @include breakpoint($s) {
          margin-top: 30px;
        }
      }
    }
  }

  .mailbox-sort-bar {
    .sorting-panel {
      left: -1px;
    }
  }
</style>