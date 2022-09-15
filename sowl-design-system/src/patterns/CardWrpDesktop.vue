<template>
  <section class="bg-gray-4 shadow-m rounded-sm text-gray-1">
    <header class="px-30">
      <slot name="header" />
    </header>
    <footer
      v-if="$slots['footer']"
      style="border-top: 1px solid #F3F3F3"
      class="px-30 min-h-45 flex items-center"
    >
      <slot name="footer" />
    </footer>
  </section>
</template>
