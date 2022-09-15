let savedCallback = null;

const bind = callback => {
  if(!callback || typeof callback !== 'function')
    throw Error("Please provide correct callback");

  if(savedCallback) throw Error("Can't bind more than one callback")

  savedCallback = callback;

  window.addEventListener("keypress", ev => {
    if(ev.keyCode === 13) {
      callback();
    }
  })
}

const unbind = () => {
  if(!savedCallback) return;

  window.removeEventListener("keypress", savedCallback);

  savedCallback = null;
}

export const enterKeyPressHolder = callback => {
  return {
    bind,
    unbind
  }
}