<template>
  <i class="svg-icon" v-html="svg"></i>
</template>

<script>
  import svgIcons from "components/Pages/Own/SvgIcons/icons";

  export default {
    props: {
      name: {type: String, require: true}
    },
    computed: {
      svg() {
        return svgIcons[this.name]
      }
    }
  }
</script>