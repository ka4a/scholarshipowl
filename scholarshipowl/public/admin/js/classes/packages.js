

/* 
 * SavePackageForm JS Class
 * By Marko Prelic
 */
var SavePackageForm = Element.extend({
	_init: function(element) {
		this._super(element);
		
		var caller = this;
		
		var scholarshipsSelect = new FormElement("select[name=scholarships]");
		scholarshipsSelect.bind("change", function() {
			caller.changeScholarships(scholarshipsSelect.getValue());
		});
		this.changeScholarships(scholarshipsSelect.getValue());
		
		var expirationSelect = new FormElement("select[name=expiration_type]");
		expirationSelect.bind("change", function() {
			caller.changeExpirationType(expirationSelect.getValue());
		});
		this.changeExpirationType(expirationSelect.getValue());

        var isFreemiumCheckbox = new FormElement("input[name=is_freemium]");
        isFreemiumCheckbox.bind("change", function(v) {
            caller.togglePriceValue();
        });
        this.togglePriceValue();
	},
	
	changeScholarships: function(type) {
		if(type == "fixed") {
			$("#PackageScholarshipsFixed").show();
		}
		else if(type == "unlimited") {
			$("#PackageScholarshipsFixed").hide();
		}
		else {
			$("#PackageScholarshipsFixed").hide();
		}
	},
	
	changeExpirationType: function(type) {
		if(type == "date") {
			$("#PackageExpirationTypeDate").show();
			$("#PackageExpirationTypePeriod").hide();
			$("#PackageExpirationTypeRecurrent").hide();
		}
		else if(type == "period") {
			$("#PackageExpirationTypeDate").hide();
			$("#PackageExpirationTypePeriod").show();
            $("#PackageExpirationTypeRecurrent").hide();
		}
        else if(type == "recurrent") {
            $("#PackageExpirationTypeDate").hide();
            $("#PackageExpirationTypePeriod").hide();
            $("#PackageExpirationTypeRecurrent").show();
        }
		else {
			$("#PackageExpirationTypeDate").hide();
			$("#PackageExpirationTypePeriod").hide();
            $("#PackageExpirationTypeRecurrent").hide();
		}
	},

	togglePriceValue: function(checkbox){
        if($('input[name=is_freemium]').is(':checked') && $("input[name=price]").parents('.form-group').is(":visible")){
            $("input[name=price]").parents('.form-group').hide();
        }else{
			if(!$(checkbox).is(':checked') && !$("input[name=price]").parents('.form-group').is(":visible")){
				$("input[name=price]").parents('.form-group').show();
			}
        }
	}
});


