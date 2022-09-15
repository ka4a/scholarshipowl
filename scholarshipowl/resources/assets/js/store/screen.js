import screenResolution from "lib/screen-resolution";

export default {
  namespaced: true,
  state: {
    screenResolution
  },
  getters: {
    xs({ screenResolution }) {
      return screenResolution.breakpointNames.includes("xs");
    },
    s({ screenResolution }) {
      return screenResolution.breakpointNames.includes("s");
    },
    m({ screenResolution }) {
      return screenResolution.breakpointNames.includes("m");
    },
    l({ screenResolution }) {
      return screenResolution.breakpointNames.includes("l");
    },
    xl({ screenResolution }) {
      return screenResolution.breakpointNames.includes("xl");
    },
    xxl({ screenResolution }) {
      return screenResolution.breakpointNames.includes("xxl");
    },
    resolution({ screenResolution }) {
      return screenResolution.resolution;
    }
  }
};
