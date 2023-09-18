<?php
require_once(__DIR__.'/../../../../config.php');
require_login();
use block_mylearners\lib;
$lib = new lib();
$returnText = new stdClass();
$p = 'block_mylearners';

if(!isset($_POST['s'])){
    $returnText->error = 'No start date provided';
} else if(!isset($_POST['e'])){
    $returnText->error = 'No end date provided';
} else if(!isset($_POST['u'])){
    $returnText->error = 'No user provided';
} else if(!isset($_POST['c'])){
    $returnText->error = 'No course provided';
} else {
    $startdate = $_POST['s'];
    $enddate = $_POST['e'];
    $userid = $_POST['u'];
    $courseid = $_POST['c'];
    if(!preg_match("/^[0-9\-]*$/", $startdate) || empty($startdate)){
        $returnText->error = 'Invalid start date provided';
    } else if(!preg_match("/^[0-9\-]*$/", $enddate) || empty($enddate)){
        $returnText->error = 'Invalid end date provided';
    } else if(!preg_match("/^[0-9]*$/", $userid) || empty($userid)){
        $returnText->error = 'Invalid user provided';
    } else if(!preg_match("/^[0-9]*$/", $courseid) || empty($courseid)){
        $returnText->error = 'Invalid course provided';
    } else {
        if(!has_capability('block/mylearners:coach', context_course::instance($courseid))){
            $returnText->error = "You aren't a coach for the course provided";
        } else {
            $startdate = (new DateTime($startdate))->format('U');
            $enddate = (new DateTime($enddate))->format('U');
            $returnText->return = $lib->create_comp_dates($courseid, $userid, $startdate, $enddate);
        }
    }
}


echo(json_encode($returnText));