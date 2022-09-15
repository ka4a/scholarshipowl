export const setState = (state, { stateName, data }) => {
  if(!stateName || typeof stateName !== "string")
    throw Error("Please provide correnct state name");

  state[stateName] = data;
}