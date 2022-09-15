<template>
  <search-select
    class="org-select"
    v-if="organisations.length > 1"
    :clearable="false"
    :options="organisations"
    :value="selected"
    @input="selectOrgnisation"
  >
  </search-select>
</template>
<script>
import SearchSelect from 'vue-select';

export default {
  name: 'OrganisationSelect',
  components: {
    SearchSelect,
  },
  created() {
    //do something after creating vue instance
    if (this.isRootSelect) {
      this.$store.dispatch('organisation/list/load');
    }
  },
  computed: {
    isRootSelect() {
      return this.$store.getters['user/isRoot'];
    },
    selected() {
      return this.organisations.find(({ value }) => value === this.$store.getters['user/organisation'].id);
    },
    organisations() {
      const organisations = this.isRootSelect ?
        this.$store.getters['organisation/list/collection'] : this.me.organisations;
      return organisations.map((o) => {
        const value = o.id;
        let label = o.name;

        if (o.owners && o.owners.length) {
          const owner = o.owners[0];
          if (!label) {
            label = `(${o.id}) ${owner.name}`;
          }
          label = `${label} (${owner.email})`;
        }

        return { label, value };
      });
    }
  },
  methods: {
    selectOrgnisation(selected) {
      if (selected.value && selected.value !== (this.$store.getters['user/organisation'] || {}).id) {
        this.$router.push({ name: 'scholarships' })
        this.$store.dispatch('user/setWorkingOrganisation', selected.value);
        this.$store.dispatch('organisation/scholarships/load');
      }
    }
  }
}
</script>
<style lang="scss" scoped>
.org-select {
  width: 100%;
  cursor: pointer;
  /deep/ .selected-tag {
    display: inline;
    position: absolute;
    top: 2px;
    left: 0;
  }
  /deep/ .dropdown-toggle {
    width: 100%;
    overflow: hidden;
  }
}
// .org-select {
//
// }
// /deep/ .autocomplete {
//   .is-root-organisations {
//
//   }
// }
</style>
