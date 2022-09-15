import { isMobile as defineIsMobile } from "lib/utils/utils";
import { ROUTES } from "router.js";

export const toPayment = () => {
  if(defineIsMobile()) {
    window.location = ROUTES.UPGRADE_MOBILE;
  } else {
    window.invokeUpgradeModal && window.invokeUpgradeModal();
  }
}