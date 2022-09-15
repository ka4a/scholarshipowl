<template>
  <span :class="['icon-star-wrp', {'icon-star-wrp_favorite': isFavorite}]">
    <i class="icon icon-star"></i>
    <i class="icon icon-star"></i>
  </span>
</template>

<script>
export default {
  props: {
    isFavorite: {type: Number, required: true}
  }
};
</script>

<style lang="scss">
  $yellow: #FFD80A;

  .icon-star-wrp {
    position: relative;
    display: block;

    i:first-child {
      position: absolute;
      font-size: 16px;
      color: white;
      left: 2px;
      top: 2px;
    }

    i:first-child + i {
      font-size: 20px;
      color: #ACCAF6;
    }

    &_favorite {
      i:first-child {
        display: none;
      }

      i:first-child + i {
        color: $yellow;
      }
    }
  }
</style>