<template>
  <div class="card winner-information" id="winner-basic-info">
    <div class="card-header has-text-centered">
      <div v-if="disqualified" class="disqualified">
        <Disqualified class="hero-image"/>
        <p>You have failed to complete the scholarship winner criteria before the deadline and are therefore disqualified.</p>
        <p>The Good News</p>
        <p>You can register again for a chance to win the weekly scholarship!</p>
        <a :href="'http://' + winner.scholarship.website.domain" class="button is-warning">TRY AGAIN</a>
      </div>
      <div v-else-if="filled" class="all-done">
        <AllDone class="hero-image"/>
        <p class="title">Thank you for submitting your form. We will review it and get back to you soon.</p>
        <p class="subtitle">If you have any questions, please contact us at <a :href="`mailto:${contactEmail}`">{{ contactEmail }}</a></p>
        <a class="to-form" @click="editForm">Return to form</a>
      </div>
      <div v-else class="congrats">
        <Congrats class="hero-image"/>
        <p>All fields must be completed within 3 days.</p>
        <p>Failure to do so will result in automatic forfeit and a new winner will be chosen.</p>
      </div>
    </div>
    <div class="card-content">
      <div v-if="!filled && !disqualified" class="content">
        <winner-form :winner="winner"/>
      </div>
    </div>
  </div>
</template>
<script>
import Vue from 'vue';
import store from '../store';

import WinnerForm from './winner-information/form.vue';
import Congrats from './winner-information/congrats.vue';
import AllDone from './winner-information/all-done.vue';
import Disqualified from './winner-information/disqualified.vue';

export default {
  name: 'WinnerInformation',
  components: {
    AllDone,
    Congrats,
    Disqualified,
    WinnerForm,
  },
  computed: {
    winner: ({ $store }) => $store.state.winnerInformation.item,
    filled: ({ $store }) => $store.state.winnerInformation.filled,
    disqualified: ({ $store }) => $store.state.winnerInformation.disqualified,
    contactEmail: ({ winner }) => winner.scholarship.website.meta.contacts.email,
  },
  methods: {
    editForm() {
      this.$store.dispatch('winnerInformation/markFilled', false);
    }
  },
  beforeRouteEnter: function(to, from, next) {
    if (to && to.name === 'winner-information' && to.params.id) {
      store.dispatch('winnerInformation/load', to.params.id)
        .then((item) => { next(); })
    }
  },
}
</script>
<style lang="scss">
@import "../scss/winner-information/variables.scss";
@import url('https://fonts.googleapis.com/css?family=Open+Sans');

.button.is-warning {
  color: $white;
  font-weight: bold;
  font-size: 20px;
  &.is-loading:after {
    border-color: transparent transparent white white !important;
  }
  &:hover {
    background: $yellow-darker;
    color: $white;
  }
}

.winner-information {
  border: #ffffff;
  border-radius: 8px;
  box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);

  font-family: "Open Sans", sans-serif;
  font-size: 16px;

  .hero-image {
    width: 100%;
  }

  > .card-header {
    box-shadow: none;
    border: none;
    > div {
      margin: 40px auto;
      > p {
        font-size: 18px;
      }
    }

    .congrats {
      p:nth-child(2) {
        margin-top: 35px;
      }
    }

    .disqualified {
      > p {
        width: 634px;
        margin: 0 auto;
        &:nth-child(2) {
          margin-top: 20px;
          margin-bottom: 40px;
        }
      }
      .button {
        width: 260px;
        margin-top: 116px;
      }
    }

    .all-done {
      margin: 0 auto;
      p {
        display: inline-block;
        &.title {
          font-size: 20px;
          font-weight: normal;
          margin-top: 40px;
          margin-bottom: 45px;
        }
        &.subtitle {
          font-size: 16px;
          font-weight: normal;
        }
      }
      .to-form {
        font-size: 18px;
        text-decoration-line: underline;
        display: block;
      }
    }
  }

  @media screen and (min-width: $tablet) {
    .all-done p {
      width: 420px;
    }
    .card-content {
      padding: 0 136px 50px;
    }
  }
}
</style>
