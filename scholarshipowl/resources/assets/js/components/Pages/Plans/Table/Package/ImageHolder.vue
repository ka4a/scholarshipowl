<template>
  <div class="package-img">
    <img :class="className" v-if="className" :src="packageIcon">
  </div>
</template>

<script>
  const classNameByPackageId = {
    '89': 'cake',
    '70': 'candy',
    '71': 'cap-cake',
    '72': 'lollipop'
  };

  export default {
    props: {
      packageId: {type: Number, required: true},
      packageIcon: {type: String, required: true}
    },
    computed: {
      className() {
        return classNameByPackageId[this.packageId.toString()]
      }
    }
  }
</script>

<style lang="scss">
  .package-img {
    height: 62px;
    display: flex;
    justify-content: center;
    align-items: flex-end;

    @include breakpoint($l) {
      align-items: center;
    }

    @include breakpoint($l) {
      height: 45px;
    }

    .cake {
      width: 70px;

      @include breakpoint($l) {
        width: 42px;
      }
    }

    .candy {
      width: 76px;

      @include breakpoint($l) {
        width: 57px;
      }
    }

    .cap-cake {
      width: 45px;

      @include breakpoint($l) {
        width: 27px;
      }
    }

    .lollipop {
      width: 76px;

      @include breakpoint($l) {
        width: 48px;
      }
    }
  }
</style>