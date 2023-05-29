<?php
echo '<input ';
array_walk($attributes, function($value, $attribute) { 
    if($value!=null && $attribute!=null) {
        echo $attribute . '="'.$value.'" ';
    }
});
echo ' />';

if($errors->count()):
    ?>
<span class="invalid-feedback">
    <?=$errors->first()?>
</span>
<?php
endif; 