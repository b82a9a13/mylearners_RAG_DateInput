<?php
require_once(__DIR__.'/../../../../config.php');
require_login();
use block_mylearners\lib;
$lib = new lib();
$returnText = new stdClass();
$p = 'block_mylearners';

if(!isset($_POST['id'])){
    $returnText->error = get_string('no_idvp', $p);
} else {
    $id = $_POST['id'];
    if(!preg_match("/^[0-9]*$/", $id) || empty($id)){
        $returnText->error = get_string('invalid_idp', $p);
    } else {
        if(!has_capability('block/mylearners:coach', context_course::instance($id))){
            $returnText->error = "You aren't a coach for the course provided";
        } else {
            $array = $lib->get_enrolled_learners($id);
            if(count($array) == 0){
                $returnText->return = '<h2 class="text-danger">'.get_string('no_la', $p).'</h2>';
            } else {
                $returnText->return = "<div class='table-section'><h2 class='text-center'>".$lib->get_course_fullname($id)."</h2><table class='table table-bordered table-striped table-hover'>
                    <thead>
                        <tr>
                            <th>".get_string('learner', $p)."</th>
                            <th>".get_string('on_t', $p)."</th>
                            <th>".get_string('total_ou', $p)."</th>
                        </tr>
                    </thead>
                    <tbody>
                ";
                $int = 0;
                foreach($array as $arr){
                    $returnText->return .= "
                    <tr>
                        <td onclick='window.location.href=`./../user/view.php?id=$arr[1]&course=$id`' class='text-primary c-pointer'>$arr[0]</td>
                    ";
                    if($arr[2] === false){
                        $returnText->return .= "
                        <td>
                            Input start and end date for all modules to be completed
                            <div>
                                <span>Start Date:<input type='date' id='ml_startdate_$int'></span>
                                <span>End Date:<input type='date' id='ml_enddate_$int'></span>
                                <button class='btn btn-primary' onclick='submit_comp_dates($int, $arr[1], $id)'>Submit</button>
                                <h4 class='text-center text-danger' style='display:none;' id='ml_error_$int'></h4>
                            </div>
                        </td>";
                    } else {
                        $returnText->return .= "<td style='background-color:$arr[2];'></td>";
                    }
                    $returnText->return .= "
                        <td>$arr[3]</td>
                    </tr>";
                    $int++;
                }
                $returnText->return .= "</tbody></table></div>";
                $returnText->return = str_replace("  ","",$returnText->return);
                \block_mylearners\event\viewed_mylearners_course::create(array('context' => \context_course::instance($id), 'courseid' => $id))->trigger();
            }
        }
    }
}

echo(json_encode($returnText));