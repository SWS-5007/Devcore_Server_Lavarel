<?php
echo '<button ';
array_walk($attributes, function($value, $attribute) { 
    if($value!=null && $attribute!=null) {
        echo $attribute . '="'.$value.'" ';
    }
});
echo '>' . $text . '</button>';