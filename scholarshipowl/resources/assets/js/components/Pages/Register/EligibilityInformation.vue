<template>
  <div class="eligib-info">
    <div class="eligib-info__item">
      <h5 class="eligib-info__amount amount-title-reg"><i-count-up :end-val="count" /></h5>
      <p class="eligib-info__amount-sign sub-title-reg">scholarships</p>
    </div>
    <div class="eligib-info__item">
      <h5 class="eligib-info__amount amount-title-reg">$<i-count-up :end-val="amount" /></h5>
      <p class="eligib-info__amount-sign sub-title-reg">total value</p>
    </div>
  </div>
</template>

<script>
  import { AccountResource } from "resource";
  import ICountUp from 'vue-countup-v2';

  const COUNT = 236;
  const AMOUNT = 272151

  export default {
    components: {
      ICountUp
    },
    mounted() {
      //TODO change it to call server action
      const eligibilityInitialUserData = () => {
        return new Promise((resolve, reject) => {
          setTimeout(() => {
            let data = window.localStorage.getItem('home-page');

            try {
              data = JSON.parse(data);
            } catch(err) {
              reject(Error(err));
            }

            resolve(data);
          }, 1500)
        })
      }

      eligibilityInitialUserData()
        .then(data => AccountResource.eligibilityInitial(data))
        .then(response => {
          if(response.body && response.body.status === 200) {
            this.count = response.body.data.count;
            this.amount = response.body.data.amount;

            return;
          }

          this.count = COUNT;
          this.amount = AMOUNT;
        })
        .catch(response => {
          setTimeout(() => {
            this.count = COUNT;
            this.amount = AMOUNT;
          }, 1000);
        })
    },
    data() {
      return {
        counterOptions: {
          duration: 0.5,
        },
        count: 0,
        amount: 0
      }
    }
  }
</script>

<style lang="scss">

  .eligib-info {
    @include breakpoint(410px $s) {
      display: flex;
    }

    &__item {
      display: flex;
      align-items: flex-end;

      & + & {
        margin-top: 5px;

        @include breakpoint($m) {
          margin-top: 8px;
        }

        @include breakpoint($l) {
          margin-top: 13px;
        }

        @include breakpoint(410px $s) {
          margin-top: 0;
          margin-left: 12px;
        }
      }
    }

    &__amount-sign {
      margin-left: 13px;

      @include breakpoint($m) {
        margin-left: 18px;
      }
    }
  }
</style>