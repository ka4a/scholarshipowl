<template>
  <transition @after-enter="backDropAfterEnter" @after-leave="$emit('closed')" name="fade">
    <section v-show="backDropIsShown" class="v-modal-backdrop">

      <transition @after-leave="modalIframeAfterLeave" @after-enter="$emit('opened')" name="slide">
        <div v-show="modalFrameIsShown" class="v-modal-frame">
          <slot />
        </div>
      </transition>

    </section>
  </transition>
</template>

<script>
  import { isMobile } from "lib/utils/utils";

  export default {
    props: {
      show: {type: Boolean, required: true, default: false}
    },
    data() {
      return {
        backDropIsShown: false,
        modalFrameIsShown: false
      }
    },
    watch: {
      show(newVal) {
        if(newVal) {
          this.backDropIsShown = true;
        } else {
          this.modalFrameIsShown = false;
        }
      }
    },
    methods: {
      backDropAfterEnter() {
        this.modalFrameIsShown = true;
      },
      modalIframeAfterLeave() {
        this.backDropIsShown = false;
      }
    }
  }
</script>

<style lang="scss">
  .v-modal-backdrop {
    @include breakpoint(max-width $m - 1px) {
      -webkit-overflow-scrolling: touch;
    }

    @extend %modal-backdrop-basic;
  }

  .v-modal-frame {
    position: relative;

    @include breakpoint(max-width $m - 1px) {
      top: 0;
      bottom: 0;
      -webkit-overflow-scrolling: touch;
    }

    @include breakpoint($m) {
      transform: translateY(-50%);
      top: 50%;
    }
  }
</style>