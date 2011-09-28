<?php
$head = array('title' => 'Edit Profile');
head($head);
$field_type_atts = array(
                    'Text' => array(),
                    'Textarea' => array(),
                    'Radio' => array(),
                    'Select' => array(),
                    'Checkbox' => array()
                    );
?>
<h1>Edit your profile</h1>
<?php foreach($this->profile_types as $type): ?>
<form method="post">
<div class="user-profiles-profile">
<h2><?php echo $type->label; ?></h2>
<p class="user-profiles-profile-description">
<?php echo $type->description; ?>
</p>
<?php foreach($type->fields as $field): ?>
<div class="user-profiles-profile-field">
	<h3><?php echo $field['label']; ?></h3>
	<p><?php echo $field['description']; ?></p>
	<?php $form_type = 'form'. $field['type']; ?>
	<div class="user-profiles-input">
	<?php echo __v()->$form_type('user_profiles['. $type->id . '][' . $field['label'] .']', null, $field_type_atts[$field['type']], $field['values']); ?>
	</div>
</div>
<?php endforeach; ?>
</div>
<?php endforeach; ?>
<?php echo __v()->formSubmit('user_profile_submit', 'Submit', array('class' => 'submit submit-medium')); ?>
</form>


<?php foot(); ?>