<?php
if(isset($item)) {
    $owner = $item->getOwner();    
} 



?>

<div id="user-profiles-link-to-owner">
<?php echo $text; ?> <a href="<?php echo url('user-profiles/profiles/user/id/' . $owner->id); ?>"><?php echo $owner->name; ?></a>
</div>