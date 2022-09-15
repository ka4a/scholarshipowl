import { CoregResource } from "../resource";

const prioritizeCoregs = coregs => {
  let above = [],
    below = [];

  coregs.forEach(coreg => {
    (/a$/g.test(coreg.position) ? above : below).push(coreg);
  });

  const compare = (a, b) => {
    return Number(b.position.match(/\d/g)) - Number(a.position.match(/\d/g));
  };

  const alphabeticSort = (a, b) => {
    a = a.name.toLowerCase();
    b = b.name.toLowerCase();

    if(a < b) return -1;
    if(a > b) return 1;
    return 0;
  };

  above.sort(alphabeticSort).sort(compare);
  below.sort(alphabeticSort).sort(compare);

  return {
    above,
    below
  };
};

export default {
  namespaced: true,
  state: {
    coregsData: {
      above: [],
      below: []
    },
  },
  mutations: {
    ["SET_COREGS"](state, data) {
      if(!data) throw Error("Data not defined!");

      state.coregsData = data;
    }
  },
  actions: {
    getCoregs({ commit }, options) {
      if (!(options && typeof options === "object"))
        throw Error("Please provide cored route name!");

      return CoregResource.coregs(options)
        .then(response => {
          if(response.status === 200 && response.data.data) {
            commit("SET_COREGS", prioritizeCoregs(response.data.data));
          }
        });
    }
  }
};
