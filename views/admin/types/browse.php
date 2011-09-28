<?php
$head = array('title' => 'Browse Profile Types');
head($head);
?>

<div id="primary">
<?php echo flash(); ?>


<table>
    <thead>
        <tr>
            <th>Field Label</th>
            <th>Description</th>
            <th>Fields</th>
            <th>Edit</th>

        </tr>
    </thead>
    <tbody>
	<?php foreach($this->types as $type): ?>
        <tr>
        	<td><?php echo $type->label; ?></td>
        	<td><?php echo $type->description; ?></td>
        	<td>
        		<ul>
        	    <?php foreach(unserialize($type->fields) as $field): ?>
        			<li><?php echo $field['label']; ?></li>
        		<?php endforeach; ?>
        		</ul>
        	</td>
        	<td><a href="<?php echo html_escape($this->url("user-profiles/types/edit/id/{$type->id}"));?>">Edit</a></td>
       	</tr>
     <?php endforeach; ?>
    </tbody>
</table>
</div>



