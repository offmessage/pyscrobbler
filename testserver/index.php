<?php
// Author:    Andy Theyers <andy@isotoma.com>
// Version:   1.0
// Docformat: restructuredtext
/*
---------------------------------
index.php: Performs the handshake
---------------------------------
This attempts to mimic the handshake for a Protocol1.1_ client.

.. _Protocol1.1: http://www.audioscrobbler.net/wiki/Protocol1.1

*/
require_once('config.php');

header("Content-type: text/plain");
echo getMessage();
logMessage();

function getMessage() {
    global $config_phrase, $config_baseurl;
    $interval = getInterval();
    
    $test = checkVariables();
    if ($test != '') {
        return $test . $interval;
    }
    $test = checkUser();
    if ($test != '') {
        return $test . $interval;
    }
    $test = checkHandshake();
    if ($test != '') {
        return $test . $interval;
    }
    $test = checkProtocol();
    if ($test != '') {
        return $test . $interval;
    }
    $test = checkClientName();
    if ($test != '') {
        return $test . $interval;
    }
    $message = checkClientVersion();
    if (substr($message, 0, 6) == "FAILED") {
        return $message . $interval;
    }
    // OK - we have a success:
    $msg = $message . "\n";
    $msg .= md5($config_phrase) . "\n";
    $msg .= $config_baseurl . "post.php";
    $msg .= $interval;
    return $msg;
}

function checkVariables() {
    $expected_keys = array('hs', 'p', 'c', 'v', 'u');
    foreach ($expected_keys as $key) {
        if (!isset($_GET[$key])) {
            return "FAILED Not all variables set";
        }
    }
    return '';
}

function checkHandshake() {
    if ($_GET['hs'] != "true") {
        return "FAILED Handshake not set";
    }
    return '';
}

function checkProtocol() {
    global $config_protocol_version;
    if ($_GET['p'] != $config_protocol_version) {
        return "FAILED Unsupported protocol version";
    }
    return '';
}

function checkClientName() {
    global $config_client_name;
    if ($_GET['c'] != $config_client_name) {
        return "FAILED Unknown client name";
    }
    return '';
}

function checkUser() {
    global $config_username;
    if ($_GET['u'] != $config_username) {
        return "BADUSER";
    }
    return '';
}

function checkClientVersion() {
    global $config_current_version, $config_baseurl;
    $cv = floatval($config_current_version);
    $ov = floatval($_GET['v']);
    if ($cv > $ov) {
        return "UPDATE " . $config_baseurl . "update.php";
    }
    if ($cv == $ov) {
        return "UPTODATE";
    }
    if ($cv < $ov) {
        return "FAILED Unknown client version";
    }
}
?>