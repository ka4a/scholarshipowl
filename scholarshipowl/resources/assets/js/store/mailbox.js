import { objectToArray } from "lib/utils/utils";
import { setState } from "lib/utils/store";
import { sortByDate } from "lib/utils/sort";
import { addDayMonthYearFields } from "lib/utils/date";
import { MailboxResources } from "resource";

export const INBOX = "INBOX";
export const SENT = "SENT";

export default {
  namespaced: true,
  state: {
    mailState: null,
    currentMail: null,
    [INBOX]: null,
    [SENT]: null,
  },
  getters: {
    stateMails(state) {
      return state.mailState && state[state.mailState]
    },
    mails(state, getters, { list }) {
      return list.mailbox.result
        ? list.mailbox.result
        : state[state.mailState];
    }
  },
  mutations: {
    setState
  },
  actions: {
    setMails({ state, commit, dispatch }, type) {
      if(!type || typeof type !== "string")
        throw Error("Please provide correntc type");

      commit("setState", { stateName: "mailState", data: type });

      if(state[type] !== null && state[type].length) {
        commit("setState", {
          stateName: "currentMail",
          data: state[state['mailState']][0]
        });

        return new Promise((resolve) => {
          resolve();
        });
      }

      return MailboxResources.mails({ type: type.toLowerCase() })
        .then(response => {
          if(response.status === 200 && response.body.data) {

            let convertedDecoratedMailList =
              objectToArray(response.body.data);

            convertedDecoratedMailList =
              sortByDate(convertedDecoratedMailList, true);

            commit("setState", {
              stateName: type,
              data: convertedDecoratedMailList
            });

            if(!convertedDecoratedMailList.length) return;

            commit("setState", {
              stateName: "currentMail",
              data: state[state['mailState']][0]
            });
          }
        });
    },
    markAsRead({ state, commit }, mail) {
      commit("setState", {
        stateName: "currentMail",
        data: mail
      });

      let currentMails = state[state['mailState']];

      if(currentMails === null || !currentMails.length || mail.isRead) return;

      MailboxResources.markAsRead({ id: mail.emailId, isRead: 1 }, {})
        .then(response => {
          if(response.status === 200 && response.body.data) {
            commit("account/READ_MAIL", null, {root: true});
            mail.isRead = true;
          }
        })
    }
  }
};