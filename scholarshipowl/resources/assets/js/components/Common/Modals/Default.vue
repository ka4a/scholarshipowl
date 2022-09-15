<template>
  <ModalFrame class="default-modal" :closeHandler="hide">
    <template v-if="content">
      <div v-if="content.img" :class="['default-modal__img', imageHolderClass]">
        <img :src="content.img">
      </div>

      <ModalContent class="v-modal-content" :title="content.title"
        :text="content.text" :html="content.html" />

      <div v-if="content.button" class="default-modal__btn-set">
        <Button v-if="content.button['cancel']" @click.prevent.native="cancel"
          theme="grey" size="s" sizeLoader="s" style="width: 172px"
          :show-loader="showCancelLoader" :label="content.button['cancel'].text" />

        <Button v-if="content.button['keep']" theme="orange" @click.prevent.native="keep(); hide();"
          size="s" sizeLoader="s" :label="content.button['keep'].text" style="width: 172px" />
      </div>

      <ModalPixels v-if="tracking && modalName === 'success-basic'" :tracking="tracking" />
    </template>
  </ModalFrame>
</template>

<script>
  import mixpanel from "lib/mixpanel";
  import ModalFrame from "components/Common/Modals/ModalFrame.vue";
  import ModalContent from "components/Common/Modals/ModalContent.vue";
  import ModalPixels from "components/Common/Modals/ModalPixels.vue";
  import Button from "components/Common/Buttons/ButtonCustom.vue";

  export default {
    mounted() {
      if (this.modalName === 'success-basic') {
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            'event': 'CongratulationsPopup'
        });
      }
    },
    props: {
      content: {type: Object, required: true},
      tracking: {type: Object, required: false},
      modalName: {type: String, required: false},
      showCancelLoader: {type: Boolean, required: false}
    },
    components: {
      ModalFrame,
      ModalContent,
      ModalPixels,
      Button,
    },
    computed: {
      imageHolderClass() {
        return {
          'default-modal__img_basic': this.modalName === 'success-basic',
          'default-modal__img_cancelation': this.modalName.indexOf('canselation') > -1
        }
      }
    },
    methods: {
      hide() {
        this.$emit('hide');
      },
      cancel() {
        this.$emit('action', {
          name: 'subscription-cancel',
          playload: {
            id: this.content.button['cancel'].subscriptionId
          }
        })

        let eventName = this.content.isFreeTrial
          ? 'CancelNow_FreeTrial_Click_Cancelation_Popup'
          : 'CancelNow_PaidMembership_Click_Cancelation_Popup';

        mixpanel.track(eventName);
      },
      keep() {
        let eventName = this.content.isFreeTrial
          ? 'KeepActive_FreeTrial_Click_Cancelation_Popup'
          : 'KeepActive_PaidMembership_Click_Cancelation_Popup';

        mixpanel.track(eventName);
      }
    }
  }
</script>

<style lang="scss">
  .default-modal {
    &__img {
      > img {
        display: block;
        width: 100%;
      }

      width: 59%;
      max-width: 352px;
      margin-left: auto;
      margin-right: auto;
      margin-top: 20px;
      min-height: 96px;

      @include breakpoint($s) {
        width: 59%;
      }

      @include breakpoint($m) {
        width: 67%;
      }

      &_basic {
        min-height: 122px;

        @include breakpoint($s) {
          min-height: 180px;
        }

        @include breakpoint($m) {
          min-height: 240px;
        }
      }

      &_cancelation {
        min-height: 80px;

        @include breakpoint($s) {
          min-height: 118px;
        }

        @include breakpoint($m) {
          min-height: 154px;
        }
      }
    }

    .v-modal-content {
      max-width: 406px;
      margin-left: auto;
      margin-right: auto;

      @include breakpoint(max-width $s - 1px) {
        padding: 0 5px;
      }

      .db-s {
        @include breakpoint(max-width $s - 1px) {
          display: block;
        }
      }
      .db-s-m {
        @include breakpoint($s $m - 1px) {
          display: block;
        }
      }
    }

    &__loader {
      height: 60px;
    }

    &__btn-set {
      margin-top: 15px;
      height: 90px;
      max-width: 360px;
      margin-left: auto;
      margin-right: auto;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      align-items: center;

      @include breakpoint($s) {
        margin-top: 25px;
        height: auto;
        flex-direction: row;
      }

      @include breakpoint($m) {
        margin-top: 30px;
      }
    }
  }
</style>