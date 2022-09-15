<template>
  <div>
    <div class="top">
      <div class="top__wrp">
        <section class="greeting">
          <div class="greeting__wrp bp-l-wrp">
            <h1 class="greeting__title title">Double Your<br />SCHOLARSHIP</h1>
            <p class="greeting__title-sub title-sub">ScholarshipOwl.com</p>
            <div class="greeting__paragraph paragraph z1">
              <p>Now, we are going a step further.</p>
              <p>Any scholarship that you apply for and</p>
              <p>are awarded through ScholarshipOwl,</p>
              <p>is eligible to be doubled!</p>
            </div>
            <p class="greeting-img">
              <img class="fluid-img" :src="greetingSrc" alt="logo">
            </p>
          </div>
          <button
              v-float-top="'.greeting__wrp'"
              v-scroll-to="'#registerForm'"
              class="icon icon-arrow-double greeting__scroll-to-form">
          </button>
        </section>
        <Phone class="bp-l__phone"/>
        <RegisterForm />
      </div>
    </div>
    <HowWork />
    <OurMission />
    <Carousel />
    <section class="double-now">
      <div class="bp-l-wrp">
        <h2 class="double-now__title title-bouble">Double NOW!</h2>
        <span class="delimeter-orange"></span>
        <p class="double-now__text paragraph-bouble">Winning a scholarship is great. Having that scholarship instantaneously doubled is awesome!</p>
        <p class="double-now__text paragraph-bouble">So, what are you waiting for? Search and apply for financial aid opportunities on ScholarshipOwl.com</p>
        <button v-scroll-to="'#registerForm'" class="double-now__btn btn-orange">Register NOW!</button>
      </div>
    </section>
    <section class="disclaimer">
      <div class="bp-l-wrp">
        <p class="disclaimer__text paragraph-details">*Details and qualifications for participation in this promotion may apply.
        Please read the Rules for the <a href="/terms#exhibit" target="_blank">”You Deserve It”</a> Scholarship and the
        <a href="/promotion-rules" target="_blank">“Double Your Opportunity“</a> promotion.
        Only those applying for a third-party scholarship through Owl's website and using Owl's services between July 1, 2017 through 11:59 p.m. EST on July 31, 2018,
        who win such scholarship, and fulfill requirements, will win the "Double Your Opportunity" promotion.
        Void where prohibited or restricted by law.</p>
      </div>
    </section>
  </div>
</template>

<script>
import Phone        from "../Common/Phone.vue";
import RegisterForm from "../Layout/RegisterForm.vue";
import Carousel     from "../Layout/Carousel.vue";
import OurMission   from "../Layout/LP/Mission.vue";
import HowWork      from "../Layout/LP/Double/HowWork.vue";

export default {
  components: {
    HowWork,
    RegisterForm,
    Carousel,
    OurMission,
    Phone,
  },
  data() {
    return {
      greetingSrc: "/assets/img/greeting@2x.png"
    };
  }
};
</script>
