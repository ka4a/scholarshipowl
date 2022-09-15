<template>
  <div
    :class="{scrolled: isScroll}"
    class="toolbar"
  >

    <div
      :class="{open: isMobileMenu}"
      class="mobile-menu"
    >
      <div class="background" @click="isMobileMenu = !isMobileMenu">
        <div class="menu" @click.stop="">
          <div class="top">
            <button
              class="burger-button"
              @click="toggleMobileMenu"
            >
              <img src="~/assets/img/burger_icon.svg" alt="burger">
            </button>

            <nuxt-link to="/">
              <img class="logo" src="~/assets/img/logo.svg" alt="logo">
            </nuxt-link>
          </div>

          <div class="content">
            <div class="nav-menu">
              <nuxt-link
                :class="{active: scrollPos >= sectionsOffset.how_it_works && scrollPos < sectionsOffset.features}"
                to="/#video"
                class="menu-item"
              >
                How it works
              </nuxt-link>

              <nuxt-link
                :class="{active: scrollPos >= sectionsOffset.features && scrollPos < sectionsOffset.case_studies}"
                to="/#benefits"
                class="menu-item"
              >
                Features
              </nuxt-link>

              <nuxt-link
                :class="{active: scrollPos >= sectionsOffset.case_studies && scrollPos < sectionsOffset.case_end}"
                to="/#partnerships"
                class="menu-item"
              >
                Case studies
              </nuxt-link>
            </div>

            <sun-button
              :icon="true"
              :small="true"
              variant="red"
              class="left-button"
              text="REGISTER FOR FREE"
              @click="goToRegistrationPage"
            />

            <a :href="loginURL" class="login-link">
              <img src="~/assets/img/login_lock_icon.svg" alt="lock">
              Login
            </a>
          </div>

          <div class="bottom-text">
            <div>
              © sunrise
            </div>

            <div>
              <a
                class="terms"
                href="javascript:;"
                @click="$emit('openTerms')"
              >
                TERMS &amp; CoNDITIONS
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container is-fluid">
      <div class="mobile">
        <div class="top">
          <div>
            <button
              class="burger-button"
              @click="toggleMobileMenu"
            >
              <img src="~/assets/img/burger_icon.svg" alt="burger">
            </button>

            <nuxt-link to="/">
              <img class="logo" src="~/assets/img/logo.svg" alt="logo">
            </nuxt-link>
          </div>

          <sun-button
            :icon="true"
            :small="true"
            class="get-started-btn"
            variant="white-grim"
            text="GET STARTED NOW"
            @click="goToRegistrationPage"
          />
        </div>
      </div>

      <div
        :class="{white: isScroll}"
        class="desktop"
      >
        <div>
          <nuxt-link to="/">
            <img class="desktop" src="~/assets/img/logo.svg" alt="logo">
            <img class="widescreen" src="~/assets/img/logo.svg" alt="logo">
            <img class="hd" src="~/assets/img/logo.svg" alt="logo">
            <img class="fullhd" src="~/assets/img/logo.svg" alt="logo">
          </nuxt-link>
        </div>

        <div class="menu">
          <nuxt-link to="/#video" class="menu-item">
            How it works
          </nuxt-link>

          <nuxt-link to="/#benefits" class="menu-item">
            Features
          </nuxt-link>

          <nuxt-link to="/#partnerships" class="menu-item">
            Case studies
          </nuxt-link>
        </div>

        <a :href="loginURL" class="login-link">
          <img src="~/assets/img/login_lock_icon.svg" alt="lock">
          Login
        </a>

        <sun-button
          :icon="true"
          :small="true"
          class="get-started-btn"
          variant="white-grim"
          text="GET STARTED NOW"
          @click="goToRegistrationPage"
        />
      </div>
    </div>
  </div>
</template>

<script>
import SunButton from './partial/SunButton';

export default {
  name: 'SunToolbar',

  components: {
    SunButton,
  },

  data() {
    return {
      scrollPos: 0,
      isScroll: false,
      isMobileMenu: false,
      sectionsOffset: {
        how_it_works: 0,
        features: 0,
        case_studies: 0,
        case_end: 0,
      },
    };
  },

  mounted() {
    this.updateScroll();
    this.geteElementsPosition();
    window.addEventListener('scroll', this.updateScroll);
  },

  destroyed() {
    window.removeEventListener('scroll', this.updateScroll);
  },

  methods: {
    updateScroll() {
      this.scrollPos = window.scrollY;
      this.isScroll = window.scrollY > 560;
    },

    geteElementsPosition() {
      const offset = window.innerHeight * 0.6;
      this.sectionsOffset.how_it_works = document.getElementById('video')
        .getBoundingClientRect().top + window.scrollY - offset;
      this.sectionsOffset.features = document.getElementById('benefits')
        .getBoundingClientRect().top + window.scrollY - offset;
      this.sectionsOffset.case_studies = document.getElementById('partnerships')
        .getBoundingClientRect().top + window.scrollY - offset;
      this.sectionsOffset.case_end = document.getElementById('partnerships')
        .getBoundingClientRect().bottom + window.scrollY;
    },

    toggleMobileMenu() {
      this.isMobileMenu = !this.isMobileMenu;
    },

    goToRegistrationPage() {
      window.location.href = this.registrationURL;
    },
  },
};
</script>

<style lang="scss" scoped>
@import '~assets/css/_variables.scss';
@import '~assets/css/_mixins.scss';

  .toolbar {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    z-index: 10;

    &.scrolled {
      background-color: $white;
      box-shadow: 0px 3px 15px rgba(0, 0, 0, 0.08);
      position: fixed;
    }

    .mobile {
      min-height: 65px;
      padding-top: 17px;
      padding-bottom: 17px;
      display: flex;
      align-items: center;

      @include sun-tablet {
        padding-top: 23px;
        padding-bottom: 23px;
      }

      @include sun-desktop {
        display: none;
      }

      .top {
        @include sun-tablet {
          display: flex;
          justify-content: space-between;
          align-items: center;
          width: 100%;
        }

        .logo {
          @include sun-tablet {
            transform: translateY(2px);
          }
        }
      }
    }

    .mobile-menu {
      width: 0;
      position: fixed;
      z-index: 15;
      top: 0;
      left: 0;
      bottom: 0;
      transition: width 0.35s ease;
      overflow: hidden;

      .background {
        width: 100%;
        min-height: 100vh;
        position: relative;
        background-color: rgba(115, 115, 115, 0.2);
      }

      &.open {
        width: 100%;
        overflow: auto;
      }

      .menu {
        width: 269px;
        height: 100%;
        padding-left: 17px;
        padding-right: 17px;
        background-color: $white;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        z-index: 20;

        @include sun-tablet {
          width: 385px;
          padding-left: 29px;
          padding-right: 29px;
        }

        .top {
          min-height: 65px;
          margin-top: 17px;

          @include sun-tablet {
            margin-top: 23px;
            display: flex;
            align-items: center;
          }
        }

        .content {
          display: flex;
          align-items: center;
          justify-content: center;
          flex-direction: column;

          .nav-menu {
            .menu-item {
              display: block;
              position: relative;
              font-family: 'Catamaran-Light';
              line-height: 89px;
              font-size: 25px;
              color: $dark-blue;
              cursor: pointer;

              @include sun-tablet {
                font-size: 32px;
                text-align: center;
              }

              &.active::before {
                content: '';
                position: absolute;
                height: 22px;
                width: 22px;
                background-image: url('~assets/img/logo_icon.svg');
                background-repeat: no-repeat;
                background-size: cover;
                left: -33px;
                top: 48%;
                transform: translateY(-50%);
              }
            }
          }

          .login-link {
            margin-top: 15px;
            color: $black;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
            font-family: 'Catamaran-Light';

            img {
              width: 18px;
              height: 26px;
              margin-right: 11px;
              transform: translateY(-3px);
            }
          }
        }

        .bottom-text {
          margin-bottom: 20px;
          text-align: center;
          font-size: 24px;
          font-family: 'Catamaran-Bold';
          color: $gray-link;

          .terms {
            color: $gray-link;
            font-size: 15px;
            text-decoration-line: underline;
            text-transform: uppercase;
          }
        }
      }
    }

    .burger-button {
      background-color: transparent;
      border: none;
    }

    .logo {
      margin-left: 16px;

      @include sun-tablet {
        margin-left: 56px;
      }
    }

    .desktop {
      display: none;

      @include sun-desktop {
        display: flex;
      }
    }

    .get-started-btn {
      display: none;

      @include sun-tablet {
        display: block;
      }
    }

    .desktop {
      align-items: center;
      padding-top: 25px;
      padding-bottom: 25px;
      max-height: 100px;

      .login-link {
        margin-left: auto;
        margin-right: 20px;
        color: $white;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 17px;
        font-family: 'Catamaran-Medium';

        @include sun-widescreen {
          font-size: 20px;
          margin-right: 31px;
        }

        @include sun-hd {
          font-size: 32px;
          margin-right: 50px;
        }

        @include sun-fullhd {
          font-size: 35px;
          margin-right: 60px;
        }

        img {
          filter: invert(1);
          width: 18px;
          height: 26px;
          margin-right: 9px;
          transform: translateY(-4px);

          @include sun-hd {
            width: 32px;
            height: 43px;
          }

          @include sun-fullhd {
            margin-right: 15px;
          }
        }
      }

      @include sun-widescreen {
        max-height: unset;
      }

      @include sun-hd {
        padding-top: 30px;
        padding-bottom: 30px;
      }

      &.white {
        padding-top: 10px;
        padding-bottom: 10px;

        .login-link {
          color: $black;

          img {
            filter: none;
          }
        }

        @include sun-widescreen {
          padding-top: 20px;
          padding-bottom: 20px;
        }
      }

      img {
        &.widescreen,
        &.hd, &.fullhd {
          display: none;
        }

        @include sun-widescreen {
          &.widescreen {
            display: block;
          }

          &.desktop {
            display: none;
          }
        }

        @include sun-hd {
          &.hd {
            display: block;
          }

          &.widescreen {
            display: none;
          }
        }

        @include sun-fullhd {
          &.fullhd {
            display: block;
          }

          &.hd {
            display: none;
          }
        }
      }

      .menu {
        display: flex;
        margin-left: 95px;

        @include sun-widescreen {
          margin-left: 133px;
        }

        @include sun-fullhd {
           margin-left: 163px;
        }

        .menu-item {
          min-width: 107px;
          color: $dark-blue;
          font-size: 17px;
          font-family: 'Catamaran-Medium';

          &:first-child {
            margin-right: 24px;
          }

          @include sun-widescreen {
            font-size: 20px;

            &:first-child {
              margin-right: 31px;
            }
          }

          @include sun-hd {
            font-size: 32px;
            line-height: 89px;
            margin-right: 51px;

            &:first-child {
              margin-right: 51px;
            }
          }
        }
      }
    }
  }
</style>
