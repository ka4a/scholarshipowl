<template>
  <header class="layout__header header">
    <div class="header__wrp">
      <button @click="toggleNavigation()" :class="{'burger-icon': true, 'burger-icon_active': isOpenNavigation}"></button>
      <NavigationTop :is-open="isOpenNavigation" />

      <h1 class="header__logo logo">
        <a href="/" class="logo__link">
          <span class="logo__img db">
            <img class="fluid-img" src="/assets/img/new-logo.png" alt="logo">
          </span>
          <span class="logo__text"><strong>Scholarship</strong>Owl</span>
        </a>
      </h1>

      <div class="header__right">
        <address class="phone-num-top">
          <i class="icon-phone icon"></i>
          <a href="tel:+18004944908" class="phone-num-top__value">+1 800 494 49 08</a>
        </address>
        <div class="header__interaction interaction">
          <a @click="openModal('login')" class="interaction__btn-login btn-login">login</a>
          <a href="/register" class="interaction__btn-apply btn-apply">apply</a>
          <LoginRegister />
        </div>
      </div>

    </div>
  </header>
</template>

<style>

</style>

<script>
import NavigationTop from "./NavigationTop.vue";
import LoginRegister from "./LoginRegister.vue";
import { mapActions } from "vuex";

export default {
  data() {
    return {
      isOpenNavigation: false
    };
  },
  methods: {
    toggleNavigation() {
      this.isOpenNavigation = !this.isOpenNavigation;
    },
    ...mapActions("modal", [
      "openModal"
    ])
  },
  components: {
    NavigationTop,
    LoginRegister
  }
};
</script>
