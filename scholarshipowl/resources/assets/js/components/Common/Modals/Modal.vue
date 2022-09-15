<template>
  <transition @after-enter="overlayOpenedHolder" @after-leave="afterLeaveHolder" name="fade">
    <section class="modal-overlay-vue" v-if="overlayStatus">
      <transition @after-leave="hideOverlay" name="slide">
        <component class="modal-vue" v-if="modalStatus" @action="action"
          :is="componentName" v-bind="modalProps" @hide="hide" />
      </transition>
    </section>
  </transition>
</template>

<script>
  import { mapActions, mapState } from "vuex";

  import Default from "components/Common/Modals/Default.vue";
  import Promotion from "components/Common/Modals/Promotion.vue";
  import SentMessage from "components/Common/Modals/SentMessage.vue";
  // import Payment from "components/Common/Modals/Payment.vue";

  const eventHolder = (eventName, callback, target = document.body) => {

    const event = target.addEventListener(eventName, callback);

    return () => {
      target.removeEventListener(eventName, callback);
    }
  }

  export default {
    components: {
      Default,
      Promotion,
      SentMessage,
      // Payment
    },
    created() {
      window.modalVue = this;

      const ESCAPE = 27;

      this.escEvent = eventHolder("keyup", ev => {
        const code = ev.which || ev.keyCode;

        if(!code) return;

        if(code === ESCAPE) {
          this.hide();
        }
      });
    },
    beforeDestroy() {
      this.escEvent();
    },
    data() {
      return {
        showCancelLoader: false,
        afterHookPlayload: null
      }
    },
    computed: {
      ...mapState("modal", {
        overlayStatus: state => state.modal.overlayStatus,
        modalStatus: state => state.modal.modalStatus,
        content: state => state.modal.content,
        tracking: state => state.modal.tracking,
        modalName: state => state.modal.modalName,
        componentName: state => state.modal.componentName
      }),
      modalProps() {
        return {
          content: this.content,
          tracking: this.tracking,
          modalName: this.modalName,
          showCancelLoader: this.showCancelLoader
        };
      }
    },
    methods: {
      show(options) {
        this.showModal(options);
      },
      hide(playload) {
        if(playload) this.afterHookPlayload = playload;

        this.hideModal();
      },
      overlayOpenedHolder() {
        this.getContent();
      },
      afterLeaveHolder() {
        this.triggerAfterHooks(this.afterHookPlayload);

        this.afterHookPlayload = null;
      },
      action({ name, playload }) {
        if(!name || !playload || !Object.keys(playload).length)
          throw Error("Please provide correct name and playload.");

        if(name === 'subscription-cancel') {
          if(!playload.id) throw Error("subscription id not exist");

          this.showCancelLoader = true;

          this.subscriptionCancel(playload.id)
            .then(() => {
              this.showCancelLoader = false;
              this.hideModal();
            })
        }
      },
      ...mapActions("modal", [
        "showModal",
        "hideModal",
        "getContent",
        "hideOverlay",
        "triggerAfterHooks",
      ]),
      ...mapActions("account", [
        "subscriptionCancel"
      ])
    },
  }
</script>

<style lang="scss">
  .modal-vue {
    transform: translateY(-50%);
    top: 50%;
  }

  .modal-overlay-vue {
    @extend %modal-backdrop-basic;
  }
</style>

