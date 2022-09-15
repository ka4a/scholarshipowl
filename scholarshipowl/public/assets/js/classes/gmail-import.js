var CLIENT_ID = '501097268358-fii4dv0ctgudasmi8qf4qhh2irsmd69h.apps.googleusercontent.com';

var SCOPES = ['https://www.google.com/m8/feeds'];

/*
 *  GoogleAuthorize JS Class
 *  By Ivan Krkotic
 */
var GoogleAuthorize = Element.extend({
    _init: function(element){
        this._super(element);
        var caller = this;
        if (element) {
            element.bind("click", function(e) {
                e.preventDefault();
                handleAuthClick(e);
            });
        }
    }
});

/*
 *  GoogleImport JS Class
 *  By Ivan Krkotic
 */
var GoogleImport = Element.extend({
    _init: function(element){
        this._super(element);
        var caller = this;
        if (element) {
            element.bind("click", function(e) {
                e.preventDefault();
                getGoogleContactEmails(e);
            });
        }
    }
});

/**
 * Check if current user has authorized this application.
 */
function checkAuth() {
    gapi.auth.authorize(
        {
            'client_id': CLIENT_ID,
            'scope': SCOPES,
            'immediate': true
        }, handleAuthResult);
}

/**
 * Handle response from authorization server.
 *
 * @param {Object} authResult Authorization result.
 */
function handleAuthResult(authResult) {
    if (authResult && !authResult.error) {
        new GoogleImport($(".GoogleButton"));
    } else {
        new GoogleAuthorize($(".GoogleButton"));
    }
}

/**
 * Initiate auth flow in response to user clicking authorize button.
 *
 * @param {Event} event Button click event.
 */
function handleAuthClick(event) {
    gapi.auth.authorize(
        {client_id: CLIENT_ID, scope: SCOPES, immediate: false},
        getGoogleContactEmails);
    return false;
}

function getGoogleContactEmails() {
    var authParams = gapi.auth.getToken();
    var firstTry = true;
    $.ajax({
        url: 'https://www.google.com/m8/feeds/contacts/default/full?max-results=10000',
        dataType: 'jsonp',
        type: "GET",
        data: authParams,
        success: function (data) {
            var parser = new DOMParser();
            var xmlDoc = parser.parseFromString(data,"text/xml");
            var entries = xmlDoc.getElementsByTagName('feed')[0].getElementsByTagName('entry');
            var contacts = [];
            for (var i = 0; i < entries.length; i++){
                var name = entries[i].getElementsByTagName('title')[0].innerHTML;
                if(window.chrome){
                    var emails = entries[i].getElementsByTagName('email');
                }else{
                    var emails = entries[i].getElementsByTagName('gd:email');
                }

                for (var j = 0; j < emails.length; j++){
                    var email = emails[j].attributes.getNamedItem('address').value;
                    var html = "<div class=\"checkbox-wrapper\"><label><input type=\"checkbox\" name=\"email\" class=\"cbFriendEmail\" value=\"" + email + "\">" + email + "<span class=\"lbl padding-0\"></span></label></div>";
                    $("#friendEmailsWrapper").append(html);
                }
            }
            $('#friendEmailsWrapper').mCustomScrollbar({
                theme: "dark-thick",
                contentTouchScroll: false,
                scrollButtons: { enable: false }
            });
            $(".cbFriendEmail").change(function() {
                if($(this).is(":checked")) {
                    $("#FriendsEmails").val($("#FriendsEmails").val() + $(this).val() + " ");
                }else{
                    $("#FriendsEmails").val($("#FriendsEmails").val().replace($(this).val() + " ", ""));
                }
            });
            console.log(contacts);
        },
        error: function (data) {
            if (firstTry){
                getGoogleContactEmails();
            }
            firstTry = false;
        }
    });
}