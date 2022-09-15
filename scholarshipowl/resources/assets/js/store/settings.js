import { isPropDefined } from "lib/utils/utils";

let SOWLStorage = window.SOWLStorage || window.SOWLStorageOptimized;

export default {
  namespaced: true,
  state: {
    settings: typeof SOWLStorage !== undefined ? SOWLStorage.settings : null,
  },
  getters: {
    showPhone({ settings }) {
      return settings !== null && settings.content ? settings.content.showPhone : null;
    },
    phoneNumber({ settings }) {
      return settings !== null && settings.content ? settings.content.phoneNumber : null;
    },
    notifIOsApp({ settings }) {
      if(!settings) return false;

      return isPropDefined(settings, 'scholarships.mobile_app_ad');
    }
  }
};
