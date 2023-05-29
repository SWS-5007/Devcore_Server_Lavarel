<?php
echo '<option ';
array_walk($attributes, function($value, $attribute) { 
    if($value!=null && $attribute!=null) {
        echo $attribute . '="'.$value.'" ';
    }
});
echo '>' . $label . '</option>';