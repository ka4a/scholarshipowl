<template>
  <header class="app-navbar animated slideInDown">
    <nav class="nav">
      <div class="nav-left">
        <a href="/" class="nav-item hero-brand">
          <logo />
        </a>
      </div>
      <div class="nav-center"></div>
      <div class="nav-right is-flex">
        <div class="organisation-select">
          <organisation-select />
        </div>
        <div class="profile-pic" @click.cancel="openDropdown" v-click-outside="hideDropdown">
          <user-picture :picture="me.picture" />
          <!-- <div v-if="me.name" class="profile-pic_name">
            <span>{{ me.name }}</span>
          </div> -->
          <div class="profile-pic_arrow-down">
            <icon-arrow-down />
          </div>
        </div>
        <div v-show="dropdownOpen" class="dropdown-menu">
          <div class="dropdown-content">
            <router-link class="dropdown-item" :to="{ name: 'profile' }">
              <c-icon class="dropdown-item__icon" icon="profile" />
              <span>Profile</span>
            </router-link>
            <a href="#" class="dropdown-item" @click.prevent="logout">
              <c-icon class="dropdown-item__icon" icon="logout" />
              <span>Logout</span>
            </a>
          </div>
        </div>
      </div>
    </nav>
  </header>
</template>
<script>
import IconLogout from 'icon/logout';
import IconArrowDown from 'icon/arrow-down';
import Logo from 'components/logo.vue';

import UserPicture from 'components/user/UserPicture';
import OrganisationSelect from 'components/header/OrganisationSelect';

export default {
  name: 'LayoutHeader',
  components: {
    IconLogout,
    IconArrowDown,
    Logo,
    UserPicture,
    OrganisationSelect,
  },
  data: function() {
    return {
      dropdownOpen: false,
    }
  },
  computed: {
    me: ({ $store }) => $store.state.user.me,
    org: ({ $store }) => $store.state.user.organisation,
  },
  methods: {
    logout() {
      this.$auth.logout()
        .finally(() => window.location = '/' );
    },
    openDropdown() {
      this.dropdownOpen = true;
    },
    hideDropdown() {
      if (this.dropdownOpen) {
        this.dropdownOpen = false;
      }
    }
  }
}
</script>
<style lang="scss">
@import "../scss/variables.scss";

.app-navbar {
  position: fixed;
  z-index: 5;
  left: 0;
  right: 0;
  top: 0;
  background-color: white;
  height: $header-height;

  animation-duration: .5s;

  > .nav {
    padding: 0 20px;
    height: $header-height;
    background: #FFFFFF;
    box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.1);
  }

  .organisation-select {
    position: absolute;
    width: 240px;
    right: 100px;
    top: 12px;
    // display: flex;
    // align-items: center;
    // margin-right: 20px;
  }

  .profile-pic {
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;

    font-family: 'Catamaran', sans-serif;
    color: #888888;

    .avatar {
      margin-right: 8px;
    }

    &_name {
      padding: 0 12px;
    }
    &_arrow-down {
      padding-left: 4px;
    }
  }

  .dropdown-menu {
    position: absolute;
    top: 100%;
    right: 10px;
    border: 0;

    display: block;
    left: inherit;

    .dropdown-content {
      background: $white;
      box-shadow: 0px 7px 12px rgba(0, 0, 0, 0.25);
      text-align: left;
    }

    .dropdown-item {
      display: flex;
      font-size: 16px;
      padding: 10px 30px 10px 30px;

      .dropdown-item__icon {
        margin-right: 16px;
      }
    }
  }

}
</style>
