<template>
  <section class="plans">
    <header class="plans__header base-hor-indent">
      <Content @to-table="toTableHandler" class="plans__content"
        :isMobile="isMobile" v-bind="ctnt">
        <Carousel v-if="carouselScholarships && isMobile"
          class="plans__carousel"
          :is-mobile="xs || s || m"
          :scholarships="carouselScholarships" />
        <Winners class="plans__winners" />
      </Content>
        <Carousel v-if="carouselScholarships && !isMobile"
          class="plans__carousel"
          :is-mobile="xs || s || m"
          :scholarships="carouselScholarships" />
    </header>
    <div class="base-hor-indent">
      <Table @package="selectPackage"
        ref="plansTable"
        title="Activate Membership to Apply"
        :selectedPackage="selectedPackage"
        :isPaymentInitialized="brainTreePMIsInitialized" />
      <p class="plans__cancel-text">Cancel anytime without penalty or hassle</p>
    </div>
    <div style="background-color: white">
      <FAQ :is-mobile="isMobile"/>
      <Press class="base-hor-indent" />
    </div>
    <footer class="plans__contact-us">
      <ContactUs page="plans-page" class="base-hor-indent" />
    </footer>
    <PaymentModal
      @ready-to-close="showPaymentModal = false"
      @closed="afterPaymentClose"
      @opened="afterPaymentOpen"
      :show="showPaymentModal"
      :package="selectedPackage" />
  </section>
</template>

<script>
  import { mapGetters } from "vuex";
  import { SCHOLARSHIPS } from "store/scholarships";
  import mixpanel from "lib/mixpanel";
  import {PACKAGE_BTN_CLK, PAYMENT_MODAL_OPEN, PAYMENT_MODAL_OPEN_MOB,
    PAYMENT_MODAL_CLOSE, PAYMENT_MODAL_CLOSE_MOB} from "lib/mixpanel";
  import { isMobile } from "lib/utils/utils";

  import Content from "components/Pages/Plans/Header/Content.vue";
  import Carousel from "components/Pages/Plans/Carousel.vue";
  import Table from "components/Pages/Plans/Table.vue";
  import ContactUs from "components/Common/Contact.vue";
  import PaymentModal from "components/Common/Modals/Payment.vue";
  import Winners from "components/Pages/Plans/Header/Winners.vue"
  import FAQ from "components/Pages/Plans/FAQ.vue";
  import Press from "components/Pages/Plans/Press.vue";

  const MIN_SCSP_COUNT = 8;

  const titleAmountList = scholarships =>
    scholarships.map(scsp => ({title: scsp.title, amount: scsp.amount}))

  export default {
    components: {
      Content,
      Carousel,
      Table,
      Press,
      ContactUs,
      PaymentModal,
      Winners,
      FAQ
    },
    created() {
      Promise.all([
        this.$store.dispatch("fset/getFsetData"),
        this.$store.dispatch("scholarships/fetchScholarships"),
        this.$store.dispatch("paymentSet/getData", "plans-page"),
        this.$store.dispatch("account/fetchData", ["account"]),
        this.$store.dispatch("account/fetchData", ["profile"]),
      ]).then(() => {this.$emit("loaded")})
    },
    data() {
      return {
        showPaymentModal: false,
        selectedPackage: null,
      }
    },
    watch: {
      brainTreePMIsInitialized(state) {
        if(state && this.selectedPackage) {
          this.showPaymentModal = true;
        }
      }
    },
    computed: {
      ...mapGetters({ctnt: "fset/plansPageContent"}),
      ...mapGetters("payment", ["brainTreePMIsInitialized"]),
      ...mapGetters("screen", ["xs", "s", "m", "l"]),
      carouselScholarships() {
        let scholarships = this.$store.state.scholarships[SCHOLARSHIPS];

        // let scholarships = [
        //   {title: "1. Snubnose eel bandfish kelp perch grunion, ghost", amount: "2000"},
        //   {title: "2. Snubnose eel bandfish kelp perch grunion, ghost", amount: "2000"},
        //   {title: "3. Snubnose eel bandfish kelp perch grunion, ghost", amount: "2000"},
        //   {title: "4. Snubnose eel bandfish kelp perch grunion, ghost", amount: "2000"},
        //   {title: "5. Snubnose eel bandfish kelp perch grunion, ghost", amount: "2000"},
        //   {title: "6. Snubnose eel bandfish kelp perch grunion, ghost", amount: "2000"},
        //   {title: "7. Snubnose eel bandfish kelp perch grunion, ghost", amount: "2000"},
        //   {title: "8. Snubnose eel bandfish kelp perch grunion, ghost", amount: "2000"},
        //   {title: "9. Snubnose eel bandfish kelp perch grunion, ghost", amount: "2000"},
        //   {title: "10. Snubnose eel bandfish kelp perch grunion, ghost",amount:  "2000"},
        //   {title: "11. Snubnose eel bandfish kelp perch grunion, ghost",amount:  "2000"},
        //   {title: "12. Snubnose eel bandfish kelp perch grunion, ghost",amount:  "2000"},
        // ]

        if(!scholarships || !scholarships.length || !this.ctnt) return null;

        const count = this.ctnt.carouselItemCnt;

        if(count > 0 && count < MIN_SCSP_COUNT)
          throw Error("It's not possible data value. Value should be 0 or more/equal 8");

        if(count === 0) return titleAmountList(scholarships)

        if(scholarships.length < count) {
          let newSet = scholarships.slice(),
              i = 0;

          while(newSet.length < count) {
            if(i === scholarships.length) i = 0;
            newSet.push(scholarships[i]);
            i += 1;
          }

          return newSet;
        }

        return titleAmountList(scholarships.slice(0, count));
      },
      isMobile() {
        return this.xs || this.s || this.m || this.l;
      }
    },
    methods: {
      selectPackage({p, el}) {
        mixpanel.track(PACKAGE_BTN_CLK);

        this.selectedPackage = p;

        if(this.brainTreePMIsInitialized) {
          this.showPaymentModal = true;
        }
      },
      afterPaymentOpen() {
        mixpanel.track(isMobile()
          ? PAYMENT_MODAL_OPEN_MOB
          : PAYMENT_MODAL_OPEN);
      },
      afterPaymentClose(data) {
        this.selectedPackage = null;

        const isMob = isMobile();

        mixpanel.track(isMob
          ? PAYMENT_MODAL_CLOSE_MOB
          : PAYMENT_MODAL_CLOSE);

        if(!data) return;

        this.$store.dispatch('modal/showModal', {
          modalName: 'success-basic',
          content: {
            html: data.message
          },
          tracking: {
            hasOffersTransactionId: data.hasOffersTransactionId,
            isFreeTrial: data.isFreeTrial,
            isFreemium: data.isFreemium,
          },
          hooks: {after: function() {
            window.location = data.redirect || "/scholarships";
          }}
        })
      }
    }
  }
</script>

<style lang="scss">
  .plans {
    background: no-repeat $foam;
    background-position: left 160px, top right;
    background-image: url(./Plans/bg-figures.svg), url(./Plans/bg-s-header.svg);
    padding-top: 1px;
    position: relative;

    @include breakpoint(max-width $m - 1px) {
      background-size: contain, contain;
    }

    @include breakpoint($m $l - 1px) {
      background-size: 89% auto, contain;
      background-position: left 85px, top right;
    }

    @include breakpoint($l $xl - 1) {
      background-size: 60% auto, auto;
      background-position: 98% 23px, top right;
    }

    @include breakpoint($xl) {
      background-size: 60% auto, auto;
      background-position: 98% 23px, right -58px;
    }

    @include breakpoint(395px) {
      background-image: url(./Plans/bg-figures.svg), url(./Plans/bg-m-header.svg);
    }

    @include breakpoint(865px) {
      background-image: url(./Plans/bg-figures.svg), url(./Plans/bg-l-header.svg);
    }

    @include breakpoint($xl) {
      background-image: url(./Plans/bg-figures.svg), url(./Plans/bg-xl-header.svg);
    }

    &-btn {
      border-radius: 2px;
      padding-right: 5px;
      padding-left: 5px;
    }

    &__bg {
      position: absolute;
      width: 404%;
      left: -151%;
    }

    &__header {
      position: relative;
      z-index: 1;
      margin-top: 25px;

      @include breakpoint($m) {
        margin-top: 50px;
      }

      @include breakpoint($m $l - 1px) {
        max-width: 590px;
        margin-left: auto;
        margin-right: auto;
      }

      @include breakpoint($l) {
        display: flex;
        justify-content: space-between;
        margin-top: 100px;
      }

      @include breakpoint($xl) {
        margin-top: 98px;
        justify-content: space-between;
      }
    }

    &__carousel {
      margin-top: 10px;

      @include breakpoint($m $l - 1px) {
        margin-top: 30px;
      }

      @include breakpoint(max-width $l - 1px) {
        margin-left: auto;
        margin-right: auto;
      }
    }

    &__winners {
      margin-top: 15px;

      @include breakpoint($m) {
        margin-top: 10px;
      }

      @include breakpoint($l) {
        margin-top: 30px;
      }
    }

    &__cancel-text {
      font-size: 12px;
      line-height: 1.8em;
      text-align: center;
      margin-bottom: 20px;

      @include breakpoint($s) {
        font-size: 14px;
      }

      @include breakpoint($l) {
        text-align: right;
        margin-bottom: 49px;
        margin-right: 7px;
      }
    }

    &__content {
      @include breakpoint(max-width $m - 1px) {
        max-width: 280px;
        margin-left: auto;
        margin-right: auto;
      }

      @include breakpoint($l) {
        max-width: 480px;
      }
    }

    &__contact-us {
      background-color: $alice-blue;
    }
  }
</style>

