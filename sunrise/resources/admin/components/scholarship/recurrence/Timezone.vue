<template>
  <c-field
    label="Scholarship time zone"
    tip="Select a timezone (e.g, If New York, select “(GMT -04:00) EST”)"
    :type="errors.has('timezone') ? 'is-danger' : null"
    :message="errors.first('timezone')">
    <b-select name="timezone"
      v-validate="'required'"
      :value="value"
      @input="$emit('input', $event)">
      <option
        v-for="(name, key) in timezones"
        :value="key"
        :key="key" >
        {{ name }}
      </option>
    </b-select>
  </c-field>
</template>
<script>
import timezones from 'timezones.json';

export default {
  props: {
    value: String
  },
  computed: {
    timezones: () => timezones
  }
}
</script>
