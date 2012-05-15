<?php
	set_error_handler("customError");
	function customError($errno, $errstr)
	{
		echo "<b>Error:</b> [$errno] $errstr<br />";
	}
/**
 * This sample app is provided to kickstart your experience using Facebook's
 * resources for developers.  This sample app provides examples of several
 * key concepts, including authentication, the Graph API, and FQL (Facebook
 * Query Language). Please visit the docs at 'developers.facebook.com/docs'
 * to learn more about the resources available to you
 */

// Provides access to app specific values such as your app id and app secret.
// Defined in 'AppInfo.php'
require_once('AppInfo.php');

// Enforce https on production
if (substr(AppInfo::getUrl(), 0, 8) != 'https://' && $_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
  header('Location: https://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
  exit();
}

// This provides access to helper functions defined in 'utils.php'
require_once('utils.php');


/*****************************************************************************
 *
 * The content below provides examples of how to fetch Facebook data using the
 * Graph API and FQL.  It uses the helper functions defined in 'utils.php' to
 * do so.  You should change this section so that it prepares all of the
 * information that you want to display to the user.
 *
 ****************************************************************************/

require_once('sdk/src/facebook.php');

$facebook = new Facebook(array(
  'appId'  => AppInfo::appID(),
  'secret' => AppInfo::appSecret(),
));

$user_id = $facebook->getUser();
if ($user_id) {
  try {
    // Fetch the viewer's basic information
    $basic = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    // If the call fails we check if we still have a user. The user will be
    // cleared if the error is because of an invalid accesstoken
    if (!$facebook->getUser()) {
      header('Location: '. AppInfo::getUrl($_SERVER['REQUEST_URI']));
      exit();
    }
  }

  // This fetches some things that you like . 'limit=*" only returns * values.
  // To see the format of the data you are retrieving, use the "Graph API
  // Explorer" which is at https://developers.facebook.com/tools/explorer/
  $likes = idx($facebook->api('/me/likes?limit=4'), 'data', array());

  // This fetches 4 of your friends.
  $friends = idx($facebook->api('/me/friends?limit=4'), 'data', array());

  // And this returns 16 of your photos.
  $photos = idx($facebook->api('/me/photos?limit=16'), 'data', array());

  // Here is an example of a FQL call that fetches all of your friends that are
  // using this app
  $app_using_friends = $facebook->api(array(
    'method' => 'fql.query',
    'query' => 'SELECT uid, name FROM user WHERE uid IN(SELECT uid2 FROM friend WHERE uid1 = me()) AND is_app_user = 1'
  ));
}

// Fetch the basic info of the app that they are using
$app_info = $facebook->api('/'. AppInfo::appID());

$app_name = idx($app_info, 'name', '');

?>
<!DOCTYPE html>
<html xmlns:fb="http://ogp.me/ns/fb#" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes" />

    <title><?php echo he($app_name); ?></title>
    <link rel="stylesheet" href="stylesheets/screen.css" media="Screen" type="text/css" />
    <link rel="stylesheet" href="stylesheets/mobile.css" media="handheld, only screen and (max-width: 480px), only screen and (max-device-width: 480px)" type="text/css" />

    <!--[if IEMobile]>
    <link rel="stylesheet" href="mobile.css" media="screen" type="text/css"  />
    <![endif]-->

    <!-- These are Open Graph tags.  They add meta data to your  -->
    <!-- site that facebook uses when your content is shared     -->
    <!-- over facebook.  You should fill these tags in with      -->
    <!-- your data.  To learn more about Open Graph, visit       -->
    <!-- 'https://developers.facebook.com/docs/opengraph/'       -->
    <meta property="og:title" content="<?php echo he($app_name); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo AppInfo::getUrl(); ?>" />
    <meta property="og:image" content="<?php echo AppInfo::getUrl('/logo.png'); ?>" />
    <meta property="og:site_name" content="<?php echo he($app_name); ?>" />
    <meta property="og:description" content="My first app" />
    <meta property="fb:app_id" content="<?php echo AppInfo::appID(); ?>" />

    <!--[if IE]>
      <script type="text/javascript">
        var tags = ['header', 'section'];
        while(tags.length)
          document.createElement(tags.pop());
      </script>
    <![endif]-->
	
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/dojo/1.7.2/dojo/dojo.js">
	</script>
    <script type="text/javascript" src="/javascript/jquery-1.7.1.min.js"></script>

    <script type="text/javascript">
      function logResponse(response) {
        if (console && console.log) {
          console.log('The response was', response);
        }
      }

      $(function(){
        // Set up so we handle click on the buttons
        $('#postToWall').click(function() {
          FB.ui(
            {
              method : 'feed',
              link   : $(this).attr('data-url')
            },
            function (response) {
              // If response is null the user canceled the dialog
              if (response != null) {
                logResponse(response);
              }
            }
          );
        });

        $('#sendToFriends').click(function() {
          FB.ui(
            {
              method : 'send',
              link   : $(this).attr('data-url')
            },
            function (response) {
              // If response is null the user canceled the dialog
              if (response != null) {
                logResponse(response);
              }
            }
          );
        });

        $('#sendRequest').click(function() {
          FB.ui(
            {
              method  : 'apprequests',
              message : $(this).attr('data-message')
            },
            function (response) {
              // If response is null the user canceled the dialog
              if (response != null) {
                logResponse(response);
              }
            }
          );
        });
      });
    </script>

	<script src="js/jquery-1.2.3.pack.js"></script>
	<script src="js/runonload.js"></script>
	<script src="js/tutorial.js"></script>
	<link href="css/tutorial.css" media="all" type="text/css" rel="stylesheet">
<script type="text/javascript">
    function upLoadAll() { 
		alert("you are here");
	}
	    function downLoadAll($user_id) { 
		$str = "bin/downLoadAll.php?user_id="+$user_id;
	//	alert ($str);// 
      dojo.xhrGet( { // 
        // The following URL must match that used to test the server.	
        url: $str, 
        handleAs: "text",
        timeout: 5000, // Time in milliseconds
        // The LOAD function will be called on a successful response.
        load: function(response, ioArgs) { //
          dojo.byId("replacexx").innerHTML = response; 
          return response; // 
        },
        // The ERROR function will be called in an error case.
        error: function(response, ioArgs) {
		alert (response)
		alert ("HTTP status code: ", ioArgs.xhr.status);			// 
          console.error("HTTP status code: ", ioArgs.xhr.status); //
          dojo.byId("replacexx").innerHTML = 'Loading the ressource from the server did not work'; //  
          return response; // 
          }
        });
      }
	  
		function registerUser(userId) { 
	$str = "/url.php?user_id=greggh";//"/url.php?user_id=" + userId;
		//alert ($str);// 
      dojo.xhrGet( { // 
        // The following URL must match that used to test the server.
		
        url: $str, 
        handleAs: "text",

        timeout: 5000, // Time in milliseconds

        // The LOAD function will be called on a successful response.
        load: function(response, ioArgs) { //
			//	alert (response);
		       var json_data = JSON.parse(response);
         // dojo.byId("replacexx").innerHTML = json_data._message + "...." + json_data._status ; //
		alert (response);		  
          return response; // 
        },

        // The ERROR function will be called in an error case.
        error: function(response, ioArgs) {
		alert (response)
		alert ("HTTP status code: ", ioArgs.xhr.status);			// 
          console.error("HTTP status code: ", ioArgs.xhr.status); //
          dojo.byId("replacexx").innerHTML = 'Loading the ressource from the server did not work'; //  
          return response; // 
          }
        });
      }
  </script>

  </head>
  <body>

    <div id="fb-root"></div>
    <script type="text/javascript">
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '<?php echo AppInfo::appID(); ?>', // App ID
          channelUrl : '//<?php echo $_SERVER["HTTP_HOST"]; ?>/channel.html', // Channel File
          status     : true, // check login status
          cookie     : true, // enable cookies to allow the server to access the session
          xfbml      : true // parse XFBML
        });

        // Listen to the auth.login which will be called when the user logs in
        // using the Login button
        FB.Event.subscribe('auth.login', function(response) {
          // We want to reload the page now so PHP can read the cookie that the
          // Javascript SDK sat. But we don't want to use
          // window.location.reload() because if this is in a canvas there was a
          // post made to this page and a reload will trigger a message to the
          // user asking if they want to send data again.
          window.location = window.location;
        });

        FB.Canvas.setAutoGrow();
      };

      // Load the SDK Asynchronously
      (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/all.js";
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
    </script>

    <script type="text/javascript">
		//he(idx($basic, 'name'))
	    $(document).ready(function() {
        downLoadAll("<?php if ($user_id) echo he($user_id); else echo 'anonymous';?>");
    });
  </script> 
    <header class="clearfix">
	
      <?php if (isset($basic)) { ?>
      <p id="picture" style="background-image: url(https://graph.facebook.com/<?php echo he($user_id); ?>/picture?type=normal)"></p>

      <div>
        <h1>Welcome, <strong><?php echo he(idx($basic, 'name')); ?></strong></h1>
        <p class="tagline">
          This is the
          <a href="<?php echo he(idx($app_info, 'link'));?>" target="_top"><?php echo he($app_name); ?></a>
        </p>

        <div id="share-app">
          <p>Share your app:</p>
          <ul>
            <li>
              <a href="#" class="facebook-button" id="postToWall" data-url="<?php echo AppInfo::getUrl(); ?>">
                <span class="plus">Post to Wall</span>
              </a>
            </li>
            <li>
              <a href="#" class="facebook-button speech-bubble" id="sendToFriends" data-url="<?php echo AppInfo::getUrl(); ?>">
                <span class="speech-bubble">Send Message</span>
              </a>
            </li>
            <li>
              <a href="#" class="facebook-button apprequests" id="sendRequest" data-message="Test this awesome app">
                <span class="apprequests">Send Requests</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
      <?php } else { ?>
      <div>
        <h1>Welcome</h1>
        <div class="fb-login-button" data-scope="user_likes,user_photos"></div>
      
	  Login to update your social tax graph to add new items or utilize the advance Tax Graph tools.
	  If you decide not to login, input your will be recorded in the pre-determined Choice. ( sort of like politics witth United Citzens runing the show!)</div>
      <?php } ?>
	  <!-- Place this tag where you want the +1 button to render -->
<div class="g-plusone" data-annotation="inline"></div>

<!-- Place this render call where appropriate -->
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
	  	  </header>
<table>
<tr><td>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-7293850990239714";
/* socialtaxgraph */
google_ad_slot = "0306136225";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</td></tr><tr><td>
   <section id="why-me" class="clearfix">

<div id="contact_form">
  <form name="contact" method="post" action="">
    <fieldset>
	<div id="replacexx" style="font-size: big"></div>
	<input type="hidden" name="userid" id="userid" value="<?php  if ($user_id) echo  he($user_id); else echo 'anonymous';?>">
	<input type="hidden" name="pwd" id="pwd" value="<?php  if ($user_id) echo $user_id; else echo 'anonymous';?>">

<hr/>


    	<br />
      <input type="submit" name="submit" class="button" id="submit_btn" value="Send" />
    </fieldset>
  </form>
 </div>
 <?php 
if ($user_id) {
?>
<a href="bin/addnew.php?user_id=<?php echo $user_id;?>">Click here to add a new category for spending </a> OR <a href="bin/newtree.php?user_id=<?php echo $user_id;?>"> to manage the social graph in tree form</a>

<?php
}
?>

    </section>
	</td></tr></table>

<?php 
if ($user_id) {
?>

    <section id="samples" class="clearfix">
  <div class="list">
        <h3>A few of your friends</h3>
        <ul class="friends">
          <?php
            foreach ($friends as $friend) {
              // Extract the pieces of info we need from the requests above
              $id = idx($friend, 'id');
              $name = idx($friend, 'name');
          ?>
          <li>
            <a href="https://www.facebook.com/<?php echo he($id); ?>" target="_top">
              <img src="https://graph.facebook.com/<?php echo he($id) ?>/picture?type=square" alt="<?php echo he($name); ?>">
              <?php echo he($name); ?>
            </a>
          </li>
          <?php
            }
          ?>
        </ul>
      </div>


      <div class="list">
        <h3>Friends using this app</h3>
        <ul class="friends">
          <?php
            foreach ($app_using_friends as $auf) {
              // Extract the pieces of info we need from the requests above
              $id = idx($auf, 'uid');
              $name = idx($auf, 'name');
          ?>
          <li>
            <a href="https://www.facebook.com/<?php echo he($id); ?>" target="_top">
              <img src="https://graph.facebook.com/<?php echo he($id) ?>/picture?type=square" alt="<?php echo he($name); ?>">
              <?php echo he($name); ?>
            </a>
          </li>
          <?php
            }
          ?>
        </ul>
      </div>
    </section>
<?php
}
?>

  </body>
</html>
