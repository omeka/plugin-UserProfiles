<?php
queue_css_file('profiles');
$head = array('title' => 'Edit Profile', 'content_class' => 'horizontal-nav');
echo head($head);

$types = apply_filters('user_profiles_type', $profile_types);
?>

<ul id='section-nav' class='navigation tabs'>
<?php 

$typesNav = array();
foreach($types as $type) {
    $typesNav[$type->label] = array('label'=>$type->label, 'uri'=>url('user-profiles/profiles/edit/id/' . $this->user->id .'?type='.$type->id));
}

echo nav($typesNav, 'user_profiles_types_user_edit');
?>
</ul>
<p class='warning'>Save changes before moving to edit a new profile type.</p>
<?php echo flash(); ?>

<p>
    <?php user_profiles_link_to_profile($user, $user->username . "'s public profile"); ?>
</p>

<div id="primary">
<h1>Edit your <?php echo $profile_type->label; ?> profile</h1>

<p class="user-profiles-profile-description">
    <?php echo $profile_type->description; ?>
</p>
<form method="post" action="">
<section class="user-profiles-profile seven columns alpha">
    <?php echo element_form($profile_type->getElements(), $profile);?>
</section>

<section class="three columns omega">
    <div id='save' class='panel'>
        <input type="submit" value='Save Changes to <?php echo $profile_type->label; ?>' name='submit' class='big green button'/>    
    </div>
</section>
</form>
</div>

<?php echo foot(); ?>