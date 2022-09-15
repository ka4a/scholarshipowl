import { clientTime } from "lib/utils/format";

export const SORT_EXPIRATION  = "deadline";
export const SORT_PROGRESS    = "progress";
export const SORT_AMOUNT      = "amount";
export const SORT_NAME        = "name";
export const SORT_DATE        = "date";
export const SORT_SUBJECT     = "subject";

export const ORDER_ASC = "asc";
export const ORDER_DESC = "desc";

export const sortByExpiration = (scholarships, order) => {
  return scholarships.sort((a, b) => {
    if (a.expirationDate && b.expirationDate) {
      const dateA = clientTime(a.expirationDate.date, a.timezone).valueOf();
      const dateB = clientTime(b.expirationDate.date, b.timezone).valueOf();

      return order ? dateA - dateB : dateB - dateA;
    }

    return 0;
  });
};

export const sortByStatus = (scholarships, order) => {
  return scholarships.sort((a, b) => {
    const statusA = parseInt(a.application.status, 10);
    const statusB = parseInt(b.application.status, 10);
    return order ? (statusB - statusA) : (statusA - statusB);
  });
};

export const sortByAmount = (scholarships, order) => {
  return scholarships.sort((a, b) => (
    order ? a.amount - b.amount : b.amount - a.amount
  ));
};

export const sortByDate = (mails, order) => {
  mails.sort((a, b) => Date.parse(a.date.date) - Date.parse(b.date.date));

  return order ? mails.reverse() : mails;
}

export const sortByPropertyName = (mails, propName, asc) => {
  if(!Array.isArray(mails) || !propName || !mails[0][propName])
    throw Error('Please provide correct parameters');

  mails.sort((a, b) => {
    let valueA = a[propName].toUpperCase(),
        valueB = b[propName].toUpperCase();

    if(valueA > valueB) return 1;
    if(valueB < valueA) return -1;
    return 0;
  })

  return asc ? mails.reverse() : mails;
}

export function applySorting(source, sortBy, order) {
  order = order === ORDER_ASC;

  if(sortBy === SORT_DATE) {
    return sortByDate(source, order);
  }

  if(sortBy === SORT_SUBJECT) {
    return sortByPropertyName(source, 'subject', !order);
  }

  if(sortBy === SORT_NAME) {
    let targetField = source.folder === 'Inbox' ? 'sender' : 'recipient';
    return sortByPropertyName(source, targetField, order);
  }

  if(sortBy === SORT_EXPIRATION) {
    return sortByExpiration(source, order);
  }

  if(sortBy === SORT_PROGRESS) {
    return sortByStatus(source, order);
  }

  if(sortBy === SORT_AMOUNT) {
    return sortByAmount(source, !order);
  }

  throw Error("Provided wrong sorting sign!");
}