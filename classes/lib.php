<?php 
/**
 * @package   block_mylearners
 * @author    Robert Tyrone Cullen
 * @var stdClass $plugin
 */
namespace block_mylearners;
use dml_exception;
use stdClass;

class lib{
    //Function is used to get all courses the current user is enrolled as a coach for
    public function get_enrolled_courses(): array{
        global $DB;
        global $USER;
        $records = $DB->get_records_sql('SELECT ra.id as id, c.id as courseid, c.fullname as fullname, eu.userid as userid, eu.firstname as firstname, eu.lastname as lastname, ra.roleid as roleid FROM {course} c
        INNER JOIN {context} ctx ON c.id = ctx.instanceid
        INNER JOIN {role_assignments} ra ON ra.contextid = ctx.id AND (ra.roleid = 4 OR ra.roleid = 3)
        INNER JOIN (
            SELECT e.courseid, ue.userid, u.firstname, u.lastname FROM {enrol} e
            INNER JOIN {user_enrolments} ue ON ue.enrolid = e.id AND ue.status != 1
            INNER JOIN {user} u ON u.id = ue.userid
        ) eu ON c.id = eu.courseid AND ra.userid = eu.userid AND eu.userid = ?',[$USER->id]);
        $array = [];
        foreach($records as $record){
            array_push($array, [$record->fullname, $record->courseid]);
        }
        usort($array, function($a, $b){
            return strcmp($a[0], $b[0]);
        });
        return $array;
    }
    //Function is used to get all the learners for a specific course id
    public function get_enrolled_learners($cid): array{
        global $DB;
        $records = $DB->get_records_sql('SELECT ra.id as id, c.id as courseid, c.fullname as fullname, eu.userid as userid, eu.firstname as firstname, eu.lastname as lastname, ra.roleid as roleid FROM {course} c
        INNER JOIN {context} ctx ON c.id = ctx.instanceid
        INNER JOIN {role_assignments} ra ON ra.contextid = ctx.id AND ra.roleid = 5
        INNER JOIN (
            SELECT e.courseid, ue.userid, u.firstname, u.lastname FROM {enrol} e
            INNER JOIN {user_enrolments} ue ON ue.enrolid = e.id AND ue.status != 1
            INNER JOIN {user} u ON u.id = ue.userid
        ) eu ON c.id = eu.courseid AND ra.userid = eu.userid AND eu.courseid = ?',[$cid]);
        $array = [];
        foreach($records as $record){
            array_push($array, [$record->firstname.' '.$record->lastname, $record->userid]);
        }
        usort($array, function($a, $b){
            return strcmp($a[0], $b[0]);
        });
        return $array;
    }
    //Get course full name for a specific course id
    public function get_course_fullname($cid): string{
        global $DB;
        return $DB->get_record_sql('SELECT fullname FROM {course} WHERE id = ?',[$cid])->fullname;
    }
}