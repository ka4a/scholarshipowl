import moment from "moment";

export default {
  computed: {
    date() {
      if(!this.endDate) return '';

      let date = moment((this.endDate), "DD-MM-YYYY");

      if(!date.isValid()) return '';

      return date.format("MMM DD, YYYY");
    },
    freeTrialEndDate() {
      return this.membership.isMember && this.membership.freeTrial && this.membership.freeTrialEndDate
          ? this.membership.freeTrialEndDate : null;
    },
    activeUntil() {
      return this.membership.isMember && this.membership.activeUntil && !this.membership.freeTrial
        ? this.membership.activeUntil : null;
    },
    endDate() {
      return this.freeTrialEndDate || this.activeUntil;
    }
  }
}