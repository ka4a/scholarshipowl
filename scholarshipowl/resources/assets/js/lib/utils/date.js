export const spliteDateToSemanticItems = date => {
  let values = date.split(" ");

  values.forEach((dateItem, index) => {
    if(/[a-z],$/i.test(dateItem)) {
      values.splice(index, 1, dateItem.substring(0, dateItem.length - 1))
    }
  })

  return values;
}

export const monthNumberRepresentation = month => {
  const monthSequence = ["January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"];

    month = monthSequence.find(name => name.indexOf(month) !== -1);

    let index = monthSequence.indexOf(month);

    if(index !== -1) return index + 1;

    throw Error('Please define proper month name!');
}

export const stringDateToObject = date => {
  let day, month, year;

  spliteDateToSemanticItems(date)
    .forEach(item => {
      if(/[0-9]+/g.test(item)) {
        if(item.length === 4) {
          year = Number(item);

          if(year > new Date().getFullYear()) {
            year = null;
          }
        }

        if(item.length <= 2) {
          day = Number(item);

          if(day > 31) {
            day = null;
          }

          return
        }
      }

      if(/[a-z]+,?/ig.test(item)) {
        month = item;

        if(item.indexOf(',') !== -1) {
          month = item.substring(0, item.length - 1);
        }

        month = monthNumberRepresentation(month);
        return;
      }
    })

    if(day && month && year) {
      return { day, month, year }
    }

    return null;
};

export const addDayMonthYearFields = object =>
  Object.assign({}, object, stringDateToObject(object.date));
