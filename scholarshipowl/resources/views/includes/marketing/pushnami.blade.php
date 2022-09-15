<link rel="manifest" href="/pushnami/manifest.json">
<script type="text/javascript">
    function storageAvailable(type) {
        try {
            var storage = window[type],
                x = '__storage_test__';
            storage.setItem(x, x);
            storage.removeItem(x);
            return true;
        }
        catch(e) {
            return false;
        }
    }

    console.log('include pushnami');

    (function(document, window){

        var noDiff = false;
        var props = {
            "fname": '{!! account() ? account()->getProfile()->getFirstName() : '' !!}',
            "age": '{!! account() ? account()->getProfile()->getAge() : '' !!}',
            "schoolLevel": '{!! account() && account()->getProfile()->getSchoolLevel() ? account()->getProfile()->getSchoolLevel()->getName() : '' !!}',
            "state": '{!! account() && account()->getProfile()->getState() ? account()->getProfile()->getState()->getName()  : '' !!}',
            "membership": '{!! account() ? account()->membershipStatus() : '' !!}'
        };

        if (storageAvailable('localStorage')) {
            if(localStorage.getItem('pushnamiProps') != null) {
                noDiff = (localStorage.getItem('pushnamiProps') === JSON.stringify(props));
                if(!noDiff) {
                    localStorage.setItem('pushnamiProps', JSON.stringify(props));
                }
            }else{
                localStorage.setItem('pushnamiProps', JSON.stringify(props));
            }
        }
        else {
            //no localStorage
            noDiff = true;
        }

        console.log(localStorage.getItem('pushnamiProps'));

        var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = "https://api.pushnami.com/scripts/v1/pushnami-adv/5ca3888f0f193c463862ee19";

        if(!noDiff) {
            console.log('update subscription');
            script.onload = function() {
                Pushnami
                    .update(props)
                    .prompt()
            };
        }
        document.getElementsByTagName("head")[0].appendChild(script);
    })(document, window);
</script>
