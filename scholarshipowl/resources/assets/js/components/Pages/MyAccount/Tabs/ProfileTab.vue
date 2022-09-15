<template>
  <div class="education-sub-tab">
    <slot name="ctrl"/>
    <education-tab v-if="currentTab === subTabs[0]" @submit="data => submit(data, 'education')"
      :validation-errors="errors.basic" :submiting="submiting" :profile="profile" />
    <basic-tab v-if="currentTab === subTabs[1]" @submit="data => submit(data, 'basic')"
      :validation-errors="errors.basic" :submiting="submiting" :profile="profile" />
    <contact-tab v-if="currentTab === subTabs[2]" @submit="data => submit(data, 'contact')"
      :validation-errors="errors.contact" :submiting="submiting"  :profile="profile" />
    <slot name="banner" />
  </div>
</template>

<script>
  import { ELIGIBLE_SCHOLARSHIP_COUNT, NOT_SEEN_SCHOLARSHIP_COUNT } from "store/eligibility-cache";

  import EducationTab from "components/Pages/MyAccount/SubTabs/EducationTab.vue";
  import BasicTab from "components/Pages/MyAccount/SubTabs/BasicTab.vue";
  import ContactTab from "components/Pages/MyAccount/SubTabs/ContactTab.vue";

  export default {
    name: "profile-tab",
    components: {
      EducationTab,
      ContactTab,
      BasicTab
    },
    props: {
      subTabs: {type: Array, required: true},
      currentTab: {type: String, required: true},
      profile: {type:Object, required: true},
    },
    data() {
      return {
        submiting: false,
        errors: {
          education: {},
          basic: {},
          contact: {}
        }
      }
    },
    methods: {
      updateEligibilityStore() {
        this.$store.dispatch("eligibilityCache/getEligibilities", [
          ELIGIBLE_SCHOLARSHIP_COUNT,
          NOT_SEEN_SCHOLARSHIP_COUNT
        ])
      },
      submit(data, tabName) {
        this.submiting = true;
        this.$store.dispatch("account/updateProfile", data)
          .then((response) => {
            if(response.status !== 200) return;

            return this.$store.dispatch('account/fetchAndUpdateField', ['profile'])
          })
          .then(() => {
            this.submiting = false;
            this.$emit('updated');

            setTimeout(this.updateEligibilityStore, 5000)
          })
          .catch((response) => {
            this.submiting = false;
            if (response.body && response.body.error) {
              this.errors[tabName] = response.body.error;
            }
          });
      }
    }
  }
</script>