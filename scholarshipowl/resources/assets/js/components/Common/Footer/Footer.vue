<template lang="html">
  <section class="footer-layout">
    <header>
      <logo-footer class="footer-layout__logo" />
      <copyright-footer class="footer-layout__copyright" />
    </header>

    <menu-footer :menu="menu" class="footer-layout__menu" />

    <address-footer class="footer-layout__address" :company-details="companyDetails" />

    <links-footer :links="links" class="footer-layout__links second" />

    <p class="footer-layout__download">
      <span style="margin-right: 15px">Download our App</span>
      <link-i-os-app :href="linksApp.ios.href" :name="linksApp.ios.name"
        :text="linksApp.ios.text" :img="linksApp.ios.img" />
    </p>

    <footer>
      <p class="explanation-footer footer-layout__explanation">
        <span>ScholarshipOwl is a personalized scholarship matching service and  management tool.
        It does not guarantee the receipt of any scholarship.</span>
        <span class="explanation-footer__new-line">Upgrading to premium membership
        does not increase your chances of winning a specific scholarship.</span>
      </p>
      <watermarks-footer class="footer-layout__watermarks" />
    </footer>

  </section>

</template>

<script>
import LogoFooter       from "./LogoFooter.vue";
import CopyrightFooter  from "./CopyrightFooter.vue";
import MenuFooter       from "./MenuFooter.vue";
import AddressFooter    from "./AddressFooter.vue";
import LinksFooter      from "./LinksFooter.vue";
import WatermarksFooter from "./WatermarksFooter.vue";
import LinkIOsApp       from "components/Common/Footer/LinkIOsApp.vue";

export default {
  components: {
    LogoFooter,
    CopyrightFooter,
    MenuFooter,
    AddressFooter,
    LinksFooter,
    WatermarksFooter,
    LinkIOsApp
  },
  computed: {
    links() {
      return [
        {href: "/privacy",    text: "Privacy Policy", id: 1},
        {href: "/terms",      text: "Terms of Use", id: 2},
      ];
    },
    linksApp() {
      return {
        ios: {
          name: "App Store",
          text: "Download on the",
          img: require('./svg/logo-apple.svg'),
          href: "https://itunes.apple.com/us/app/scholarshipowl/id1447107897?ls=1&mt=8"
        }
      }
    },
    menu() {
      return [
        {id: "1", href: "/", text: "home"},
        {id: "2", href: "/help", text: "help"},
        {id: "3", href: "/faq", text: "faq"},
        {id: "4", href: "/press", text: "press"},
        {id: "5", href: "/jobs", text: "jobs"},
        {id: "6", href: "/contact", text: "contact"},
      ];
    },
    companyDetails() {
      return window.SOWLStorage.companyDetails;
    }
  }
};
</script>

<style lang="scss">

@import 'style-gide/_breakpoints.scss';

  $footer-layout-background-color: #354C6D;
  $footer-layout-color: #8094B0;

  // ul reset
  %reset-ul {
    margin: 0; padding: 0;
    list-style: none;
  }

  .footer-layout {
    background-color: $footer-layout-background-color;
    color: $footer-layout-color;
    font-size: 12px;
    padding-top: 40px;
    padding-bottom: 50px;

    & > * {
      box-sizing: content-box;
    }

    @include breakpoint($xl) {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
    }

    // helpers


    header,
    main,
    footer {
      padding-left: 14px;
      padding-right: 14px;
    }

    &__header {
      padding-left: 25px;
      padding-right: 25px;
    }

    &__footer {
      padding-left: 25px;
      padding-right: 25px;
    }

    &__copyright {
      font-size: 10px;
      margin: 5px 0 0 0;
    }

    &__menu {
      margin-top: 15px;
    }

    &__address {
      margin: 20px auto 0 auto;
    }

    &__download {
      display: flex;
      justify-content: center;
      align-items: center;

      margin-top: 30px;
      margin-bottom: 20px;

      @include breakpoint($xl) {
        float: right;
        padding-right: 57px;
        margin-top: 15px;
        margin-bottom: 0;
      }
    }

    &__links {
      @extend %reset-ul;
      margin: 30px 0 0 0;
    }

    &__explanation {
      margin-top: 25px;
      margin-bottom: 0;
      line-height: 1.43em;
    }

    &__watermarks {
      @extend %reset-ul;
      margin-top: 30px;
    }

    @include breakpoint(max-width $xl - 1px) {
      text-align: center;
    }

    @include breakpoint($s) {
      header,
      main,
      footer {
        padding-left: 20px;
        padding-right: 20px;
      }
    }

    @include breakpoint($m) {
      header,
      footer {
        padding-left: 30px;
        padding-right: 30px;
      }

      main {
        padding: 0;
      }

      &__menu {
        margin-top: 30px;
      }
    }

    @include breakpoint($xl) {
      // padding-bottom: 10px;
      // text-align: left;
      // overflow: hidden;

      // header {
      //   position: absolute;
      // }

      &__menu {
        width: 100%;
        margin-top: 0;
      }

      &__address {
        width: 260px;
        margin: 0;
      }

      &__links {
        width: 50%;
        margin-top: 0;
        padding-left: 30px;
        padding-right: 30px;
      }

      &__explanation {
        margin-top: 15px;
        float: left;
      }

      &__watermarks {
        margin-top: 10px;
        float: right;
      }

      footer {
        overflow: hidden;
        width: 100%;
        margin-top: 20px;
        border-top: solid 1px #405573;
      }
    }

    @include breakpoint(max-width $m - 1px) {
      &__menu {
        margin-left: auto;
        margin-right: auto;
        width: 64%;
      }
    }

    @include breakpoint(max-width $s - 1px) {
      &__menu {
        width: 84%;
      }
    }
  }

  .explanation-footer {
    &__new-line {
      @include breakpoint($l) {
        display: block;
      }
    }
  }
</style>
