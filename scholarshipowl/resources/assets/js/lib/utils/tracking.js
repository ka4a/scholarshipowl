const GOALS_30 = {
  LEAD: 16,
  ACCOUNT: 18,
  SALE: 0
}

const GOALS_32 = {
  LEAD: 22,
  ACCOUNT: 20,
  TRIAL: 40,
  MISSION_COMPLETE: 32
}

export const OFFER_IDS = {
  "30": {goals: GOALS_30, tagName: 'img'},
  "32": {goals: GOALS_32, tagName: 'iframe'}
}

const validateTrackingObj = (props, cb) => {
  const mainFields = ['affiliateId', 'offerId'];

  let isValid = mainFields.map(key => props[key]).every(key => {
    return key !== undefined
      && typeof key === 'number'
      && key > 0
  });

  if(!isValid) cb(isValid);

  const {goalName, transactionId} = props;

  isValid = isValid && goalName && typeof goalName === 'string';
  isValid = isValid && transactionId && typeof transactionId === 'string';

  if(isValid) cb(isValid);
}

const getMountNodes = () => {
  const scrps = document.getElementsByTagName('script'),
        s = scrps[scrps.length - 1];

  return {s, parent: s.parentNode}
}

const createPixel = (tagName, src, id, cb) => {
  const {s, parent} = getMountNodes();

  const pxl = document.createElement(tagName);
  pxl.id = "goal-id" + id;

  const handle = (status) => {
    clearInterval(timer);
    cb(status);
    parent.removeChild(pxl);
  }

  const timer = setTimeout(() => handle(false), 4000);

  pxl.onload = () => handle(true);
  pxl.onerror = () => handle(false);

  pxl.height = "1";
  pxl.width = "1";
  pxl.src = src;

  parent.insertBefore(pxl,s)
}

export const firePixel = (options, cb = () => {}) => {
  validateTrackingObj(options, isValid => {
    const offerId = options.offerId.toString();

    if(!isValid || !OFFER_IDS[offerId]) {
      cb(isValid);
      return;
    }

    const { goals, tagName } = OFFER_IDS[offerId],
          goalId = goals[options.goalName],
          { transactionId } = options;

    if(!goalId) {
      cb(false);
      return;
    }

    const link = `https://scholarship.go2cloud.org/aff_goal?a=l&goal_id=${goalId}&transaction_id=${transactionId}`;

    createPixel(tagName, link, goalId, isFired => {
      console.log(`tracking pixel with offerId: ${options.offerId} and goalId: ${goalId} is fired`)
      cb(isFired);
    });
  })
}