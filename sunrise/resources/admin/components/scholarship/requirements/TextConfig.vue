<template>
  <div>
    <c-field
      label="Required text amount"
      tip="Select how many words or characters essay must be."
      :message="errors.first('config')"
      :type="errors.has('config') ? 'is-danger' : null"
    >
    </c-field>
    <b-field>
      <b-input type="text" value="Minimum" readonly />
      <b-input type="number" v-model="form.minNumber" placeholder="None" />
      <b-select v-model="form.minType">
        <option v-if="allowWords" value="Words">Words</option>
        <option v-if="allowChars" value="Chars">Characters</option>
      </b-select>
    </b-field>
    <b-field>
      <b-input type="text" value="Maximum" readonly />
      <b-input type="number" v-model="form.maxNumber" placeholder="None" />
      <b-select v-model="form.maxType">
        <option v-if="allowWords" value="Words">Words</option>
        <option v-if="allowChars" value="Chars">Characters</option>
      </b-select>
    </b-field>
  </div>
</template>
<script>
export default {
  props: {
    value: Object,
    allowWords: {
      type: Boolean,
      default: true,
    },
    allowChars: {
      type: Boolean,
      default: true,
    },
  },
  data() {
    let minType = 'Words';
    let minNumber = null;
    let maxType = 'Words';
    let maxNumber = null;

    Object.keys(this.value).forEach(c => {
      if (['min','max'].indexOf(c.substr(0, 3)) !== -1) {
        if (c.substr(0, 3) === 'min') {
          minNumber = this.value[c];
          if (['Words', 'Chars'].indexOf(c.substr(3)) !== -1) {
            minType = c.substr(3);
          }
        } else {
          maxNumber = this.value[c];
          if (['Words', 'Chars'].indexOf(c.substr(3)) !== -1) {
            maxType = c.substr(3);
          }
        }
      }
    })

    return {
      form: {
        minType,
        minNumber,
        maxType,
        maxNumber,
      }
    }
  },
  watch: {
    form: {
      deep: true,
      handler({ minType, minNumber, maxType, maxNumber }) {
        const config = {};
        if (minNumber) {
          config[`min${minType}`] = minNumber;
        }
        if (maxNumber) {
          config[`max${minType}`] = maxNumber;
        }
        this.$emit('input', config);
      }
    }
  }
}
</script>
