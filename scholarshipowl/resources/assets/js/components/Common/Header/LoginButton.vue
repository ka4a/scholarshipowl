<template>
  <button class="login-button" @click.prevent="openModal('login')">login</button>
</template>

<script>
import {mapActions} from "vuex";
import store from "../../../store";
import { parse } from "cookie-js";

if (parse(window.document.cookie)['showLoginPopup']) {
    window.document.cookie = 'showLoginPopup' + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    store.dispatch("modal/openModal", "login", {root: true});
}

export default {
  methods: {
    ...mapActions("modal", ["openModal"])
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';

  .login-button {
    border: none;
    background-color: transparent;

    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    color: #2f2f2f;

    @include breakpoint($s) {
      font-size: 14px;
    }
  }
</style>