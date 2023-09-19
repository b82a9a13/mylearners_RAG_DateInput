<?php
// This file is part of My learners block plugin
/**
 * @package     block_mylearners
 * @author      Robert Tyrone Cullen
 * @var stdClass $plugin
 */

namespace block_mylearners\event;

use core\event\base;

defined('MOODLE_INTERNAL') || die();

class viewed_target_colour extends base {
    protected function init(){
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_TEACHING;
    }
    public static function get_name(){
        return 'Target colour viewed';
    }
    public function get_description(){
        return "The user with id '".$this->userid."' viewed the target colour for learner with id '".$this->relateduserid."' and for the course with id '".$this->courseid."'";
    }
    public function get_url(){
        return new \moodle_url('/my/index.php');
    }
    public function get_id(){
        return $this->objectid;
    }
}