<template>
  <a href="#" class="user-inform-panel">
      <p :class="['user-inform-panel__avatar', {'gender-male': gender === 'male', 'gender-famale': gender === 'female', 'jelenas-case': capitalizedFirstLetter === 'J'}]">
        {{ capitalizedFirstLetter }}
      </p>
      <p class="user-inform-panel__wrp" v-if="!isShort">
        <span :class="['user-inform-panel__pack-name', packageNameClass]">{{ packageName }}</span>
        <span class="user-inform-panel__user-name">{{ firstName }} {{ lastName }} <i class="icon icon-arrow-down"></i></span>
      </p>
    </a>
</template>

<script>
export default {
  props: {
    isShort: {type: Boolean, default: true},
    packageName: {type: String, required: true},
    firstName: {type: String, required: true},
    lastName: {type: String, requred: true},
    gender: {required: true}
  },
  computed: {
    packageNameClass() {
      if(!this.packageName) return;

      let packageName = this.packageName.toLowerCase().trim();

      if(/^.*elite.*$/.test(packageName)) {
        return "elite";
      }

      if(/^.*month.*$/.test(packageName)) {
        return "monthly";
      }

      if(/^.*quart.*$/.test(packageName)) {
        return "quarterly";
      }

      if(/^.*freemium.*$/.test(packageName)) {
        return "freemium";
      }

      if(/^.*year.*$/.test(packageName)) {
        return "yearly";
      }
    },
    capitalizedFirstLetter() {
      return this.firstName.substring(0, 1).toUpperCase();
    }
  }
};
</script>

<style lang="scss">
  .user-inform-panel {
    display: flex;
    padding-left: 8px;

    &:hover,
    &:focus {
      text-decoration: none;
    }

    &__avatar {
      display: inline-block;
      width: 38px; height: 38px;
      border-radius: 50%;
      background-color: #95A7B1;
      font-size: 20px;
      color: white;
      text-align: center;
      line-height: 37px;
      margin: 0;

      &.gender-male {
        background-color: #708FE7;
      }

      &.gender-famale {
        background-color: #F59F9F;
      }

      &.jelenas-case {
        line-height: 33px;
      }
    }

    &__wrp {
      margin:0 0 0 10px;

    @include breakpoint($s) {
      max-width: 110px;
    }

    @include breakpoint($l) {
      max-width: 145px;
    }
    }

    &__pack-name {
      font-size: 10px;
      color: white;
      text-transform: uppercase;
      padding: 1px 3px;
      background-color: #FF6633;
      min-width: 30px;
      display: inline-block;

      &.elite {
        background-color: #FF3333;
      }

      &.monthly {
        background-color: #FF6633;
      }

      &.quarterly {
        background-color: #339DFF;
      }

      &.freemium {
        background-color: #FF33EB;
      }

      &.yearly {
        background-color: #98D992;
      }
    }

    &__user-name {
      font-size: 12px;
      color: #2f2f2f;
      display: block;
      margin-top: 2px;

      @include breakpoint($s) {
        position: relative;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding-right: 15px;

        .icon {
          position: absolute;
          right: 0;
        }
      }
    }
  }
</style>