<link rel="manifest" href="/manifest.json">
<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async='async'></script>
<script>
    var postAjax = function (url, data) {
        var params = typeof data == 'string' ? data : Object.keys(data).map(
                function(k){ return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]) }
        ).join('&');

        var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
        xhr.open('POST', url);
//        xhr.onreadystatechange = function() {
//            if (xhr.readyState>3 && xhr.status==200) { success(xhr.responseText); }
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(params);
        return xhr;
    }

    var oneSignalSyncUser = function() {
        if (!OneSignal.isPushNotificationsSupported()) {
            return;
        }

        OneSignal.getUserId(function (userId) {
            console.log("OneSignal User ID:", userId);
            postAjax('/rest/v1/onesignal/account', {'userId': userId, 'app': 'web'});
        });
    };

    var OneSignal = window.OneSignal || [];
    OneSignal.push(["init", {
        appId: "{!! config('onesignal.web.app_id') !!}",
        subdomainName: "{!! config('onesignal.web.subdomain') !!}",
        autoRegister: false, /* Set to true to automatically prompt visitors */
        httpPermissionRequest: {
            enable: {!! is_production() ? 'false' : 'true'  !!}
        },
        // Your other init options here
        promptOptions: {
            /* Change bold title, limited to 30 characters */
            siteName: 'ScholarshipOwl',
            /* Subtitle, limited to 90 characters */
            actionMessage: "We'd like to show you notifications for the latest news and updates.",
            /* Example notification title */
            exampleNotificationTitle: 'Example notification',
            /* Example notification message */
            exampleNotificationMessage: 'This is an example notification',
            /* Text below example notification, limited to 50 characters */
            exampleNotificationCaption: 'You can unsubscribe anytime',
            /* Accept button text, limited to 15 characters */
            acceptButtonText: "ALLOW",
            /* Cancel button text, limited to 15 characters */
            cancelButtonText: "NO THANKS"
        },
        notifyButton: {
            enable: false /* Set to false to hide */
        }
    }]);

    var OneSignalEnabled = OneSignal.isPushNotificationsSupported ? OneSignal.isPushNotificationsSupported() : false;

    OneSignal.push(function() {
        OneSignal.isPushNotificationsEnabled(function(enabled) {
            OneSignalEnabled = enabled;
        });
        OneSignal.on('subscriptionChange', function (isSubscribed) {
            if (isSubscribed) {
                OneSignalEnabled = true;
                oneSignalSyncUser();
            }
        });
    });

    var oneSignalRequestPermissionAfterApplication = function() {
        if (OneSignalEnabled) return;

        var getCookie = function (name) {
            var value = "; " + document.cookie;
            var parts = value.split("; " + name + "=");
            if (parts.length == 2) return parts.pop().split(";").shift();
        };

        /**
         * Don't ask for permission after first request.
         * TODO: Move to scholarships page logic
         */
        if (!getCookie('__sofpo')) {
            document.cookie = '__sofpo=1; path=/;';
            return;
        }

        /**
         * Don't show popup for 2 hours after request.
         */
        if (!getCookie('__soospn')) {
            console.log('registerForPushNotifications');
            OneSignal.registerForPushNotifications({ modalPrompt: true });

            var expire = new Date();
            expire.setTime(expire.getTime() + 2 * 3600 * 1000);
            document.cookie = '__soospn=1; expires=' + expire.toUTCString() + '; path=/;';
        }
    };
</script>
