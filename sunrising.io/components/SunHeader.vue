<template>
  <header
    id="header"
    class="header"
  >
    <div class="container is-fluid">
      <div class="columns is-mobile">
        <div
          class="
            column is-three-fifths-mobile full-tablet is-two-thirds-widescreen
            is-half-sunwidescreen is-two-fifths-sunhd
          "
        >
          <h1 class="leading-title">Boost the Results of Your<br> Scholarship Outreach<br> Campaign</h1>
        </div>
      </div>

      <div class="description">
        <h2 class="description-title">
          <b>Sunrise</b> is a system designed to help organizations and
          businesses run, manage and promote their scholarships
        </h2>
      </div>

      <div class="buttons">
        <div>
          <a :href="demoURL" target="_blank">
            <sun-button
              :icon="true"
              variant="red"
              class="left-button"
              text="Schedule a demo"
            />
          </a>
        </div>

        <div>
          <sun-button
            text="How It Works"
            @click="$router.push('/#video')"
          />
        </div>
      </div>
    </div>

    <img
      class="header-image only-mobile"
      src="~/assets/img/bg_top_mobile.png"
      alt="Top Background"
    >

    <img
      class="header-image only-tablet"
      src="~/assets/img/bg_top_tablet.png"
      alt="Top Background"
    >

    <img
      class="header-image only-desktop"
      src="~/assets/img/bg_top_desktop.png"
      alt="Top Background"
    >

    <img
      class="header-image only-widescreen"
      src="~/assets/img/bg_top_widescreen.png"
      alt="Top Background"
    >

    <img
      class="header-image only-hd"
      src="~/assets/img/bg_top_hd.png"
      alt="Top Background"
    >

    <img
      class="header-image only-fullhd"
      src="~/assets/img/bg_top_fullhd.png"
      alt="Top Background"
    >
  </header>
</template>

<script>
import SunButton from './partial/SunButton';

export default {
  name: 'SunHeader',

  components: {
    SunButton,
  },

  methods: {
    goToRegistrationPage() {
      window.location.href = this.registrationURL;
    },
  },
};
</script>

<style lang="scss" scoped>
@import '~assets/css/_mixins.scss';

.is-two-fifths-sunwidescreen {
  @include sun-widescreen {
    flex: none;
    width: 40%;
  }
}

.header {
  position: relative;
  z-index: 1;

  @include sun-tablet {
    min-height: 930px;
  }

  @include sun-desktop {
    min-height: 890px;
  }

  @include sun-widescreen {
    min-height: 1070px;
  }

  @include sun-hd {
    min-height: 1440px;
  }

  @include sun-fullhd {
    min-height: 1620px;
  }

  .description {
    @include sun-mobile {
      max-width: 280px;
    }
    @include only-sun-tablet {
      max-width: 400px;
    }

    @include sun-desktop {
      width: 45%;
    }

    @include sun-widescreen {
      width: 40%;
    }
  }

  .header-image {
    position: absolute;
    z-index: -1;
    top: 0;
    right: 0;
  }

  .leading-title {
    margin-top: 78px;

    @include sun-tablet {
      margin-top: 182px;
    }

    @include sun-desktop {
      margin-top: 203px;
      line-height: 89px;
    }

    @include sun-widescreen {
      margin-top: 259px;
    }

    @include sun-hd {
      margin-top: 368px;
    }

    br {
      display: none;

      @include sun-tablet {
        display: block;
      }
    }
  }

  .description-title {
    margin-top: 0;

    @include sun-tablet {
      margin-top: 23px;
    }

    @include sun-widescreen {
      max-width: 90%;
    }
  }

  .buttons {
    margin-top: 15px;

    @include sun-tablet {
      margin-top: 35px;
      max-width: 55%;
    }

    & > div {
      width: 100%;
      text-align: center;

      @include sun-desktop {
        width: unset;
        text-align: left;
      }
    }

    .left-button {
      margin-bottom: 27px;

      @include sun-desktop {
        margin-right: 26px;
        margin-bottom: 0;
      }
    }
  }

  .is-half-sunwidescreen {
    @include sun-widescreen {
      width: 50%;
    }
  }

  .is-two-fifths-sunhd {
    @include sun-hd {
      max-width: 40%;
    }
  }
}
</style>
