<?php
$head = array('title'=> __('User Profiles'), 'bodyclass'=>'browse');
queue_css_file('user-profiles');
echo head($head);

?>
<?php echo flash(); ?>

<?php if(is_allowed('UserProfiles_Type', 'add')): ?>
<p id="add-type" class="add-button"><a class="add green button" href="<?php echo html_escape(url('user-profiles/types/add')); ?>"><?php echo __('Add a Profile Type'); ?></a></p>
<?php endif; ?>

<table>
<thead>
<tr>
<th><?php echo __('Profile Type'); ?></th>
<th><?php echo __('Description'); ?></th>
<th><?php echo __('Elements'); ?></th>
<th><?php echo __('My Profile'); ?></th>
</tr>
</thead>
    <tbody>
    <?php foreach($this->types as $type): ?>
    <tr>
    	<td>
    	    <?php echo $type->label; ?>
    		<?php if($type->public): ?>
        		<?php echo __('(Public)')?>
        		<?php else: ?>
        		<?php echo __('(Private)')?>
    		<?php endif;?>
    		<?php if(is_allowed('UserProfiles_Type', 'edit')): ?>
    		<ul class="action-links group">
    		    <li>
            		<a href="user-profiles/types/edit/id/<?php echo $type->id; ?>">
            		<?php echo __('Edit');?>
            		</a>
    		    </li>
    		    <li>
            		<a  class='delete-confirm' href="user-profiles/types/delete-confirm/id/<?php echo $type->id; ?>">
            		<?php echo __('Delete');?>
            		</a>
    		    </li>
    		</ul>

    		<?php else: ?>
    		<?php echo $type->label; ?>
    		<?php endif; ?>

    	</td>
    	<td><?php echo __('%s', $type->description); ?></td>
    	<td><ul id="user-profiles-element-list">
    	<?php foreach($type->Elements as $element): ?>
    	<li><?php echo __('%s', $element->name); ?>
        	<?php if($element->type) :?>
        	(<?php echo __('%s', $element->type); ?>)
        	<?php else: ?>
        	<?php echo __("(text)"); ?>
        	<?php endif; ?>
    	</li>
    	<?php endforeach; ?>
    	</ul></td>
    	<?php $user = current_user();
            $profile = get_db()->getTable('UserProfilesProfile')->findByUserIdAndTypeId($user->id, $type->id);
    
    	?>
    	<td><a href="<?php echo url('user-profiles/profiles/user/id/' . $user->id . '/type/' . $type->id); ?>"><?php echo __('View'); ?></a> 
    	    | <a href="<?php echo url('user-profiles/profiles/edit/id/' . $user->id . '/type/' . $type->id); ?>"><?php if ($profile) { echo __("Edit"); } else { echo __("Add"); } ?></a></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php echo foot(); ?>