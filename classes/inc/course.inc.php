<?php
require_once(__DIR__.'/../../../../config.php');
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
        $array = $lib->get_enrolled_learners($id);
        if(count($array) == 0){
            $returnText->return = '<h2 class="text-danger">'.get_string('no_la', $p).'</h2>';
        } else {
            $returnText->return = "<div class='table-section'><h2 class='text-center'>".$lib->get_course_fullname($id)."</h2><table class='table table-bordered table-striped table-hover'>
                <thead>
                    <tr>
                        <th>".get_string('learner', $p)."</th>
                        <th>".get_string('on_t', $p)."</th>
                    </tr>
                </thead>
                <tbody>
            ";
            foreach($array as $arr){
                $returnText->return .= "<tr>
                    <td onclick='window.location.href=`./../user/view.php?id=$arr[1]&course=$id`' class='text-primary c-pointer'>$arr[0]</td>
                    <td></td>
                </tr>";
            }
            $returnText->return .= "</tbody></table></div>";
            $returnText->return = str_replace("  ","",$returnText->return);
            \block_mylearners\event\viewed_mylearners_course::create(array('context' => \context_course::instance($id), 'courseid' => $id))->trigger();
        }
    }
}

echo(json_encode($returnText));