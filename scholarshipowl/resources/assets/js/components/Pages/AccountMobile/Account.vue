<template>
  <section class="my-account-mobile">
    <pre-loader v-if="loading" />
    <template v-else>
      <my-account-sub-tabs :tabs="tabs" :current-tab="tab" @change-tab="tab => tab = tab" />
      <div class="my-account-mobile__wrp">
        <profile-tab class="my-account__tab-wrp" v-if="tab.tab === 'profile'" :profile="profile"
          :sub-tabs="tabs[0].subTabs" :current-tab="tab.subTab"
          @updated="updateModal" />
      </div>
    </template>
    <modal />
  </section>
</template>

<script>
  import { ACCOUNT_UPDATE } from "store/modal";
  import { mapGetters, mapActions } from "vuex";
  import PreLoader from "components/Pages/Own/PreLoader/PreLoader.vue";
  import MyAccountSubTabs from "components/Pages/MyAccount/MyAccountSubTabs.vue";
  import ProfileTab from "components/Pages/MyAccount/Tabs/ProfileTab.vue";
  import Modal from "components/Common/Modals/Modal.vue";

  const tabs = [
    {tab: 'profile',
      subTabs: ['education', 'basic', 'contact']},
  ];

  // TODO show notification when account is updated.

  export default {
    components: {
      PreLoader,
      MyAccountSubTabs,
      ProfileTab,
      Modal
    },
    mounted() {
      this.$store.dispatch('account/fetchData', [
        'profile',
        'account',
      ]).then(() => {
        setTimeout(() => {
          this.loading = false;
        }, 1000)
      })
    },
    data() {
      return {
        loading: true,
        tabs,
        tab: {
          tab: 'profile',
          subTab: 'education'
        }
      }
    },
    computed: {
      ...mapGetters({
        profile: "account/profile",
      }),
    },
    methods: {
      ...mapActions("modal", {
        showModal: "showModal"
      }),
      updateModal() {
        this.showModal({
          modalName: ACCOUNT_UPDATE
        })
      },
    }
  }
</script>

<style lang="scss">
  .my-account-mobile {
    color: #333333;
    font-family: "Open Sans", sans-serif;
    background-color: #f2f7ff;

    .my-account-sub-tabs {
      height: 56px;
    }

    input {
      -webkit-appearance: none;
    }

    &__wrp {
      padding: 25px 20px;
    }
  }
</style>