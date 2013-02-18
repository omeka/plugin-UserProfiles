<?php
if(isset($item)) {
    $owner = $item->getOwner();    
} 

if(!isset($type)) {
    //get the first type
    $types = get_db()->getTable('UserProfilesType')->findBy(array(), 1);
    $type = $types[0];
}

?>

<div id="user-profiles-link-to-owner">
<?php echo $text; ?> <a href="<?php echo url('user-profiles/profiles/user/id/' . $owner->id . '?type=' . $type->id); ?>"><?php echo $owner->name; ?></a>
</div>