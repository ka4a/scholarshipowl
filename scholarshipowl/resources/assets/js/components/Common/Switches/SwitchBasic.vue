<template>
  <div :class="{
      'mdc-form-field': hasLabel,
      'mdc-form-field--align-end': hasLabel && alignEnd,
      'mdc-switch-wrapper': true }">
    <div :class="classes" :styles="styles" class="mdc-switch">
      <div class="mdc-switch__track" />
      <div class="mdc-switch__thumb-underlay">
        <div class="mdc-switch__thumb">
          <input
            ref="control"
            :name="name"
            :id="hash"
            :value="value"
            type="checkbox"
            role="switch"
            class="mdc-switch__native-control"
            @change="onChanged" >
        </div>
      </div>
    </div>

    <label
      v-if="hasLabel"
      :for="hash"
      class="mdc-switch-label">
      <slot>{{ label }}</slot>
    </label>
    <div>
  </div>
  </div>
</template>

<script>
// import { DispatchFocusMixin, VMAUniqueIdMixin } from '../base';
// import { RippleBase }                           from '../ripple';
import MDCSwitchFoundation                      from '@material/switch/foundation';

export default {
  name: 'mdc-switch',
  // mixins: [DispatchFocusMixin, VMAUniqueIdMixin],
  model: {
    prop: 'checked',
    event: 'change'
  },
  props: {
    checked: Boolean,
    disabled: Boolean,
    value: String,
    label: String,
    alignEnd: Boolean,
    name: String
  },
  data() {
    return {
      classes: {},
      styles: {}
    }
  },
  computed: {
    hasLabel() {
      return this.label || this.$slots.default
    },
    hash() {
      const scope = Math.floor(Math.random() * Math.floor(0x10000000)).toString() + '-';

      return scope + this._uid;
    }
  },
  watch: {
    checked(value = !!value) {
      this.foundation && this.foundation.setChecked(value)
    },
    disabled(value = !!value) {
      this.foundation && this.foundation.setDisabled(value)
    }
  },
  mounted() {
    this.foundation = new MDCSwitchFoundation({
      addClass:                 className => this.$set(this.classes, className, true),
      removeClass:              className => this.$delete(this.classes, className),
      setNativeControlChecked:  checked   => this.$refs.control.checked = checked,
      setNativeControlDisabled: disabled  => this.$refs.control.disabled = disabled,
    })
    this.foundation.init()
    this.foundation.setChecked(this.checked)
    this.foundation.setDisabled(this.disabled)
    // this.ripple = new RippleBase(this)
    // this.ripple.init()
  },
  beforeDestroy() {
    this.foundation && this.foundation.destroy()
    // this.ripple && this.ripple.destroy()
  },
  methods: {
    onChanged(event) {
      this.foundation.handleChange(event);
      this.$emit('change', event.target.checked)
    }
  }
}
</script>

<style lang="scss">
  $mdc-switch-track-width: 45px;
  $mdc-switch-track-height: 30px;
  $mdc-switch-tap-target-size: 62px;
  $mdc-switch-thumb-diameter: 28px;
  $mdc-switch-thumb-offset: -1px;

  @import "@material/switch/mdc-switch";

  .mdc-switch {
    @include mdc-switch-toggled-on-track-color(#00e5b1);
    @include mdc-switch-toggled-on-thumb-color(#ffffff);

    &__thumb {
      box-shadow: none;
    }
  }
</style>