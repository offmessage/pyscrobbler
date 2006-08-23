<?php
// Author:    Andy Theyers <andy@isotoma.com>
// Version:   1.0
// Docformat: restructuredtext
/*
-------------------------------------------------------
Config.php: Holds the configuration for the test server
-------------------------------------------------------
The following variables should be changed to match your environment.

$baseurl
    Set this to be the URL of the directory from which these files are served.
    
$username
    Set this to the username that you want to use
    
$password
    Set this to the password that you're trying to check
    
$secret
    Set this to anything you like, it really doesn't matter.
    
$logfile
    If this is the empty string the requests to the server are not logged to
    disk. If you set it to a file that file must be writeable by the user that
    your web server runs as.
*/
$config_baseurl = "http://garlic-docs/andy/audioscrobbler/";
$config_username = "offmessage";
$config_password = "mypasswd";
$config_client_name = "tst";
$config_current_version = "1.0";
$config_protocol_version = "1.1";
$config_phrase = "Pardon me while I poke out my mind's eye";
$config_logfile = "/tmp/audioscrobbler.log";

// Calculated variables
$config_secret = md5(md5($config_password) . md5($config_phrase));

// Base functions
function getInterval() {
    // Return a random interval
    $intervals = array (
        "",
        "",
        "",
        "\nINTERVAL 0",
        "\nINTERVAL 0",
        "\nINTERVAL 0",
        "\nINTERVAL 1",
        "\nINTERVAL 2"
    );
    return $intervals[array_rand($intervals)];
}

function logMessage() {
    global $config_logfile;
    $expected_keys = array('a', 't', 'b', 'm', 'l', 'i');
    if ($config_logfile != "") {
        $handle = fopen($config_logfile, 'a+');
        $logmsg = "=====================================================\n";
        $logmsg .= date("Y-m-d H:i:s", time()) . "\n";
        $logmsg .= "=====================================================\n";
        ob_start();
        var_dump($_POST);
        var_dump($_GET);
        $logmsg .= ob_get_contents();
        ob_end_clean();
        $logmsg .= "\n";
        fwrite($handle, $logmsg);
        fclose($handle);
    }
}
?>