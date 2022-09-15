<template>
  <div>
    <Header
      :class="['layout-reg-wrp__header', routeName.substr(1)]"
      :imageSrc="imgSrc"
      :title="content.title"
      :sub-title="content.subTitle">
      <eligibility-information v-if="routeName === steps[0]"
        class="layout-reg-wrp__eligib-info" />
    </Header>
    <section class="form-bg">
      <component :is="component"
        :contentSet="content"
        :isSubmitting="isSubmitting"
        :serverFieldErrors="serverFieldErrors"
        @loaded="$emit('loaded')"
        @submit="submit" />
    </section>
  </div>
</template>

<script>
  import { ROUTES } from "router.js";
  import { mapGetters } from "vuex";
  import { fetchWithDalay } from "lib/utils/utils";
  import { FSetResource } from "resource";
  import { loadImage } from "lib/utils/utils";
  import { firePixel } from "lib/utils/tracking";

  import Header from "components/Pages/Register/HeaderRegister.vue";
  import Register1Form from "components/Pages/Register/Register1Form.vue";
  import Register2Form from "components/Pages/Register/Register2Form.vue";
  import Register3Form from "components/Pages/Register/Register3Form.vue";
  import EligibilityInformation from "components/Pages/Register/EligibilityInformation.vue";

  const imgSrcs = [
    require("components/Pages/Register/img/reg1-head-illustration.jpg"),
    require("components/Pages/Register/img/reg2-head-illustration.jpg"),
    require("components/Pages/Register/img/reg3-head-illustration.jpg")
  ]

  const steps = [
    ROUTES.REGISTER,
    ROUTES.REGISTER_2,
    ROUTES.REGISTER_3,
    ROUTES.SELECT
  ];

  const navigateTo = path => window.location = path;

  const nextRouteName = (currentStepIndex, steps) => {
    if(typeof currentStepIndex !== 'number' || !steps)
      throw Error("Please provide correct parameters");

    return steps[currentStepIndex + 1];
  }

  const reportException = (error) => {
    console.error(error);

    if(error instanceof Error) {
      error = JSON.stringify(error);
    }

    if(window.Raven) window.Raven.captureException(error);
  }

  export default {
    components: {
      Header,
      EligibilityInformation,
      Register1Form,
      Register2Form,
      Register3Form
    },
    created() {
      FSetResource.fset({fields: ["contentSet"]}).then(response => {
        if(response.body && response.body.status === 200) {
          this.contentSet = response.body.data.contentSet;

          this.imgSrc = this.getImgSrc(this.step);
        }
      })
    },
    data () {
      return {
        steps,
        isSubmitting: false,
        serverFieldErrors: null,
        scholarships: {
          count: 54,
          amount: 35877
        },
        marketing: null,
        contentSet: {},
        imgSrc: null
      };
    },
    computed: {
      routeName() {
        return this.$route.path;
      },
      step() {
        return steps.indexOf(this.routeName);
      },
      component() {
        return `Register${this.step + 1}Form`;
      },
      count() {
        return this.scholarships.count;
      },
      contentSets() {
        return [
          {
            title: this.contentSet.registerHeadingText || "Congratulations!",
            subTitle: this.contentSet.registerSubheadingText || "You are eligible to apply for up to",
            textButton: this.contentSet.registerCtaText || "register for free",
            img: this.contentSet.registerIllustration
          },
          {
            title: this.contentSet.register2HeadingText || "More is more -",
            subTitle: this.contentSet.register2SubheadingText || "provide more information,</br>get more scholarship matches!",
            textButton: this.contentSet.register2CtaText || "continue",
            img: this.contentSet.register2Illustration
          },
          {
            title: this.contentSet.register3HeadingText,
            subTitle: this.contentSet.register3SubheadingText,
            textButton: this.contentSet.register3CtaText,
            img: this.contentSet.register3Illustration
          }
        ]
      },
      content() {
        return this.contentSets[this.step]
      }
    },
    methods: {
      getImgSrc(i) {
        return this.contentSets[i].img || imgSrcs[i] || null;
      },
      submit(data) {
        if(this.isSubmitting) return;

        this.isSubmitting = true;

        const actionByPathName = (pathName, data) => {
          const aliases = {
            "register" : "registration",
            "register2" : "updateProfile",
            "register3" : "updateProfile"
          }

          return this.$store.dispatch(`account/${aliases[pathName]}`, data)
        }

        const navigate = (routeName, cb) => {
          const routeTo = routeName => (this.$router.push(routeName));

          const locateTo = routeName => (window.location = routeName);

          const getImage = () => loadImage(this.getImgSrc(this.step + 1))
                                  .then(({src}) => (this.imgSrc = src))
                                  .catch(reportException);

          const firePixels = () => {
            return new Promise((resolve, reject) => {
              this.$store.dispatch('account/fetchData', ['marketing'])
                .then(response => {
                  const marketing = response.body.data.marketing;

                  if(!marketing || !marketing.transactionId
                    || (marketing.offerId !== 30 && marketing.offerId !== 32)) {
                    resolve();
                    return;
                  }

                  firePixel({...marketing, goalName: "ACCOUNT"}, resolve);
                })
                .catch(cons => reject(cons))
            })
          }

          const routes = {
            [ROUTES.REGISTER] :   {handler: routeTo, before: getImage},
            [ROUTES.REGISTER_2] : {handler: routeTo, before: getImage},
            [ROUTES.REGISTER_3] : {handler: routeTo, before: getImage},
            [ROUTES.SELECT] :     {handler: locateTo, before: firePixels}
          }

          const scenario = routes[routeName];

          scenario.before()
            .then(() => {
              scenario.handler(routeName);
              cb(true)
            })
            .catch(cons => {
              reportException(cons);
              scenario.handler(routeName);
              cb(false);
            })
        }

        const responseSuccessHandler = response => {
          const nextStep = nextRouteName(this.step, steps);

          navigate(nextStep, isSuccess => (this.isSubmitting = false));
        }

        const responseFailHandler = response => {
          try {
            if(isNoInternet(response, 10000)) {
              this.isSubmitting = true;

              setTimeout(() => {
                actionByPathName(this.routeName.substring(1), data)
                  .then(responseSuccessHandler)
                  .catch(responseFailHandler);
              }, 250)

              return;
            }

            if(response.body.status === 400 && response.body.error) {
              this.serverFieldErrors = response.body.error;
            } else {
              const nextStep = nextRouteName(this.step, steps);

              reportException(`Unexpected response on register [ ${this.step + 1} ] :` + JSON.stringify(response));

              navigate(nextStep, isSuccess => (this.isSubmitting = false));
            }
          } catch(err) {
            reportException(err);
            alert("Cannot connect to the server. Please check your internet connection and try again");
          }

          this.isSubmitting = false;
        }

        function isNoInternet(response, duration) {
          let isNo = response.status === 0,
            attempts = 1;
          const moment = Date.now();

          isNoInternet = response => {
            attempts += 1;
            const elapsedTime = Date.now() - moment;

            if(response.status === 0 && elapsedTime >= duration)
              throw Error(`User don't have internet or our server can't be reached.
                Maximum timout ${elapsedTime / 1000} seconds is reached. ${attempts} attemps`);

            return response.status === 0;
          }

          return isNo;
        }

        actionByPathName(this.routeName.substring(1), data)
          .then(responseSuccessHandler)
          .catch(responseFailHandler)
      }
    }
  }
</script>

<style lang="scss">
  $blue-light: #f2f9ff;

  .form-wrp {
    padding-top: 25px;
    padding-bottom: 25px;

    @include breakpoint($s) {
      padding-top: 30px;
      padding-bottom: 30px;
    }
  }

  .form-bg {
    background-color: $blue-light;
  }

  .reg-eligib-info {
    margin-top: 14px;

    @include breakpoint($s $m - 1px) {
      margin-top: auto;
    }

    @include breakpoint($l) {
      margin-top: 32px;
    }
  }

  .err-mess {
    font-size: 12px;
    color: $carnation;
  }
</style>