import {capitalize} from "lodash";
import moment from "moment-timezone";

const name = wordCount => string =>
  string.replace(/[^a-zA-Z ]+/g,'')
    .split(' ')
   .map(capitalize)
   .splice(0, wordCount)
   .join(' ');

const zip = str => str.replace(/\D+/g, '').substr(0, 5);

const alphaNumeric = str => str.replace(/[^a-zA-Z0-9/_ ]/g,'').replace(/\s\s+/g, ' ');

const numeric = str => str.replace(/\D+/g, '');

const USAPhoneNumber = number => {
  let x = number.replace(/\D/g, "").match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
  return !x[2] ? x[1] : "(" + x[1] + ") " + x[2] + (x[3] ? " - " + x[3] : "");
}

const numberToSimple = number => number.replace(/[^+\d]/g, "");

const clientTime = (
  (timeZoneUserInit = moment.tz.guess()) =>
  (date, timeZone, timeZoneUser = timeZoneUserInit) =>
    moment.tz(date, timeZone).tz(timeZoneUser)
)()

const dateFormat = (date, format) => {
  return (date instanceof moment ? date : moment(date)).format(format);
}

const getRecurrentTypeMessage = (type, value) => {
  const EXPIRATION_PERIOD_TYPES = {
    DAY: "day",
    WEEK: "week",
    MONTH: "month",
    YEAR: "year",
    NEVER: "never"
  }

  if(EXPIRATION_PERIOD_TYPES.DAY === type) {
    return "Billed " + (value === 1 ? "daily" : `every ${value} days`)
  }

  if(EXPIRATION_PERIOD_TYPES.WEEK === type) {
    return "Billed " + (value === 1 ? "weekly" : `every ${value} weeks`)
  }

  if(EXPIRATION_PERIOD_TYPES.MONTH === type) {
    return "Billed " + (value === 1 ? "monthly" : `every ${value} months`)
  }

  if(EXPIRATION_PERIOD_TYPES.YEAR === type) {
    return "Billed " + (value === 1 ? "annually" : `every ${value} years`)
  }

  return "";
}

const formatPricePeriod = (pac, expirationType) => {
  const validatePrice = price => {
    price = Number(price);

    if(isNaN(price)) throw Error("Not possible value");

    return price;
  }

  if(validatePrice(pac.price) === 0)
    return {price: "FREE"};

  let price = validatePrice(pac.discount_price);
  const currencyPrefix = "$";

  if(price) return {price: `${currencyPrefix}${price}`};

  price = validatePrice(pac.price_per_month);

  if(pac.expiration_type === expirationType
    && price) return {price: `${currencyPrefix}${price}`, period: "mo"}

  price = validatePrice(pac.price);

  if(price) return {price: `${currencyPrefix}${price}`};

  throw Error("Not one of the price based requirements is not satisfied");
}

export {
  name,
  numeric,
  zip as formatZip,
  alphaNumeric,
  USAPhoneNumber as formatUSAPhoneNumber,
  numberToSimple as formatNumberToSimple,
  clientTime,
  dateFormat,
  getRecurrentTypeMessage,
  formatPricePeriod
}