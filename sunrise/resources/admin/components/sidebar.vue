<template>
  <aside class="app-sidebar animated">
    <div class="has-text-centered">

      <router-link :to="{ name: 'scholarships.create' }"
        @click.native="onNewScholarshipClick"
        :class="{ 'z-index-25':  isNewScholarshipTutorialOpen }">
        <button class="button is-rounded is-centered is-primary">
          <icon-plus class="icon" />
          <span>New Scholarship</span>
        </button>
      </router-link>

    </div>
    <div class="org-menu">
      <!-- <h3 v-if="org" class="has-text-centered">{{ org.name }}</h3> -->
      <ul class="menu-list">
        <!-- <li>
          <router-link class="link" :to="{ name: 'dashboard' }" exact>
            <icon-dashboard class="icon" />
            <span>Dashboard</span>
          </router-link>
        </li> -->
        <li>
          <router-link class="link" :to="{ name: 'scholarships' }">
            <icon-scholarships class="icon"/>
            <span>Scholarships</span>
          </router-link>
        </li>
        <li>
          <router-link class="link" :to="{ name: 'winners' }">
            <icon-winner class="icon"/>
            <span>Winners</span>
          </router-link>
        </li>
      </ul>
    </div>
    <div v-if="isRoot" class="admin-menu">
      <hr />
      <div class="menu">
        <p class="menu-label">Administration</p>
        <ul class="menu-list">
          <li>
            <router-link :to="{ name: 'settings.legal' }">
              <b-icon icon="content-paste" />
              <span>Legal Content</span>
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </aside>
</template>
<script>
import IconPlus from 'icon/plus.vue';
import IconDashboard from 'icon/dashboard.vue';
import IconScholarships from 'icon/scholarships.vue';
import IconWinner from 'icon/winner.vue';

export default {
  name: 'Sidebar',
  methods: {
    onNewScholarshipClick() {
      if (this.isNewScholarshipTutorialOpen) {
        this.$store.dispatch('user/tutorials/close', 'newScholarship');
      }
    }
  },
  computed: {

    isRoot: ({ $store }) => $store.getters['user/isRoot'],

    isNewScholarshipTutorialOpen: ({ $store }) =>
      $store.getters['user/tutorials/active'] === 'newScholarship',

  },
  components: {
    IconPlus,
    IconDashboard,
    IconScholarships,
    IconWinner
  }
}
</script>
<style lang="scss">
@import "../scss/variables";

.app-sidebar {
  box-shadow: 1px 0px 0px rgba(0, 0, 0, 0.1);

  .admin-menu {
    > hr {
      margin: 1rem 0;
    }
    > .menu {
      .menu-label {
        padding-left: 42px;
      }
      .menu-list {
        a {
          padding-left: 40px;
          display: flex;
          align-items: center;
          .icon {
            margin-right: 8px;
          }
        }
      }
    }
  }

  .org-menu {
    h3 {
      margin-top: 16px;
      line-height: 30px;
      font-size: 20px;
      font-weight: bold;
    }
    ul.menu-list {
      margin-top: 30px;
    }
  }
  .button.is-rounded {
    height: 48px;
    padding-right: 30px;
    margin-top: 20px;

    .icon:first-child:not(:last-child) {
      margin-left: 0px;
      margin-right: 15px;
    }
  }
  ul.menu-list {
    // margin-right: 20px;
    li {
      margin: 10px 0;
      &:first-child {
        background: none;
      }
    }

    .link {
      position: relative;
      padding: 12px 8px 12px 80px;
      padding-left: 80px;
      display: flex;

      .icon {
        position: absolute;
        top: 10px;
        left: 42px;
      }
      &.active {
        background-color: $primary-lighter;
        fill: $primary;
      }
      &.router-link-exact-active {
      }
    }
  }
}
</style>
