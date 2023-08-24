<?php
// This file is part of Moodle Course Analytics Plugin
/**
 * @package     block_mylearners
 * @author      Robert Tyrone Cullen
 * @var stdClass $plugin
 */

namespace block_mylearners\event;

use core\event\base;

defined('MOODLE_INTERNAL') || die();

class viewed_mylearners_course extends base {
    protected function init(){
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_TEACHING;
    }
    public static function get_name(){
        return 'Learners for a course viewed';
    }
    public function get_description(){
        return "The user with id '".$this->userid."' viewed the learners for the course with id '".$this->courseid."'";
    }
    public function get_url(){
        return new \moodle_url('/my/index.php');
    }
    public function get_id(){
        return $this->objectid;
    }
}