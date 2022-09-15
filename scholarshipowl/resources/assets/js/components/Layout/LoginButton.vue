<template>
  <a @click.prevent="openModal('login')" :class="className">{{ text }}</a>
</template>

<style>
</style>

<script type="javascript">
import { mapActions } from "vuex";

export default {
  methods: mapActions("modal", [
    "openModal"
  ]),
  props: ["className", "text"],
};
</script>
