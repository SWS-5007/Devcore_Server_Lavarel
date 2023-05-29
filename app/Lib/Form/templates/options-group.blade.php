<?php
echo '<optgroup label="' . $label . '" ';
array_walk($attributes, function($value, $attribute) { 
    if($value!=null && $attribute!=null) {
        echo $attribute . '="'.$value.'" ';
    }
});
echo '>';
foreach($items as $item){
    echo $item->getHtml();
}
echo '</optgroup>';