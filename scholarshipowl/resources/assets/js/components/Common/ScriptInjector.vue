<script>
let options = {
  attrs: {
    type: "text/javascript"
  }
}
export default {
  created() {
    if(this.id) {
      options.attrs["id"] = this.id;
    }

    if(this.async) {
      options.attrs["async"] = this.async;
    }

    options.attrs["src"] = this.source;
  },
  render: function(createElement) {
    return this.source && createElement("script", options);
  },
  props: {
    source: {type: String, required: true},
    id: {type: String },
    async: {type: Boolean, default: true}
  }
};
</script>
