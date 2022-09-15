<template>
  <section id="benefits" class="benefits-section">
    <div class="container is-fluid">
      <h2 class="medium-title has-text-centered">Service Features and Benefits</h2>

      <div class="columns">
        <div class="column is-half-suntablet">
          <img
            class="only-mobile"
            src="~/assets/img/service_applications_mobile.svg"
            alt="Service applications"
          >

          <img
            class="bg-image only-tablet"
            src="~/assets/img/service_applications_tablet.svg"
            alt="Service applications"
          >

          <img
            class="bg-image only-desktop"
            src="~/assets/img/service_applications_desktop.svg"
            alt="Service applications"
          >

          <img
            class="bg-image only-widescreen"
            src="~/assets/img/service_applications_widescreen.svg"
            alt="Service applications"
          >

          <img
            class="bg-image only-hd"
            src="~/assets/img/service_applications_hd.svg"
            alt="Service applications"
          >

          <img
            class="bg-image only-fullhd"
            src="~/assets/img/service_applications_fullhd.svg"
            alt="Service applications"
          >
        </div>

        <div
          ref="first_benefit"
          :class="{
            'animated slideInLeft': scrollPosition >= elementsPositions.first,
            'transparent': scrollPosition < elementsPositions.first,
          }"
          class="column is-half-suntablet"
        >
          <div>
            <h4 class="feature-title">
              <b>Manage your applications</b><br>
              Enables all applications to be housed and organized in one place
            </h4>

            <div class="feature-description">
              Sunrise will provide you with an <span class="nowrap">e-storage</span> space so
              you can track and review your scholarship applications in an organized fashion.
            </div>
          </div>
        </div>
      </div>

      <div class="columns">
        <div class="column is-half-suntablet">
          <img
            class="only-mobile"
            src="~/assets/img/service_winner_mobile.svg"
            alt="Service winner"
          >

          <img
            class="bg-image only-tablet"
            src="~/assets/img/service_winner_tablet.svg"
            alt="Service winner"
          >

          <img
            class="bg-image only-desktop"
            src="~/assets/img/service_winner_desktop.svg"
            alt="Service winner"
          >

          <img
            class="bg-image only-widescreen"
            src="~/assets/img/service_winner_widescreen.svg"
            alt="Service winner"
          >

          <img
            class="bg-image only-hd"
            src="~/assets/img/service_winner_hd.svg"
            alt="Service winner"
          >

          <img
            class="bg-image only-fullhd"
            src="~/assets/img/service_winner_fullhd.svg"
            alt="Service winner"
          >
        </div>

        <div
          ref="second_benefit"
          :class="{
            'animated slideInRight': scrollPosition >= elementsPositions.second,
            'transparent': scrollPosition < elementsPositions.second,
          }"
          class="column is-half-suntablet">
          <h4 class="feature-title">
            <b>Automatic winner selection</b><br>
            Streamlines the application review and selection process
          </h4>

          <div class="feature-description">
            Don’t want to go through all the applications and choose a winner?
            Sunrise will randomly select one for you!
          </div>
        </div>
      </div>

      <div class="columns">
        <div class="column is-half-suntablet">
          <img
            class="only-mobile"
            src="~/assets/img/service_details_mobile.svg"
            alt="Service details"
          >

          <img
            class="bg-image only-tablet"
            src="~/assets/img/service_details_tablet.svg"
            alt="Service details"
          >

          <img
            class="bg-image only-desktop"
            src="~/assets/img/service_details_desktop.svg"
            alt="Service details"
          >

          <img
            class="bg-image only-widescreen"
            src="~/assets/img/service_details_widescreen.svg"
            alt="Service details"
          >

          <img
            class="bg-image only-hd"
            src="~/assets/img/service_details_hd.svg"
            alt="Service details"
          >

          <img
            class="bg-image only-fullhd"
            src="~/assets/img/service_details_fullhd.svg"
            alt="Service details"
          >
        </div>

        <div
          ref="third_benefit"
          :class="{
            'animated slideInLeft': scrollPosition >= elementsPositions.third,
            'transparent': scrollPosition < elementsPositions.third,
          }"
          class="column is-half-suntablet details"
        >
          <div>
            <h4 class="feature-title">
              <b>Winner payout details</b><br>
              Improves the communication between scholarship provider and applicant
            </h4>

            <div class="feature-description">
              No need to play broken telephone — Sunrise will collect all winner payout details
              for you so you don’t have to worry about it.
            </div>

            <sub-button
              :icon="true"
              class="get-started-btn"
              variant="red"
              text="get started now"
              @click="goToRegistrationPage"
            />
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import SubButton from './partial/SunButton';

export default {
  name: 'SunBenefits',

  components: {
    SubButton,
  },

  data() {
    return {
      scrollPosition: 0,
      elementsPositions: {
        first: 0,
        second: 0,
        third: 0,
      },
    };
  },

  mounted() {
    this.updateScroll();
    this.geteElementsPosition();
    window.addEventListener('scroll', this.updateScroll);
    window.addEventListener('resize', this.geteElementsPosition);
  },

  destroyed() {
    window.removeEventListener('scroll', this.updateScroll);
    window.removeEventListener('resize', this.geteElementsPosition);
  },

  methods: {
    updateScroll() {
      this.scrollPosition = window.scrollY;
    },

    geteElementsPosition() {
      const offset = window.innerHeight * 0.7;
      this.elementsPositions.first = this.$refs.first_benefit.getBoundingClientRect().top + window.scrollY - offset;
      this.elementsPositions.second = this.$refs.second_benefit.getBoundingClientRect().top + window.scrollY - offset;
      this.elementsPositions.third = this.$refs.third_benefit.getBoundingClientRect().top + window.scrollY - offset;
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

.benefits-section {
  margin-top: 50px;

  @include sun-desktop {
    margin-top: 150px;
  }

  @include sun-widescreen {
    margin-top: 140px;
  }

  @include sun-hd {
    margin-top: 380px;
  }

  img {
    @include sun-tablet {
      max-width: unset;
    }
  }

  h2 {
    margin-bottom: 18px;

    @include sun-widescreen {
      margin-bottom: 80px;
    }
  }

  .columns {
    @include sun-tablet {
      display: flex;
      flex-wrap: wrap;
    }

    &:nth-child(even) {
      @include sun-tablet {
        flex-direction: row-reverse;
      }
    }

    &:not(:nth-of-type(1)) {
      @include sun-tablet {
        margin-top: -100px;
      }

      @include sun-desktop {
        margin-top: 0;
      }
    }

    .column {
      position: relative;
      box-sizing: border-box;

      @include sun-tablet {
        min-height: 520px;
      }

      @include sun-desktop {
        min-height: 540px;
      }

      @include sun-widescreen {
        min-height: 760px;
      }

      @include sun-hd {
        min-height: 820px;
      }

      .bg-image {
        position: absolute;
        right: calc(208% - 100vw);

        @include sun-tablet {
          right: calc(205% - 100vw);
        }

        @media (min-width: 970px) {
          right: calc(239% - 100vw);
        }

        @include sun-desktop {
          right: calc(206% - 100vw);
        }

        @media (min-width: 1600px) {
          right: calc(202% - 100vw);
        }

        @include sun-widescreen {
          right: calc(207% - 100vw);
        }

        @media (min-width: 2000px) {
          right: calc(216% - 100vw);
        }

        @include sun-hd {
          right: calc(211% - 100vw);
        }

        @include sun-fullhd {
          right: 0;
        }
      }

      &:first-child {
        padding-bottom: 0;

        @include sun-tablet {
          padding-bottom: 0.75rem;
        }
      }

      &:last-child {
        padding-top: 0;

        .feature-title {
          font-family: 'Catamaran';
          margin-bottom: 10px;

          @include sun-tablet {
            margin-bottom: 20px;
          }

          @include sun-widescreen {
            margin-bottom: 40px;
          }
        }

        .feature-title,
        .feature-description {
           @include sun-desktop {
            width: 600px;
          }

          @include sun-widescreen {
            width: 673px;
          }

          @include sun-fullhd {
            width: 690px;
          }
        }

        &.details {
          .feature-title,
          .feature-description {
            @include sun-fullhd {
              width: 720px;
            }
          }
        }

        @include sun-tablet {
          padding-top: 0.75rem;
          display: flex;
          justify-content: center;
          flex-direction: column;
        }

        @include sun-hd {
          align-items: center;
        }
      }
    }

    &:nth-last-of-type(even) {
      .column {
        .bg-image {
          left: calc(208% - 100vw);

          @include sun-tablet {
            left: calc(205% - 100vw);
          }

          @media (min-width: 970px) {
            left: calc(240% - 100vw);
          }

          @include sun-desktop {
            left: calc(206% - 100vw);
          }

           @media (min-width: 1600px) {
            left: calc(202% - 100vw);
          }

          @include sun-widescreen {
            left: calc(200% - 100vw);
          }

          @media (min-width: 2050px) {
            left: calc(208% - 100vw);
          }

          @media (min-width: 2000px) {
            left: calc(215% - 100vw);
          }

          @include sun-hd {
            left: calc(211% - 100vw);
          }

          @include sun-fullhd {
            left: 0;
          }
        }
      }
    }

    &:nth-of-type(odd) {
      .column {
        @include sun-desktop {
          padding-right: 5%;
        }
      }
    }
  }

  .is-half-suntablet {
    @include sun-tablet {
      width: 50%;
      flex: auto;
    }
  }

  .get-started-btn {
    margin-top: 20px;
    display: block;
    margin-left: auto;
    margin-right: auto;

    @include sun-tablet {
      margin-left: 0;
      margin-right: 0;
    }

    @include sun-widescreen {
      margin-top: 40px;
    }
  }
}

.transparent {
  opacity: 0;
}
</style>
