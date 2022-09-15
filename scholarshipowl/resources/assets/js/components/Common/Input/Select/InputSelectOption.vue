<template>
  <span>
    <span v-if="props.option.isTag">{{ props.option.label }}</span>
    <template v-else>
      <span v-if="props.option.choosen" @click.stop.prevent
      class="multiselect-option_choosen">{{ props.option.label }}</span>
      <span v-else>{{ props.option.label || props.option }}</span>
    </template>
  </span>
</template>

<script>
  export default {
    props: {
      props: {type: Object, required: true}
    }
  }
</script>