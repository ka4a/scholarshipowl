<template>
  <button class="brand-button button is-rounded"
    v-bind="$props"
    :class="{ 'is-red': isRed, 'is-white': isWhite }">
    <slot />
    <i v-if="hasDot" class="brand-button--dot">
      <svg v-if="disabled" width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="13" cy="13" r="11" stroke="#C5C5C5" stroke-width="4"/>
        <circle cx="13" cy="13" r="6" fill="#E4E4E4"/>
      </svg>
      <svg v-else width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="13" cy="13" r="11" stroke="#E08385" stroke-width="4"/>
        <circle cx="13" cy="13" r="6" fill="#FDC0C1"/>
      </svg>
    </i>
  </button>
</template>
<script>
export default {
  props: {
    isRed: Boolean,
    isWhite: Boolean,
    hasDot: Boolean,
    disabled: Boolean
  }
}
</script>
<style lang="scss" scoped>
.brand-button {
  font-size: 17px;
  font-weight: 800;
  color: #4F4F4F;

  line-height: 26px;
  padding: 18px 20px;
  border-radius: 90px;
  height: auto;
  background: linear-gradient(83.45deg, #EAEEEE 4.63%, #F0F5F6 95.01%);

  border: 1px solid transparent;
  border-color: transparent;

  .brand-button--dot {
    display: flex;
    margin-left: 14px;
  }

  &:hover {
    background: linear-gradient(83.45deg, #DCEAEC 4.63%, #DCE8EA 95.01%);
    border-color: transparent;
  }
  &:active {
    background: linear-gradient(83.45deg, #CFDEE0 4.63%, #CFDBDC 95.01%);
    border-color: transparent;
  }
  &:focus {
    border-color: transparent;
    &:not(:active) {
      box-shadow: none;
    }
  }

  &.is-red {
    background: linear-gradient(259.63deg, #D73148 7.32%, #EB6668 92.68%);
    border: 1px solid transparent;
    color: white;

    &:hover {
      color: white;
      background: linear-gradient(254.14deg, #EC0E2D 7.32%, #FF5C60 92.68%);
    }
    &:active {
      color: white;
      background: linear-gradient(254.14deg, #D61833 7.32%, #DF5457 92.68%);
    }
    &:focus {
      color: white;
    }
  }

  &.is-white {
    border: 2px solid #FFFFFF;
    box-sizing: border-box;
    box-shadow: 0px 20px 25px rgba(0, 0, 0, 0.08);
    background: white;
    color: #4F4F4F;

    &:focus {
      box-sizing: border-box;
      box-shadow: 0px 20px 25px rgba(0, 0, 0, 0.08);
      color: #4F4F4F;
      &:not(:active) {
        box-sizing: border-box;
        box-shadow: 0px 20px 25px rgba(0, 0, 0, 0.08);
      }
      &:hover {
        box-shadow: none;
      }
    }
    &:hover {
      color: #4F4F4F;
      background: #F9F9F9;
      box-shadow: none;
    }
    &:active {
      color: #4F4F4F;
      background: #EFEFEF;
      box-shadow: none;
    }
  }

  &:disabled,
  [disabled] {
    opacity: 1 !important;
    color: #D7D7D7 !important;
    border: 1px solid transparent !important;
    background: linear-gradient(254.14deg, #9C9C9C 7.32%, #C0C0C0 92.68%),
                linear-gradient(254.14deg, #D61833 7.32%, #DF5457 92.68%)
                !important;
  }
}
</style>
