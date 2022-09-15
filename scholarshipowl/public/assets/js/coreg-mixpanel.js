$(".coregBox").change(function() {
    var $name = $(this).attr('name').replace('agree_', '');
    if($(this).is(":checked")) {
      window.SOWLMixpanelTrack($name + ' checked');
    }
});
