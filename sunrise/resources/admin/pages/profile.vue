<template>
  <section class="page">
    <breadcrumbs :breadcrumbs="{ 'Profile': { name: 'profile' }}" />
    <div class="container">
      <b-tabs v-model="activeTab" class="is-fullheight">
        <b-tab-item label="PERSONAL INFO">
          <div class="block is-fullheight">
            <h6 class="title is-6 has-barline">
              <c-icon icon="profile" />
              <span>Personal Info</span>
            </h6>
            <user-form
              ref="userForm"
              :user="me"
            />
            <c-field class="is-clearfix">
              <!-- <button class="button is-rounded is-grey">
                <c-icon icon="cancel" />
                <span>Cancel</span>
              </button> -->
              <button class="button is-rounded is-primary is-pulled-right" @click="userSave">
                <c-icon icon="check-circle" />
                <span>Save</span>
              </button>
            </c-field>
          </div>
        </b-tab-item>
        <b-tab-item label="ORGANISATION INFO">
          <div class="block is-fullheight">
            <h6 class="title is-6 has-barline">
              <c-icon icon="checked-list" />
              <span>Organisation Info</span>
            </h6>
            <organisation-form
              ref="organisationForm"
              :organisationId="organisationId"
            />
            <c-field class="is-bottom-right">
              <!-- <button class="button is-rounded is-grey">
                <c-icon icon="cancel" />
                <span>Cancel</span>
              </button> -->
              <button class="button is-rounded is-primary"
                @click="organisationSave">
                <c-icon icon="check-circle" />
                <span>Save</span>
              </button>
            </c-field>
          </div>
        </b-tab-item>
        <b-tab-item label="API KEYS">
          <div class="block is-fullheight">
            <h6 class="title is-6 has-barline">
              <c-icon icon="profile" />
              <span>API Access token</span>
            </h6>
            <div class="columns">
              <div class="column">
                <user-api-keys />
              </div>
              <div class="column">
              </div>
            </div>
          </div>
        </b-tab-item>
      </b-tabs>
    </div>
  </section>
</template>
<script>
import UserForm from 'components/user/UserForm';
import UserApiKeys from 'components/user/UserApiKeys';
import OrganisationForm from 'components/organisation/OrganisationForm';

export default {

  components: {
    UserForm,
    UserApiKeys,
    OrganisationForm
  },

  data() {
    const tabs = [
      '#personal',
      '#organisation',
      '#api-keys',
    ];

    return {
      activeTab: tabs.indexOf(this.$route.hash) === -1 ? 0 : tabs.indexOf(this.$route.hash),
    }
  },

  computed: {
    organisationId: ({ $store }) => $store.getters['user/workingOrganisation'],
  },

  methods: {

    userSave() {
      this.$refs['userForm'].save()
        .then(() => {
          this.$store.dispatch('user/loadMe');
          this.$toast.open({
            message: 'Personal information updated!',
            type: 'is-success',
          });
        });
    },

    organisationSave() {
      this.$refs['organisationForm'].save()
        .then(() => {
          this.$store.dispatch('user/loadMe');
          this.$toast.open({
            message: 'Organisation information updated!',
            type: 'is-success',
          });
        });
    }

  },

}
</script>
<style lang="scss" scoped>
@import "../scss/variables";

.page {
  overflow: hidden;
}
.tab-item > .block {
  position: relative;
  padding-bottom: 80px;
}

.field {
  @include widescreen {
    width: 70%;
  }

  .button:not(:first-child) {
    margin-left: 13px;
  }

  &.is-bottom-right {
    width: auto;
    position: absolute;
    bottom: 30px;
    right: 20px;
  }
}
</style>
