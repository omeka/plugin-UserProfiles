<?php
$head = array('title'=>'User Profiles', 'bodyclass'=>'');
queue_css_file('user-profiles');
echo head($head);

?>
<?php echo flash(); ?>

<?php if(is_allowed('UserProfiles_Type', 'add')): ?>
<p id="add-type" class="add-button"><a class="add green button" href="<?php echo html_escape(url('user-profiles/types/add')); ?>">Add a Profile Type</a></p>
<?php endif; ?>
<div id="primary">

<table>
<thead>
<tr>
<th>Profile Type</th>
<th>Description</th>
<th>Elements</th>
<th>My Profile</th>
</tr>
</thead>
    <tbody>
    <?php foreach($this->types as $type): ?>
    <tr>
    	<td>
    		<?php if(is_allowed('UserProfiles_Type', 'edit')): ?>
    		<a href="user-profiles/types/edit/id/<?php echo $type->id; ?>">
    		<?php echo $type->label; ?>
    		</a>
    		<?php else: ?>
    		<?php echo $type->label; ?>
    		<?php endif; ?>
    	</td>
    	<td><?php echo $type->description; ?></td>
    	<td><ul id="user-profiles-element-list">
    	<?php foreach($type->Elements as $element): ?>
    	<li><?php echo $element->name; ?>
        	<?php if($element->type) :?>
        	(<?php echo $element->type; ?>)
        	<?php else: ?>
        	(text)
        	<?php endif; ?>
    	</li>
    	<?php endforeach; ?>
    	</ul></td>
    	<td><a href="<?php echo url('user-profiles/profiles/user/id/' . current_user()->id . '?type=' . $type->id); ?>">View</a> | <a href="<?php echo url('user-profiles/profiles/edit/id/' . current_user()->id . '?type=' . $type->id); ?>">Edit</a></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php echo foot(); ?>