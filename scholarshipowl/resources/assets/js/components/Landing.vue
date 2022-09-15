<template>
  <div class="lp lp-dys">
    <Header-simple />
    <router-view></router-view>
    <footer class="footer-light">
      <div class="footer-light__wrp">
        <nav class="footer-light__nav">
          <ul class="nav-footer-light">
            <li class="nav-footer-light__item"><a href="/" class="nav-footer-light__link link-footer-light">Home</a></li>
            <li class="nav-footer-light__item"><a href="/faq" class="nav-footer-light__link link-footer-light">FAQ’s</a></li>
            <li class="nav-footer-light__item"><a href="/help" class="nav-footer-light__link link-footer-light">Help</a></li>
          </ul>
          <ul class="nav-footer-light nav-footer-light_second">
            <li class="nav-footer-light__item"><a href="/privacy" class="nav-footer-light__link link-footer-light">Privacy Policy</a></li>
            <li class="nav-footer-light__item"><a href="/terms" class="nav-footer-light__link link-footer-light">T&C</a></li>
          </ul>
        </nav>
        <div class="footer-light__logo">
          <div class="footer-light__footer">
            <logo :isShort="false" />
          </div>
          <ul class="footer-light__social social">
            <li class="social__item"><a href="https://www.facebook.com/scholarshipowl" target="_blank" class="social__link"><i class="icon icon-facebook-round"></i></a></li>
            <li class="social__item"><a href="https://plus.google.com/114562434536822179936" target="_blank" class="social__link"><i class="icon icon-google-plus-round"></i></a></li>
            <li class="social__item"><a href="https://www.linkedin.com/company-beta/10072818" target="_blank" class="social__link"><i class="icon icon-linked-in-round"></i></a></li>
            <li class="social__item"><a href="https://twitter.com/ScholarshipOwl" target="_blank" class="social__link"><i class="icon icon-twitter-round"></i></a></li>
          </ul>
        </div>
      </div>
      <div class="footer-light__bottom">
        <div>
          <p class="mw780 white-more-darker f_12 lh13 tac">
            ScholarshipOwl is a personalized scholarship matching service and management tool.
            It does not guarantee the receipt of any scholarship. Upgrading to premium membership does
            not increase your chances of winning a specific scholarship.
          </p>
          <p class="white-more-darker top-10 f_10 tac">Copyright © 2017.  All Rights Reserved</p>
        </div>
      </div>
    </footer>
    <LoginRegister />
  </div>
</template>

<script>
  import LoginRegister        from "./Layout/LoginRegister.vue";
  import HeaderSimple         from "./Layout/HeaderSimple.vue";
  import Logo                 from "components/Common/Header/Logo.vue";

  export default {
    components: {
      Logo,
      LoginRegister,
      HeaderSimple,
    }
  };
</script>

<style lang="scss">
  @import 'node_modules/breakpoint-sass/stylesheets/breakpoint';

  .footer-light {
    &__footer {
      height: 100px;
      display: flex;
      justify-content: center;
      align-items: center;

      @include breakpoint(993px) {
        display: inline-flex;
      }
    }
  }
</style>