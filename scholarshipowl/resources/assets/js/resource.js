import Vue from "vue";
import VueResource from "vue-resource";

Vue.use(VueResource);
Vue.http.options.emulateJSON = true;

Vue.http.interceptors.push(function(req) {
  const token = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute('content');

  if(!token) throw Error("CSRF TOKEN is not defined on the page");

  req.headers.set('X-CSRF-TOKEN', token);
})

export const abortPreviouse = function(cb) {
  var old = null;
  return function(request) {
    if (old) old.abort();
    old = request;
    if (typeof cb === "function") cb(request);
  };
};

export const defaultOptions = {
  root: "/rest/v1/"
};

export const OptionsResource = Vue.resource("/rest/v1/options", {}, {
  account: { method: "GET", url: "/rest/v1/options{/id}" }
});

export const SettingsResource = Vue.resource("/rest/v1/options", {}, {
  private: { method: "GET", url: "/rest/v1/settings-private{?fields}"},
  public:  { method: "GET", url: "/rest/v1/settings-public{?fields}"},
});

export const AccountResource = Vue.resource("/rest/v1/account{/id}", {}, {
  profile:            { method: "GET",  url: "/rest/v1/account/profile{/id}" },
  profileUpdate:      { method: "PUT",  url: "/rest/v1/account/profile{/id}" },
  register:           { method: "POST", url: "/rest/v1/account/register" },
  options:            { method: "GET",  url: "/rest/v1/account/{id}/options" },
  login:              { method: "POST", url: "/rest/v1/auth/session" },
  accountInfo:        { method: "GET",  url: "/rest/v1/account-info{?fields}"},
  eligibilityInitial: { method: "GET",  url: "/rest/v1/eligibility-initial" },
});

export const UpdateUserProfile = Vue.resource("/", {}, {
  basic:        { method: "POST",  url: "/post-basic" },
  education:    { method: "POST",  url: "/post-education" },
  interests:    { method: "POST",  url: "/post-interests" },
  account:      { method: "POST",  url: "/post-account" },
  recurrence:   { method: "POST",  url: "/post-recurrence" }
});

export const ScholarshipResource = Vue.resource("/rest/v1/scholarship/", {}, {
  scholarships:                 { method: "GET",    url: "/rest/v1/scholarship/eligible" },
  sentScholarships:             { method: "GET",    url: "/rest/v1/scholarship/sent" },
  apply:                        { method: "POST",   url: "/rest/v1/application" },
  applyText:                    { method: "POST",   url: "/rest/v1/application/text/" },
  applyFile:                    { method: "POST",   url: "/rest/v1/application/file/" },
  applyImage:                   { method: "POST",   url: "/rest/v1/application/image/" },
  applyInput:                   { method: "POST",   url: "/rest/v1/application/input/" },
  applySurvey:                  { method: "POST",   url: "/rest/v1/application/survey/" },
  "applySpecial-eligibility":   { method: "POST",   url: "/rest/v1/application/special-eligibility/" },
  markFavorite:                 { method: "POST",   url: "/rest/v1/scholarship/favorite/{id}" },
  deleteText:                   { method: "DELETE", url: "/rest/v1/application/text/{id}" },
  deleteFile:                   { method: "DELETE", url: "/rest/v1/application/file/{id}" },
  deleteImage:                  { method: "DELETE", url: "/rest/v1/application/image/{id}" },
  deleteInput:                  { method: "DELETE", url: "/rest/v1/application/input/{id}" },
  unmarkFavorite:               { method: "DELETE", url: "/rest/v1/scholarship/unfavorite/{id}" },
});

export const AutocompleteResource = Vue.resource("/rest/v1/autocomplete", {}, {
  highschool:     { method: "GET",  url: "/rest/v1/autocomplete/highschool{/q}" },
  university:     { method: "GET",  url: "/rest/v1/autocomplete/college{/q}"},
  stateAndCity:   { method: "GET", url: "/rest/v1/autocomplete/state_and_city{/zip}" },
});

export const CoregResource = Vue.resource("/rest/v1/coregs", {}, {
  coregs:         { method: "GET", url: "/rest/v1/coregs{/path}{/id}" }
});

export const PopupsResource = Vue.resource("/rest/v1/popup", {}, {
  popups:         { method: "GET", url: "/rest/v1/popup{/route}{/account_id}"}
});

export const SubscriptionResource = Vue.resource("/api/v1.0/subscription/", {}, {
  cancel:         { method: "PUT", url: "/rest/v1/subscription/cancel/{id}"}
});

export const WinnersResource = Vue.resource("/rest/v1/winner/", {}, {
  winners:        { method: "GET", url: "/rest/v1/winner"},
  winner:         { method: "GET", url: "/rest/v1/winner{/id}"}
});

export const MailboxResources = Vue.resource("/rest/v1/mailbox/", {}, {
  mails:          { method: "GET", url: "/rest/v1/mailbox/?folder={type}"},
  markAsRead:     { method: "PUT", url: "/rest/v1/mailbox/{id}"},
});

export const FSetResource = Vue.resource("/rest/v1/fset", {}, {
  fset:           { method: "GET", url: "/rest/v1/fset{?fields}"}
});

export const EligibilityCache = Vue.resource("/rest/v1/eligibility_cache", {}, {
  retrive: { method: "GET", url: "/rest/v1/eligibility_cache{?fields}"},
  update:  { method: "PUT", url: "/rest/v1/eligibility_cache"}
})

export const PaymentSet = Vue.resource("/rest/v1/payment_set", {}, {
  retrive: { method: "GET", url: "/rest/v1/payment_set/{name}"}
})

export const Payment = Vue.resource("/braintree", {}, {
  retriveBraintreeToken: { method: "GET",  url: "/braintree/token"},
  sendTokenizedData:    { method: "POST", url: "/braintree"},
  applyFreemiumPackage: { method: "GET", ulr: "/apply-freemium/{id}"}
})