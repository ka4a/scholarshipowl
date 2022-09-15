<template>
  <a :href="'tel:' + phoneNumber" class="sowl-phone-number">
    <i class="sowl-phone-number__icon icon icon-sowl-phone"></i>
    <span v-if="!isShort" class="sowl-phone-number__number">{{ phoneNumber }}</span>
  </a>
</template>

<script>
export default {
  props: {
    phoneNumber: {type: Boolean, required: true},
    isShort: {type: Boolean, default: true}
  }
};
</script>

<style lang="scss">
  .sowl-phone-number {
    color: #2f2f2f;
    font-size: 14px;
    line-height: 16px;

    display: flex;
    align-items: center;

    &:hover, &:focus {
      text-decoration: none;
      color: #2f2f2f;
    }

    &__icon {
      font-size: 20px;
    }

    &__number {
      margin-left: 10px;
    }
  }  
</style>