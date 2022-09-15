<template>
  <p class="plans-show-more">
    <span class="plans-show-more__text">Show more membership plans</span>
    <svg class="plans-show-more__icon" width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M10.8496 9.75405C11.0501 9.57694 11.0501 9.28218 10.8496 9.09889C10.6559 8.92178 10.3334 8.92178 10.1401 9.09889L6.50583 12.4209L6.50583 1.45865C6.50537 1.20311 6.28317 1 6.00361 1C5.72405 1 5.49463 1.20311 5.49463 1.45865L5.49463 12.4209L1.86713 9.09889C1.66661 8.92178 1.34369 8.92178 1.15039 9.09889C0.949869 9.28218 0.949869 9.57736 1.15039 9.75405L5.64502 13.8625C5.83877 14.0458 6.16123 14.0458 6.35453 13.8625L10.8496 9.75405Z" fill="#597CE1" stroke="#597CE1" stroke-width="0.5"/>
    </svg>
  </p>
</template>

<style lang="scss">
  .plans-show-more {
    &__text {
      font-style: normal;
      font-weight: 600;
      font-size: 14px;
      line-height: 1.46em;

      text-align: center;

      color: $havelock-blue;
    }

    &__icon {
      margin-left: 3px;
      margin-bottom: -2px;
    }
  }
</style>