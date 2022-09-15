import { Payment } from "resource";
import braintreeClient from "braintree-web/client";
import braintreeHostedFields from "braintree-web/hosted-fields";
import braintreePayPal from "braintree-web/paypal";
import { dataCollector } from "braintree-web";

const BRAIN_TREE = 'brainTree';
const SET_STATE = 'setState';

const createPaymentClient = (constructor, token) => constructor.create({authorization: token}); //'sandbox_zjdmwd8p_67tmbjvth37qzdj7'

const paymentClientConsumerInstances = {
  [BRAIN_TREE] : {
    hostedFields: (client, options) => braintreeHostedFields.create({client, ...options}),
    payPal: (client, options) => braintreePayPal.create({client}),
    dataCollector: (client, options) => dataCollector.create({client, ...options})
  }
}

const initPaymentProcessor = (paymentClientConsumers) => {
  return Payment.retriveBraintreeToken()
    .then(res => {
      if(!res.body || res.body.status !== 200 || !res.body.data)
        throw Error("Error occured during payment token retriving");

      if(!res.body.data.token)
        throw Erro("Payment token is not defined");

      return res.body.data.token;
    })
    .then(token => createPaymentClient(braintreeClient, token))
    .then(clientInstance => {
      return Promise.all(
        paymentClientConsumers
          .map(method => paymentClientConsumerInstances[BRAIN_TREE][method.name](
            clientInstance,
            method.options
          ))
      )
    })
    .catch(error => {throw Error(error)})
}

export default {
  namespaced: true,
  state: {
    [BRAIN_TREE]: {
      hostedFieldsInstance: null,
      payPalInstance: null,
      dataCollectorInstance: null
    }
  },
  getters: {
    brainTreePMIsInitialized(state) {
      return Object
        .keys(state[BRAIN_TREE])
        .every(key => !!state[BRAIN_TREE][key])
    }
  },
  mutations: {
    [SET_STATE](state, { stateName, stateData, rootDir }) {
      if(!stateName || stateData === undefined)
        throw Error("Please provide correct state name or/and data");

      if(!rootDir) rootDir = state;

      rootDir[stateName] = stateData;
    }
  },
  actions: {
    initPaymentProcessing({ commit, state }, { processingServiceName = BRAIN_TREE, paymentClientConsumers }) {
      if(!paymentClientConsumers) throw Error("Please provide payment type");

      if(!Array.isArray(paymentClientConsumers || !paymentClientConsumers.length))
        throw Error("paymentClientConsumers parameter should not empty array");

      if(processingServiceName === BRAIN_TREE) {
        return initPaymentProcessor(paymentClientConsumers).then(instances => {
          paymentClientConsumers.forEach(method => {
            const indexByName = paymentClientConsumers.map(method => method.name).indexOf(method.name);

            commit([SET_STATE], {
              rootDir: state[processingServiceName],
              stateName: `${method.name}Instance`,
              stateData: instances[indexByName]
            })
          })

          return instances;
        })
      }
    }
  }
}