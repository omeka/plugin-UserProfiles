<?php


$head = array('title' => "User Profile | " . $this->user->username,
              'bodyclass' => '');
head($head); ?>



<div id="primary">
<?php echo flash(); ?>
<?php if(empty($this->user)) { die(); } ?>
    
    <h1><?php echo $head['title']; ?></h1>
<?php if(current_user()->id == $this->user->id): ?>
	<p>
	<?php user_profiles_link_to_profile_edit($this->user); ?>
	</p>
<?php endif; ?>
<?php if(empty($this->profiles)): ?>
<p><?php echo $this->user->username; ?> has not filled out a profile yet.</p>
<?php die(); endif; ?>

<?php foreach($this->profile_types as $type): ?>

<div class="user-profiles-profile">
<h2><?php echo $type->label; ?></h2>

<?php foreach($type->fields as $field): ?>

<div class="user-profiles-profile-field">
	<h3><?php echo $field['label']; ?></h3>
	<div class="user-profiles-field-values">
	<?php $values =  $this->profiles[$type->id]['values'][$field['label']];?>
	<?php if($field['type'] == 'Text' || $field['type'] == 'Textarea'): ?>

    	<p><?php echo $values; ?></p>
    	<?php else: ?>
	
    	<ul>
    	<?php if(is_array($values)): ?>
        	<?php foreach($values as $value): ?>
        	<li><?php echo $field['values'][$value]; ?></li>
        	<?php endforeach; ?>
        <?php else: ?>
        	<?php if($field['type'] == 'Checkbox'): ?>
            	<li><?php echo $values; ?></li>
        	<?php else: ?>
            	<li><?php echo $field['values'][$values]; ?></li>
        	<?php endif; ?>
        <?php endif; ?>
    	</ul>

	<?php endif; ?>

	</div>
</div>
<?php endforeach; ?>
</div>

<?php endforeach; ?>
<?php fire_plugin_hook('user_profiles_append_to_user_page', $this->user); ?>


<!--  end primary -->
</div>
<?php foot(); ?>