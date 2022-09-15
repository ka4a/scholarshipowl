<template lang="html">
  <section class="how-work">
    <div class="filled-bg"></div>
    <div class="path-bg-f bp-l-wrp">
      <h2 class="how-work__title title-how-work"><span class="new-line">How it</span> <span>WORKS?</span></h2>
      <span class="delimeter-orange"></span>

      <div class="step step_1 step_1-facebook">
        <div class="step-illustration">
          <span class="step-illustration__img_1"></span>
        </div>
        <div class="step-text-set step-text-set_facebook">
          <h2 class="step-text-set__title title-step">01</h2>
          <h2 class="step-text-set__action title-step-sub title-step-sub_facebook-lp">
            <p>Create a profile</p>
          </h2>
          <div class="step-text-set__paragraph paragraph-step">
            <p>It takes less than 5 minutes to create</p>
            <p>a profile.</p>
          </div>
        </div>
      </div>

      <div class="step step_2 step_2-facebook">
        <div class="step-illustration">
          <span class="step-illustration__img_2 step-illustration__img_2-f"></span>
        </div>
        <div class="step-text-set step-text-set_facebook even">
          <h2 class="step-text-set__title title-step">02</h2>
          <h2 class="step-text-set__action title-step-sub title-step-sub_facebook-lp">
            <p>We match you</p>
            <p>with scholarships</p>
          </h2>
          <div class="step-text-set__paragraph paragraph-step">
            <p>We search and match you with</p>
            <p>dozens of eligible scholarships.</p>
          </div>
        </div>
      </div>

      <div class="step step_3 step_3-facebook">
        <div class="step-illustration">
          <span class="step-illustration__img_3 step-illustration__img_3-f"></span>
        </div>
        <div class="step-text-set step-text-set_facebook">
          <h2 class="step-text-set__title title-step">03</h2>
          <h2 class="step-text-set__action title-step-sub title-step-sub_facebook-lp">
            <p>We submit your</p>
            <p>application</p>
          </h2>
          <div class="step-text-set__paragraph paragraph-step">
            <p>Get applied to multiple scholarships easily</p>
            <p>and efficiently.</p>
            <p>You are now closer to winning scholarship</p>
            <p>money for college!</p>
          </div>
        </div>
      </div>
    </div>
    <FacebookLoginButton redirect="/select" class="f_18 ttu btn_facebook-lp how-work__btn-f" />
  </section>
</template>

<script>
import FacebookLoginButton from "../../../Common/Buttons/FacebookLogin.vue";

export default {
  components: {
    FacebookLoginButton
  }
};
</script>

<style lang="css">
</style>
