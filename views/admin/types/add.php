<?php
$head = array('title' => 'Edit Profile Type');
head($head);
$fieldTypeOptions = array(
                    'Text' => 'Short Text',
                    'Textarea' => 'Long Text',
                    'Select' => 'Dropdown',
                    'Checkbox' => 'Single Checkbox',
                    'MultiCheckbox' => 'Multiple Checkboxes',
                    'Radio' => 'Radio Buttons'
                    );
?>
<script type="text/javascript">

UserProfiles = {

    toggleDisabled : function() {
		target = jQuery(this);
		textArea = jQuery('textarea', target.parent().next() );
		switch(target.val()) {
		    case "Textarea":
		    case "Text":
		    	textArea.attr('disabled', true);
		    break;

		    default:
		    	textArea.attr('disabled', false);
		    break;
		}

    }

}


jQuery(document).ready(function () {
    jQuery('#add-field').click(function () {
        var oldDiv = jQuery('.new-field').last();
        var div = oldDiv.clone();
        oldDiv.parent().append(div);
        var inputs = div.find('input');
        inputs.val('');
        jQuery('[name="field_types[]"]', div).change(UserProfiles.toggleDisabled);
    });


    jQuery('[name="field_types[]"]').change(UserProfiles.toggleDisabled);
});
</script>
<div id="primary">
<?php echo flash(); ?>
<p>Create your profile type here. Keep in mind that making changes to the profile type
could cause confusion or errors if users have already created their profile for this type.
</p>
<form method="post">
<p>Type label</p>
<?php echo __v()->formText("type_label", null, array('size' => 15)); ?>
<p>Type Description</p>
<?php echo __v()->formTextarea("type_description", null, array('cols' => 25, 'rows' => 3)); ?>

<table>
    <thead>
        <tr>
            <th>Field Label</th>
            <th>Description</th>
            <th>Field Type</th>
            <th>Allowed Values (one per line)</th>
        </tr>
    </thead>
    <tbody>

        <tr class="new-field">
            <td><?php echo __v()->formText("field_labels[]", null, array('size' => 15)); ?></td>
            <td><?php echo __v()->formTextarea("field_descriptions[]", null, array('cols' => 25, 'rows' => 2)); ?></td>
            <td><?php echo __v()->formSelect("field_types[]", null, array('multiple'=>false), $fieldTypeOptions ); ?></td>
            <td><?php echo __v()->formTextarea("field_values[]", null, array('cols' => 20, 'rows' => 2, 'disabled'=>true)); ?></td>
        </tr>
    </tbody>
</table>
<?php echo __v()->formButton('add_field', 'Add a field', array('id' => 'add-field')); ?>
<?php echo __v()->formSubmit('submit_add_type', 'Submit', array('class' => 'submit submit-medium')); ?>
</form>
</div>