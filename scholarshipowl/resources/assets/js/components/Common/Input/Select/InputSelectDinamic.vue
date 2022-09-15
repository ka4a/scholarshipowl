<template>
  <input-select-base
    :class="{'input-select-dinamic_empty': !searchQueryLength}"
    :placeholder="placeholderDinamic"
    :options="options"
    :value="value"
    :taggable="taggable"
    :loading="loading"
    :searchable="true"
    :name="name"
    :optionsLimit="optionsLimit"
    :type="type"
    v-on="$listeners"
    v-bind="$attrs"
    @open="openHolder"
    @close="closeHolder"
    @tag="tag"
    @search-change="search" />
</template>

<script>
  import { placeholderMixin }     from "components/Common/Input/mixins";
  import { debounce }             from "lib/utils/utils";
  import { AutocompleteResource } from "resource";
  import { alphaNumeric }         from "lib/utils/format";
  import InputSelectBase          from "components/Common/Input/Select/InputSelectBase.vue";

  const MAX_LENGTH = 60;

  const updateSelectedOptions = (options, selectedValues) => {
    if(!options || !selectedValues)
      throw Error('Please provide correct parameters');

    return options.map(item => {
      item.choosen = false;

      if(selectedValues.indexOf(item.label) > -1) {
        item.choosen = true;
      }

      return item;
    })
  }

  export default {
    name: 'input-select-dinamic',
    mixins: [placeholderMixin],
    components: {
      InputSelectBase
    },
    props: {
      name:           {type: String,  required: true},
      value:          {type: Object,  required: true},
      selectedValues: {type: Array,   default: []},
      taggable:       {type: Boolean, default: true},
      placeholder:    {type: String},
      optionsLimit:   {type: Number,  default: 150},
      type:           {type: String}
    },
    watch: {
      selectedValues(val) {
        if(!val.length) return;

        this.options = updateSelectedOptions(
          this.options, val
        );
      },
    },
    mounted() {
      this.input = this.$el.querySelector("input");

      if(!this.input) return;

      this.input.addEventListener("touchstart", function(ev) {
        ev.target.focus();
      })
    },
    data() {
      return {
        loading: false,
        options: [],
        input: null,
        searchQueryLength: 0,
      }
    },
    methods: {
      openHolder() {
        this.placeHolder();

        setTimeout(() => {
          const input = this.$el.querySelector("input");

          if(!input) return;

          input.addEventListener("touchstart", function(ev) {
            ev.target.focus();
          })

          let event = new Event('touchstart', {
            'bubbles': true,
            'cancelable': true
          });

          input.dispatchEvent(event);
        })
      },
      closeHolder() {
        this.input.value = "";
        this.searchQueryLength = 0;

        this.placeHolder()
      },
      formatAndSetInputValue(val) {
        let formatedVal = alphaNumeric(val);

        let input = this.$el.querySelector('input');

        input.value = formatedVal;

        let event = new Event('input', {
          'bubbles': true,
          'cancelable': true
        });

        input.dispatchEvent(event);

        return formatedVal
      },
      search(val) {
        this.searchQueryLength = this.input.value.length;

        this.autocompleteSearch(
          this.name,
          this.formatAndSetInputValue(val)
        )
      },
      tag(val) {
        let item = {
          label: val,
          value: val
        };

        this.$emit('input', item);
      },
      autocompleteSearch(option, q) {
        if (!q || !q.trim()) return;

        this.loading = true;

        debounce(() => {
          AutocompleteResource[option]({q})
          .then((response) => {
            if (response.status === 200 && response.data.data) {
              if(response.data.data.length > MAX_LENGTH) {
                response.data.data.splice(
                  MAX_LENGTH,
                  response.data.data.length - 1
                );
              }

              response.data.data = response.data.data.map(item => {
                return {label: item.text, value: item.id}
              })

              if(this.selectedValues && this.selectedValues.length) {
                response.data.data = updateSelectedOptions(
                  response.data.data,
                  this.selectedValues
                );
              }

              this.options = response.data.data;
            }

            this.loading = false;
          })
          .catch(() => {
            this.loading = false;
          });
        })
      },
    }
  }
</script>

<style lang="scss">
  .input-select-dinamic_empty {
    .multiselect__content-wrapper {
      display: none;
    }
  }
</style>