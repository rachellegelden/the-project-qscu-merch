<?php
function isValidInput($input){
    $regex ='/^(?!\s*$)[a-zA-Z0-9 ._()\':,\n@]+$/';
    if(preg_match_all($regex, $input)){
        return true;
    }else{
        return false;
    }
}


function sanitizeInput($input){
    $regex = '[<>"=/\[\]!@#$%^&*{}`~\;]';
    return preg_replace($regex,"",$input);
}
?>