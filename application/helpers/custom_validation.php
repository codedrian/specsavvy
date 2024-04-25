<?php
// my_custom_validation_helper.php
function no_script_tags($str)
{
    $pattern = '/<|>/';
    if (preg_match($pattern, $str)) {
        return false;
    } else {
        return true;
    }
}
