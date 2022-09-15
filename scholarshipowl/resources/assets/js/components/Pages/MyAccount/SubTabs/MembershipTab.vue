<template>
  <div v-if="membership" class="membership-sub-tab">
      <h3 class="ma-title title-bottom-indent">{{ title }}</h3>
      <subscription-remainder class="membership-sub-tab__remainder"
        :membership="membership" v-if="isFreeTrial" :inline="true" />
      <p v-if="isCanceled" class="ma-text text-top-indent title-bottom-indent">You will not be charged any future subscription payments and your membership access will expire on {{ membership.activeUntil || membership.endDate}}.</p>

      <template v-if="instance && instance.bar" v-for="bar in instance.bar">
        <information-block class="info-blk-top-indent" :title="bar.title" :text="bar.text" :img="bar.img" />
      </template>

      <Button v-if="instance && instance.texts" class="upgrade-btn-top-indent" style="margin-left: auto; width: 156px"
      :theme="this.instance.button === 'upgrade' ? 'orange' : 'grey'" @click.native="upgrade" size="s" :label="instance.button"
      :href="isMobile() && instance.button === 'upgrade' ? '/upgrade-mobile' : ''" />

      <p v-if="instance && instance.texts" v-for="text in instance.texts" v-html="text" class="ma-mem-text text-top-indent"></p>
  </div>
</template>

<script>
  import InformationBlock from "components/Pages/MyAccount/InformationBlock.vue";
  import SubscriptionRemainder from "components/Pages/MyAccount/SubscriptionRemainder.vue";
  import Button from "components/Common/Buttons/ButtonCustom.vue";
  import { capitalize, isMobile } from "lib/utils/utils";

  const contactInformationText = `If you have any questions regarding your account,
    please call us toll free at <a href="tel:contact@scholarshipowl.com">1-800-494-4908`;
  const workTimeInformationText = `We are available <b>Monday through Friday, 11 a.m. to 6 p.m.
    EST and 8 a.m. to 3 p.m. PST.</b>`;
  const cancelationInformationText = `If you would like to cancel your membership, please call us
    for immediate assistance or click to cancel your subscription.`;
  const assistanceInformationText = `If you need assistance learning how to use ScholarshipOwl, or have other questions regarding your account, please call us toll free at <a href="tel:contact@scholarshipowl.com">1-800-494-4908</a>.`
  const queriesInformationText = `For any queries about your membership or ScholarshipOwl, please write an email to <a href="mailto:contact@scholarshipowl.com">contact@scholarshipowl.com</a>`;
  const emailInformationText = `You can also email us at <a href="mailto:contact@scholarshipowl.com">contact@scholarshipowl.com`;

  const unlimitedBar = {
    title: "Allowed scholarship applications",
    img: require("components/Pages/MyAccount/img/allowed.png"),
    text: "Unlimited"
  };

  const renewalPeriod = (expirationPeriodType, expirationValue) => {
    if(typeof expirationValue !== 'number' || expirationValue <= 0)
      throw Error('Please provide correct expiration value');

    return 'billed every ' + (expirationValue === 1
      ? expirationPeriodType
      : `${expirationValue} ${expirationPeriodType}s`)
  }

  const dateBar = (type, date) => {
    if(!type || typeof type !== 'string')
      throw Error('Please provide correct type');

    return {
      title: `${capitalize(type)} date`,
      img: require(`components/Pages/MyAccount/img/${type}-date.png`),
      text: date
    }
  }

  const billingBar = (amount, period) => {
    return {
      title: "Billing",
      text: `${amount} ${period}`,
      img: require("components/Pages/MyAccount/img/billing.png"),
    }
  }

  const renewalBar = date => {
    return  {
      title: "Next renewal date",
      text: date,
      img: require("components/Pages/MyAccount/img/renewal-date.png"),
    }
  }

  export default {
    components: {
      InformationBlock,
      SubscriptionRemainder,
      Button
    },
    props: {
      membership: {type: Object, required: true}
    },
    data() {
      return {
        isMobile
      }
    },
    computed: {
      isFree() {
        return this.membership && !this.membership.isMember;
      },
      isFreemium() {
        return this.membership && this.membership.isMember && this.membership.isFreemium;
      },
      isRecurrent() {
        return this.membership && this.membership.isMember && !!this.membership.renewalDate;
      },
      isCanceledRecurrent() {
        return this.membership && this.membership.isMember && !!this.membership.renewalDate &&
            !!this.membership.activeUntil;
      },
      isRegular() {
        return this.membership && this.membership.isMember && !!this.membership.endDate;
      },
      isCanceledRegular() {
        return this.membership && this.membership.isMember && !!this.membership.endDate
            && !!this.membership.activeUntil;
      },
      isCanceled() {
        return (!this.membership || !!this.membership.activeUntil || !this.membership.isMember)
            && this.membership.subscriptionId;
      },
      isFreeTrial() {
        return this.membership && this.membership.isMember
          && this.membership.freeTrial && !!this.membership.freeTrialEndDate
      },
      instance() {
        if(this.isFree) {
          return {
            bar: [{
              title: "None",
              img: require("components/Pages/MyAccount/img/no-membership.png"),
              text: ""
            }],
            button: "upgrade",
            texts: [
              `Access ScholarshipOwl scholarship application tool to get unlimited application submissions and automatic application to recurring scholarships by Upgrading to a premium membership.`,
              `For additional information email us at: <a href="mailto:contact@scholarshipowl.com">contact@scholarshipowl.com</a>`
            ]
          }
        }

        if(this.isFreemium) {
          let startDate = this.membership.startDate,
              credits = this.membership.freemiumCredits;

          return {
            bar: [
              dateBar("start", startDate),
              dateBar("end", 'Open-ended'),
              {
                title: "Allowed scholarship applications",
                text: this.membership.packageAlias === "freemium-mvp"
                  ? `${credits}` : `${credits} per day`,
                img: require("components/Pages/MyAccount/img/allowed.png")
              }],
            button: "upgrade",
            texts: [
              contactInformationText,
              workTimeInformationText
            ]
          }
        }

        if(this.isCanceledRecurrent) {
          let startDate = this.membership.startDate,
              activeUntilDate = this.membership.activeUntil || this.membership.renewalDate;

          return {
            bar: [
              dateBar("start", startDate),
              dateBar("end", activeUntilDate),
              unlimitedBar
            ],
            button: "upgrade",
            texts: [
                queriesInformationText
            ]
          }
        }

        if(this.isRecurrent) {
          let packagePrice = this.membership.packagePrice,
              period = renewalPeriod(
                this.membership.expirationPeriodType,
                this.membership.expirationValue
              ),
              renewalDate = this.membership.renewalDate;

          return {
            bar: [
              billingBar(`$${packagePrice}`, period),
              renewalBar(renewalDate),
              unlimitedBar
            ],
            button: "cancel",
            texts: [
              assistanceInformationText,
              cancelationInformationText,
              workTimeInformationText,
              queriesInformationText
            ]
          }
        }

        if(this.isCanceledRegular) {
          let startDate = this.membership.startDate,
              endDate = this.membership.activeUntil || this.membership.endDate;

          return {
            bar: [
              dateBar("start", startDate),
              dateBar("end", endDate),
              unlimitedBar
            ],
            button: "cancel",
            texts: [
              contactInformationText,
              workTimeInformationText,
              emailInformationText
            ]
          }
        }

        if(this.isRegular) {
          let startDate = this.membership.startDate,
              endDate = this.membership.endDate;

          return {
            bar: [
              dateBar("start", startDate),
              dateBar("end", endDate),
              unlimitedBar
            ],
            button: "cancel",
            texts: [
              contactInformationText,
              workTimeInformationText,
              emailInformationText
            ]
          }
        }

        if(this.isCanceled) {
          let startDate = this.membership.startDate,
              endDate = this.membership.activeUntil || this.membership.endDate;

          return {
            bar: [
              dateBar("start", startDate),
              dateBar("end", endDate),
              unlimitedBar
            ],
            batton: "upgrade",
            texts: [
              contactInformationText,
              emailInformationText
            ]
          }
        }

        return null;
      },
      title() {
        return this.isCanceled
          ? "Your membership is now canceled"
          : `${this.membership.name} membership`
      }
    },
    methods: {
      upgrade(ev) {
        if(!this.instance || !this.instance.button) return;

        if(this.instance.button === 'cancel') {
          ev.preventDefault();
        }

        if(this.instance.button === 'upgrade') {
          this.$emit('upgrade', ev)
          return;
        }

        if(this.isFreeTrial) {
          this.$emit('modal', true);
          return;
        }

        this.$emit('modal', false);
      }
    }
  }
</script>

<style lang="scss">
  $black: #2f2f2f;
  $blue: #708fe7;

  $open-sans: 'Open Sans';
  .membership-sub-tab {
    width: 100%;

    @include breakpoint($l) {
      max-width: 646px;
    }

    &__remainder {
      text-align: left;
      margin-bottom: 12px;

      @include breakpoint($s) {
        margin-bottom: 15px;
      }

      @include breakpoint($m) {
        margin-bottom: 20px;
      }
    }
  }

  .title-bottom-indent {
    margin-bottom: 12px;

    @include breakpoint($s) {
      margin-bottom: 15px;
    }

    @include breakpoint($s) {
      margin-bottom: 20px;
    }
  }

  .info-blk-top-indent {
    & + & {
      margin-top: 12px;
    }
  }

  .upgrade-btn-top-indent {
    margin-top: 20px;

    @include breakpoint($s) {
      margin-top: 23px;
    }
  }

  .text-top-indent {
    margin-top: 20px;
  }

  .ma-mem-text {
    font-family: $open-sans;
    font-size: 16px;
    color: $black;
    line-height: 1.4em;

    @include breakpoint($m) {
      font-size: 18px;
    }

    a {
      color: $havelock-blue;
    }

    b {
      font-weight: 600;
    }
  }
</style>