<template>
  <div v-if="subTabs" class="my-account-sub-tabs-wrp">
    <ul class="my-account-sub-tabs">
      <li class="my-account-sub-tabs__item" v-for="tab in subTabs">
        <a :class="['my-account-sub-tabs__link', {'active': currentTab.subTab === tab}]"
          @click.prevent="currentTab.subTab = tab; $emit('change-tab', {tab: currentTab.tab, subTab: tab})" href="#">
          <svg-icon :name="tab === currentTab.subTab ? `${tab}-active` : tab" />
          <span class="my-account-sub-tabs__text" v-if="!(s || xs)">{{ tab }}</span>
        </a>
      </li>
    </ul>
  </div>
</template>

<script>
  import { mapGetters } from "vuex";
  import svgIcon from "components/Pages/Own/SvgIcons/SvgIcon.vue";

  export default {
    components: {
      svgIcon
    },
    props: {
      tabs: {type: Object, required: true},
      currentTab: {type: Object, required: true}
    },
    computed: {
      ...mapGetters({
        xs: "screen/xs",
        s: "screen/s",
      }),
      subTabs() {
        let tabName = this.currentTab.tab;

        return this.tabs.filter(tab => tab.tab === tabName)[0].subTabs
      }
    }
  }
</script>

<style lang="scss">
  $black: #2f2f2f;
  $white: #ffffff;
  $blue: $havelock-blue;
  $blue-light: #cdd7e8;
  $blue-more-light: #cdd7e8;
  $open-sans: 'Open Sans';

  .my-account-sub-tabs-wrp {
    border-bottom: 1px solid $blue-light;
    background-color: $white;
  }

  .my-account-sub-tabs {
    justify-content: space-around;
    align-items: center;
    height: 45px;
    display: flex;

    @include breakpoint($s) {
      height: 50px;
    }

    @include breakpoint($l) {
      height: 60px;
      max-width: 750px;
      margin-left: auto;
      margin-right: auto;
      justify-content: space-between;
    }

    &__link {
      font-family: $open-sans;
      font-size: 16px;
      color: $black;
      display: flex;
      align-items: center;

      &.active {
        color: $blue;
        font-weight: bold;
        letter-spacing: -0.6px;
      }
    }

    &__text {
      margin-left: 15px;
      text-transform: capitalize;
    }
  }
</style>