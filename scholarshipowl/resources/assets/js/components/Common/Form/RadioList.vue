  <template lang="html">
  <ul class="radio-list-fm" :class="{'radio-list-fm_horizontal' : Object.keys(list).length <= 3}">
    <!-- eslint-disable-next-line vue/require-v-for-key -->
    <li v-for="item in list" :class="['radio-list-fm__item', {'disabled': item.disabled}]">
      <label @keydown="keyDownHandle" tabindex=0 :for="id(item)" class="radio-fm">
        <input :id="id(item)"
          :name="name"
          :value="item.value"
          :checked="item.value == value"
          :disabled="item.disabled"
          @change="$emit('input', item.value, item)"
          type="radio" />
        <span class="radio-fm__radio"></span>
        <span class="radio-fm__label">{{ item.label }}</span>
      </label>
      <slot :tooltip="item.tooltip"/>
    </li>

  </ul>
</template>

<script>
export default {
  name: "RadioList",
  props: {
    value: { required: true },
    list:  { required: true, type: Array },
    name:  { required: true, type: String },
  },
  methods: {
    id ({ value }) {
      const name = this.name;

      return `radio-list-${name}-${value}`;
    },
    keyDownHandle(ev) {
      if(ev.code && ev.type.indexOf('keydown') > -1
        && ev.code.toLowerCase().indexOf('space') > -1) {

        ev.preventDefault();
        ev.target.click()
      }
    }
  }
};
</script>
