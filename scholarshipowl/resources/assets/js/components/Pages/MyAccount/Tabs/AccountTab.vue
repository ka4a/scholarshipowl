<template>
  <div class="account-tab">
    <membership-tab @modal="ev => $emit('modal', ev)" @upgrade="ev => $emit('upgrade', ev)"
      :membership="membership" v-if="currentTab === subTabs[0]" />
    <credentials-tab @updated="$emit('updated')" :email="account.email" v-if="currentTab === subTabs[1]" />
    <recurrence-tab @updated="$emit('updated')" :recurring-application="profile.recurringApplication" v-if="currentTab === subTabs[2]" />
  </div>
</template>

<script>
  import MembershipTab from "components/Pages/MyAccount/SubTabs/MembershipTab.vue";
  import CredentialsTab from "components/Pages/MyAccount/SubTabs/CredentialsTab.vue";
  import RecurrenceTab from "components/Pages/MyAccount/SubTabs/RecurrenceTab.vue";

  export default {
    name: "account-tab",
    components: {
      MembershipTab,
      CredentialsTab,
      RecurrenceTab
    },
    props: {
      subTabs: {type: Array, required: true},
      currentTab: {type: String, required: true},
      profile: {type:Object, required: true},
      membership: {type:Object, required: true},
      account: {type:Object, required: true}
    }
  }
</script>
