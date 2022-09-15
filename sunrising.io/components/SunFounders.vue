<template>
  <section
    id="founders"
    class="founders-section"
  >
    <div class="container is-fluid">
      <h2 class="has-text-centered medium-title">
        Meet the founders
      </h2>

      <div
        v-for="founder in founders"
        :key="founder.name"
        class="columns"
      >
        <div class="column avatar-holder">
          <img
            :src="require(`~/assets/img/${founder.avatar}_mobile.png`)"
            class="avatar only-mobile"
            alt="Avatar"
          >

          <img
            :src="require(`~/assets/img/${founder.avatar}_tablet.png`)"
            class="avatar from-tablet"
            alt="Avatar"
          >

          <img
            :src="require(`~/assets/img/${founder.avatar}_tablet.png`)"
            class="avatar only-hd"
            alt="Avatar"
          >

          <img
            :src="require(`~/assets/img/${founder.avatar}_tablet.png`)"
            class="avatar only-fullhd"
            alt="Avatar"
          >
        </div>

        <div class="column texts">
          <div class="name feature-title">
            {{ founder.name }}
          </div>

          <div class="position">
            {{ founder.position }}
          </div>

          <div class="touch-desc description">
            {{ founder[founder.isOpen ? 'longText' : 'shortText'] }}

            <div>
              <button
                class="show-details__button"
                @click="founder.isOpen = !founder.isOpen"
              >
                <span>{{ founder.isOpen ? 'Hide' : 'Show' }} details</span>

                <img
                  :class="{reverse: founder.isOpen}"
                  src="~/assets/img/arrow_down_icon.svg"
                  alt="arrow"
                >
              </button>
            </div>
          </div>

          <div class="show-desktop description">
            {{ founder.longText }}
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import texts from '~/assets/founders_texts.json';

export default {
  name: 'SunFounders',

  data() {
    return {
      founders: [
        {
          name: 'Kenny Sandorffy',
          position: 'Co-Founder & CMO',
          shortText: texts.Kenny_short,
          longText: texts.Kenny_long,
          avatar: 'kenny',
          isOpen: false,
        },
        {
          name: 'David Tabachnikov',
          position: 'Co-Founder & CTO',
          shortText: texts.David_short,
          longText: texts.David_long,
          avatar: 'david',
          isOpen: false,
        },
      ],
    };
  },
};
</script>

<style lang="scss" scoped>
@import '~assets/css/_variables.scss';
@import '~assets/css/_mixins.scss';

.founders-section {
  margin-top: 54px;
  margin-bottom: 40px;
  color: $black;

  @include sun-tablet {
    margin-top: 95px;
  }

  @include sun-desktop {
    margin-top: 136px;
  }

  @include sun-widescreen {
    margin-top: 197px;
  }

  @include sun-hd {
    margin-top: 207px;
  }

  @include sun-fullhd {
    margin-top: 218px;
  }

  .columns {
    margin-top: 37px;

    @include sun-tablet {
      margin-top: 60px;
    }

    @include sun-widescreen {
      margin-top: 96px;
    }

    @include sun-hd {
      margin-top: 116px;
    }

    @include sun-fullhd {
      margin-top: 136px;
    }
  }

  .avatar-holder {
    text-align: center;

    @include sun-desktop {
      margin-top: 0;
      width: 265px;
      flex: none;
    }

    @include sun-widescreen {
      width: calc(285px + 1.5rem);
    }

    @include sun-fullhd {
      width: calc(335px + 1.5rem);
    }

    .avatar {
      width: 155px;
      margin: auto;

      @include sun-tablet {
        width: 239px;
      }

      @include sun-desktop {
        width: 285px;
      }

      @include sun-fullhd {
        width: 335px;
      }
    }
  }

  .texts {
    text-align: center;

    @include sun-desktop {
      text-align: left;
      padding-left: calc(24px + 0.75rem);
    }

    @include sun-widescreen {
      padding-left: calc(55px + 0.75rem);
    }

    @include sun-fullhd {
      padding-left: calc(108px + 0.75rem);
    }

    .name {
      font-family: 'Catamaran-Medium';
      line-height: 42px;

      @include sun-tablet {
        line-height: 42px;
        font-size: 25px;
      }

      @include sun-widescreen {
        font-size: 28px;
        margin-bottom: 10px;
      }

      @include sun-hd {
        font-size: 36px;
        margin-bottom: 15px;
      }

      @include sun-fullhd {
        font-size: 38px;
      }
    }

    .position {
      line-height: 21px;
      font-size: 17px;
      margin-bottom: 10px;

      @include sun-desktop {
        margin-bottom: 20px;
      }

      @include sun-widescreen {
        font-size: 22px;
      }

      @include sun-hd {
        font-size: 26px;
        margin-bottom: 30px;
      }

      @include sun-fullhd {
        font-size: 28px;
      }
    }

    .description {
      line-height: 24px;
      font-size: 18px;
      font-family: 'Catamaran-Light';

      @include sun-tablet {
        line-height: 42px;
        font-size: 28px;
      }

      @include sun-widescreen {
        line-height: 44px;
        font-size: 31px;
      }

      @include sun-hd {
        line-height: 50px;
        font-size: 35px;
      }

      @include sun-fullhd {
        line-height: 50px;
        font-size: 36px;
      }
    }

    .touch-desc {
      @include sun-desktop {
        display: none;
      }

      .show-details__button {
        position: relative;
        cursor: pointer;
        color: $link;
        background-color: transparent;
        border: none;
        font-family: 'Catamaran-Medium';
        line-height: 42px;
        font-size: 18px;
        padding-left: 20px;
        padding-right: 20px;

        @include sun-tablet {
          font-size: 28px;
          margin-top: 20px;
        }

        span {
          text-decoration: underline;
        }

        img {
          position: absolute;
          right: 5px;
          top: 50%;
          transform: translateY(-50%);
          width: 8px;
          height: 12px;

          @include sun-tablet {
            height: 16.78px;
            width: 13px;
          }

          &.reverse {
            transform: translateY(-50%) rotate(180deg);
          }
        }
      }
    }
  }

  .show-desktop {
    display: none;

    @include sun-desktop {
      display: block;
    }
  }

  .from-tablet {
    display: none;

    @include sun-tablet {
      display: block;
    }

    @include sun-hd {
      display: none;
    }
  }
}
</style>
