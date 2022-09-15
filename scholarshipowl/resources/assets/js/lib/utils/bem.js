export function build(element, modificators, result = {}) {
  switch (typeof modificators) {
  case "object":
    Object.keys(modificators).forEach((modificator) => {
      result[element + "_" + modificator] = modificators[modificator];
    });
    break;
  case "string":
    result[element + "_" + modificators] = true;
    break;
  default:
  }

  return result;
}

export function block(block, modificators) {
  return this.build(block, modificators, { [block]: true });
}

export function element (block, element, modificators) {
  const className = block + "__" + element;

  return this.build(className, modificators, { [className]: true });
}
