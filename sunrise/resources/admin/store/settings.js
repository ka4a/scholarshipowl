import deepmerge from 'deepmerge';
import GridStore from 'lib/store/grid-store';

export const CONFIG_LEGAL_AFFIDAVIT = 'affidavit';
export const CONFIG_LEGAL_PRIVACY_POLICY = 'privacyPolicy';
export const CONFIG_LEGAL_TERMS_OF_USE = 'termsOfUse';

export default GridStore('settings');
