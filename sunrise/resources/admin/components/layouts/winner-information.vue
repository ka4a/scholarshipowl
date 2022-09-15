<template>
  <div class="winner-page">
    <section class="header hero-body has-text-centered">
      <h1 class="title is-1">{{ scholarshipTitle }}</h1>
    </section>
    <section class="container">
      <router-view ></router-view>
    </section>
    <footer class="footer">
      <div class="content has-text-centered">
        <a class="is-link" @click="openTermsOfUse = true">Terms of Use</a>
        <span>|</span>
        <a class="is-link" @click="openPrivacyPolicy = true">Privacy Policy</a>
      </div>
      <b-modal :active.sync="openTermsOfUse">
        <div class="card content">
          <div class="card-content">
            <div class="content" v-html="termsOfUse" />
          </div>
        </div>
      </b-modal>
      <b-modal :active.sync="openPrivacyPolicy">
        <div class="card content">
          <div class="card-content">
            <div class="content" v-html="privacyPolicy" />
          </div>
        </div>
      </b-modal>
    </footer>
  </div>
</template>
<script>
export default {
  name: 'DefaultLayout',
  data() {
    return {
      openTermsOfUse: false,
      openPrivacyPolicy: false,
    }
  },
  computed: {
    scholarshipTitle() {
      return this.$store.state.winnerInformation.item ?
        this.$store.state.winnerInformation.item.scholarship.title : null;
    },
    termsOfUse({ $store }) {
      const scholarship = $store.state.winnerInformation.item.scholarship;
      return scholarship.content.termsOfUse;
    },
    privacyPolicy({ $store }) {
      const scholarship = $store.state.winnerInformation.item.scholarship;
      return scholarship.content.privacyPolicy;
    }
  }
}
</script>
<style lang="scss">
@import "../../scss/winner-information/index";

.label {
  color: #4F4F4F;
}

.button.is-primary:hover {
  background-color: $blue-darker;
}

.header {
  background-color: $blue;
  padding: 20px;

  .title {
    font-family: 'Courgette', cursive;
    line-height: 62px;
    color: white;
  }

}


.container {
  background: #FFFFFF;
  max-width: 787px;
  padding-bottom: 60px;
  border: #ffffff;
  border-radius: 8px;
}

.footer {
  padding: 0;
  padding: 13px;
  background: $blue;
  color: #ffffff;

  a, a:hover, a:visited, a:active {
    &.is-link {
      font-family: $family-roboto;
      color: white;
    }
  };
}

@media screen and (min-width: $tablet) {
  .container {
    margin-top: -246px;
  }
  .header {
    height: 428px;
    padding-top: 60px;
  }
}
</style>
