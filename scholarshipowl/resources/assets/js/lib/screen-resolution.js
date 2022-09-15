import debounce from "lodash/debounce";

let breakpoints = {
  "xs": [- Infinity, 320],
  "s" : [320, 480],
  "m" : [480, 768],
  "l" : [768, 1024],
  "xl": [1024, 1440],
  "xxl": [1440, Infinity]
};

function getViewportWidth() {
  return document.documentElement.clientWidth;
}


function defineDevice(screenWidth) {
  let breakpointNames = [];

  for (var key in breakpoints) {
    let min = breakpoints[key][0],
      max = breakpoints[key][1];

    if(screenWidth >= min && screenWidth < max) {
      breakpointNames.push(key);
    }
  }

  state.resolution = screenWidth;
  state.breakpointNames = breakpointNames;
}

function getMetrics() {
  getViewportWidth();
  defineDevice(getViewportWidth());
}

let state = {};

window.setBreakpoints = state.setBreakpoints = newBreakpoints => {
  if(!newBreakpoints) throw Error("breakpoints config not defined!");
  if(typeof newBreakpoints !== "object") throw Error("confis object no object!");

  Object.assign(breakpoints, newBreakpoints);

  return state;
};

window.addEventListener("resize", debounce(function() {
  getMetrics();
}, 300));

getMetrics();

export default state;