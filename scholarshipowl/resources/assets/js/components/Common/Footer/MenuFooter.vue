<template lang="html">
  <nav class="menu-footer-wrp">
    <ul class="menu-footer _1">
      <li v-for="item in splitedMenu[0]" :style="{width: itemWidth(splitedMenu[0].length)}" :key="item.id" class="menu-footer__item">
        <a class="menu-footer__link" :href="item.href">{{item.text}}</a>
      </li>
    </ul>
    <ul class="menu-footer _2">
      <li v-for="item in splitedMenu[1]" :style="{width: itemWidth(splitedMenu[1].length)}" :key="item.id" class="menu-footer__item">
        <a class="menu-footer__link" :href="item.href">{{item.text}}</a>
      </li>
    </ul>
  </nav>

</template>

<script>
export default {
  props: {
    menu: {type: Array, required: true}
  },
  computed: {
    splitedMenu() {
      return [this.menu.slice(0, 4), this.menu.slice(4, this.menu.length)];
    }
  },
  methods: {
    itemWidth(items) {
      return (window.document.body.clientWidth >= 768)
        ? (100 / items) + "%"
        : "auto";
    }
  }
};
</script>

<style lang="scss">
@import 'main/meta/flex-box';

@import 'style-gide/_breakpoints.scss';

  %reset-ul {
    margin: 0; padding: 0;
    list-style: none;
  }

  .menu-footer {
    @extend %reset-ul;

    &._2 {
      @include justify-content(center);
    }

    // common
    &__link {
      color: white;
      text-transform: uppercase;
      font-size: 14px;
    }

    @include breakpoint(max-width $m - 1px) {
      @include flexbox();
      @include flex-wrap(wrap);
      @include justify-content(space-around);

      &__item {
      margin-top: 15px;

        & + & {
          margin-left: 15%;
        }
      }
    }

    @include breakpoint($s) {
      &__link {
        font-size: 16px;
        &:hover {
          color: darken(white, 10);
          text-decoration: none;
        }
        &:focus {
          color: white;
          text-decoration: none;
        }
      }

      
    }

    @include breakpoint($m) {
      overflow: hidden;
      
      &._1 {
        @include flex(4);
        margin-left: -10px;
        
        &__item {
          float: none;
        }
        
        .menu-footer__item:first-child:before {
          content: none;
        }
      }

      &._2 {
        @include flex(2);
      }

      &__item {
        float: left;
        text-align: center;
        position: relative;

        &:before {
          content: '|';
          font-weight: 700;
          font-size: 1.2em;
          position: absolute;
          left: 0;
        }
      }

      @at-root {
        .menu-footer-wrp {
          @include flexbox();
          max-width: 820px;
          margin-left: auto;
          margin-right: auto;
        }
      }
    }

    @include breakpoint($l) {
      max-width: 910px;
    }

    @include breakpoint($xl) {
      max-width: 720px;
    }
  }
</style>
