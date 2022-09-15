<template>
  <c-icon v-if="type === 'text'" icon="requirement-text" />
  <c-icon v-else-if="type === 'input'" icon="requirement-text" />
  <c-icon v-else-if="type === 'link'" icon="requirement-link" />
  <c-icon v-else-if="type === 'image'" icon="requirement-image" />
  <c-icon v-else="type === 'file'" icon="requirement-file" />
</template>
<script>
export default {
  props: {
    type: String,
  }
}
</script>
