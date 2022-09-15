import VueRouter from "vue-router";
import Vue from "vue";
import mixpanel from "lib/mixpanel";
import store from "store"

import { fetchModalData } from "components/Common/Modals/promotion";
import beforeUnloadStore from "lib/before-unload-store";
import { capitalize } from "lodash";

import LayoutRegister from "components/LayoutRegister.vue";
import LayoutLanding from "components/Landing.vue";
import LayoutBasic from "components/LayoutBasic.vue";

export const ROUTES = {
  // first level
  REGISTER:             "/register",
  REGISTER_2:           "/register2",
  REGISTER_3:           "/register3",
  PLANS:                "/plans",
  MY_ACCOUNT:           "/my-account",
  SCHOLARSHIPS:         "/scholarships",
  MAIL_BOX:             "/mailbox",
  WINNERS:              "/winners",
  SELECT:               "/select",
  UPGRADE_MOBILE:       "/upgrade-mobile",

  //second
  SUCCESS:              "success",
  FREEMIUM_SUCCESS:     "freemium-success",
  FREEMIUM_NO_CREDITS:  "freemium-no-credits",
  NO_MATCHES:           "no-matches",
  FAILURE:              "failure",
  NO_FAVOURITES:        "no-favourites",
  NO_SENT:              "no-sent",
  NO_NEW:               "no-new",
  NO_AUTHORIZED:        "no-authorized",
  WON:                  "won",
  MISSED:               "missed",
  AWARDED:              "awarded",
  WINNER_CHOSEN:        "winner-chosen"
};

Vue.use(VueRouter);

/* eslint-disable no-undef */
const Page = (page) => {
  return () => System.import(`components/Pages/${page}.vue`);
};
/* eslint-enable no-undef */

const pageViewTrack = (from, to) => {
  const list = [
    ROUTES.REGISTER,
    ROUTES.REGISTER_2,
    ROUTES.REGISTER_3,
    ROUTES.MY_ACCOUNT,
    ROUTES.MAIL_BOX,
    ROUTES.PLANS,
    "/lp/double-your-scholarship",
    "/lp/facebooksignup"
  ]

  if(list.includes(to)) {
    mixpanel.track(`pageView`, {
      url: to
    });
  }

  if(!/scholarships\/.+/g.test(from) && to === ROUTES.SCHOLARSHIPS) {
    mixpanel.track(`pageView`, {
      url: to
    });
  }
}

const retriveRelatePathData = path => {
  const list = [
    ROUTES.REGISTER,
    ROUTES.REGISTER_2,
    ROUTES.REGISTER_3,
    ROUTES.MY_ACCOUNT,
    ROUTES.MAIL_BOX,
    ROUTES.WINNERS
  ]

  if(list.includes(path)) {
    const account = store.state.account.account;

    fetchModalData(
      (account && account.accountId),
      path,
      store.dispatch
    )
  }
}

export const lastPathPartName = pathResource => {
  let routePathLevels = pathResource.split("/");

  return routePathLevels[routePathLevels.length - 1];
}

export const routes = {
  login: "/",
  logout: "/logout",
  register: "/register",
}

const eventByRouteName = {
  [ROUTES.FREEMIUM_SUCCESS]:    "Freemium Application Sent Open",
  [ROUTES.FREEMIUM_NO_CREDITS]: "Freemium Application Sent Open",
  [ROUTES.SUCCESS]:             "Application Sent",
  [ROUTES.WON]:                 "Application Won Screen",
  [ROUTES.MISSED]:              "Application Missed Screen",
  [ROUTES.AWARDED]:             "Application Awarded Screen",
  [ROUTES.WINNER_CHOSEN]:       "Winner Chosen Screen",
}

const router = new VueRouter({
  mode: "history",
  routes: [
    {
      path: ROUTES.REGISTER,
      component: LayoutRegister,
      children: [
        { path: "", component: Page("Register")}
      ]
    },
    {
      path: ROUTES.REGISTER_2,
      component: LayoutRegister,
      children: [
        { path: "", component: Page("Register")}
      ]
    },
    {
      path: ROUTES.REGISTER_3,
      component: LayoutRegister,
      children: [
        { path: "", component: Page("Register")}
      ]
    },
    {
      path: ROUTES.PLANS,
      component: LayoutBasic,
      props: {footerType: "MiniFooter", headerType: "MiniHeader"},
      children: [
        { path: "", component: Page("Plans")}
      ]
    },
    {
      path: ROUTES.MY_ACCOUNT,
      component: LayoutBasic,
      children: [
        { path: "", component: Page("MyAccount/MyAccountLayout")}
      ]
    },
    {
      path: ROUTES.SCHOLARSHIPS,
      component: Page("Scholarships/ScholarshipsLayout"),
      children: [
        { path: "",                         component: Page("Scholarships/Details") },
        { path: ROUTES.SUCCESS,             component: Page("Scholarships/Notifications/NotificationHolder") },
        { path: ROUTES.FREEMIUM_SUCCESS,    component: Page("Scholarships/Notifications/NotificationHolder") },
        { path: ROUTES.FREEMIUM_NO_CREDITS, component: Page("Scholarships/Notifications/NotificationHolder") },
        { path: ROUTES.NO_MATCHES,          component: Page("Scholarships/Notifications/NotificationHolder") },
        { path: ROUTES.FAILURE,             component: Page("Scholarships/Notifications/NotificationHolder") },
        { path: ROUTES.NO_FAVOURITES,       component: Page("Scholarships/Notifications/NotificationHolder") },
        { path: ROUTES.NO_SENT,             component: Page("Scholarships/Notifications/NotificationHolder") },
        { path: ROUTES.NO_NEW,              component: Page("Scholarships/Notifications/NotificationHolder") },
        { path: ROUTES.NO_AUTHORIZED,       component: Page("Scholarships/Notifications/NotificationHolder") },
        { path: ROUTES.WON,                 component: Page("Scholarships/Notifications/NotificationHolder") },
        { path: ROUTES.MISSED,              component: Page("Scholarships/Notifications/NotificationHolder") },
        { path: ROUTES.AWARDED,             component: Page("Scholarships/Notifications/NotificationHolder") },
        { path: ROUTES.WINNER_CHOSEN,       component: Page("Scholarships/Notifications/NotificationHolder") },
      ]
    },
    {
      path: ROUTES.MAIL_BOX,
      component: Page("MailBox/MailBoxLayout")
    },
    {
      path: ROUTES.WINNERS,
      component: LayoutBasic,
      children: [
        { path: "", component: Page("Winners")}
      ]
    },
    {
      path: "/lp",
      component: LayoutLanding,
      children: [
        {
          path: "double-your-scholarship",
          component: Page("DoublePromotion")
        },
        {
          path: "facebooksignup",
          component: Page("LP/Facebook")
        }
      ]
    }
  ],
  scrollBehavior() {
    return {x: 0, y: 0}
  }
});

const callAfterDefinition = ((interval, timoutTime = 500) => {
  return (variable, callback) => {
    interval = setInterval(() => {
      if(!variable()) return;
      callback()
      clearInterval(interval);
    }, timoutTime)
  }
})()

router.beforeEach((to, from, next) => {
  /**
   * Trigger facebook event 'Lead-ALL'
   */
  if(to.path === ROUTES.REGISTER_2) {
    callAfterDefinition(
      () => window.fbq,
      () => window.fbq('track', 'Lead-ALL')
    )
  }

  /**
   * Non registered user guard. If user is not registered and want to go to /register2 or /register3 step,
   * he will be redirected to /register
   */
  if(to.path === ROUTES.REGISTER_2 || to.path === ROUTES.REGISTER_3) {
    const account = store.state.account.account;

    if(!account || !account.accountId) {
      next(ROUTES.REGISTER);
      return;
    }
  }

  /**
   * Registered user guard. If user is registered and want to go to /register step,
   * he will be redirected to /my-account
   */
  if(to.path === ROUTES.REGISTER) {
    const account = store.state.account.account;

    if(account && account.accountId) {
      next(ROUTES.MY_ACCOUNT);
      return;
    }
  }

  /**
   * Trigger notification modal for every applied
   * client routing
   */
  retriveRelatePathData(to.path)

  /**
   * Mixpanel page view tracking for every client rout
   */
  pageViewTrack(from.path, to.path);

  /**
   * Track notification screens in scholarship page
   */
  const eventName = eventByRouteName[lastPathPartName(to.path)];

  if(eventName) mixpanel.track(eventName);

  /**
   * Clear before unload data which was save on registration steps
   * when user come to scholarship or my-account page
   */
  if(to.path === ROUTES.SCHOLARSHIPS || to.path === ROUTES.MY_ACCOUNT) {
    beforeUnloadStore.removeAllStoreData();
  }

  return next();
});

export default router;