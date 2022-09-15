/**
 * ACL Permissions form
 */
var AclPermissionsForm = Element.extend({
    _init: function(element) {
        this._super(element);

        var $checkboxes = $(this._selector).find('input:checkbox');

        $('#permission-form-check-all').click(function() {
            $checkboxes.prop('checked', true);
        });

        $('#permissoin-form-uncheck-all').click(function() {
            $checkboxes.prop('checked', false);
        });
    }
});
