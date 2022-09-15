<template>
  <div v-if="loaded" class="my-account">
    <notif-error-facebook v-if="facebookError" :message="facebookError" />
    <div style="background-color: white; padding-top: 1px">
      <banner-holder v-if="isFreemium" class="my-account__top-banner" :banners="topBanners" style="background-color: #708fe7;" />
      <header class="my-account-header my-account__header my-account__limiter inner-indent">
        <user-avatar v-if="profile && (l || xl || xxl)" class="my-account-header__avatar" :gender="profile.gender" />
        <div class="my-account-header__wrp">
          <Title v-if="profile && profile.fullName" size="myAccount">{{ profile.fullName }}</Title>
          <user-avatar v-if="profile && (xs || s || m)" class="my-account-header__avatar" :gender="profile.gender" />
          <p v-if="profile && profile.age" class="my-account-header__age texts">Age: {{ profile.age }}</p>
          <p v-if="scholarshipCount" class="my-account-header__scholarships-count texts dark">
            Eligible for <a href="/scholarships" class="orange">{{ scholarshipCount }}</a> scholarships
          </p>
          <profile-completeness-indicator v-if="profile && profile.completeness"
            class="my-account-header__indicator" :completeness="profile.completeness" />
        </div>
        <subscription-information v-if="membership" @upgrade="upgrade"
          class="my-account-header__sub-info" @click="setCurrentTab('account', 'membership')"
          :membership="membership" />
      </header>
      <div class="inner-indent my-account__limiter">
        <my-account-tabs class="my-account__tabs"
          :tabs="tabs.map(tab => tab.tab)" :current-tab="tab.tab"
          @change-tab="tab => setCurrentTab(tab)" />
      </div>
    </div>
    <main class="my-account__separator">
      <my-account-sub-tabs :tabs="tabs" :current-tab="tab" @change-tab="tab => tab = tab" />
      <div class="my-account__tabs-wrp">
        <general-tab class="my-account__tab-wrp" v-if="tab.tab === 'general'" :infos="general" />
        <profile-tab class="my-account__tab-wrp" v-if="tab.tab === 'profile'" :profile="profile"
          :sub-tabs="tabs[1].subTabs" :current-tab="tab.subTab"
          @updated="updateModal" />
        <account-tab class="my-account__tab-wrp" v-if="tab.tab === 'account'" @upgrade="upgrade"
          @updated="updateModal"
          @modal="cancelationModal"
          :sub-tabs="tabs[2].subTabs" :membership="membership" :account="account" :current-tab="tab.subTab"
          :profile="profile" />
        <!-- <banner-holder slot="banner" style="background-color: #708fe7"
          class="my-account__campus-explorer-banner" :banners="campusExplorerBanner" /> -->
          <campus-explorer v-if="isFreemium" class="my-account__campus-explorer-banner"/>
      </div>
    </main>
  </div>
</template>

<script>
  import { mapGetters, mapActions, mapState } from "vuex";
  import mixpanel from "lib/mixpanel";
  import { capitalize, isMobile } from "lib/utils/utils";
  import { myAccountTopBannerZones } from "banners/settings";
  import { AccountResource } from "resource";
  import { CANCELATION_FREE_TRIAL, CANCELATION_BASIC, ACCOUNT_UPDATE } from "store/modal";
  import { ELIGIBLE_SCHOLARSHIP_COUNT, NOT_SEEN_SCHOLARSHIP_COUNT } from "store/eligibility-cache";

  import Title from "components/Common/Typography/Title.vue";
  import NotifErrorFacebook from "components/Pages/MyAccount/NotifErrorFacebook.vue";
  import ProfileCompletenessIndicator from "components/Pages/MyAccount/ProfileCompletenessIndicator.vue";
  import UserAvatar from "components/Pages/MyAccount/UserAvatar.vue";
  import SubscriptionInformation from "components/Pages/MyAccount/SubscriptionInformation.vue";
  import MyAccountTabs from "components/Pages/MyAccount/MyAccountTabs.vue";
  import MyAccountSubTabs from "components/Pages/MyAccount/MyAccountSubTabs.vue";
  import BannerHolder from "banners/BannerHolder.vue";
  import AccountTab from "components/Pages/MyAccount/Tabs/AccountTab.vue";
  import GeneralTab from "components/Pages/MyAccount/Tabs/GeneralTab.vue";
  import ProfileTab from "components/Pages/MyAccount/Tabs/ProfileTab.vue";
  import CampusExplorer from "banners/CampusExplorer.vue";

  const tabs = [
    {tab: 'general'},
    {tab: 'profile',
      subTabs: ['education', 'basic', 'contact']},
    {tab: 'account',
      subTabs: ['membership', 'credentials', 'recurrence']}
  ];

  export default {
    created() {
      // facebook error check
      if(location.pathname.indexOf('my-account') > -1) {
        if(location.search.indexOf('error') > -1) {
          let errorMessage = new URL(location.href)
                              .searchParams.get('error');
          try {
            errorMessage = decodeURIComponent(errorMessage);
          } catch(err) {
            throw Error(err);
          }

          this.facebookError = errorMessage;
        }

        if(location.hash) {
          let resource = location.hash.slice(1);

          if(resource === 'credentials') {
            this.setCurrentTab('account', 'credentials');
          }
        }
      }

      window.showModal = () => {
        this.cancelationModal()
      }

      this.$store.dispatch('account/fetchData', [
        'account',
        'profile',
        'scholarship',
        'application',
        'mailbox',
        'socialAccount'
      ]).then(() => {
        setTimeout(() => {
          this.loaded = true;
          this.$emit('loaded');
        }, 1500)
      })
    },
    components: {
      Title,
      NotifErrorFacebook,
      ProfileCompletenessIndicator,
      UserAvatar,
      SubscriptionInformation,
      MyAccountTabs,
      MyAccountSubTabs,
      BannerHolder,
      AccountTab,
      GeneralTab,
      ProfileTab,
      CampusExplorer
    },
    data() {
      return {
        facebookError: '',
        isMobile,
        loaded: false,
        tabs,
        tab: {tab: "general", subTab: ""},
      }
    },
    computed: {
      ...mapGetters({
        xs: "screen/xs",
        s: "screen/s",
        m: "screen/m",
        l: "screen/l",
        xl: "screen/xl",
        xxl: "screen/xxl",
        isFreemium: "account/isFreemium",
        account: "account/account",
        profile: "account/profile",
        mailbox: "account/mailbox",
        application: "account/application",
        socialAccount: "account/socialAccount",
        scholarshipAmount: "account/scholarshipAmount",
        membership: "account/membership",
        accountId: "account/accountId",
        scholarshipCount: `eligibilityCache/${ELIGIBLE_SCHOLARSHIP_COUNT}`,
        notSeenScholarshipCount: `eligibilityCache/${NOT_SEEN_SCHOLARSHIP_COUNT}`,
      }),
      general() {
        const scholarships = {
          value: this.scholarshipCount,
          new: !!this.notSeenScholarshipCount
        }

        let application = {
          value: this.application ? this.application.total.toString() : "0",
          new: false
        }

        let mailbox = {
          value: "0/0",
          new: false
        };

        if(this.mailbox) {
          let value = `${this.mailbox.inbox.unread}/${this.mailbox.inbox.total}`,
              isNew = Number(this.mailbox.inbox.unread) > 0;

          mailbox.value = value;
          mailbox.new = isNew;
        }

        return [{
          title: 'Scholarships',
          text: 'you are eligible for',
          img: require('components/Pages/MyAccount/img/scholarships.png'),
          slot: scholarships,
          redirect: '/scholarships'
        }, {
          title: 'Applications',
          text: 'submitted',
          img: require('components/Pages/MyAccount/img/applications.png'),
          slot: application,
          redirect: '/scholarships#sent'
        }, {
          title: 'Messages',
          text: 'unread',
          img: require('components/Pages/MyAccount/img/messages.png'),
          slot: mailbox,
          redirect: '/mailbox'
        }]
      },
      campusExplorerBanner() {
        return {
          'xs|s|m|l|xl|xxl': myAccountTopBannerZones['campusExplorer']
        }
      },
      topBanners() {
        return {
          'xs|s': myAccountTopBannerZones['320'],
          'm': myAccountTopBannerZones['468'],
          'l|xl|xxl': myAccountTopBannerZones['728']
        }
      },
    },
    methods: {
      /**
       * Invoke cancelation modal
       * @param  {vue event} ev
       * @return {undefined}
       */
      cancelationModal(isFreeTrial) {
        let modalName = isFreeTrial
          ? CANCELATION_FREE_TRIAL
          : CANCELATION_BASIC;

        this.showModal({ modalName, content: { isFreeTrial } });

        let eventName = isFreeTrial
          ? 'Membership_Tab_Cancel_Click_FreeTrial'
          : 'Membership_Tab_Cancel_Click_PaidMembership';

        mixpanel.track(eventName);
      },
      setCurrentTab(tabName, subTabName) {
        if(!tabName || typeof tabName !== 'string')
          throw Error('Please provide correct tab name');

        if(subTabName && typeof subTabName === 'string') {
          this.tab = {tab: tabName, subTab: subTabName};
          return;
        }

        if(subTabName === undefined) {
          let tabByName = tabs
            .filter(tab => tab.tab === tabName);

          if(!tabByName[0].subTabs) {
            this.tab = {tab: tabName, subTab: ""}
            return;
          }

          this.tab = {
            tab: tabName,
            subTab: tabByName[0].subTabs[0]
          }
        }
      },
      updatedHolder() {
        console.log('updated');
      },
      updateModal() {
        this.showModal({
          modalName: ACCOUNT_UPDATE
        })
      },
      upgrade(ev) {
        if(!this.isMobile()) {
          ev.preventDefault();
        }

        if(!window.invokeUpgradeModal)
            throw Error('Nooooooooo!')

        window.invokeUpgradeModal()
      },
      ...mapActions("modal", {
        showModal: "showModal"
      })
    }
  }
</script>

<style lang="scss">
  $dark: #2f2f2f;
  $dark-lighter: #616161;
  $orange: #ff6634;
  $orange-lighter: #fe774a;
  $red: #ed5858;
  $green: #2fd9b3;
  $blue: #708fe7;
  $blue-light: #cdd7e8;
  $blue-more-light: #f2f7ff;
  $blue-darker: #94abce;
  $blue-more-darker: #3b5998;
  $white: #ffffff;
  $orange-lighter: #fe774a;
  $grey: #cdd7e8;
  $pinkish-orange-two: #fe774a;
  $dark-grey-blue: #354c6d;

  $open-sans: 'Open Sans';

  .ttu {
    text-transform: uppercase;
  }

  .ma-title {
    font-family: $open-sans;
    font-size: 21.3px;
    color: $dark;
    line-height: 1.375em;

    @include breakpoint($m) {
      font-size: 24px;
    }
  }

  .paragraph16-18 {
    font-family: "Open Sans";
    font-size: 16px;
    line-height: 1.3em;
    color: $mine-shaft;

    @include breakpoint($m) {
      font-size: 18px;
      line-height: 1.25em;
    }
  }

  .ma-text {
    font-family: $open-sans;
    font-size: 14px;
    color: $dark;
  }

  .my-account {
    background-color: $blue-more-light;

    &__top-banner {
      display: block;
      margin-top: 20px;
      margin-left: auto;
      margin-right: auto;

      @include breakpoint($s) {
        margin-top: 25px;
      }

      @at-root {
        .my-account {
          &__top-banner + &-header {
            padding-top: 15px;

            @include breakpoint($m) {
              padding-top: 25px;
            }
          }
        }
      }
    }

    .texts {
      font-family: $open-sans;
      font-size: 16px;
      color: $dark-lighter;

      @include breakpoint($s) {
        font-size: 18px;
      }

      // eligible notification
      .dark {
        color: $dark;
      }

      // header / scholarship counter
      .orange {
        color: $orange
      }
    }

    .ma-facebook-btn {
      font-family: $open-sans; //SanFranciscoText
      font-size: 20px;
      font-weight: 600;
      color: $white;
      line-height: 55px;
      cursor: pointer;

      display: flex;
      justify-content: center;
      align-items: center;

      @include breakpoint($s) {
        font-size: 22px;
      }

      @include breakpoint($m) {
        font-size: 24px;
        height: 65px;
        line-height: 65px;
      }

      &__icon {
        margin-right: 10px;
      }

      width: 100%;
      height: 55px;
      border-radius: 4px;
      background-color: $blue-more-darker;
    }

    // radio list
    $radio-list-fm: 'radio-list-fm';
    $radio-fm: 'radio-fm';

    .#{$radio-list-fm} {
      display: flex;
    }

    .#{$radio-list-fm}__item + .#{$radio-list-fm}__item {
      margin-left: 20px;
    }

    .#{$radio-fm} {
      display: flex;
      cursor: pointer;

      input {
        display: none;
      }

      &__radio {
        min-width: 19px;
        width: 19px;
        height: 19px;
        border: 1px solid rgba(155, 176, 210, 0.5);
        display: inline-block;
        border-radius: 50%;
        box-sizing: border-box;
        position: relative;
        vertical-align: middle;
        margin-right: 8px;
        background-color: $white;
      }

      &__label {
        font-family: 'Open Sans';
        font-size: 14px;
        color: #555555;
        line-height: 1.35em;
      }

      input:checked + .#{radio-fm}__radio:before {
        content: '';
        display: block;
        width: 11px;
        height: 11px;
        background-color: $blue;
        position: absolute;
        border-radius: 50%;
        top: 0; bottom: 0;
        left: 0; right: 0;
        margin: auto;
      }
    }

    &__header {
      width: 100%;
      box-sizing: border-box;
    }

    &__limiter {
      box-sizing: border-box;

      @include breakpoint($xl) {
        max-width: 1120px;
        margin-left: auto;
        margin-right: auto;
      }
    }

    &__tabs {
      @include breakpoint($m) {
        width: 67%;
        margin-left: 185px;
      }

      @include breakpoint($l) {
        width: 56%;
        margin-left: 240px;
      }

      @include breakpoint($xl) {
        width: 56.7%;
        margin-left: 240px;
      }
    }

    &__tab-wrp {
      padding: 25px 20px;

      @include breakpoint($s) {
        padding: 25px;
      }

      @include breakpoint($m) {
        padding: 0;
        margin-left: auto;
        margin-right: auto;
      }

      @include breakpoint($l) {
        align-self: flex-start;
        flex: 1 1 auto;
        display: flex;
        justify-content: center;
      }
    }

    &__tabs-wrp  {
      max-width: 986px;
      margin-left: auto;
      margin-right: auto;

      @include breakpoint($m) {
        padding: 30px 25px;
      }

      @include breakpoint($l) {
        display: flex;
        justify-content: center;
      }
    }

    &__campus-explorer-banner {
      display: block;
      width: 100%;
      text-align: center;

      @include breakpoint($s) {
        padding-bottom: 25px;
      }

      @include breakpoint($m) {
        margin-top: 30px;
        padding-bottom: 0;
      }

      @include breakpoint($l) {
        width: 330px;
        min-width: 330px;
        margin-top: 0;
        margin-left: 25px;
      }
    }

    &__separator {
      border-top: 1px solid $blue-light;
    }

    &__count {
      margin-left: auto;
      font-size: 16px;
      color: $dark-lighter;

      @include breakpoint($s $m - 1px) {
        font-size: 20px;
      }
    }
  }

  .ma-button {
    font-family: 'Raleway';
    font-size: 14px;
    font-weight: bold;
    color: $white;
    text-align: center;
    text-transform: uppercase;
    line-height: 40px;
    display: block;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);

    width: 140px;
    height: 40px;
    border-radius: 4px;

    @include breakpoint($s) {
      font-size: 16px;
    }

    @include breakpoint($m) {
      width: 160px;
    }

    &_orange {
      background-color: $orange-lighter;

      &:hover {
        background-color: darken($orange-lighter, 5);
      }
    }

    &_grey {
      background-color: $grey;

      &:hover {
        background-color: darken($grey, 5);
      }
    }
  }

  .ma-tooltip {
    font-family: $open-sans;
    font-size: 9px;
    color: $white;
    background-color: $dark-grey-blue;
    height: 25px;
    line-height: 25px;
    border-radius: 2px;
    padding-left: 8px;
    padding-right: 8px;
    box-sizing: border-box;
  }

  .my-account-header {
    padding-top: 20px;
    padding-bottom: 20px;
    position: relative;
    line-height: 1.2em;

    @include breakpoint(max-width $m - 1px) {
      text-align: center;
    }

    @include breakpoint($s) {
      padding-top: 25px;
      padding-bottom: 25px;
    }

    @include breakpoint($m) {
      padding-bottom: 10px;
      display: flex;
    }

    @include breakpoint($l) {
      padding-bottom: 0;
    }

    &__wrp {
      @include breakpoint($m) {
        margin-left: 28px;
        width: 55%;
      }

      @include breakpoint($l) {
        margin-left: 38px;
      }
    }

    &__avatar {
      @include breakpoint(max-width $m - 1px) {
        margin-top: 12px;
        margin-left: auto;
        margin-right: auto;
      }
    }

    &__age {
      margin-top: 12px;
      line-height: 1.375em;
    }

    &__scholarships-count {
      margin-top: 12px;
      line-height: 1.29em;

      @include breakpoint($s) {
        margin-top: 15px;
      }

      @include breakpoint($m) {
        margin-top: 18px;
      }

      @include breakpoint($l) {
        margin-top: 38px;
      }
    }

    &__indicator {
      margin-top: 13px;
      line-height: 0.9em;

      @include breakpoint($s) {
        margin-top: 12px;
        width: 78%;
        margin-left: auto;
        margin-right: auto;
        max-width: 386px;
      }

      @include breakpoint($m) {
        margin-left: 0;
        margin-right: 0;
      }

      @include breakpoint($l) {
        margin-top: 12px;
        margin-bottom: 18px;
      }
    }

    &__sub-info {
      flex: 1 1 auto;

      @include breakpoint(max-width $s - 1px) {
        margin-top: 22px;
      }

      @include breakpoint($s $m - 1px) {
        margin-top: 26px;
      }
    }
  }

  .ma-disclaimer {
    font-family: $open-sans;
    font-size: 14px;
    color: $blue-darker;
  }

  .orange-c {
    color: $orange;
  }

  .dark-c {
    color: $dark;
  }
</style>