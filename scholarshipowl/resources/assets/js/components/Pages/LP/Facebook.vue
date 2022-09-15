<template lang="html">
  <div>
    <section class="top-block fb-lp">
      <section class="fb-login top-block__facebook">
        <div class="fb-login__wrp">
          <header>
            <h3 style="line-height: 1.2" class="tac">
              <p class="dark-light f_52m ttu ls22"><b>Hundreds of</b></p>
              <p class="blue-lighter f_52m ttu"><b>Scholarships</b></p>
              <p class="dark-light f_28m ttu ls04 fw300">with just one registration</p>
            </h3>
            <p class="delimeter-line"></p>
          </header>
          <main>
            <div class="bottom-30 tac f_18m lh13">
              <p>ScholarshipOwl helps you find scholarships</p>
              <p>and makes applying to fast and easy</p>
              <p>Get the financial aid you deserve today.</p>
            </div>

            <FacebookLoginButton redirect='/select' class="f_18 ttu btn_facebook-lp" />

            <a href="/register" class="top-10 btn_email fb-login__btn-email db">
              sign up using email<i class="icon icon-mailbox"></i>
            </a>

            <p class="tac f_13m blue-lighter top-10">We'll never post anything without your permission.</p>
          </main>
          <footer class="fb-login__footer">
            <p class="fb-login__wrp dib"><span>Already have an account?</span>
            <a @click.prevent="openModal('login')" href>Log in here</a></p>
          </footer>
        </div>
      </section>
      <Phone class="phone_facebook-lp" />
    </section>
    <HowWork />
    <OurMission />
    <Slider />
    <section class="video">
      <iframe class="video__frame" width="100%" src="https://www.youtube.com/embed/Y0eE7Tr8PcI" frameborder="0" allowfullscreen></iframe>
    </section>
  </div>
</template>

<script>
import { mapActions } from "vuex";

import Phone                from "../../Common/Phone.vue";
import HowWork              from "../../Layout/LP/Facebook/HowWork.vue";
import OurMission           from "../../Layout/LP/Mission.vue";
import Slider               from "../../Layout/Carousel.vue";
import FacebookLoginButton  from "../../Common/Buttons/FacebookLogin.vue";

export default {
  components: {
    Phone,
    HowWork,
    OurMission,
    Slider,
    FacebookLoginButton,
  },
  methods: mapActions({
    openModal: "modal/openModal"
  })
};
</script>

<style lang="css">
</style>
