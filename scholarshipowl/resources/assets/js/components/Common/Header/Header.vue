<template>
  <header ref="header" class="header-layout">
    <div class="header-layout__wrp-first">
      <logo :isShort="xs || s || m" class="header-layout__logo" />
      <phone :phone-number="phoneNumber" :isShort="xs || s || m" v-if="showPhone && (authenticated || (!authenticated && xs || s))"
        :class="['header-layout__sowl-phone-number', {'phone-authenticated': authenticated}]" />
    </div>
    <div :class="['header-layout__wrp-second', {'changed-order': !authenticated}]">
      <phone :phone-number="phoneNumber" v-if="showPhone && (!authenticated && (m || l || xl || xxl))"
             :isShort="xs || s" class="header-layout__sowl-phone-number"
           style="margin-right: 25px;"/>
      <user-menu :profile="profile" :scholarship-count="scholarshipCount" :account-id="accountId"
        :unread-inbox="unreadInbox" :path-name="pathName" v-if="authenticated" />

      <login-button v-if="!authenticated" class="header-layout__login-button" />
      <a v-if="!authenticated && showApplyBtn" class="apply-button" href="/register">apply</a>
    </div>
    <main-menu :isShort="(xs || s || m || l) || authenticated && (xl || xxl)" :menuIsOpen="(xl || xxl) && !authenticated" :menu="menu" />
  </header>
</template>

<script>
import { mapGetters } from "vuex";
import screenResolution from "lib/screen-resolution";
import Logo from "./Logo.vue";
import LoginButton from "./LoginButton.vue";
import Phone from "./Phone.vue";
import MainMenu from "./MainMenu.vue";
import UserMenu from "./UserMenu.vue";

const stickyHeaderOnPages = [
  "what-people-say-about-scholarshipowl",
  "faq",
  "awards/scholarship-winners",
  "about-us",
  "press",
  "additional-services",
  "ebook",
  "offer-wall",
  "jobs",
  "contact",
  "list-your-scholarship",
  "partners",
  "help",
  "privacy",
  "terms"
];

export default {
  components: {
    Logo,
    LoginButton,
    Phone,
    MainMenu,
    UserMenu,
  },
  mounted() {
    if(stickyHeaderOnPages.indexOf(this.pathName) === -1) {
      return;
    }

    let isSticky = false,
      headerHeight = this.$refs.header.offsetHeight,
      middleBlock = window.main;

    window.document.addEventListener("scroll", () => {
      let scrollSize = window.pageYOffset;

      if(scrollSize >= headerHeight && !isSticky) {
        this.$refs.header.style.position = "fixed";
        this.$refs.header.style.top = "-" + headerHeight + "px";
        middleBlock.style.marginTop = headerHeight + "px";

        setTimeout(() => {
          this.$refs.header.style.transition = "top 300ms";
          this.$refs.header.style.top = "0";
        }, 200);

        isSticky = true;
      }

      if(!window.pageYOffset && isSticky) {
        this.$refs.header.style.transition = "none";
        middleBlock.style.marginTop = "0";
        this.$refs.header.style.position = "relative";

        isSticky = false;
      }
    });
  },
  created() {
    if(!this.authenticated) return;

    this.$store.dispatch('account/fetchData', [
      'membership',
      'account',
      'scholarship',
      'profile',
      'mailbox']);
  },
  computed: {
    ...mapGetters({
      authenticated: "account/authenticated",
      profile: "account/profile",
      scholarshipCount: "account/scholarshipCount",
      unreadInbox: "account/unreadInbox",
      accountId: "account/accountId",
      showPhone: "settings/showPhone",
      phoneNumber: "settings/phoneNumber",
      xs: "screen/xs",
      s: "screen/s",
      m: "screen/m",
      l: "screen/l",
      xl: "screen/xl",
      xxl: "screen/xxl"
    }),
    pathName() {
      return document.location.pathname.substring(1).toLowerCase();
    },
    showApplyBtn() {
      return !(!this.pathName ||
        this.pathName.indexOf("awards/you-deserve-it-scholarship") > -1)
    }
  },
  data: function() {
    return {
      screenResolution,
      menu: [
        {
          name: "Info",
          id: "1",
          links: [
            {
              "text": "FAQ",
              "href": "/faq",
              id: 1
            },
            {
              "text": "Reviews",
              "href": "/what-people-say-about-scholarshipowl",
              id: 2
            },
            {
              "text": "Scholarship Winners",
              "href": "/awards/scholarship-winners",
              id: 3
            },
          ]
        }, {
          name: "About us",
          id: "2",
          links: [
            {
              "text": "About us",
              "href": "/about-us",
              id: 4
            },
            {
              "text": "Press",
              "href": "/press",
              id: 5
            },
          ]
        }, {
          name: "Services",
          id: "3",
          links: [
            {
              "text": "Additional Services",
              "href": "/additional-services",
              id: 6
            },
            {
              "text": "eBook",
              "href": "/ebook",
              id: 7
            },
            {
              "text": "ScholarshipOwl Academy",
              "href": "http://academy.scholarshipowl.com/",
              "target": "_blank",
              id: 8
            },
            {
              "text": "Featured Scholarships",
              "href": "/offer-wall",
              id: 9
            },
            {
              "text": "Student discount",
              "href": "https://scholarshipowl.studentbeans.com/us",
              "target": "_blank",
              id: 10
            },
            {
              "text": "Jobs",
              "href": "/jobs",
              id: 11
            }
          ]
        },
        {
          name: "Contact",
          id: "4",
          links: [
            {
              "text": "Contact",
              "href": "/contact",
              id: 11
            },
            {
              "text": "List your scholarship",
              "href": "/list-your-scholarship",
              id: 12
            },
            {
              "text": "Partners",
              "href": "/partners",
              id: 13
            },
          ]
        }, {
          name: "Blog",
          id: "5",
          links: [
            {
              "text": "Blog",
              "href": "http://blog.scholarshipowl.com/",
              "target": "_blank",
              id: 14
            }
          ]
        }
      ]
    };
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';

  // mautic modificator
  .MauticFocusBar .mf-bar-iframe {
    height: 64px !important;
  }

  .header-layout {
    height: 58px;
    min-height: 58px;
    width: 100%;
    max-height: 58px;
    background-color: #fff;
    position: relative;
    z-index: 5002;
    box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.25);
    @include flexbox();
    @include justify-content(flex-end);
    @include align-items(center);

    padding-left: 15px;
    padding-right: 15px;

  a:focus {
    text-decoration: none;
    color: inherit;
  }

  &__wrp-first {
    margin-right: auto;
    @include flexbox();
  }

  &__wrp-second {
    @include flexbox();
  }

  &__sowl-phone-number {
    margin: 0 0 0 20px;
  }

  &__login-button {
    margin: 0 5px 0 0;
  }

  @include breakpoint($s) {
    padding-left: 25px;
    padding-right: 25px;
    z-index: 17;
  }

  .apply-button {
    // text
    color: white;
    font-size: 12px;
    line-height: 30px;
    font-weight: bold;
    text-transform: uppercase;
    text-align: center;

    // box
    display: block;
    width: 62px;
    height: 30px;
    background-color: #FF6633;
    border-radius: 2px;

    &:hover,
    &:focus {
      text-decoration: none;
    }
  }
  }

  @include breakpoint($s) {
    .header-layout {
      .apply-button {
        font-size: 14px;
        width: 84px;
      }
    }
  }

  @include breakpoint($s $m) {
    .header-layout {
      &__sowl-phone-number {
        margin-left: 60px;
      }

      &__sowl-phone-number.phone-authenticated {
        margin-left: 20px;
      }
    }
  }

  @include breakpoint($l) {
    .header-layout {
      @include justify-content(flex-start);

      &__wrp-first {
      margin-right: 30px;
      }

      &__wrp-second {
        margin-left: auto;
      }

      &__wrp-second.changed-order {
        @include order(3);
      }
    }
  }
</style>