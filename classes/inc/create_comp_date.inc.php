<?php
require_once(__DIR__.'/../../../../config.php');
require_login();
use block_mylearners\lib;
$lib = new lib();
$returnText = new stdClass();
$p = 'block_mylearners';

if(!isset($_POST['s'])){
    $returnText->error = get_string('no_sdp', $p);
} else if(!isset($_POST['e'])){
    $returnText->error = get_string('no_edp', $p);
} else if(!isset($_POST['u'])){
    $returnText->error = get_string('no_up', $p);
} else if(!isset($_POST['c'])){
    $returnText->error = get_string('no_cp', $p);
} else {
    $startdate = $_POST['s'];
    $enddate = $_POST['e'];
    $userid = $_POST['u'];
    $courseid = $_POST['c'];
    if(!preg_match("/^[0-9\-]*$/", $startdate) || empty($startdate)){
        $returnText->error = get_string('invalid_sdp', $p);
    } else if(!preg_match("/^[0-9\-]*$/", $enddate) || empty($enddate)){
        $returnText->error = get_string('invalid_edp', $p);
    } else if(!preg_match("/^[0-9]*$/", $userid) || empty($userid)){
        $returnText->error = get_string('invalid_up', $p);
    } else if(!preg_match("/^[0-9]*$/", $courseid) || empty($courseid)){
        $returnText->error = get_string('invalid_cp', $p);
    } else {
        if(!has_capability('block/mylearners:coach', context_course::instance($courseid))){
            $returnText->error = get_string('you_aac', $p);
        } else {
            $startdate = (new DateTime($startdate))->format('U');
            $enddate = (new DateTime($enddate))->format('U');
            $returnText->return = $lib->create_comp_dates($courseid, $userid, $startdate, $enddate);
            \block_mylearners\event\created_comp_dates::create(array('context' => \context_course::instance($courseid), 'courseid' => $courseid, 'relateduserid' => $userid))->trigger();
        }
    }
}


echo(json_encode($returnText));