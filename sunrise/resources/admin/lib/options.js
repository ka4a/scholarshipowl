/**
 * Helper for working this multiselect options.
 */
export default class SelectOptions {
  constructor(options, trackBy = 'id', label = 'name') {
    this.options = options;
    this.trackBy = trackBy;
    this.label = label;
  }
  /**
   * Get option id by option object or value.
   */
  id (option) {
    if (Array.isArray(this.options)) {
      for (var i = 0; i < this.options.length; i++) {
        if (option === this.options[i]) {
          return option[this.trackBy];
        }
      }
    }

    if (typeof this.options === 'object') {
      const optionKeys = Object.keys(this.options);
      for (let i = 0; i < optionKeys.length; i++) {
        if (option === this.options[optionKeys[i]]) {
          return option[this.trackBy];
        }
      }
    }

    throw new Error('Option not found');
  }
  /**
   * Get all options
   */
  all() {
    return this.options;
  }
  /**
   * Find option label by id.
   */
  label(id) {
    const option = this.option(id);

    if (option && option[this.label]) {
      return option[this.label];
    }
  }
  /**
   * Find option by id
   */
  option(id) {
    if (Array.isArray(this.options)) {
      for (let i = 0; i < this.options.length; i++) {
        if (id + '' === this.options[i][this.trackBy] + '') {
          return this.options[i];
        }
      }
    }

    if (typeof this.options === 'object') {
      const optionKeys = Object.keys(this.options);
      for (let i = 0; i < optionKeys.length; i++) {
        if (id + '' === this.options[optionKeys[i]][this.trackBy] + '') {
          return this.options[optionKeys[i]];
        }
      }
    }

    throw new Error('Option not found for id: ' + id);
  }

  /**
   * Filter options
   */
  filter(cb) {
    if (Array.isArray(this.options)) {
      return new SelectOptions(
        this.options.filter(cb),
        this.trackBy,
        this.label
      )
    }

    if (typeof this.options === 'object') {
      let newOptions = Object.assign({}, this.options);

      Object.keys(newOptions).forEach((key) => {
        if (!cb(newOptions[key])) {
          delete newOptions[key];
        }
      })

      return new SelectOptions(newOptions, this.trackBy, this.label);
    }

    throw new Error('Options is not defined!');
  }
}
