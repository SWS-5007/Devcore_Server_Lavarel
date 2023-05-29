<?php
echo '<form ';
array_walk($attributes, function($value, $attribute) { 
    if($value!=null && $attribute!=null) {
        echo $attribute . '="'.$value.'" ';
    }
});
echo '>';
if(strtoupper($method??'GET')!='GET'):
?>
<input type="hidden" name="_method" value="<?=strtoupper($method??'POST') ?>" />
<?php if(session('_token')):
?>
<input type="hidden" name="_token" value="<?= session('_token') ?>">
<?php
endif;
endif;
?>
<input type="hidden" name="_frm" value="<?=$name?>" />