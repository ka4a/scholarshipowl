<template>
  <b-field>
    <c-field
      label="Allowed file extensions"
      tip="Comma separated file extensions"
      :message="errors.first('fileExtensions')"
      :type="errors.has('fileExtensions') ? 'is-danger' : null"
    >
      <b-input
        type="text"
        name="fileExtensions"
        v-model="value.fileExtensions"
        placeholder="Example: txt,jpg,video,doc,pdf"
      />
    </c-field>
    <c-field
      label="Maximum file size (Kb)"
      tip="Maximum allowed file size in Kilobytes."
      :message="errors.first('maxFileSize')"
      :type="errors.has('maxFileSize') ? 'is-danger' : null"
    >
      <b-input
        type="number"
        name="maxFileSize"
        v-model="value.maxFileSize"
        placeholder="Enter number"
      />
    </c-field>
  </b-field>
</template>
<script>
export default {
  props: {
    value: Object,
  },
  watch: {
    value: {
      deep: true,
      handler(value) {
        this.$emit('input', value);
      }
    }
  }
}
</script>
