export const carousel = (options) => {
  const { renderer, duration, components,
      drower, phaseChanged} = options;

  let starTime = performance.now(),
      imprintTime = 0,
      reverse = false,
      stop = false,
      timeGuard = false,
      timeItemDistance = 1 / components.length,
      cycleFinishTimeMarker = duration / components.length;

  const resetValues = () => {
    starTime = performance.now();
    imprintTime = 0;
  }

  const straight = time => {
    const timeFraction = (imprintTime + (time - starTime)) / duration;

    if(timeFraction >= 1) {
      starTime = performance.now();
      imprintTime = 0;
    }

    return timeFraction;
  }

  const backward = time => {
    const timeFraction = (imprintTime - (time - starTime)) / duration;

    if(timeFraction <= 0) {
      starTime = performance.now();
      imprintTime = duration;
    }

    return timeFraction;
  }

  const stopAnimation = () => (stop = true);

  const startAnimation = () => (stop = false);

  const reset = () => {
    timeGuard = false
    stopAnimation();
    setTimeout(() => {
      startAnimation()
      init();
    }, 30)
  }

  const changeDirection = cursorPosition => {
    if((cursorPosition && reverse)
      || (!cursorPosition && !reverse)) return;

    timeGuard = false;
    stopAnimation();
    setTimeout(function() {
      reverse = !reverse;
      startAnimation();
      init();
      setTimeout(() => (timeGuard = true), 30)
    }, 50)
  }

  const definePhase = ((duration, components, reverse) => {
    const timeFrame = 1 / components.length;

    let phase = reverse ? components.length - 1 : 1;

    const ascending = timeFraction => {
      if(timeFraction >= 1) {
        phase = 1;
      } else {
        if(timeFraction >= phase * timeFrame) {
          phase += 1;
        }
      }

      return phase;
    }

    const descending = timeFraction => {
      if(timeFraction <= 0) {
        phase = components.length - 1;
      } else {
        if(timeFraction < phase * timeFrame) {
          phase -= 1;
        }
      }

      return phase;
    }

    return (timeFraction, reverse) => reverse
      ? descending(timeFraction)
      : ascending(timeFraction)

  })(duration, components, reverse);

  const execWhenValIsChanged = ((prevVal = null) => (newVal, cb) => {
    if(newVal !== prevVal) {
      cb(newVal);
      prevVal = newVal;
    }
  })()

  const init =() => {
    let defineTimeFraction = reverse
      ? backward : straight;

      starTime = performance.now();

      requestAnimationFrame(function executor(time) {

        const timeFraction = defineTimeFraction(time);

        const phase = definePhase(timeFraction, reverse);

        execWhenValIsChanged(phase, phase => {
          phaseChanged(phase, reverse);
        })

        renderer(
          components,
          timeFraction,
          timeItemDistance,
          drower,
          phase,
          reverse
        );

        if(stop) {
          imprintTime = reverse
            ? imprintTime - (time - starTime)
            : time - starTime + imprintTime

          return;
        }

        requestAnimationFrame(executor);
      });
  }

  return {
    changeDirection: changeDirection,
    init: init,
    stop: stopAnimation,
    start: startAnimation,
    reset: reset
  }
}

export const drower = () => {
  const easeInOutQuad = t => (t<.5 ? 2*t*t : -1+(4-2*t)*t);

  const easeOutCubic = t => ((--t)*t*t+1); // scale timefraction

  function easeOutSine(pos) {
    return Math.sin(pos * (Math.PI/2));
  }

  const positionTimeFunction = (t, delayCoefficient) => {
    if(t <= delayCoefficient) return 0;

    if(t >= 1 - delayCoefficient) return 1;

    const duration = 1 - delayCoefficient * 2;

    return (t - delayCoefficient) / duration;
  }

  const opacityTimeFunction = (t, delay) => {
    const tailTime = delay * 1;

    if(t <= tailTime) return easeOutCubic(t / tailTime);

    if(t > 1 - tailTime) return easeOutCubic((1 - t) / tailTime);

    return 1;
  }

  //TODO replace it
  const edge = 80;

  const drawPosition = (el, timeFraction, phase, phaseTime) => {
    timeFraction = (timeFraction - phaseTime) / (phaseTime * 6);

    if(timeFraction < 0) {
      timeFraction = 0;
    }

    if(timeFraction >= 1) {
      timeFraction = 1;
    }

    let position = 0;

    el.style.top = timeFraction * edge + '%';
  }

  const drawOpacity = (el, timeFraction) => {
    el.style.opacity = timeFraction
  }

  const drawScale = (el, timeFraction, phase, phaseTime, reverse) => {
    if(timeFraction >= 0.5) {
      timeFraction = 1 - timeFraction;
    }

    let value = 0.8;
    timeFraction = timeFraction * 2;
    value = value + 0.2 * easeOutSine(timeFraction);

    el.style.transform = `scale(${value})`
  }

  const defineZIndex = (el, timeFraction, phase) => {
    // console.log(el, timeFraction, phase);

  }

  const draw = (target, timeFraction, delay, phase, reverse) => {
    // draw position
    const phaseTime = 1 / 8;

    drawPosition(target, timeFraction, phase, phaseTime);
    // draw scale
    drawScale(target, timeFraction, phase, phaseTime, reverse);

    // draw opacity
    let opacityTimeFraction = opacityTimeFunction(timeFraction, delay);
    drawOpacity(target, opacityTimeFraction);

    // draw z-index
    defineZIndex(target, timeFraction, phase);
  }

  return draw;
}