import "./bootstrap";
import Vue from "vue";
import store from "./store";
import Modal from "components/Common/Modals/Modal.vue";

if (document.getElementById("login-modal")) {
  require.ensure(["components/Layout/LoginRegister.vue"], function() {
    let LoginRegister = require("components/Layout/LoginRegister.vue").default;
    window["LoginModal"] = new Vue({
      el: "#login-modal",
      ...LoginRegister,
      store,
    });
  });
}

// main modal component modal-vue
if (document.getElementById("modal-vue")) {
  new Vue({
    el: "#modal-vue",
    ...Modal,
    store,
  });
}

// registration desktop page // will be extened and rebuild on vue
if (document.getElementById("vue-header") && document.location.pathname !== "/mailbox") {
  require.ensure(["components/Common/Header/HeaderMount.vue"], function() {
    let Header = require("components/Common/Header/HeaderMount.vue").default;

    new Vue({
      el: "#vue-header",
      ...Header,
      store,
    });
  });
}

// create login button instances based on el text and classes
// for triggering vue LoginRegister modal window form non vue scope
[".login-btn", "#btnLogin2", ".login-button"].forEach(selector => {
  Array.prototype.forEach.call(document.querySelectorAll(selector), el => {
    el.addEventListener("click", () => {
      store.dispatch("modal/openModal", "login");
    });
  });
});

// jobs page
if (document.getElementById("jobs-vue-bind-point")) {
  require.ensure(["components/Pages/Jobs.vue"], function() {
    let Jobs = require("components/Pages/Jobs.vue").default;
    window["JobsVuePage"] = new Vue({
      el: "#jobs-vue-bind-point",
      ...Jobs,
      store,
    });
  });
}

// footer
if (document.getElementById("footer-vue") && document.location.pathname !== "/mailbox") {
  require.ensure(["components/Common/Footer/FooterMount.vue"], function() {
    let FooterMount = require("components/Common/Footer/FooterMount.vue").default;
    new Vue({
      el: "#footer-vue",
      ...FooterMount,
      store,
    });
  });
}