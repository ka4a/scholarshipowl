<template>
  <nav class="breadcrumb" aria-label="breadcrumbs">
    <ul>
      <li v-for="(link, title, index) in breadcrumbs"
        :key="index"
        :class="{ 'is-active': index + 1 === Object.keys(breadcrumbs).length}">
        <router-link :to="link">
          {{ title }}
        </router-link>
      </li>
    </ul>
    <template v-if="$slots.right">
      <slot name="right" />
    </template>
  </nav>
</template>
<script>
export default {
  props: {
    breadcrumbs: Object
  },
}
</script>
<style lang="scss">
.breadcrumb {
  background: #EAECEF;
  color: #1B2942;
  padding: 4px 20px;
  margin: 0;
  &:not(:last-child) {
    margin-bottom: 0 !important;
  }
}
</style>
