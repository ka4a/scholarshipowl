<template>
  <section @mouseenter="enterHandler" @mouseover="overHandler"
    @mouseleave="leaveHandler" class="plans-carousel">
    <div class="plans-carousel__body">
      <Element v-for="(item, i) in carouselElements"
        :key="item.title" :ref="`el${i}`"
        :class="['plans-carousel__element', `el-${i}`]"
        :name="item.title" :amount="formatAmount(item.amount)" />
    </div>
  </section>
</template>

<script>
  import { formatAmount } from "lib/utils/utils";
  import { carousel, drower } from "components/Pages/Plans/Carousel/carousel";
  import Element from "components/Pages/Plans/Carousel/Element.vue";

  const CAROUSEL_AMOUNT_MOBILE = 5;
  const CAROUSEL_AMOUNT_REGULAR = 7;

  export default {
    components: {
      Element,
    },
    props: {
      scholarships: {type: Array, required: true},
      isMobile: {type: Boolean, default: false}
    },
    data() {
      return {
        carouselElements: [],
        sceneDemention: {
          width: 0,
          height: 0
        },
        currentComponentIndex: undefined,
        currentItemIndex: undefined,
        currentReverseState: false,
      }
    },
    created() {
      this.defineCarouselElementsAmount();
    },
    watch: {
      isMobile(newVal, oldVal) {
        if(newVal === oldVal) return;
        this.defineCarouselElementsAmount();
        this.$nextTick(() => {
          console.log(Object.values(this.$refs))
        })
        setTimeout(this.initCarousel, 1000)
      }
    },
    mounted() {
      this.initCarousel();

      this.currentItemIndex = this.elementsAmount - 2;
      this.currentComponentIndex = 0;
    },
    computed: {
      elementsAmount() {
        return this.isMobile
          ? CAROUSEL_AMOUNT_MOBILE
          : CAROUSEL_AMOUNT_REGULAR;
      }
    },
    methods: {
      defineCarouselElementsAmount() {
        const elements = []
        elements.push(this.scholarships[this.elementsAmount - 1]);
        this.carouselElements = elements.concat(this.scholarships.slice(0, this.elementsAmount - 1));
      },
      setNextItem(phase, isReverse) {
        if(isReverse !== this.currentReverseState) {
          if(isReverse) {
            this.currentItemIndex += this.elementsAmount - 2;
          } else {
            this.currentItemIndex -= this.elementsAmount - 2;
          }
        }

        if(isReverse) {
          if(this.scholarships[this.currentItemIndex + 1]) {
            this.currentItemIndex += 1;
          } else {
            this.currentItemIndex = 0;
          }
        } else {
          if(this.scholarships[this.currentItemIndex - 1]) {
            this.currentItemIndex -= 1;
          } else {
            this.currentItemIndex = this.scholarships.length - 1;
          }
        }

        const component = this.$refs[`el${this.currentComponentIndex}`][0];
        const element = this.scholarships[this.currentItemIndex];

        component.name = element.title;
        component.amount = formatAmount(element.amount);

        if(isReverse) {
          if(this.currentComponentIndex + 1 >= this.elementsAmount) {
            this.currentComponentIndex = 0;
          } else {
            this.currentComponentIndex += 1;
          }
        } else {
          if(this.currentComponentIndex - 1 < 0) {
            this.currentComponentIndex = this.elementsAmount - 1;
          } else {
            this.currentComponentIndex -= 1;
          }
        }

        this.currentReverseState = isReverse;
      },
      initCarousel() {
        const components = Object.values(this.$refs).map(arr => arr[0]);

        const sceneRender = (components, time, timeItemDistance, draw, phase, reverse) => {
          components.forEach((component, i) => {
            let totalTime = time + timeItemDistance * i;

            if(totalTime >= 1) {
              totalTime = totalTime - 1;
            }

            draw(component.$el, totalTime, timeItemDistance, phase);
          })
        }

        this.carousel = carousel({
          duration: 10000,
          renderer: sceneRender,
          components,
          drower: drower(),
          phaseChanged: this.setNextItem
        })

        this.carousel.init();
      },
      formatAmount,
      enterHandler(ev) {
        this.defineSceneDemention();
      },
      defineSceneDemention() {
        this.sceneDemention = this.$el.getBoundingClientRect();
      },
      overHandler: (prevState  => (function(ev) {
        const height = this.sceneDemention.height;

        if(!height) return;

        const halfHeight = height / 2;

        const positionFlag = halfHeight >= ev.clientY - this.sceneDemention.top;

        if(positionFlag === prevState) return;

        prevState = positionFlag;
        this.carousel.changeDirection(positionFlag)
      }))(),
      leaveHandler() {
        this.carousel.reset();
      }
    }
  }
</script>

<style lang="scss">
  $elements-amount-mobile: 5;
  $elements-amount-regular: 7;

  $scene-height-s: 148px;
  $scene-height-m: 428px;
  $scene-height-l: 370px;

  $indent-elements-s: 1.6px;
  $indent-elements-m: 6.5px;
  $indent-elements-l: 5.5px;

  @function define-element-height($scene-height, $amount, $indent: 0) {
    $inner-indent: ($indent * $amount - $indent) / $amount;

    @return $scene-height / $amount - $inner-indent;
  }

  .plans-carousel {
    position: relative;
    height: $scene-height-s;
    min-width: 282px;
    width: 282px;

    @include breakpoint($m) {
      height: $scene-height-m;
      min-width: 592px;
      width: 592px;
    }

    @include breakpoint($l) {
      height: $scene-height-l;
      min-width: 510px;
      width: 510px;
    }

    &__body {
      margin-left: auto;
      margin-right: auto;
      height: 100%;
      position: relative;
    }

    &__element {
      margin-left: auto;
      margin-right: auto;
      left: 0; right: 0;
      position: absolute;
      height: define-element-height(
        $scene-height-s,
        $elements-amount-mobile,
        $indent-elements-s
      );

      @include breakpoint($m) {
        height: define-element-height(
          $scene-height-m,
          $elements-amount-regular,
          $indent-elements-m
        );
      }

      @include breakpoint($l) {
        height: define-element-height(
          $scene-height-l,
          $elements-amount-regular,
          $indent-elements-l
        );
      }
    }
  }
</style>