/*
 * SavePopupForm JS Class
 * By Ivan Krkotic
 */
var SavePopupForm = Element.extend({
    _init: function(element) {
        this._super(element);

        var caller = this;

        var popupTypeSelect = new FormElement("select[name=popup_type]");
        popupTypeSelect.bind("change", function() {
            caller.changePopupType(popupTypeSelect.getValue());
        });
        this.changePopupType(popupTypeSelect.getValue());
    },

    changePopupType: function(type) {
        if(type == 'raf' || type == 'popup') {
            $("#PopupTypeMission").hide();
            $("#PopupTypePackage").hide();
        }
        else if(type == "mission") {
            $("#PopupTypeMission").show();
            $("#PopupTypePackage").hide();
        }
        else if(type == "package") {
            $("#PopupTypeMission").hide();
            $("#PopupTypePackage").show();
        }
    }
});

/*
 * DeletePopupButton JS Class
 * By Ivan Krkotic
 */
var DeletePopupButton = Element.extend({
    _init: function(element) {
        this._super(element);

        element.bind("click", function(e) {
            e.preventDefault();

            var $url = $(this).attr("data-delete-url");
            var $message = $(this).attr("data-delete-message");

            if(confirm($message)) {
                window.location = $url;
            }
        });
    }
});

