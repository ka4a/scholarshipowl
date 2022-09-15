import Vue from 'vue';
import axios from 'axios';
import { GridStore, ItemStore } from 'lib/store/factory.js';

const winnerImages = {
  namespaced: true,
  state: {
    images: {},
  },
  mutations: {
    setImage(state, { id, image }) {
      Vue.set(state.images, id, image);
    }
  },
  actions: {
    load({ state, commit }, id) {
      return axios.get(`/api/application_file/${id}/file`, { responseType: 'blob' })
        .then(({ data }) => {
          const reader = new FileReader();
          reader.onload = () => {
            commit('setImage', { id, image: reader.result })
          }
          reader.readAsDataURL(data);
        })
    }
  }
}

const winnerPage = {
  namespaced: true,
  ...ItemStore('application_winner', {
    updateMethod: 'POST',
    include: ['application', 'scholarship', 'scholarship_winner'],
  })
}

const search = GridStore('application_winner', {
  baseURL: '/api/',
  include: ['application', 'scholarship', 'scholarship_winner'],
})

export default {
  namespaced: true,
  modules: {
    winnerImages,
    winnerPage,
    search,
  },
  state: {},
  mutations: {},
  actions: {}
}
