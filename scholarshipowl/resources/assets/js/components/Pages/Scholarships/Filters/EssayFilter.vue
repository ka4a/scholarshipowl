<template>
	<multi-select
	:options="options"
	:value="essayRequired"
	@input="val => setParam(val)"
	:searchable="false"
	:showPointer="false"
	selectedLabel=""
	:allow-empty="false"/>
</template>

<script>
import { mapState, mapActions } from "vuex";
import { OPTION_ANY, OPTION_YES, OPTION_NO } from "lib/utils/filter";
import MultiSelect from "vue-multiselect";

export default {
  components: {
    MultiSelect
  },
  data() {
    return {
      options: [OPTION_ANY, OPTION_YES, OPTION_NO]
    };
  },
  computed: {
    ...mapState({
      essayRequired: state => state.list.scholarships.filter.essayRequired
    })
  },
  methods: {
    ...mapActions({
      setFilterParam: "list/setFilterParam"
    }),
    setParam(parameter) {
      this.setFilterParam({
        nameSpace: 'scholarships',
        filterBy: 'essayRequired',
        parameter
      })

      this.$emit('filter');
    }
  }
};
</script>

<style src="./options-filter.scss" lang="scss"></style>