<?php
$owner = $item->getOwner();
?>

<div id="user-profiles-link-to-owner">
Added by <a href="<?php echo url('user-profiles/profiles/user/id/' . $owner->id); ?>"><?php echo $owner->name; ?></a>
</div>