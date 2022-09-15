import { parse, serialize } from "cookie-js";

const storeNotification = () => {
  const date = new Date;
  date.setDate(date.getDate() + 365);

  document.cookie = serialize("cookiePrivatePolicy","notified",
    {path: "/", expires: date}
  );
}

const isShowed = () => parse(document.cookie).hasOwnProperty("cookiePrivatePolicy")

export default {
  namespaced: true,
  state: {
    show: false
  },
  mutations: {
    show(state) {
      state.show = true;
    },
    hide(state) {
      state.show = false;
    }
  },
  actions: {
    notify({ commit }) {
      if(isShowed()) {
        commit("hide");
        return;
      }

      commit("show");

      storeNotification();
    }
  }
}