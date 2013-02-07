<?php echo js_tag('item-types'); ?>
<script type="text/javascript">
jQuery(document).ready(function () {
    var addNewRequestUrl = '<?php echo admin_url('user-profiles/types/add-new-element'); ?>';
    var changeExistingElementUrl = '<?php echo admin_url('item-types/change-existing-element'); ?>';
<!-- somewhat cheesily reusing the javascript from ItemTypes. we never reuse for profile types, so use the url twice !>
    Omeka.ItemTypes.manageItemTypes(addNewRequestUrl, addNewRequestUrl, changeExistingElementUrl);
});
</script>

<form action="" method="post">
    <section class="seven columns alpha">
        <fieldset id="type-information">
            <p class='explanation'>* <?php echo __('required field'); ?></p>
            <div class="field">
                <div class="two columns alpha">
                    <label><?php echo __("Name"); ?> *</label>
                </div>
                <div class="inputs five columns omega">
                    <p class='explanation'><?php echo __('The name of the profile type'); ?></p>
                    <div class="input-block">
                    <?php echo $this->formText('name', $profileType->label, array(
                            'required' => true,
                            )
                        );
                    ?>                    
                    </div>
                </div>

            </div>
                        
            <div class="field">
                <div class="two columns alpha">
                    <label><?php echo __('Description'); ?> *</label>    
                </div>
                <div class="inputs five columns omega">
                    <p class="explanation"><?php echo __('The description of the profile type.'); ?></p>
                    <div class="input-block">     
                    <?php echo $this->formTextarea('description', $profileType->description, array(
                            'cols' => 50,
                            'rows' => 5,
                        )); 
                    ?>   
                    </div>
                </div>
            </div>
                
        </fieldset>
        <fieldset id="type-elements">
            <h2><?php echo __('Elements'); ?></h2>
            <div id="element-list">
        <ul class="sortable">
        <?php foreach ($profileType->Elements as $element): ?>
            <li class="element">
            <div class="sortable-item">
                <?php echo __($element->name); ?>
                <?php //echo $this->formHidden("elements[{$element->id}][order]", $element->order, array('class' => 'element-order')); ?>
                
                <a id="return-element-link-<?php echo html_escape($element->id); ?>" href="#" class="undo-delete"><?php echo __('Undo'); ?></a>
                <a id="remove-element-link-<?php echo html_escape($element->id); ?>" href="#" class="delete-element"><?php echo __('Remove'); ?></a>
                <?php echo $this->formHidden("elements[{$element->id}][delete]", 0, array('class' => 'delete')); ?>
            </div>
            <div class="drawer-contents">
                <label for="<?php echo "elements[{$element->id}][description]"; ?>"><?php echo __('Description'); ?></label>
                <?php echo $this->formTextarea("elements[{$element->id}][description]", $element->description, array('rows' => '3')); ?>
                <?php fire_plugin_hook('admin_element_sets_form_each', array('element_set' => $profileType->ElementSet, 'element' => $element, 'view' => $this)); ?>
            </div>
            </li>
        <?php endforeach; ?>
        <?php fire_plugin_hook('admin_element_sets_form', array('element_set' => $profileType->ElementSet, 'view' => $this)); ?>            
     
                    <li>
                        <div class="add-new">
                            <?php echo __('Add Element'); ?>
                        </div>
                        <div class="drawer-contents">
                            <button id="add-element" name="add-element"><?php echo __('Add Element'); ?></button>            
                        </div>
                    </li>
                </ul>
                
                <?php echo $this->formHidden('remove_element', null, array('value' => '')); ?>
            </div>
        </fieldset>
    
        <?php fire_plugin_hook('user_profiles_types_form', array('profile_type' => $profileType, 'view' => $this)); ?>
    </section>
    
    <section class="three columns omega">
        <div id="save" class="panel">
        <?php if($profileType->exists()): ?>
        <?php echo $this->formSubmit('submit', __('Save Changes'), array('class'=>'big green button')); ?>
        <a class="big red button delete-confirm" href="/Omeka/admin/user-profiles/types/delete-confirm/id/1">Delete</a>
        <?php else: ?>
        <?php echo $this->formSubmit('submit', __('Add Profile Type'), array('class'=>'big green button')); ?>
        <?php endif;?>
        </div>
    </section>
</form>

<script type="text/javascript">
//<![CDATA[
Omeka.addReadyCallback(Omeka.ElementSets.enableSorting);
Omeka.addReadyCallback(Omeka.ElementSets.addHideButtons);
Omeka.addReadyCallback(Omeka.ElementSets.enableElementRemoval);
//]]>
</script>
