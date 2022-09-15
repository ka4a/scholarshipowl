/*
 * SaveReferralAwardForm JS Class
 * By Ivan Krkotic
 */
var SaveReferralAwardForm = Element.extend({
    _init: function(element) {
        this._super(element);

        var caller = this;

        var referralAwardTypeSelect = new FormElement("select[name=referral_award_type_id]");
        referralAwardTypeSelect.bind("change", function() {
            caller.changeReferralAwardType(referralAwardTypeSelect.getValue());
        });
        this.changeReferralAwardType(referralAwardTypeSelect.getValue());
    },

    changeReferralAwardType: function(type) {
        if(type == 1 || type == 2) {
            $("#AwardTypeReferrals").show();
            $("#AwardTypeShares").hide();
        }
        else if(type == 3) {
            $("#AwardTypeReferrals").hide();
            $("#AwardTypeShares").show();
        }else{
            $("#AwardTypeReferrals").show();
            $("#AwardTypeShares").hide();
        }
    }
});

