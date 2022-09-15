export const SCHOLARSHIP_STATUS = {
  UNKNOWN: 0,
  INCOMPLETE: 1,
  IN_PROGRESS: 2,
  READY_TO_SUBMIT: 3,
  SUBMITTED: 4,
};

export const SEARCH_QUERY = "SEARCH_QUERY";

export const SORT_EXPIRATION = "deadline";
export const SORT_PROGRESS = "progress";
export const SORT_AMOUNT = "amount";

export const ORDER_ASC = "asc";
export const ORDER_DESC = "desc";

export const OPTION_ANY = "any";
export const OPTION_YES = "yes";
export const OPTION_NO = "no";

export const separateFavorite = (scholarships, isFavorite) => {
  return scholarships
    .filter(scholarship => !!scholarship.isFavorite === isFavorite);
}

const filterByQuery = (list, query) => {
  let filtered = [],
      props = ['sender', 'recipient', 'subject', 'clearBody'];
      query = query.trim();

   if (!query) {
    return list;
   }

  for(let i = 0; i < list.length; i += 1) {
    let mail = list[i];

    for(let j = 0; j < props.length; j += 1) {
      if(mail[props[j]].indexOf(query) !== -1 ||
        mail[props[j]].toUpperCase().indexOf(query) !== -1 ||
        mail[props[j]].toLowerCase().indexOf(query) !== -1) {

        filtered.push(mail);
        break;
      }
    }
  }

  return filtered;
};

export const filterByStatus = (data, status) => {
  if(!status.length) return data;

  return data.filter((i) => status.includes(i.application.status));
};

export const filterByDeadline = (list, parameter) => {
  let result = list;

  if(parameter[0] && parameter[0] !== 0) {
    result = deadlineFrom(list, parameter[0]);
  }

  if(parameter[1] && parameter[1] !== 5000) {
    result = deadlineTo(list, parameter[1]);
  }

  return result;
}

export const deadlineFrom = (scholarships, deadline) => {
  return scholarships.filter((scholarship) => {
    const expirationDate = new Date(Date.parse(scholarship.expirationDate.date
      .replace(/-/g, "/").substring(0, 10)));
    const filterDate = new Date(Date.parse(deadline));
    return expirationDate >= filterDate;
  });
};

const deadlineTo = (scholarships, deadline) => {
  return scholarships.filter((scholarship) => {
    const expirationDate = new Date(Date.parse(scholarship.expirationDate.date
      .replace(/-/g, "/").substring(0, 10)));
    const filterDate = new Date(Date.parse(deadline));
    return expirationDate <= filterDate;
  });
};

const filterByAmount = (scholarships, range) => {
  return scholarships.filter((i) => {
    if (range[1] !== 5000) {
      return i.amount >= range[0] && i.amount <= range[1];
    }

    return (
      i.amount >= range[0] &&
        (i.amount <= range[1] || i.amount >= range[1])
    );
  });
};

const filterByEssayRequired = (scholarships, option) => {
  if(option === OPTION_ANY) return scholarships;

  return scholarships.filter((i) => {
    const hasRequirements = Object.keys(i.requirements)
      .map(item => i.requirements[item].length)
      .reduce((a, b) => a + b, 0);
    return option === OPTION_YES ? hasRequirements : !hasRequirements;
  });
};

const filterByIsRecurrent = (scholarships, option) => {
  if(option === OPTION_ANY) return scholarships;

  return scholarships.filter(el => option === OPTION_YES
    ? el.isRecurrent : !el.isRecurrent );
};

const filterMethods = {
  filterByQuery,
  filterByStatus,
  filterByDeadline,
  filterByAmount,
  filterByEssayRequired,
  filterByIsRecurrent
}

export function applyFilters(resource, filters) {
  let parameters = Object.keys(filters),
    result = resource;

  parameters.forEach(filterName => {
    let filterFuncName = 'filterBy' +
      filterName[0].toUpperCase() +
      filterName.substring(1);

    result = filterMethods[filterFuncName](result, filters[filterName])
  })

  return result;
}