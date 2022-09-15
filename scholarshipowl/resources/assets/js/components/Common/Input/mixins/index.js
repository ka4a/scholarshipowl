const placeholder = {
  data() {
    return {
      placeholderDinamic: ''
    }
  },
  beforeMount() {
    this.placeHolder(this.placeholder);
  },
  methods: {
    placeHolder(placeholder, isAppear = true) {
      if(!placeholder || typeof placeholder !== 'string') return;

      this.placeholderDinamic = placeholder;

      this.placeHolder = () => {
        isAppear
          ? this.placeholderDinamic = ''
          : this.placeholderDinamic = placeholder;

        isAppear = !isAppear;
      }
    }
  }
}

export {
  placeholder as placeholderMixin
}