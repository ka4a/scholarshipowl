// import { debounce } from "lodash";
import { debounce } from "lib/utils/utils";
import { AutocompleteResource } from "resource.js";
/**
 * Create http query from any object.
 *
 * @param data
 * @param amp
 * @returns {*}
 */
export const httpQuery = function httpQuery(data, amp = "&") {
  if (typeof data === "object") {
    return Object.keys(data).map(param => param + "=" + data[param]).join(amp);
  }

  return "";
};