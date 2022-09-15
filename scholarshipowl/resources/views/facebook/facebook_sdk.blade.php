<script id="fb-sdk-init-executor">
window.fbAsyncInit = function() {
    FB.init({
        appId      : "{!! config('laravel-facebook-sdk.facebook_config.app_id')  !!}", // "183053992455126"
        version    : "v2.6",
        xfbml      : true,
    });
};

(function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, "script", "facebook-jssdk"));
</script>