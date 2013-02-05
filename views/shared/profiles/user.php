<?php

$head = array('title' => "User Profile | " . $this->user->username,
              'bodyclass' => '');
echo head($head); 

?>



<div id="primary">
<?php echo flash(); ?>
<?php if(empty($this->user)) { die(); } ?>
    
    <h1><?php echo $head['title']; ?></h1>
<?php if(is_allowed('UserProfiles_Profile', 'editOwn')): ?>
	<p>
	<?php user_profiles_link_to_profile_edit($this->user); ?>
	</p>
<?php endif; ?>
<?php if(empty($this->profiles) && is_allowed('UserProfiles_Profile', 'editOwn')): ?>

<p><?php echo is_allowed('UserProfiles_Profile', 'editOwn') ? "You have" : $this->user->username . " has"; ?> not filled out a profile yet.</p>
<?php endif; ?>

<?php foreach($this->profile_types as $type): ?>

<div class="user-profiles-profile">
<h2><?php echo $type->label; ?></h2>

</div>

<?php endforeach; ?>
<?php fire_plugin_hook('user_profiles_append_to_user_page', array($this->user) ); ?>


<!--  end primary -->
</div>
<?php echo foot(); ?>