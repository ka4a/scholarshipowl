<template>
  <a :href="href" target="_blank" class="link-ios-app">
    <div class="link-ios-app__wrp">
      <i class="link-ios-app__icon"><img :src="img"></i>
      <div>
        <p class="link-ios-app__text">{{ text }}</p>
        <p class="link-ios-app__name">{{ name }}</p>
      </div>
    </div>
  </a>
</template>

<script>
  export default {
    props: {
      href: {type: String, required: true},
      name: {type: String, required: true},
      text: {type: String, required: true},
      img: {type: String, required: true}
    }
  }
</script>

<style lang="scss">
  $text-color: #ffffff;
  $background-color: #354C6D;

  .link-ios-app {
    width: 119px;
    height: 40px;
    display: block;

    background: #718198;
    background: -moz-linear-gradient(top, #718198 0%, rgba(37, 53, 76, 0.5) 100%);
    background: -webkit-linear-gradient(top, #718198 0%, rgba(37, 53, 76, 0.5) 100%);
    background: linear-gradient(to bottom, #718198 0%, rgba(37, 53, 76, 0.5) 100%);

    box-shadow: 0px 4px 4px rgba(37, 53, 76, 0.25);
    border-radius: 6px;
    overflow: hidden;

    &__wrp {
      height: 38px;
      padding: 3px;

      display: flex;
      justify-content: space-around;
      background-color: $background-color;
      border-radius: 6px;
      margin: 1px;
    }

    &__icon {
      display: block;
      width: 28px;

      > img {
        display: block;
        width: 100%;
      }
    }

    &__text {
      color: $text-color;
      margin: 0;
      font-size: 9px;
      line-height: 1em;
      margin-top: 5px;
    }

    &__name {
      color: $text-color;
      margin: 0;
      font-size: 15px;
      line-height: 1em;
      letter-spacing: 0.4px;
    }
  }
</style>