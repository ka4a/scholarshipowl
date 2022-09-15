import { PaymentSet } from "resource";

const SET_STATE = "SET_STATE";

export const PACKAGES = "packages";
const PACKAGE = "package";
const PAYMENT_SET = "payment_set";
const OPTIONS = "package_common_option";

const optionsByPackageID = (packageID, commonOptions) => {
  const optionIDs = Object.keys(commonOptions);

  return optionIDs.map(id => {
    const { text, status } = commonOptions[id];

    return {text, status: Number(status[packageID])}
  })
}

const optionsByPackages = (packageIDs, commonOptions) => {
  let options = {};

  packageIDs.forEach(id => {
    options[id] = optionsByPackageID(id, commonOptions);
  })

  return options;
}

export default {
  namespaced: true,
  state: {
    [PACKAGES]: null,
    [PAYMENT_SET]: null,
    [PACKAGE]: null
  },
  getters: {
    options(state) {
      if(!state[PAYMENT_SET]) return null;

      return state[PAYMENT_SET][OPTIONS]
    },
    optionsPerPackage({ packages }, { options }) {
      if(!packages || !options) return null;

      return optionsByPackages(packages.map(pac => pac.package_id), options);
    },
    title({ payment_set }) {
      if(!payment_set) return null;

      return payment_set.popupTitleDisplay;
    },
    mobileSpecialOfferOnly({ payment_set }) {
      if(!payment_set) return null;

      return payment_set.mobileSpecialOfferOnly;
    }
  },
  mutations: {
    [SET_STATE](state, { stateName, stateData }) {
      if(!stateName || stateData === undefined)
        throw Error("Please provide correct state name or/and data");

      if(!state.hasOwnProperty(stateName))
        throw Error("Provided wrong state name");

      state[stateName] = stateData;
    }
  },
  actions: {
    getData({ commit }, name) {
      return PaymentSet
        .retrive({name})
        .then(response => {
          if(response.body && response.body.status === 200) {
            const data = response.body.data;

            if(!data || !Object.keys(data).length)
              throw Error("Response is empty. Please check it.");

            [PACKAGES, PAYMENT_SET].forEach(field => {
              if(!data[field])
                throw Error("Hmmm... Looks like field name is changed on server...");

              commit(SET_STATE, {
                stateName: field,
                stateData: data[field]
              })
            })
          }

          return response;
        })
    }
  }
}

