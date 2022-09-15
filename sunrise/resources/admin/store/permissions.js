const URL = '/api/permissions';

export default {
  namespaced: true,
  state: {
    names: {},
    tree: null
  },
  mutations: {
    setTree(state, tree) {
      let names = {};

      Object.keys(tree).forEach(slug => {
        names[slug] = tree[slug].name;

        if (tree[slug].children) {
          const children = tree[slug].children;
          Object.keys(children).forEach(childSlug => {
            names[childSlug] = children[childSlug].name;
          })
        }
      })

      Vue.set(state, 'tree', tree);
      Vue.set(state, 'names', names);
    }
  },
  actions: {
    load({ state, commit })  {
      if (state.tree === null) {
        axios.get(URL)
          .then(response => {
            commit('setTree', response.data);
          });
      }
    },
  },
  getters: {
    options({ names }) {
      return Object.keys(names).map(slug => {
        return { label: names[slug], value: slug }
      })
    }
  }
}
