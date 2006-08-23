<?php
// Author:    Andy Theyers <andy@isotoma.com>
// Version:   1.0
// Docformat: restructuredtext
/*
----------------------------------------------
Post.php: Accepts a track update from a client
----------------------------------------------
This attempts to mimic the post URL for a Protocol1.1_ client.  To be honest
I've only mimicked the ``FAILED`` responses that I've seen, so there may well
be more.  If you find others and found this code useful please let me know and
I'll update it to include anything else you discover.

.. _Protocol1.1: http://www.audioscrobbler.net/wiki/Protocol1.1

*/
require_once('config.php');

header("Content-type: text/plain");
echo getMessage();
logMessage();

function getMessage() {
    $interval = getInterval();
    $msg = '';
    if (!checkAuth()) {
        $msg = "BADAUTH" . $interval;
        return $msg;
    }
    if (!checkVariables()) {
        $msg = "FAILED Not all variables set" . $interval;
        return $msg;
    }
    if (!checkDates()) {
        $msg = "FAILED Date not in correct format" . $interval;
        return $msg;
    }
    $msg = "OK" . $interval;
    return $msg;
}

function checkAuth() {
    // Test that the username is set
    global $config_username, $config_secret;
    if (!(isset($_POST['u']) && $_POST['u'] == $config_username)) {
        return False;
    }
    if (!(isset($_POST['s']) && $_POST['s'] == $config_secret)) {
        return False;
    }
    return True;
}

function checkVariables() {
    $array_length = 0;
    $expected_keys = array('a', 't', 'b', 'm', 'l', 'i');
    foreach ($expected_keys as $key) {
        if (!(isset($_POST[$key]) && is_array($_POST[$key]))) {
            return False;
        }
        $new_length = count($_POST[$key]);
        if ($array_length == 0) {
            $array_length = $new_length;
        } else {
            if ($array_length != $new_length) {
                return False;
            }
        }
        for ($i = 0; $i < $array_length; $i++) {
            if (!isset($_POST[$key][$i])) {
                return False;
            }
        }
    }
    return True;
}

function checkDates() {
    $re = "([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})";
    for ($i = 0; $i < count($_POST['i']); $i++) {
        if (!ereg($re, $_POST['i'][$i])) {
            return False;
        }
    }
    return True;
}

?>