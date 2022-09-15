/**
 * Find closest element
 */
export const closest = function closest(el, selector) {
  var matchesFn;

  // find vendor prefix
  ["matches","webkitMatchesSelector","mozMatchesSelector","msMatchesSelector","oMatchesSelector"].some(function(fn) {
    if (typeof document.body[fn] == "function") {
      matchesFn = fn;
      return true;
    }
    return false;
  });

  var parent;

  // traverse parents
  while (el) {
    parent = el.parentElement;
    if (parent && parent[matchesFn](selector)) {
      return parent;
    }
    el = parent;
  }

  return null;
};

/**
 * Scroll to element
 */
export const scroll = function(landmark, duration, delay, cb) {
  if (!landmark) return;

  function defineMetrics(elem) {
    return {
      topIndent: elem.getBoundingClientRect().top,
      scrollTop: window.pageYOffset
    };
  }

  function animate(options) {

    var start = performance.now();

    requestAnimationFrame(function animate(time) {

      var timeFraction = (time - start) / options.duration;
      if (timeFraction > 1) timeFraction = 1;

      var progress = options.timing(timeFraction);

      options.draw(progress);

      if (timeFraction < 1) {
        requestAnimationFrame(animate);
      } else if (typeof cb === "function") {
        cb(landmark);
      }
    });
  }

  function callAnimate() {
    animate({
      duration: duration || 800,
      timing: function(timeFraction) {
        return timeFraction;
      },
      draw: function(progress) {
        if(typeof landmark === "number") {
          let scrollTop = window.pageYOffset;
          scrollTo(0, scrollTop - (scrollTop - landmark) * progress);
        } else {
          let metrics = defineMetrics(landmark);
          scrollTo(0, metrics.scrollTop - 10 + (progress * metrics.topIndent));
        }
      }
    });
  }

  delay ? setTimeout(callAnimate, delay) : callAnimate();
};

/**
 * Focus on form errors.
 */
export const focusOnError = (errorBag, to = ".interaction-item") => {
  for (let i = 0; i < errorBag.items.length; i++) {
    const field = errorBag.items[i].field;
    const inputSelector = `${to} [name=${field}]`;
    const elm = closest(document.querySelector(inputSelector), to);

    if (elm) {
      scroll(elm);
      return;
    }
  }
};