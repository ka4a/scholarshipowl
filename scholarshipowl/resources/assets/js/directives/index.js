import Vue from "vue";
import { scroll } from "../lib/utils/dom.js";

Vue.directive("focus", { inserted: element => element.focus() });

Vue.directive("scrollTo", {
  inserted: (element, binding) => {
    if(!binding.value) {
      throw Error("scorll to element selector not defined");
    }

    let targetScrollElement = document.querySelector(binding.value);

    if(!targetScrollElement) {
      throw Error("scroll to element not defined");
    }

    element.addEventListener("click", function() {
      scroll(targetScrollElement);
    });
  }
});

Vue.directive("best", {
  inserted: (element) => {
    const className = element.className || "";

    if (className.indexOf("best") === -1) {
      element.className += " best";
    }
  }
});

Vue.directive('click-outside', {
  bind (el, binding, vnode) {
    const handler = (e) => {
      e.stopPropagation();
      const { handler, exclude = [], skipIfTargetRemoved = false } = binding.value;
      let clickedOnExcludedEl = false;
      exclude.forEach(refName => {
        if (!clickedOnExcludedEl) {
          if (vnode.context.$refs[refName]) {
            // Get the element using the reference name
            const excludedEl = vnode.context.$refs[refName].$vnode ?
              vnode.context.$refs[refName].$vnode.elm : vnode.context.$refs[refName];
            clickedOnExcludedEl = excludedEl.contains(e.target)
          }
        }
      });

      // skip if clicked element was removed from DOM
      const isTargetConditionOK= !skipIfTargetRemoved || document.body.contains(e.target);

      if (!el.contains(e.target) && isTargetConditionOK && !clickedOnExcludedEl) {
        vnode.context[handler]()
      }
    };

    el.__vueClickOutside__ = handler;

    document.addEventListener('click', handler);
    document.addEventListener('touchstart', handler);
  },

  unbind(el) {
    // If the element that has v-closable is removed, then
    // unbind click/touchstart listeners from the whole page
    document.removeEventListener("click", el.__vueClickOutside__);
    document.removeEventListener('touchstart', el.__vueClickOutside__);
    el.__vueClosable__ = null;
  }
});

Vue.directive("float-top", {
  inserted: function(el, { value }) {

    let visibleSection = document.querySelector(value);

    if(!visibleSection) {
      throw new Error("Visible section is not defined");
    }

    if(document.documentElement.clientWidth > 480) {
      return;
    }

    function scrollChangeHandler() {
      let rect = visibleSection.getBoundingClientRect();

      if (rect.top + visibleSection.clientHeight < document.documentElement.clientHeight) {
        el.style.position = "absolute";
      } else {
        el.style.position = "fixed";
      }
    }

    window.addEventListener("scroll", scrollChangeHandler);
    window.addEventListener("load", scrollChangeHandler);
  }
});
