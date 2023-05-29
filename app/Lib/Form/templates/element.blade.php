<?php
echo '<' . $type . ' ';
array_walk($attributes, function($value, $attribute) { 
    if($value!=null && $attribute!=null && $attribute!='type') {
        echo $attribute . '="'.$value.'" ';
    } 
});
echo '>';
echo $content ?? '';
echo '</' . $type . '>';