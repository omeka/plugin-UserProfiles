<?php
$head = array('title' => 'Edit Profile Type');
head($head);
$fieldTypeOptions = array(
                    'Text' => 'Short Text',
                    'Textarea' => 'Long Text',
                    'Select' => 'Dropdown',
                    'Checkbox' => 'Checkbox',
                    'MultiCheckbox' => 'Multiple Checkboxes',
                    'Radio' => 'Radio Buttons',
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
		    case "Checkbox":
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
<style type="text/css">
tr.invalid { background-color: #F1C8BA; }
</style>
<div id="primary">
<?php echo flash(); ?>
<p>Create or edit your profile type here. Keep in mind that making changes to the profile type
could cause confusion or errors if users have already created their profile for this type.
</p>

<form method="post">
<p>Type label</p>
<?php echo __v()->formText("type_label", $this->label, array('size' => 15)); ?>
<p>Type Description</p>
<?php echo __v()->formTextarea("type_description", $this->description, array('cols' => 25, 'rows' => 3)); ?>

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

    <?php foreach($this->fields as $field):?>
    <tr class="<?php echo $field['valid'] ? 'valid' : 'invalid'; ?>">
            <td><?php echo __v()->formText("field_labels[]", $field['label'], array('size' => 15)); ?></td>
            <td><?php echo __v()->formTextarea("field_descriptions[]", $field['description'], array('cols' => 25, 'rows' => 3)); ?></td>
            <td><?php echo __v()->formSelect("field_types[]", $field['type'], array('multiple'=>false), $fieldTypeOptions ); ?></td>
            <?php $atts = array('cols' => 20, 'rows' => 2);
            	switch($field['type']) {
            	    case 'Text':
            	    case 'Textarea':
            	    case 'Checkbox':
            	    	$atts['disabled'] = 'true';
            	    break;

            	    default:

            	    break;
            	}

            ?>
            <?php
            	$field_values = implode("\n", $field['values']);
            ?>
            <td><?php echo __v()->formTextarea("field_values[]", $field_values, $atts); ?></td>
    </tr>

    <?php endforeach; ?>
        <tr class="new-field">
            <td><?php echo __v()->formText("field_labels[]", null, array('size' => 15)); ?></td>
            <td><?php echo __v()->formTextarea("field_descriptions[]", null, array('cols' => 25, 'rows' => 3)); ?></td>
            <td><?php echo __v()->formSelect("field_types[]", null, array('multiple'=>false), $fieldTypeOptions ); ?></td>
            <td><?php echo __v()->formTextarea("field_values[]", null, array('cols' => 20, 'rows' => 2, 'disabled'=>'true')); ?></td>
        </tr>
    </tbody>
</table>
<?php echo __v()->formButton('add_field', 'Add a field', array('id' => 'add-field')); ?>
<?php echo __v()->formSubmit('submit_edit_type', 'Submit', array('class' => 'submit submit-medium')); ?>
</form>
<?php echo delete_button(null, 'delete-page', 'Delete this Profile Type?'); ?>
</div>