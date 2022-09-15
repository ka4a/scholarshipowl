<template>
  <div class="auth-wrapper">
    <div class="container auth-container is-fluid is-flex">
      <div class="auth-content">
        <nuxt/>
      </div>

      <div class="auth-footer">
        <div class="link-wrapper">
          <nuxt-link class="link" to="/about">ABOUT FEATURES</nuxt-link>
        </div>

        <div class="link-wrapper">
          <a
            class="link"
            href="javascript:;"
            @click="isTermsOpen = true"
          >
            TERMS &amp; CONDITIONS
          </a>
        </div>
      </div>
    </div>

    <div class="slider">
      slider
    </div>

    <terms-popup
      :visible="isTermsOpen"
      @close="isTermsOpen = false"
    />
  </div>
</template>

<script>
import { TermsPopup } from '~/components';

export default {
  name: 'Authorization',

  components: {
    TermsPopup,
  },

  data() {
    return {
      isTermsOpen: false,
    };
  },
};
</script>

<style lang="scss" scoped>
@import '~assets/css/_variables.scss';
@import '~assets/css/_mixins.scss';

.auth-wrapper {
  position: relative;
  min-height: 100vh;
  display: flex;

  & > div {
    flex-grow: 1;
    flex-shrink: 1;
  }

  .auth-container {
    flex-direction: column;
    min-height: 100vh;

    @include sun-desktop {
      padding-right: 80px;
      padding-left: 142px;
      width: 50%;
    }

    .auth-content {
      @include sun-tablet {
        width: 522px;
        max-width: 100%;
        margin-left: auto;
        margin-right: auto;
      }

      @include sun-widescreen {
        width: 622px;
      }

      @include sun-hd {
        width: 70%;
      }
    }
  }

  .slider {
    padding-left: 15%;
    background-image: url('~assets/img/auth_bg.svg');
    background-repeat: no-repeat;
    background-size: cover;
    display: none;

    @include sun-desktop {
      display: block;
      width: 50%;
    }
  }

  .auth-footer {
    display: flex;
    flex-direction: column;
    margin-top: auto;
    justify-content: center;
    padding: 10px;

    .link-wrapper {
      margin-bottom: 11px;
      text-align: center;

      @include sun-tablet {
        &:first-child {
          margin-right: 56px;
        }
      }

      .link {
        color: $gray;
        font-size: 15px;
        font-family: 'Catamaran-Bold';
        text-decoration: underline;

        @include sun-widescreen {
          font-size: 18px;
        }

        @include sun-hd {
          font-size: 21px;
        }
      }
    }

    @include sun-tablet {
      flex-direction: row;
    }
  }
}
</style>
