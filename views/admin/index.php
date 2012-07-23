<?php
$head = array('title'=>'User Profiles', 'bodyclass'=>'');
head($head);

?>
<?php flash(); ?>
<h1><?php echo $head['title']; ?></h1>

<?php if(has_permission('UserProfiles_Type', 'add')): ?>
<p id="add-type" class="add-button"><a class="add" href="<?php echo html_escape(uri('user-profiles/types/add')); ?>">Add a Profile Type</a></p>
<?php endif; ?>
<div id="primary">

<table>
<thead>
<tr>
<th>Profile Type</th>
<th>Description</th>
<th>Fields</th>
<th>Edit My Profile</th>
</tr>
</thead>
    <tbody>
    <?php foreach($this->types as $type): ?>
    <tr>
    	<td>
    		<?php if(has_permission('UserProfiles_Type', 'edit')): ?>
    		<a href="user-profiles/types/edit/id/<?php echo $type->id; ?>">
    		<?php echo $type->label; ?>
    		</a>
    		<?php else: ?>
    		<?php echo $type->label; ?>
    		<?php endif; ?>
    	</td>
    	<td><?php echo $type->description; ?></td>
    	<td><ul>
    	<?php foreach( unserialize($type->fields) as $field): ?>
    	<li><?php echo $field['label']; ?> : <?php echo $field['type']; ?></li>
    	<?php endforeach; ?>
    	</ul></td>
    	<td><a href="user-profiles/profiles/edit/id/<?php echo current_user()->id . '?type=' . $type->id; ?>">Edit</a></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php foot(); ?>