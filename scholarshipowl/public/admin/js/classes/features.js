$(document).ready(function() {
    $('.clone-feature').click(function(evt) {
        evt.preventDefault();
        var featureName = prompt('Fill name for new feature.', '');
        if(featureName !== null){
            window.location.href =  $(this).data('href')+'/' + featureName;
		}
    });
});



