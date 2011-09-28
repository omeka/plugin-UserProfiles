<?php


$head = array('title' => "User Profile | " . $this->user->username,
              'bodyclass' => '');
head($head); ?>



<div id="primary">
<?php echo flash(); ?>
<?php if(empty($this->user)) { die(); } ?>
    
    <h1><?php echo $head['title']; ?></h1>
    
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
    	<?php foreach($values as $value): ?>
    	<li><?php echo $field['values'][$value] ?></li>
    	<?php endforeach; ?>
    	</ul>

	<?php endif; ?>

	</div>
</div>
<?php endforeach; ?>
</div>
<?php endforeach; ?>
<?php echo $this->filtered_html; ?>
</div>
<?php foot(); ?>