<?php

?>


<html>
<head>
    <title>
hi i am page title
    </title>
</head>
<body>
<input type="button"  value="Login" onclick="login()" />
<input type="button"  value="Logout" onclick="logout()" />

<div id="profile"></div>
<!--<script type="text/javascript">

    function logout()
    {
        gapi.auth.signOut();
        location.reload();
    }
    function login()
    {
        var myParams = {
            'clientid' : 'PUTYOUR_CLIENT_ID.apps.googleusercontent.com',
            'cookiepolicy' : 'single_host_origin',
            'callback' : 'loginCallback',
            'approvalprompt':'force',
            'scope' : 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.profile.emails.read'
        };
        gapi.auth.signIn(myParams);
    }

    function loginCallback(result)
    {
        if(result['status']['signed_in'])
        {
            var request = gapi.client.plus.people.get(
                {
                    'userId': 'me'
                });
            request.execute(function (resp)
            {
                var email = '';
                if(resp['emails'])
                {
                    for(i = 0; i < resp['emails'].length; i++)
                    {
                        if(resp['emails'][i]['type'] == 'account')
                        {
                            email = resp['emails'][i]['value'];
                        }
                    }
                }

                var str = "Name:" + resp['displayName'] + "<br>";
                str += "Image:" + resp['image']['url'] + "<br>";
                str += "<img src='" + resp['image']['url'] + "' /><br>";

                str += "URL:" + resp['url'] + "<br>";
                str += "Email:" + email + "<br>";
                document.getElementById("profile").innerHTML = str;
            });

        }

    }
    function onLoadCallback()
    {
        gapi.client.setApiKey('PUT_YOUR_KEY');
        gapi.client.load('plus', 'v1',function(){});
    }

</script>

<script type="text/javascript">
    (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/client.js?onload=onLoadCallback';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();
</script>-->
<div
    class="fb-like"
    data-share="true"
    data-width="450"
    data-show-faces="true">
</div>
//https://api.genderize.io/?name=usa
<!--<script type="text/javascript">
    (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/client:plusone.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();

    function signinCallback(authResult) {
        if (authResult['status']['signed_in']) {
            console.log(authResult);
            document.getElementById('signinButton').setAttribute('style', 'display: none');
            /* get google plus id */
            $.ajax({
                    type: "GET",
                    url: "https://www.googleapis.com/oauth2/v2/userinfo?access_token="+access_token
                })
                .done(function( data ){
                    console.log(data);
                });
        } else {
            console.log('Sign-in state: ' + authResult['error']);
        }
    }
</script>-->
<script>
    var info={

        timeOpened:new Date(),
        timezone:(new Date()).getTimezoneOffset()/60,

        pageon(){return window.location.pathname},
        referrer(){return document.referrer},
        previousSites(){return history.length},

        browserName(){return navigator.appName},
        browserEngine(){return navigator.product},
        browserVersion1a(){return navigator.appVersion},
        browserVersion1b(){return navigator.userAgent},
        browserLanguage(){return navigator.language},
        browserOnline(){return navigator.onLine},
        browserPlatform(){return navigator.platform},
        javaEnabled(){return navigator.javaEnabled()},
        dataCookiesEnabled(){return navigator.cookieEnabled},
        dataCookies1(){return document.cookie},
        dataCookies2(){return decodeURIComponent(document.cookie.split(";"))},
        dataStorage(){return localStorage},

        sizeScreenW(){return screen.width},
        sizeScreenH(){return screen.height},
        sizeDocW(){return document.width},
        sizeDocH(){return document.height},
        sizeInW(){return innerWidth},
        sizeInH(){return innerHeight},
        sizeAvailW(){return screen.availWidth},
        sizeAvailH(){return screen.availHeight},
        scrColorDepth(){return screen.colorDepth},
        scrPixelDepth(){return screen.pixelDepth},


        latitude(){return position.coords.latitude},
        longitude(){return position.coords.longitude},
        accuracy(){return position.coords.accuracy},
        altitude(){return position.coords.altitude},
        altitudeAccuracy(){return position.coords.altitudeAccuracy},
        heading(){return position.coords.heading},
        speed(){return position.coords.speed},
        timestamp(){return position.timestamp},


    };
    console.log(info.previousSites());
</script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/userinfo/1.1.0/userinfo.min.js"></script>
<script type="text/javascript">
    UserInfo.getInfo(function(data) {
        // the "data" object contains the info
        console.log(data);
    }, function(err) {
        // the "err" object contains useful information in case of an error
    });
</script>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '235401549997398',
            xfbml      : true,
            status : true, // check login status
            cookie : true, // enable cookies to allow the server to access the session

            version    : 'v2.8'
        });

        FB.getLoginStatus(function(response) {
            console.log("inside login");
            var data = {title:document.title,url:window.location.href,user_id:"0"}
            console.log("Login status response ",response);
            if (response.status === 'connected') {

                //alert ("Page Title"+document.title+", page url"+window.location.href +", Your UID is " + response.authResponse.userID);
                data.user_id = response.authResponse.userID;
                console.log("fb id: ",response.authResponse.userID);
            }

            console.log(data);
            var xhr = new XMLHttpRequest();
            xhr.open( "POST","http://api.ragnar.shatkonlabs.com/access", true);
            xhr.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');

            // send the collected data as JSON
            xhr.send(JSON.stringify(data));

            xhr.onloadend = function () {
                // done
            }
        });

        FB.api('/me', { locale: 'en_US', fields: 'name, email' },
            function(response) {
                console.log("me api response",response);
            }

        );


    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));




</script>

</body>
</html>
