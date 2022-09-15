<template>
  <h3 :style="{fontSize}" class="title-custom"><slot /></h3>
</template>

<style lang="scss">
  .title-custom {
    font-family: "Open Sans";
    font-weight: 700;
    color: $mine-shaft;
    line-height: 1.35em;
  }
</style>

<script>
  import { mapGetters } from "vuex";
  import sizePresets from "./title-size-list";

  const TABLET_RESOLUTION = 768;

  export default {
    props: {
      size: {default: 12}
    },
    computed: {
      ...mapGetters({
        resolution: "screen/resolution"
      }),
      fontSize() {
        if(typeof this.size === 'number') {
          return this.size;
        }

        const currentSize = size => (
          this.resolution < TABLET_RESOLUTION
            ? size[0] : size[1]
          ) + 'px';

        if(typeof this.size === 'string'
          && sizePresets.hasOwnProperty(this.size)) {
          return currentSize(sizePresets[this.size]);
        }

        if(Array.isArray(this.size) && this.size.length === 2) {
          return currentSize(this.size);
        }

        throw Error('Please provide corrent parameter');
      }
    }
  }
</script>