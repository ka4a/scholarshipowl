<template>
  <section class="hero login-layout is-fullheight">
    <div class="hero-body">
      <div class="container container__main">
        <a :href="homepageURL"><sunrise-logo /></a>
        <router-view />
        <div class="hero-body--footer">
          <a class="link" :href="homepageURL">About features</a>
          <a class="link" @click.prevent="$store.state.termsModal = true">Terms & Conditions</a>
        </div>
      </div>
      <div class="container container__aside" :style="{ backgroundImage: 'url(' + require('./login/bg.svg') + ')' }">
        <div class="hero-aside">
          <div class="sunrise-preview">
            <figure class="sunrise-preview--window"
              @click="setPreviewImage(require('./login/dashboard.png'))"
              :class="{ 'sunrise-preview--window__hidden': preview !== 0}">
              <div class="sunrise-preview--controls">
                <div class="sunrise-preview--control sunrise-preview--control-red"></div>
                <div class="sunrise-preview--control sunrise-preview--control-yellow"></div>
                <div class="sunrise-preview--control sunrise-preview--control-dark"></div>
              </div>
              <figure class="sunrise-preview--image">
                <img :src="require('./login/dashboard.png')" />
              </figure>
            </figure>
            <figure class="sunrise-preview--window"
              @click="setPreviewImage(require('./login/winners.png'))"
              :class="{ 'sunrise-preview--window__hidden': preview !== 1}">
              <div class="sunrise-preview--controls">
                <div class="sunrise-preview--control sunrise-preview--control-red"></div>
                <div class="sunrise-preview--control sunrise-preview--control-yellow"></div>
                <div class="sunrise-preview--control sunrise-preview--control-dark"></div>
              </div>
              <figure class="sunrise-preview--image">
                <img :src="require('./login/winners.png')" />
              </figure>
            </figure>
            <figure class="sunrise-preview--window"
              @click="setPreviewImage(require('./login/winners2.png'))"
              :class="{ 'sunrise-preview--window__hidden': preview !== 2}">
              <div class="sunrise-preview--controls">
                <div class="sunrise-preview--control sunrise-preview--control-red"></div>
                <div class="sunrise-preview--control sunrise-preview--control-yellow"></div>
                <div class="sunrise-preview--control sunrise-preview--control-dark"></div>
              </div>
              <figure class="sunrise-preview--image">
                <img :src="require('./login/winners2.png')" />
              </figure>
            </figure>
          </div>
          <div class="sunrise-preview--bottom">
            <h4 class="sunrise-preview--title" :class="{ 'sunrise-preview--title__showen': preview === 0}">Streamlines the application review and selection process</h4>
            <h4 class="sunrise-preview--title" :class="{ 'sunrise-preview--title__showen': preview === 1}">Enables all applications to be housed and organized in one place</h4>
            <h4 class="sunrise-preview--title" :class="{ 'sunrise-preview--title__showen': preview === 2}">Improves the communication between scholarship provider and applicant</h4>
            <div class="sunrise-preview--bottom-balls">
              <div class="sunrise-preview--bottom-ball" :class="{ 'sunrise-preview--bottom-ball__selected': preview === 0 }" @click="setPreview(0)"></div>
              <div class="sunrise-preview--bottom-ball" :class="{ 'sunrise-preview--bottom-ball__selected': preview === 1 }" @click="setPreview(1)"></div>
              <div class="sunrise-preview--bottom-ball" :class="{ 'sunrise-preview--bottom-ball__selected': preview === 2 }" @click="setPreview(2)"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <b-modal :active.sync="previewImage" width="auto" @close="startPreview" has-modal-card	>
      <p class="image preview-image">
        <img :src="previewImage">
      </p>
    </b-modal>
    <b-modal class="modal-terms" width="781px" :active.sync="$store.state.termsModal" :canCancel="['escape', 'outside', 'button']">
      <div class="box">
        <i class="boxclose" @click="$store.state.termsModal = false" />
        <h3 class="modal-title">Terms and Conditions</h3>
        <terms />
      </div>
    </b-modal>
  </section>
</template>
<script>
import SunriseLogo from 'components/logo.vue';
import Terms from './login/terms';

let previewInterval = null;

export default {
  components: {
    SunriseLogo,
    Terms
  },
  mounted() {
    this.startPreview();
  },
  destroyed() {
    this.stopPreview();
  },
  computed: {
    homepageURL() {
      return typeof HOMEPAGE_URL === 'undefined' ? '/' : HOMEPAGE_URL;
    }
  },
  methods: {
    setPreviewImage(image) {
      this.previewImage = image;
      this.stopPreview();
    },
    setPreview(i) {
      this.preview = i;
      this.stopPreview();
      this.startPreview();
    },
    startPreview() {
      previewInterval = setInterval(() => {
        if (this.preview === 2) {
          this.preview = 0;
        } else {
          this.preview += 1;
        }
      }, 6000);
    },
    stopPreview() {
      if (previewInterval) {
        clearInterval(previewInterval);
        previewInterval = null;
      }
    }
  },
  data: function() {
    return {
      preview: 0,
      previewImage: null,
    }
  }
}
</script>
<style lang="scss">
@import "../../scss/login";

$hero-body-bottom-padding: 52px;

.hero.is-fullheight.login-layout {
  background-color: #ffffff;

  .hero-body {
    align-items: stretch;
    padding: 0;

    > .container {
      margin: 0;
      padding-top: 8%;

      &__main {
        display: flex;
        flex-direction: column;
        padding-right: 80px;
        padding-left: 142px;
        padding-bottom: $hero-body-bottom-padding;
      }

      &__aside {
        background-repeat: no-repeat;
        background-size: cover;

        .hero-aside {
          display: flex;
          flex-direction: column;
          height: 100%;
          padding-top: 0;
          padding-right: 0;
          padding-left: 15%;
          padding-bottom: $hero-body-bottom-padding;
        }
      }
    }

    .title {
      margin-top: 40px;
      font-size: 65px;
      font-weight: 300;
      color: #061C2E;
    }
    .subtitle {
      font-size: 25px;
      color: #939393;
    }

    &--footer {
      margin-top: auto;
      padding-top: 30px;

      .link {
        font-size: 15px;
        font-weight: bold;
        text-decoration: underline;
        text-transform: uppercase;
        color: #4F4F4F;
        &:not(:first-child) {
          margin-left: 56px;
        }
      }
    }
  }

  .hero-aside {
    position: relative;

    .sunrise-preview {

      position: absolute;
      top: 0;
      left: 15%;
      right: -2000px;
      bottom: 210px;

      &--window {
        cursor: pointer;
        height: 100%;
        width: 100%;
        position: absolute;
        left: 0;
        top: 0;
        transition: ease .5s;

        &__hidden {
          left: 100%;
        }
      }

      &--image {
        position: absolute;
        top: 50px;
        right: 0;
        bottom: 0;
        left: 0;

        img {
          height: 100%;
          border-bottom-left-radius: 20px;
          border-bottom-right-radius: 20px;
        }
      }


      &--controls {
        width: 100%;
        height: 50px;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
        background: #F2F2F2;
        display: flex;
        padding: 24px 20px 16px 20px;
      }
      &--control {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 8px;

        &-red {
          background-color: #E94949;
        }
        &-yellow {
          background-color: #FFC550;
        }
        &-dark {
          background-color: #5C5442;
        }
      }
      &--title {
        margin: 56px 0 15px 0;
        max-width: 536px;
        font-size: 24px;
        color: white;

        display: none;
        opacity: 0;

        &__showen {
          display: block;
          opacity: 1;
          animation: fadeInOpacity ease .7s;
        }
      }
      &--bottom {
        margin-top: auto;
      }
      &--bottom-balls {
        display: flex;
        height: 24px;
      }
      &--bottom-ball {
        width: 17px;
        height: 17px;
        border: 1px solid white;
        border-radius: 50%;
        margin-right: 8px;
        cursor: pointer;
        transition: background ease .5s;
        &__selected {
          background: white;
        }
      }
    }
  }

  .preview-image {
    margin: auto;
    width: 75%;
  }

  .modal.modal-terms {
    .modal-content {
      .content {
        max-height: 50vh;
        padding-right: 44px;
        overflow-y: scroll;
        /* width */
        &::-webkit-scrollbar {
            width: 6px;
        }

        /* Track */
        &::-webkit-scrollbar-track {
            background: #ECECEC;
            border-radius: 90px;
        }

        /* Handle */
        &::-webkit-scrollbar-thumb {
            background: #CE818C;
            border-radius: 90px;
        }

        /* Handle on hover */
        &::-webkit-scrollbar-thumb:hover {
            background: #edaab4;
            border-radius: 90px;
        }
      }
    }
  }

  @include until($desktop) {
    background-image: none !important;

    .hero-body {
      padding: 8% 10% 3%;
      text-align: center;

      > .container {
        &__main {
          padding: 0;
        }
        &__aside {
          display: none;
        }
      }

      .title {
        font-size: 50px;
      }
      .subtitle {
        font-size: 22px;
      }

    }
  }

  @include until($tablet) {
    .hero-body {
      .title {
        font-size: 32px;
      }
      .subtitle {
        font-size: 20px;
      }
      &--footer {
        display: flex;
        flex-direction: column;
        padding-top: 0;

        .link {
          margin: 10px 0 0;
          &:not(:first-child) {
            margin-left: 0;
          }
        }
      }
    }
  }

  @keyframes fadeInOpacity {
  	0% {
  		opacity: 0;
  	}
  	100% {
  		opacity: 1;
  	}
  }
}
</style>
