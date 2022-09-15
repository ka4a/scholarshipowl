export default {
  track: function(event, properties, cb) {
    if (window.SOWLMixpanelTrack) {
      window.SOWLMixpanelTrack(event, properties, cb);
    }
  }
};

export const REGISTER_BUTTON_CLICK   = "RegisterButton click";
export const REGISTER_2_BUTTON_CLICK = "Register2Button click";
export const REGISTER_3_BUTTON_CLICK = "Register3Button click";
export const APPLY_OWL_BTN_CLK = "Apply on ScholarshipOwl button click";
export const APPLY_EXTERNAL_BTN_CLK  = "Apply on external website button click";
export const SEE_MORE_MEMBERSHIP_OPT_CLK = "See More Membership Options Click";
export const PACKAGE_BTN_CLK = "PackageButton click";
export const PAYMENT_MODAL_OPEN = "PaymentPopup open";
export const PAYMENT_MODAL_OPEN_MOB = "PaymentPopup open mobile";
export const PAYMENT_MODAL_CLOSE = "PaymentPopup closed";
export const PAYMENT_MODAL_CLOSE_MOB = "PaymentPopup closed mobile";
export const PAYMENT_REDIRECT = "PaymentRedirect";
export const PAYMENT_BTN_CLK = "PaymentPopupForm Button Click";