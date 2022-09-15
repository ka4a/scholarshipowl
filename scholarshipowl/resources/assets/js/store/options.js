import { OptionsResource } from "../resource.js";

/**
 * TODO: Move to utils
 * @param data
 * @returns {*}
 */
const convert = (data) => {
  return data ? Object.keys(data).map(key => ({ label: data[key], value: key })) : [];
};

export default {
  namespaced: true,
  state: {
    loading: {
      countries:            false,
      states:               false,
      genders:              false,
      citizenships:         false,
      ethnicities:          false,
      gpas:                 false,
      degrees:              false,
      degreeTypes:          false,
      careerGoals:          false,
      schoolLevels:         false,
      studyOnline:          false,
      studyCountries:       false,
      profileTypes:         false,
      militaryAffiliations: false,
    },
    options: {
      countries:            null,
      states:               null,
      genders:              null,
      citizenships:         null,
      ethnicities:          null,
      gpas:                 null,
      degrees:              null,
      degreeTypes:          null,
      careerGoals:          null,
      schoolLevels:         null,
      studyOnline:          null,
      studyCountries:       null,
      profileTypes:         null,
      militaryAffiliations: null,
    }
  },
  mutations: {
    setOption (state, { name, data }) {
      if (!state.options.hasOwnProperty(name)) {
        throw new Error(`Uknown option: ${name}`);
      }

      state.options[name] = data;
    },
    setLoading (state, { names, loading }) {
      (typeof names === "string" ? [names] : names).forEach(name => {
        if (!state.loading.hasOwnProperty(name)) {
          throw new Error(`Uknown option: ${name}`);
        }

        state.loading[name] = loading;
      });
    }
  },
  actions: {
    load ({ state, commit }, { name, callback }) {
      let names = Object.keys(state.options);

      if (typeof name === "string") {
        names = [name];
      }

      if (Object.prototype.toString.call(name) === "[object Array]") {
        names = name;
      }

      const load = names.filter(name => {
        return state.options.hasOwnProperty(name) &&
          !state.options[name] &&
          !state.loading[name];
      });

      if (load.length > 0) {
        commit("setLoading", { names: load, loading: true });

        OptionsResource.get({ only: load })
          .then(response => {
            if (response.status === 200 && response.data.data) {
              Object.keys(response.data.data).forEach(name => {
                commit("setOption", { name, data: response.data.data[name] });
              });

              if(callback) setTimeout(callback, 100);
            }

            commit("setLoading", { names: load, loading: false });
          })
          .catch((response) => {
            commit("setLoading", { names: load, loading: false });
          });

          return;
      }

      if(callback) setTimeout(callback, 100);
    }
  },
  getters: {
    yesOrNo () {
      return [
        { value: 1, label: "Yes"},
        { value: 0, label: "No"},
      ];
    },
    label: ({ options }) => (name, value) => {
      if (!options.hasOwnProperty(name))
        throw new Error(`Unknown option ${name}`);
      return options[name][value];
    },
    option: (function(){
      var cache = {};
      return ({ options }) => (name, value) => {
        if (!options.hasOwnProperty(name))
          throw new Error(`Unknown option ${name}`);

        if (typeof value === "undefined" || value === null)
          return null;

        if (!cache.hasOwnProperty(name))
          cache[name] = {};

        if (!cache[name].hasOwnProperty(value)) {
          cache[name][value] = { value, label: options[name][value] };
        }

        return cache[name][value];
      };
    })(),
    srcLoaded({ loading }) {
      return Object.values(loading).indexOf(true) === -1;
    },
    countries ({ options }) {
      return convert(options.countries);
    },
    states ({ options }) {
      return convert(options.states);
    },
    genders ({ options }) {
      return convert(options.genders);
    },
    citizenships ({ options }) {
      return convert(options.citizenships);
    },
    ethnicities ({ options }) {
      return convert(options.ethnicities);
    },
    gpas ({ options }) {
      return convert(options.gpas);
    },
    degrees ({ options }) {
      return convert(options.degrees);
    },
    degreeTypes ({ options }) {
      return convert(options.degreeTypes);
    },
    careerGoals ({ options }) {
      return convert(options.careerGoals);
    },
    schoolLevels ({ options }) {
      return convert(options.schoolLevels);
    },
    studyOnline ({ options }) {
      return convert(options.studyOnline);
    },
    studyCountries ({ options }) {
      return convert(options.studyCountries);
    },
    profileTypes ({ options }) {
      return convert(options.profileTypes);
    },
    militaryAffiliations ({ options }) {
      return convert(options.militaryAffiliations);
    }
  }
};
