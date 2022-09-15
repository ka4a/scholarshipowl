<script type="javascript">
export default {
  name: "CommonInteractionInput",
  props: {
    value:        { required: true },
    name:         { type: String, required: true },
    id:           { type: String, required: true },
    type:         { type: String, required: true },
    tag:          { type: String, default: "input" },
    placeholder:  { type: String, default: "" },
    valid:        { type: Boolean, default: true },
    best:         { type: Boolean, default: false },
  },

  /**
   * @param createElement
   * @returns {*}
   */
  render (createElement) {
    return createElement(this.tag, {
      domProps: {
        id: this.id,
        type: this.type,
        value: this.value,
        name: this.name,
        placeholder: this.placeholder,
      },
      class: {
        "best": this.best,
        "interaction-item__input": true,
        "interaction-item__input_error": !this.valid,
      },
      on: {
        input: (event) => {
          this.$emit("input", event.target.value);
        },
        blur: (event) => {
          this.$emit("blur", event.target.value);
        }
      },
    });
  }
};
</script>
