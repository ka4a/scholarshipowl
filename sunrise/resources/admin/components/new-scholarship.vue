<template>
  <div class="modal-card new-scholarship" style="width: auto">
    <header class="modal-card-head">
      <p class="modal-card-title">New scholarship</p>
    </header>
    <section class="modal-card-body">
      <b-field horizontal label="Title"
        :type="errors['data.attributes.title'] ? 'is-danger':undefined"
        :message="errors['data.attributes.title'] || undefined">
          <b-input v-model="scholarship.title" placeholder="Scholarship title"></b-input>
      </b-field>
      <b-field horizontal label="Description"
        :type="errors['data.attributes.title'] ? 'is-danger':undefined"
        :message="errors['data.attributes.title'] || undefined">
          <b-input type="textarea" v-model="scholarship.title" placeholder="Scholarship description..."></b-input>
      </b-field>
    </section>
    <footer class="modal-card-foot">
      <button class="button" type="button" @click="$parent.close()">Close</button>
      <button class="button is-primary">Login</button>
    </footer>
  </div>
</template>
<script>
export default {
  name: 'NewScholarship',
  data() {
    return {
      errors: {},
      scholarship: {},
    }
  }
}
</script>
<style lang="scss">
.new-scholarship {
  .modal-card-body {
  }
}
</style>
