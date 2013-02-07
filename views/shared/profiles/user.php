<?php

$user = $profiles[0]->getOwner();

$head = array('title' => "User Profile | " . $user->name,
              'bodyclass' => '');
echo head($head); 

?>



<div id="primary">
<?php echo flash(); ?>
    
    <h1><?php echo $head['title']; ?></h1>
<?php if(is_allowed('UserProfiles_Profile', 'editOwn')): ?>
	<p>
	    <?php echo link_to($profiles[0], 'edit', 'Edit profile', array(), array('id'=>$user->id) ); ?>
	</p>
<?php endif; ?>
<?php if(empty($profiles) && is_allowed('UserProfiles_Profile', 'editOwn')): ?>

<p><?php echo is_allowed('UserProfiles_Profile', 'editOwn') ? "You have" : $user->username . " has"; ?> not filled out a profile yet.</p>
<?php endif; ?>

<?php foreach($profiles as $profile): ?>
<?php $type = $profile->getProfileType();?>
<div class="user-profiles-profile">
<?php echo all_element_texts($profile); ?>
</div>

<?php endforeach; ?>
<?php fire_plugin_hook('user_profiles_append_to_user_page', array($this->user) ); ?>


<!--  end primary -->
</div>
<?php echo foot(); ?>