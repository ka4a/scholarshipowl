<template>
  <div class="app-container">
    <!-- <preloader v-show="$store.state.loading"></preloader> -->
    <app-header />
    <div class="app-body">
      <sidebar />
      <section class="app-main">
        <router-view></router-view>
      </section>
    </div>
    <tutorials />
    <modals />
  </div>
</template>
<script>
/**
 * import preloader
 * choose from preloader and bounce
 */
import Preloader from 'components/layouts/preloader/preloader'
import Sidebar from 'components/sidebar.vue';
import AppHeader from 'components/Header.vue';
import Tutorials from 'components/Tutorials.vue';
import Modals from 'components/Modals.vue';

export default {
    name: 'LayoutDefault',

    created() {
      /**
       * Open new scholarship tutorial on first load.
       * If user seen it is not gonna be opened.
       */
      this.$store.dispatch('user/tutorials/open', 'newScholarship');
    },

    components: {
        Preloader,
        AppHeader,
        Sidebar,
        Tutorials,
        Modals,
    },
}
</script>
<style lang="scss">
@import "../../scss/default";

.image.is-140x140 img {
  width: 140px;
  height: 140px;
}

.button {
  background-color: #F8F8F8;
  border-color: transparent;
  padding-left: 1rem;
  padding-right: 1rem;

  &:hover, &.is-hovered {
    border-color: transparent;
  }
}

.modal {
  .modal-card-head {
    background-color: #FAFBFC;
    .modal-card-title {
      text-align: center;
      font-size: 18px;
    }
  }
}

.field.file {
  .file-name {
    height: 36px;
    padding-top: 0.4rem;
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    border-color: #F8F8F8;
    width: 100%;
  }
}

.app-container {
  height: 100%;
}

.app-body {
  display: flex;
  padding-top: $header-height;
  min-height: 100%;
}

.app-main {
  flex: 1;

  .container-title {
    background-color: #F4F4F4;
    padding: 10px 20px;
  }
}

.app-sidebar {
  min-width: $sidebar-width;
  width: $sidebar-width;

  background-color: white;
}

</style>
