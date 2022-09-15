<script>
    var isIE = (function() {
        var isIE = false;

        if (navigator.appVersion.indexOf("MSIE 10") !== -1) {
            isIE = true;
        }

        var UAString = navigator.userAgent;
        if (UAString.indexOf("Trident") !== -1 && UAString.indexOf("rv:11") !== -1) {
            isIE = true;
        }

        var oldIE = (function(){
            var undef,
                v = 3,
                div = document.createElement('div'),
                all = div.getElementsByTagName('i');
            while (
                div.innerHTML = '<!--[if gt IE ' + (++v) + ']><i></i>< ![endif]-->',
                all[0]
            );
            return v > 4 ? v : undef;
        }());

        if(oldIE) {
            isIE = true;
        }

        isIE = /x64|x32/ig.test(window.navigator.userAgent);

        if(navigator.userAgent.indexOf('MSIE')!==-1
        || navigator.appVersion.indexOf('Trident/') > -1){
           isIE = true;
        }

        return isIE;
    })();

    if(isIE) {
        console.log("IE", isIE);

        var $buoop = {
            required:{i:12},
            insecure:true,
            unsupported:true,
            mobile:false,
            api:2019.03,
            reminder: 365 * 3 * 24
        };
        function $buo_f(){
         var e = document.createElement("script");
         e.src = "//browser-update.org/update.min.js";
         document.body.appendChild(e);
        };
        try {
            document.addEventListener("DOMContentLoaded", $buo_f,false)
        }
        catch(e){
            window.attachEvent("onload", $buo_f)
        }
    }
</script>