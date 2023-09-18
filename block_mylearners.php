<?php
/**
 * @package   block_mylearners
 * @author      Robert Tyrone Cullen
 */
use block_mylearners\lib;
class block_mylearners extends block_base{
    //Initialization function, defines title
    public function init(){
        $this->title = 'My Learners';
    }
    //Content for the block
    public function get_content(){
        $this->content = new stdClass();
        $lib = new lib();
        $this->content->text = '';
        $array = $lib->get_enrolled_courses();
        if($array != []){
            if(has_capability('block/mylearners:coach', context_course::instance($array[0][1]))){
                $this->content->text = "<link rel='stylesheet' href='./../blocks/mylearners/classes/css/mylearners.css'><h2 class='text-center'>".get_string('your_c', 'block_mylearners')."</h1><div class='text-center'>";
                foreach($array as $data){
                    $this->content->text .= "<button class='btn btn-primary mr-1 mb-1' onclick='course_learners($data[1])'>$data[0]</button>";
                }
                $this->content->text .= "</div><h2 class='text-danger text-center' id='mylearners_error' style='display:none;'></h2><div id='mylearners_div' style='display:none;'></div><script src='./../blocks/mylearners/amd/min/mylearners.min.js'></script>";
            }
        }
    }
}