<?php 
    function redirect_to($new_loc){
        header("Location:".$new_loc);
        exit;
    }
?>