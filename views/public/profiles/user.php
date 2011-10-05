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
<?php echo $this->filtered_html; ?>

<?php $items = get_items(array('user' => $this->user->id, 'recent'=>true)); ?>
<div>
<h2>Recent Items Added by <?php echo $user->username; ?></h2>
<?php set_items_for_loop($items); ?>
	<?php while (loop_items()): ?>
		<div class="item hentry">
			<div class="item-meta">
				<h2><?php echo link_to_item(item('Dublin Core', 'Title'), array('class'=>'permalink')); ?></h2>
			</div>
			<?php if (item_has_thumbnail()): ?>
				<div class="item-thumbnail">
				<?php echo link_to_item(item_square_thumbnail()); ?>
				</div>
			<?php else: ?>
				<div class="no-thumbnail"><?php echo link_to_item('No image'); ?></div>
			<?php endif; ?>

		</div>
<?php endwhile; ?>
</div>
<!--  end primary -->
</div>
<?php foot(); ?>