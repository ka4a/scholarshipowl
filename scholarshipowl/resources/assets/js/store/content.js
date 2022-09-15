const imgMembershipActivatedMale   = require("components/Common/Modals/img/membership-activated-male.png");
const imgMembershipActivatedFemale = require("components/Common/Modals/img/membership-activated-female.png");

const imgUpdatedAccount            = require("components/Common/Modals/img/account-updated.png")
const imgFemaleAccountCancelation  = require("components/Common/Modals/img/account-female.png");
const imgMaleAccountCancelation    = require("components/Common/Modals/img/account-male.png");

const titleAccountUpdated = 'Your changes are saved!';
const exceptionMessage = `<br><p>Something went wrong.</p><p>Please try again later.</p>`;
export const successMessDefault = `<h3>Welcome to ScholarshipOwl!</h3>
<p><b>Your membership is now active.</b></p>
<p>Enjoy our service!</p>`;

const buttons = ({ subscriptionId }) => {
  if(!subscriptionId) throw Error('Please provide subscription id');

  return {
    keep: {
      text: 'keep active'
    },
    cancel: {
      text: 'cancel now',
      subscriptionId: `${subscriptionId}`
    }
  }
}

const SUBSCRIPTION_CANCEL_FREE_TRIAL = 'memberships.freeTrial.cancel_subscription';
const SUBSCRIPTION_CANCEL =            'memberships.cancel_subscription_text';

// TODO Move it back when resolve Webpack issue
// import { CANCELATION_FREE_TRIAL, CANCELATION_BASIC,
  // SUCCESS_BASIC, ACCOUNT_UPDATE } from "store/modal";

const CANCELATION_FREE_TRIAL = "canselation-free-trial";
const CANCELATION_BASIC = "canselation-basic";
const SUCCESS_BASIC = "success-basic";
const ACCOUNT_UPDATE = "account-update";

import { SettingsResource } from "resource";

const retriveAndFormatContentSet = (ctrlName, rootGetters) => {
  return new Promise((resolve, reject) => {
      SettingsResource.private({fields: [ctrlName]})
        .then(response => {
          if(response.body.status === 200 && response.body.data) {

            let subscriptionId = rootGetters['account/membership'].subscriptionId,
                gender = rootGetters['account/gender'];

            resolve({
              button: buttons({ subscriptionId }),
              img: gender && gender === 'female'
                ? imgFemaleAccountCancelation
                : imgMaleAccountCancelation,
              html: response.body.data[ctrlName],
            });
          }
        })
        .catch(err => {
          let gender = rootGetters['account/gender'];

          resolve({
            html: exceptionMessage,
            img: gender && gender === 'female'
                ? imgFemaleAccountCancelation
                : imgMaleAccountCancelation
          })
        })
  })
}

const modals = {
  [CANCELATION_FREE_TRIAL]({ rootGetters }) {
    return retriveAndFormatContentSet(
      SUBSCRIPTION_CANCEL_FREE_TRIAL,
      rootGetters
    )
  },
  [CANCELATION_BASIC]({ rootGetters }) {
    return retriveAndFormatContentSet(
      SUBSCRIPTION_CANCEL,
      rootGetters
    )
  },
  [ACCOUNT_UPDATE]() {
    return Promise.resolve({
      img: imgUpdatedAccount,
      title: titleAccountUpdated
    })
  },
  [SUCCESS_BASIC]({ rootGetters }) {
    let gender = rootGetters['account/gender'],
        img = gender && gender === 'female'
          ? imgMembershipActivatedFemale
          : imgMembershipActivatedMale;

    return Promise.resolve({ img })
  }
}

export default {
  namespaced: true,
  state: {
    modals: {
      [CANCELATION_FREE_TRIAL]: null,
      [CANCELATION_BASIC]: null
    }
  },
  mutations: {
    "SET_MODAL_CONTENT" ({ modals }, { name, content }) {
      if(!content) throw Error("Please provide correct content");

      modals[name] = content;
    },
  },
  actions: {
    getModalContent({ state, commit, rootGetters }, modalName) {
      return new Promise((resolve, reject) => {
        modals[modalName]({ rootGetters })
          .then(content => {
            commit("SET_MODAL_CONTENT", {name: modalName, content});
            resolve(content);
          })
      })
    }
  },
};
