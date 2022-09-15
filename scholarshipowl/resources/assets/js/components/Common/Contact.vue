<template>
  <section class="contact-us-v">
    <Content v-bind="ctnt" class="contact-us-v__text" />
    <Form v-if="profile && account" class="contact-us-v__form"
      :profile="profile" :account="account" :page-name="page" />
  </section>
</template>

<script>
  import { mapGetters } from "vuex";
  import Form from "components/Common/Contact/Form.vue";
  import Content from "components/Common/Contact/Content.vue";

  const title = "Need Help?";
  const text = "We're happy to share more details! Learn everything about ScholarshipOwl, ask us anything, or <a href='/faq' target='_blank'>visit our FAQ.</a>";

  export default {
    components: {
      Form,
      Content
    },
    props: {
      page: {type: String, required: true}
    },
    data() {
      return {
        ctnt: {
          title,
          text,
        },
      }
    },
    computed: {
      ...mapGetters("account", ["profile", "account"])
    }
  }
</script>

<style lang="scss">
  .contact-us-v {
    padding-top: 20px;
    padding-bottom: 20px;

    @include breakpoint($m) {
      padding-top: 30px;
      padding-bottom: 45px;
    }

    @include breakpoint($l) {
      display: flex;
      padding-top: 40px;
      padding-bottom: 55px;
    }

    @include breakpoint($l) {
      padding-top: 65px;
      padding-bottom: 70px;
    }

    &__text {
      @include breakpoint($l) {
        max-width: 400px;
      }
    }

    &__form {
      @include breakpoint(max-width $l - 1px) {
        margin-top: 20px;
      }

      @include breakpoint($l) {
        flex: 1 1 auto;
      }
    }
  }
</style>