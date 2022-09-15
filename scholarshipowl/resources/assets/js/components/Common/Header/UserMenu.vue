<template>
  <ul class="user-menu">
    <li class="user-menu__item link-item">
      <a :class="['user-menu__link', {'user-menu__link_active' : isLinkActive('scholarships') }]"
        href="/scholarships">
        <span style="position: relative">
          <span v-if="xl || xxl" style="margin-right: 10px">scholarships</span> <i class="icon icon-user-scholarships"></i>
          <span v-if="newScholarships && pathName !== 'select'" class="user-menu__num">{{ newScholarships }}</span>
        </span>
      </a>
    </li>
    <li class="user-menu__item link-item">
      <a :class="['user-menu__link', {'user-menu__link_active' : isLinkActive('mailbox') }]"
        href="/mailbox">
        <span style="position: relative">
          <span v-if="xl || xxl" style="margin-right: 10px">mailbox</span> <i class="icon icon-user-mailbox"></i>
          <span v-if="unreadInbox && pathName !== 'select'" class="user-menu__num">{{ unreadInbox }}</span>
        </span>
      </a>
    </li>
    <li class="user-menu__item">
      <section>
        <user-inform-panel v-if="profile"
          ref="userMenuBtn"
          @click.native.prevent="toggleUserInfo"
          :is-short="xs || s"
          :package-name="packageName"
          :gender="profile.gender"
          :first-name="profile.firstName"
          :last-name="profile.lastName" />

        <user-drop-down
          v-click-outside="{
            exclude: ['userMenuBtn'],
            handler: 'closeUserInfo'
          }"
          :is-open="isOpenUserInformation"
          :profile="profile"
          :membership="membership" />
      </section>
    </li>
  </ul>
</template>

<script>
import {mapGetters} from "vuex";
import { ELIGIBLE_SCHOLARSHIP_COUNT, NOT_SEEN_SCHOLARSHIP_COUNT } from "store/eligibility-cache";
import UserInformPanel from "./UserInformPanel.vue";
import UserDropDown from "./UserDropDown.vue";

export default {
  created() {
    this.$store.dispatch("eligibilityCache/getEligibilities", [
      ELIGIBLE_SCHOLARSHIP_COUNT,
      NOT_SEEN_SCHOLARSHIP_COUNT
    ])
  },
  props: {
    pathName: { type: String },
    profile: {type: Object, required: true},
    scholarshipCount: {type: Number, required: true},
    unreadInbox: {type: Object, required: true},
    accountId: {type: Number, required: true}
  },
  components: {
    UserInformPanel,
    UserDropDown
  },
  data() {
    return {
      mailbox: null,
      isOpenUserInformation: false,
      isUserInfoClosedOutside: false,
    };
  },
  computed: {
    packageName() {
      if(!this.membership) return;

      return !this.membership.subscriptionId
        ? "free" : this.membership && this.membership.freeTrial
          ? "free trial" : this.membership && this.membership.name;
    },
    ...mapGetters({
      xs: "screen/xs",
      s: "screen/s",
      l: "screen/l",
      xl: "screen/xl",
      xxl: "screen/xxl",
      membership: "account/membership",
      newScholarships: `eligibilityCache/${NOT_SEEN_SCHOLARSHIP_COUNT}`
    }),
  },
  methods: {
    toggleUserInfo() {
       this.isOpenUserInformation = !this.isOpenUserInformation;
    },
    closeUserInfo() {
      this.isOpenUserInformation = false;
    },
    isLinkActive(page) {
      return page.indexOf(document.location.pathname.substring(1).toLowerCase()) !== -1;
    },
  }
};
</script>

<style lang="scss">
  @import 'node_modules/breakpoint-sass/stylesheets/_breakpoint.scss';
  @import 'style-gide/breakpoints';
  @import 'main/meta/flex-box';

  .user-menu {
    margin: 0;
    padding: 0;
    list-style: none;

    @include flexbox();
    @include justify-content(center);
    @include align-items(center);

    &__link {
      font-size: 14px;
      color: #2F2F2F;
      text-transform: uppercase;
      font-weight: 600;

      padding: 17px 5px;

      &_active {
        border-bottom: 2px solid #708FE7;
      }

      &:hover {
        color: #708FE7;
        text-decoration: none;

        .icon {
          color: #708FE7;
        }
      }

      .icon {
        color: #C2C2C2;
        font-size: 20px;
        vertical-align: middle;
      }

      @include breakpoint($s $l - 1px) {
        padding: 17px 10px;
      }
    }

    &__item {
      & + & {
        margin-left: 15px;
      }

      &.link-item {
        position: relative;
      }
    }

    &__num {
      position: absolute;
      top: -10px;
      right: -10px;
      width: 18px;
      height: 18px;
      background-color: #FF6633;
      border-radius: 50%;
      display: block;
      font-size: 8px;
      color: white;
      text-align: center;
      line-height: 18px;
    }
  }
</style>