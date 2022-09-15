<template>
  <div>
    <b-field>
      <c-field
        label="Min width"
        :message="errors.first('minWidth')"
        :type="errors.has('minWidth') ? 'is-danger' : null"
      >
        <b-input
          type="number"
          name="minWidth"
          v-model="value.minWidth"
          v-validate="'number'"
        />
      </c-field>
      <c-field
        label="Max width"
        :message="errors.first('maxWidth')"
        :type="errors.has('maxWidth') ? 'is-danger' : null"
      >
        <b-input
          type="number"
          name="maxWidth"
          v-model="value.maxWidth"
          v-validate="'number'"
        />
      </c-field>
    </b-field>
    <b-field>
      <c-field
        label="Min height"
        :message="errors.first('minHeight')"
        :type="errors.has('minHeight') ? 'is-danger' : null"
      >
        <b-input
          type="number"
          name="minHeight"
          v-model="value.minHeight"
          v-validate="'number'"
        />
      </c-field>
      <c-field
        label="Max height"
        :message="errors.first('maxHeight')"
        :type="errors.has('maxHeight') ? 'is-danger' : null"
      >
        <b-input
          type="number"
          name="maxHeight"
          v-model="value.maxHeight"
          v-validate="'number'"
        />
      </c-field>
    </b-field>
  </div>
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
