<template>
  <section id="partnerships" class="partnerships-section">
    <div class="container is-fluid">
      <h2 class="medium-title has-text-centered">Recent Partnerships</h2>

      <div class="carousel-holder">
        <carousel
          :per-page="1"
          :navigation-enabled="true"
          :navigation-next-label="
            `<img src='${require('~/assets/img/arrow_right_round.svg')}' alt='Next'>`
          "
          :navigation-prev-label="
            `<img src='${require('~/assets/img/arrow_right_round.svg')}' alt='Prev'>`
          "
        >
          <slide>
            <div class="logo has-text-centered">
              <img src="~/assets/img/positive_rewards_logo.svg" alt="Positive rewards">
            </div>

            <div class="slide has-text-centered">
              <p class="carousel-text-top">
                Uses Sunrise to collect applications for scholarships
                that support students enrolled in US Universities.
              </p>

              <p class="carousel-text-bottom">
                “The team at Sunrise has made the launch of our new project easy, simple and seamless.
                They have exceeded our expectations and provided superb customer service. ”
              </p>

              <div class="user">
                <img class="image" src="~/assets/img/Helen.png" alt="User">

                <div class="texts">
                  <div class="name">
                    Vanessa
                  </div>

                  <div class="description">
                    Marketing Department<br> at Positive Rewards LLC
                  </div>
                </div>
              </div>
            </div>
          </slide>
        </carousel>
      </div>
    </div>
  </section>
</template>

<script>
import { Carousel, Slide } from 'vue-carousel';

export default {
  name: 'SunPartnerships',

  components: {
    Carousel,
    Slide,
  },
};
</script>

<style lang="scss" scoped>
@import '../assets/css/_variables.scss';
@import '../assets/css/_mixins.scss';

  .partnerships-section {
    margin-top: 55px;

    @include sun-tablet {
      margin-top: 45px;
    }

    @include sun-desktop {
      margin-top: 150px;
    }

    & > .medium-title {
      margin-bottom: 18px;

      @include sun-tablet {
        margin-bottom: 35px;
      }
    }

    .carousel-holder {
      font-family: 'Catamaran-Light';
      color: $black;

      @include sun-tablet {
        margin-top: 20px;
      }

      .logo {
        margin-top: 20px;
        margin-bottom: 15px;

        @include sun-desktop {
          margin-bottom: 20px;
        }
      }

      .carousel-text-top {
        line-height: 24px;
        font-size: 18px;
        margin-left: auto;
        margin-right: auto;

        @include sun-tablet {
          line-height: 24px;
          font-size: 18px;
          width: 688px;
        }

        @include sun-desktop {
          width: 854px;
          line-height: 51px;
          margin-bottom: 40px;
        }

        @include sun-widescreen {
          width: 100%;
          font-size: 22px;
        }

        @include sun-hd {
          font-size: 28px;
        }

        @include sun-fullhd {
          font-size: 32px;
        }
      }

      .carousel-text-bottom {
        position: relative;
        line-height: 29px;
        font-size: 20px;
        margin-top: 20px;
        margin-left: auto;
        margin-right: auto;

        &::before {
          content: '';
          position: absolute;
          bottom: -40px;
          right: 0;
          width: 120px;
          height: 80px;
          z-index: -1;
          background-image: url('~assets/img/quotes_icon.svg');
        }

        &::after {
          content: '';
          position: absolute;
          top: -40px;
          left: 0;
          width: 120px;
          height: 80px;
          transform: rotate(180deg);
          z-index: -1;
          background-image: url('~assets/img/quotes_icon.svg');
        }

        br {
          display: none;
        }

        @include sun-tablet {
          width: 572px;
          line-height: 42px;
          font-size: 28px;
        }

        @include sun-desktop {
          width: 920px;

          br {
            display: block;
          }
        }

        @include sun-widescreen {
          width: 1232px;
          line-height: 44px;
          font-size: 32px;
          margin-top: 20px;
        }

        @include sun-hd {
          width: 1764px;
          line-height: 67px;
          font-size: 38px;
          min-height: 200px;
        }

        @include sun-fullhd {
          line-height: 74px;
          font-size: 42px;
        }
      }

      .user {
        display: flex;
        margin-top: 15px;
        margin-left: auto;
        margin-right: auto;
        max-width: 325px;

        @include sun-tablet {
          margin-top: 32px;
        }

        @include sun-tablet {
          margin-top: 32px;
        }

        @include sun-desktop {
          margin-top: 40px;
        }

        @include sun-widescreen {
          margin-top: 70px;
          max-width: 350px;
        }

        @include sun-hd {
          margin-top: 100px;
          max-width: 450px;
        }

        @include sun-hd {
          margin-top: 100px;
          max-width: 450px;
        }

        @include sun-fullhd {
          max-width: 500px;
        }

        & > div {
          flex: auto;
        }

        .image {
          width: 66px;
          height: 66px;

          @include sun-tablet {
            width: 91px;
            height: 91px;
          }

          @include sun-hd {
            width: 130px;
            height: 130px;
          }

          @include sun-fullhd {
            width: 144px;
            height: 144px;
          }
        }

        .texts {
          text-align: left;
          padding-left: 10px;

          @include sun-tablet {
            padding-left: 15px;
          }

          .name {
            line-height: 31px;
            font-size: 18px;
            font-family: 'Catamaran-Medium';

            @include sun-tablet {
              line-height: 42px;
              font-size: 22px;
            }

            @include sun-desktop {
              font-size: 18px;
            }

            @include sun-widescreen {
              line-height: 42px;
              font-size: 22px;
            }

            @include sun-hd {
              font-size: 28px;
            }

            @include sun-fullhd {
              font-size: 32px;
            }
          }

          .description {
            font-family: 'Catamaran-Light';
            line-height: 20px;
            font-size: 15px;

            @include sun-tablet {
              line-height: 23px;
              font-size: 17px;
            }

            @include sun-desktop {
              line-height: 21px;
              font-size: 15px;
            }

            @include sun-widescreen {
              line-height: 24px;
              font-size: 17px;
            }

            @include sun-hd {
              line-height: 33px;
              font-size: 24px;
            }

            @include sun-fullhd {
              line-height: 37px;
              font-size: 26px;
            }
          }
        }
      }
    }
  }
</style>
