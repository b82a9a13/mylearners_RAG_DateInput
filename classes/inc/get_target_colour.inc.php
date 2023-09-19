<?php
require_once(__DIR__.'/../../../../config.php');
require_login();
use block_mylearners\lib;
$lib = new lib();
$returnText = new stdClass();
$p = 'block_mylearners';

if(!isset($_POST['u'])){
    $returnText->error = get_string('no_up', $p);
} else if(!isset($_POST['c'])){
    $returnText->error = get_string('no_cp', $p);
} else {
    $userid = $_POST['u'];
    $courseid = $_POST['c'];
    $match = "/^[0-9]*$/";
    if(!preg_match($match, $userid) || empty($userid)){
        $returnText->error = get_string('invalid_up', $p);
    } else if(!preg_match($match, $courseid) || empty($courseid)){
        $returnText->error = get_string('invalid_cp', $p);
    } else {
        if(!has_capability('block/mylearners:coach', context_course::instance($courseid))){
            $returnText->error = get_string('you_aac', $p);
        } else {
            $returnText->return = $lib->get_comp_target_colour($courseid, $userid);
            \block_mylearners\event\viewed_target_colour::create(array('context' => \context_course::instance($courseid), 'courseid' => $courseid, 'relateduserid' => $userid))->trigger();
        }
    }
}

echo(json_encode($returnText));