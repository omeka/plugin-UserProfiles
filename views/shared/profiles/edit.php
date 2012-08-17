<?php
queue_css('profiles');
$head = array('title' => 'Edit Profile', 'content_class' => 'horizontal-nav');
head($head);
$field_type_atts = array(
                    'Text' => array(),
                    'Textarea' => array(),
                    'Radio' => array(),
                    'Select' => array(),
                    'Checkbox' => array(),
                    'MultiCheckbox' => array()
                    );
$currentType = isset($_GET['type']) ? $_GET['type'] : $this->profile_types[0]->id;
$types = apply_filters('user_profiles_type', $this->profile_types);
?>
<p><?php user_profiles_link_to_profile($this->user, $this->user->username . "'s public profile"); ?></p>
<h1>Edit your profile</h1>
<?php if(count($types) > 1): ?>
<p>Submit your information before switching to a different tab.</p>

<ul id="section-nav" class="navigation">
	<?php foreach($types as $type): ?>
    <li class="<?php if ($currentType == $type->id )  {echo 'current';} ?>">
        <a href="<?php echo html_escape(uri('user-profiles/profiles/edit/id/' . $this->user->id .'?type='.$type->id)); ?>"><?php echo $type->label; ?></a>
    </li>
	<?php endforeach;?>
</ul>
<?php endif; ?>
<div id="primary">


<?php foreach($types as $type): ?>
<?php if($type->id != $currentType ) {continue;} ?>
<form method="post">
<div class="user-profiles-profile">
<h2><?php echo $type->label; ?></h2>
<p class="user-profiles-profile-description">
<?php echo $type->description; ?>
</p>
<?php if(isset($type->fields)): ?>
<?php foreach($type->fields as $field): ?>
<div class="user-profiles-profile-field">
	<h3><?php echo $field['label']; ?></h3>
	<p><?php echo $field['description']; ?></p>
	<?php $form_type = 'form'. $field['type']; ?>
	<div class="user-profiles-input">
	<?php
		switch($field['type']) {
		    case 'Text':
		    case 'Textarea':
		    	$values = isset($this->profiles[$type->id]) ? $this->profiles[$type->id]['values'][$field['label']] : '';
		    break;

		    default:
		    	if(isset($this->profiles[$type->id]) && isset($this->profiles[$type->id]['values'][$field['label']])) {
		    	    $values = $this->profiles[$type->id]['values'][$field['label']];
		    	} else {
		    	    $values = array();
		    	}

		    break;
		}
		echo __v()->$form_type('user_profiles['. $type->id . '][' . $field['label'] .']', $values, $field_type_atts[$field['type']], $field['values']);
	?>

	</div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>
<?php if(isset($type->html)):?>
<?php echo $type->html; ?>
<?php endif;?>

<?php endforeach; ?>

<?php echo __v()->formSubmit('user_profile_submit', 'Submit', array('class' => 'submit submit-medium')); ?>
</form>
<?php
if(isset($this->profiles[$currentType])) {
	$profile = $this->profiles[$currentType];
	echo delete_button('user-profiles/profiles/delete-confirm/id/' . $profile->id , 'delete-page', 'Delete this Profile?');
}
?>

</div>

<?php foot(); ?>
