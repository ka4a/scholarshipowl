import { PopupsResource } from "resource.js";
import { parse, serialize } from "cookie-js";

const NOT_SHOW = 0;
const SHOW_BEFORE = 1;
const SHOW_AFTER = 2;
const SHOW_BOTH = 3;
const SHOW_EXIT = 4;

const setDisplayCount = (popupName, displayCount) => {
  document.cookie = serialize(popupName, displayCount,
    {path: "/", expires: (() => { let date = new Date; date.setDate(date.getDate() + 365);})()}
  );
}

/**
 * Fetch modal data related to each applied client side
 * route.
 * @param  {Number}   accountId user account id
 * @param  {String}   path      path to
 * @param  {Function} dispatch  dispatch vue store action
 * which will be called to set retrived data to store
 * @return {Undefiend}
 */
export const fetchModalData = (accountId, path, dispatch) => {
  const reqOpts = {
    route: path.replace("/", "")
  };

  if(accountId) {
    reqOpts['account_id'] = accountId;
  }

  PopupsResource.popups(reqOpts)
    .then(response => {
      if(response.status === 200 && response.data.data
        && response.data.data.length) {

        let popup = response.data.data[0],
          whenDisplay = Number(popup.popupDisplay);

        const { popupTitle:title, popupText:text } = popup;

        // check is should show
        if(whenDisplay === NOT_SHOW) return;

        let parsedCookies = parse(window.document.cookie),
          popupName = this.popupName = "sowl-pop-up-" + popup.popupId;

        if(!parsedCookies.hasOwnProperty(popupName)) {
          setDisplayCount(popupName, popup.popupDisplayTimes);
        }

        if(!Number(parse(window.document.cookie)[popupName])) return;

        if(whenDisplay === SHOW_BEFORE || whenDisplay === SHOW_BOTH) {
          setTimeout(() => {
            dispatch("modal/showModal", {
              modalName: "promotion",
              content: {title, text: [text]}
            })
            setDisplayCount(popupName, Number(parse(window.document.cookie)[popupName]) - 1);
          }, popup.popupDelay * 1000);
        }

        if(whenDisplay === SHOW_AFTER || whenDisplay === SHOW_EXIT || whenDisplay === SHOW_BOTH) {
          window.addEventListener("beforeunload", function (e) {

            dispatch("modal/showModal", {
              modalName: "promotion",
              content: {title, text: [text]}
            })

            setDisplayCount(popupName, Number(parse(window.document.cookie)[popupName]) - 1);

            let confirmationMessage = "\o/";
            e.returnValue = confirmationMessage;
            return confirmationMessage;
          });
        }
      }
    });
}