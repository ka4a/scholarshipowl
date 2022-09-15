<template>
  <nav :class="classPrefix">
    <a
      ref="mainMenuBtn"
      :class="[`${classPrefix}__three-dots`, 'icon', 'icon-three-vertical-dots']"
      href="#"
      @click="ev => {ev.preventDefault(); toggleMenu()}"></a>
    <ul
      :class="`${classPrefix}__body`" v-if="menuIsOpen"
      v-click-outside="{
        exclude: ['mainMenuBtn'],
        handler: 'closeMenu'
      }">
      <li v-for="menuItem in menu" :key="menuItem.id"
          :class="[`${classPrefix}__item`, {'is-open': menuItem.id === openMenuItemId && menuItem.links.length > 1}]">

        <a v-if="menuItem.links.length > 1" :class="`${classPrefix}__ctrl`"
          @click.prevent="openMenuItem(menuItem.id)" href="#">{{ menuItem.name }}
          <i v-if="isShort" :class="[`${classPrefix}__ctrl-indicator`]">{{ menuItem.id === openMenuItemId ? '-' : '+' }}</i>
          <i v-else class="icon icon-arrow-drop-down"></i>
        </a>
        <a v-else :class="`${classPrefix}__ctrl`" :href="menuItem.links[0].href"
          :target="menuItem.links[0].target">{{ menuItem.name }}</a>

        <ul class="menu-links"  v-if="menuItem.links.length > 1 && menuItem.id === openMenuItemId">
          <li class="menu-links__item" v-for="link in menuItem.links" :key="link.id">
            <a class="menu-links__link" :target="link.target" :href="link.href">{{ link.text }}</a>
          </li>
        </ul>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  props: {
    menu:     {type: Array, required: true},
    isShort: {type: Boolean, default: true},
    menuIsOpen: {type: Boolean, default: true}
  },
  data: function() {
    return {
      openMenuItemId: undefined,
    };
  },
  computed: {
    classPrefix() {
      return !this.isShort ? "main-menu-large" : "main-menu";
    }
  },
  methods: {
    openMenuItem(id) {
      this.openMenuItemId = this.openMenuItemId !== id ? id : null;
    },
    toggleMenu() {
      this.menuIsOpen = !this.menuIsOpen;
    },
    closeMenu() {
      this.menuIsOpen = !this.isShort; this.openMenuItemId = null;
    },
  },
};
</script>

<style lang="scss">
  @import 'node_modules/breakpoint-sass/stylesheets/_breakpoint.scss';
@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';

  %reset-list {
    // reset
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .main-menu {
    position: relative;
    margin-right: -25px;

    @include breakpoint(max-width $s - 1px) {
      margin-right: -15px;
    }

    &__three-dots {
      height: 50px;
      display: inline-block;
      width: 40px;
      text-align: center;
      line-height: 50px;
      font-size: 20px;
      text-decoration: none;
      color: #2f2f2f;

      &:focus,
      &:hover {
        text-decoration: none;
      }
    }

    &__body {
      @extend %reset-list;

      position: absolute;
      z-index: 12;
      right: 0; top: 54px;
      padding: 13px 15px 6px 15px;

      width: 230px;
      background: #FFFFFF;
      box-shadow: 0px 8px 30px rgba(50, 50, 93, 0.15);
    }

    &__ctrl {
      display: block;
      font-size: 12px;
      text-transform: uppercase;
      color: #2F2F2F;
      padding: 10px 0;
      font-weight: 600;
      text-decoration: none;

      &:focus,
      &:hover {
        color: #2F2F2F;
        text-decoration: none;
      }
    }

    &__ctrl-indicator {
      font-size: 26px;
      float: right;
      font-weight: 200;
      line-height: 0.5em;
      color: #B6BABE;
      font-style: normal;
    }

    .is-open {
      .main-menu__ctrl {
        border-bottom: 1px solid #e0e0e0;
      }

      .main-menu__ctrl-indicator {
        color: #2f2f2f;
      }
    }

    .menu-links {
      @extend %reset-list;

      &__item {
        &:first-child {
          margin-top: 7px;
        }
      }

      &__link {
        display: block;
        font-size: 11px;
        text-transform: uppercase;
        color: #5A5A5A;
        padding: 7px 0;

        &:hover {
          color: #5A5A5A;
          text-decoration: none;
        }
      }
    }

    @include breakpoint($s) {
      &__body {
        width: 280px;
        padding: 13px 24px 20px 24px;
      }

      &__ctrl {
        font-size: 14px;
      }

      .menu-links {
        &__link {
          font-size: 13px;
        }
      }
    }
  }

  .main-menu-large {
    &__three-dots {
      display: none;
    }

    &__body {
      list-style: none;
      width: auto;
      padding: 0;
      margin-bottom: 0;

      @include flexbox();
      @include justify-content(center);
      @include align-items(center);
    }

    &__ctrl {
      font-size: 14px;
      color: #2F2F2F;
      text-transform: uppercase;
      font-weight: 700;
      text-decoration: none;

      padding-top: 12px;
      padding-bottom: 12px;

      &:focus,
      &:hover,
      &:active {
        color: #708FE7;
        text-decoration: none;
        padding-bottom: 18px;
        border-bottom: solid 2px #708FE7;
      }

      .icon {
        position: absolute;
        top:0; right: 0;
      }
    }

    &__item {
      padding-right: 16px;
      position: relative;

      & + & {
        margin-left: 15px;
      }

      &.is-open {
        .main-menu__ctrl {
          color: #708FE7;

          .icon:before {
            transform: rotate(90deg);
          }
        }
      }
    }

    .menu-links {
      list-style: none;
      margin: 0;
      padding: 0;

      width: 230px;
      position: absolute;
      top: 32px;

      padding: 6px 20px 20px;
      max-width: 230px;
      background: #FFFFFF;
      box-shadow: 0px 8px 30px rgba(50, 50, 93, 0.15);
      border-radius: 4px;
      z-index: 1;

      &__link {
        display: block;
        font-size: 13px;
        text-transform: uppercase;
        color: #2F2F2F;

        padding-top: 14px;
        padding-bottom: 9px;
        border-bottom: 1px solid #E0E0E0;

        &:hover {
          color: #708FE7;
          text-decoration: none;
        }
      }
    }
  }

</style>