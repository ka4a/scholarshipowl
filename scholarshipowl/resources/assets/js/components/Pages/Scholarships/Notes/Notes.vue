<template lang="html">
  <div v-if="notes">
    <note v-for="(note, name) in notes" :key="name" v-if="!cookies.hasOwnProperty(name)"
      :name="name" :message="note" :close="close" />
  </div>
</template>

<script>
import { serialize, parse } from "cookie-js";
import Note from "./Note.vue";

export default {
  components: {
    Note
  },
  data: function() {
    return {
      notes: window.SOWLNotifications ? Object.assign({}, window.SOWLNotifications) : null,
      cookies: parse(document.cookie)
    };
  },
  methods: {
    close(name) {
      document.cookie = serialize(name, "1", { path: "/" });
      this.cookies = parse(document.cookie);
      delete this.notes[name];
    }
  }
};
</script>
