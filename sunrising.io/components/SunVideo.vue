<template>
  <section
    id="video"
    class="video-section"
  >
    <div class="container is-fluid">
      <div class="columns">
        <div class="column is-full-tablet is-half-desktop video-column">
          <img
            class="video-bg only-mobile"
            src="~/assets/img/video_bg_mobile_alt.svg"
            alt="Video Background"
          >

          <img
            class="video-bg only-tablet"
            src="~/assets/img/video_bg_tablet_alt.svg"
            alt="Video Background"
          >

          <img
            class="video-bg only-desktop"
            src="~/assets/img/video_bg_desktop_alt.svg"
            alt="Video Background"
          >

          <img
            class="video-bg only-widescreen"
            src="~/assets/img/video_bg_widescreen_alt.png"
            alt="Video Background"
          >

          <img
            class="video-bg only-hd"
            src="~/assets/img/video_bg_hd_alt.png"
            alt="Video Background"
          >

          <img
            class="video-bg only-fullhd"
            src="~/assets/img/video_bg_hd_alt.png"
            alt="Video Background"
          >

          <!-- <img
            class="video-bg only-fullhd"
            src="~/assets/img/video_bg_fullhd_alt.svg"
            alt="Video Background"
          > -->
        </div>

        <div class="column is-half-desktop">
          <h2 class="medium-title">
            See how easy it is<br class="is-hidden-suntablet">
            to launch your own <br class="hide-before-hd"> scholarship
          </h2>

          <div class="scholarship-items">
            <div class="item">
              <h6 class="item-title">
                <img src="~/assets/img/checkmark.svg" alt="Checkmark">
                Create and edit your scholarship
              </h6>

              <div class="muted">
                Design your landing page step-by-step
              </div>
            </div>

            <div class="item">
              <h6 class="item-title">
                <img src="~/assets/img/checkmark.svg" alt="Checkmark">
                Receive applications and collect useful data
              </h6>

              <div class="muted">
                All the application in one spot.
              </div>
            </div>

            <div class="item">
              <h6 class="item-title">
                <img src="~/assets/img/checkmark.svg" alt="Checkmark">
                Select a winner and continue to grow
              </h6>

              <div class="muted">
                Promote your company’s brand awareness.
              </div>
            </div>

            <sun-button
              :icon="true"
              variant="red"
              text="get started now"
              class="sun-button"
              @click="goToRegistrationPage"
            />
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import SunButton from './partial/SunButton';

export default {
  name: 'SunVideo',

  components: {
    SunButton,
  },

  data: () => ({
    isVideoClick: false,
    videoID: 't8tjT9MA7yU',
  }),

  methods: {
    goToRegistrationPage() {
      window.location.href = this.registrationURL;
    },
  },
};
</script>

<style lang="scss" scoped>
@import '../assets/css/_variables.scss';
@import '../assets/css/_mixins.scss';

.video-section {
  margin-top: 30px;
  position: relative;

  @include sun-tablet {
    margin-top: 55px;
  }

  @include sun-desktop {
    margin-top: 90px;
  }

  @include sun-widescreen {
    margin-top: 160px;
  }

  @include sun-hd {
    margin-top: 210px;
  }

  @include sun-fullhd {
    margin-top: 260px;
  }

  .columns {
    & > .column:first-child {
      @include sun-desktop {
        padding-right: 5%;
      }

      @include sun-widescreen {
        padding-right: 3%;
      }
    }

    & > .column:last-child {
      @include sun-desktop {
        padding-left: 5%;
      }

      @include sun-widescreen {
        padding-left: 3%;
      }
    }
  }

  .video-column {
    position: relative;
    box-sizing: border-box;
    min-height: 160px;
    margin-bottom: 65px;

    @include sun-tablet {
      min-height: 360px;
      margin-bottom: 145px;
    }

    @include sun-desktop {
      margin-bottom: 0;
    }

    .video-bg {
      position: absolute;
      left: calc(102% - 100vw);
      top: 50%;
      z-index: -1;
      transform: translateY(-50%);

      @include sun-tablet {
        left: calc(102% - 100vw);
        transform: translateY(-54%);
      }

      @include sun-desktop {
        left: calc(204% - 100vw);
        transform: translateY(-50%);
      }

      @include sun-widescreen {
        left: calc(208% - 100vw);
      }

      @include sun-hd {
        left: calc(211% - 100vw);
        transform: translateY(-45%);
      }

      @include sun-hd {
        left: calc(210% - 100vw);
      }
    }
  }

  .scholarship-items {
    margin-top: 30px;

    @include sun-tablet {
      margin-top: 26px;
    }

    @include sun-widescreen {
      margin-top: 40px;
    }

    @include sun-hd {
      margin-top: 60px;
    }

    @include sun-fullhd {
      margin-top: 80px;
    }

    .item {
      margin-bottom: 30px;

      @include sun-tablet {
        margin-bottom: 26px;
      }

      @include sun-widescreen {
        margin-bottom: 40px;
      }

      @include sun-hd {
        margin-bottom: 60px;
      }

      @include sun-fullhd {
        margin-bottom: 80px;
      }

      .item-title {
        display: flex;
        font-family: 'Catamaran-Light';
        line-height: 26px;
        font-size: 22px;
        margin-bottom: 10px;

        @include sun-desktop {
          align-items: center;
        }

        @include sun-widescreen {
          font-size: 24px;
        }

        @include sun-hd {
          font-size: 32px;
        }

        @include sun-fullhd {
          font-size: 34px;
        }

        img {
          width: 27px;
          height: 27px;
          margin-right: 11px;

          @include sun-tablet {
            margin-right: 15px;
          }

          @include sun-widescreen {
            width: 37px;
            height: 37px;
            margin-right: 20px;
          }

          @include sun-fullhd {
            width: 45px;
            height: 45px;
            margin-right: 24px;
          }
        }
      }

      .muted {
        color: $light-gray;
        font-family: 'Catamaran-Light';
        padding-left: 38px;
        line-height: 26px;
        font-size: 18px;

        @include sun-tablet {
          padding-left: 42px;
          line-height: 26px;
          font-size: 18px;
        }

        @include sun-desktop {
          line-height: 26px;
          font-size: 18px;
        }

        @include sun-widescreen {
          padding-left: 57px;
          line-height: 26px;
          font-size: 21px;
        }

        @include sun-hd {
          line-height: 26px;
          font-size: 28px;
        }

        @include sun-fullhd {
          padding-left: 69px;
          line-height: 26px;
          font-size: 30px;
        }
      }
    }

    .sun-button {
      display: block;
      margin-left: auto;
      margin-right: auto;

      @include sun-tablet {
        margin-left: 0;
        margin-right: 0;
      }
    }
  }

  .is-hidden-suntablet {
    @include sun-tablet {
      display: none;
    }
  }

  .hide-before-hd {
    display: none;

    @include sun-hd {
      display: block;
    }
  }
}
</style>
