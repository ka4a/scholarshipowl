import unloadStore from "lib/before-unload-store";

export default {
  install(Vue, options) {
    Vue.prototype.unloadStore = unloadStore;
  }
}