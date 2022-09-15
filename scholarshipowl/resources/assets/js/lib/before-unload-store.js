let listenerIsBind = false;

const dataForStore = {};

let identificators = localStorage.getItem("before-unload-identificators");
identificators = identificators ? JSON.parse(identificators) : [];

const validateIdentificator = identificator => {
  if(!identificator)
    throw Error("Please provide identificator");

  if(typeof identificator !== "string")
    throw Error("Please provide identificator in write format");
}

const saveToLocalStorage = () => {
  if(!identificators.length) return;

  Object.keys(dataForStore).forEach(identificator => {
    let data = dataForStore[identificator];

    if(typeof data === "function") {
      data = data();
    }

    data = JSON.stringify(data);

    localStorage.setItem(identificator, data);

    delete dataForStore[identificator];
  })

  localStorage.setItem(
    'before-unload-identificators',
    JSON.stringify(identificators)
  );
}

const putToLocalStore = (identificator, data) => {
  if(typeof data === "function") {
    data = data();
  }

  if(identificators.indexOf(identificator) === -1) {
    identificators.push(identificator);
  }

  dataForStore[identificator] = data;
}

const saveData = (identificator, data) => {
  putToLocalStore(identificator, data);

  saveToLocalStorage();
}

/**
 * Get saved data from before unload store
 * @param  {string} identificator associated with saved data
 * @return {sbject|null} data stored in storeg or null if identificator
 * doesn't doesn't any data
 */
const getData = identificator => {
  validateIdentificator(identificator);

  let data = localStorage.getItem(identificator);

  try {
    data = JSON.parse(data);
  } catch(e) {
    return null;
  }

  return data;
}

/**
 * Remove item from local storage
 * @param  {identificator} identificator associated with localstorage field
 * @return {undefined}
 */
const removeData = identificator => {
  validateIdentificator(identificator);

  delete dataForStore[identificator]

  const identificatorIndex = identificators.indexOf(identificator);

  if(identificatorIndex > -1) {
    identificators.splice(identificatorIndex, 1);
  }

  return localStorage.removeItem(identificator);
}

const isStoreDataExist = () => !!identificators.length;

const isIdentificatorStored = id => (isStoreDataExist() && identificators.includes(id))

const removeAllStoreData = () => {
  if(!isStoreDataExist) return;

  identificators.forEach(id => localStorage.removeItem(id));

  identificators = [];

  localStorage.removeItem("before-unload-identificators")
}

/**
 * Apply callback to all object elements. Pass to call back
 * two parameters: name{String} - property name, value{Any} - value.
 * @param  {string}   identificator associated with saved data
 * @param  {function} callback      function which applies to each item
 * @return {undefined}
 */
const walkThroughSavedData = (identificator, callback) => {
  const data = getData(identificator);

  if(!data) return;

  const keys = Object.keys(data);

  if(!keys.length) return;

  keys.forEach(name => callback(name, data[name]))
}

export default {
  saveData,
  getData,
  removeData,
  walkThroughSavedData,
  isStoreDataExist,
  removeAllStoreData,
  isIdentificatorStored
}