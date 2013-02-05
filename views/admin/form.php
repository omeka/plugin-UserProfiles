<?php echo js_tag('item-types'); ?>
<script type="text/javascript">
jQuery(document).ready(function () {
    var addNewRequestUrl = '<?php echo admin_url('user-profiles/types/add-new-element'); ?>';
    var changeExistingElementUrl = '<?php echo admin_url('item-types/change-existing-element'); ?>';
<!-- somewhat cheesily reusing the javascript from ItemTypes. we never reuse for profile types, so use the url twice !>
    Omeka.ItemTypes.manageItemTypes(addNewRequestUrl, addNewRequestUrl, changeExistingElementUrl);
});
</script>

<?php $elementInfos = array(); ?>

<form action="" method="post">
    <section class="seven columns alpha">
        <fieldset id="type-information">
            <h2><?php echo __('Profile Type Information'); ?></h2>
            <p class='explanation'>* <?php echo __('required field'); ?></p>
                
                
                
            <div class="field">
                <div class="two columns alpha">
                    <label><?php echo __("Name"); ?></label>
                </div>
                <div class="inputs five columns omega">
                    <p><?php echo __('The name of the profile type'); ?>
                    <div class="input-block">
                    <?php echo $this->formText('name', $profileType->label, array(
                            'required' => true,
                            )
                        );
                    ?>                    
                    </div>
                </div>

                <?php //echo $this->form->getElement(Omeka_Form_ItemTypes::NAME_ELEMENT_ID); ?>
            </div>
                        
            <div class="field">
                <div class="two columns alpha">
                    <label><?php echo __('Description'); ?></label>    
                </div>
                <div class="inputs five columns omega">
                    <p class="explanation"><?php echo __('The description of the item type.'); ?></p>
                    <div class="input-block">     
                    <?php echo $this->formTextarea('description', $profileType->description, array(
                            'cols' => 50,
                            'rows' => 5,
                        )); 
                    ?>   
                    </div>
                </div>
            </div>
                
                <?php //echo $this->form->getElement(Omeka_Form_ItemTypes::DESCRIPTION_ELEMENT_ID); ?>
        </fieldset>
        <fieldset id="type-elements">
            <h2><?php echo __('Elements'); ?></h2>
            <div id="element-list">
                <ul id="item-type-elements" class="sortable">
                <?php
                foreach ($elementInfos as $elementInfo):
                    $element = $elementInfo['element'];
                    $elementTempId = $elementInfo['temp_id'];
                    $elementOrder = $elementInfo['order'];
    
                    if ($element && $elementTempId === null):
                ?>
                    <li class="element">
                        <div class="sortable-item">
                        <strong><?php echo html_escape($element->name); ?></strong>
                        <?php echo $this->formHidden("elements[$element->id][order]", $elementOrder, array('size'=>2, 'class' => 'element-order')); ?>
                        <?php if (is_allowed('ItemTypes', 'delete-element')): ?>
                        <a id="return-element-link-<?php echo html_escape($element->id); ?>" href="" class="undo-delete"><?php echo __('Undo'); ?></a>
                        <a id="remove-element-link-<?php echo html_escape($element->id); ?>" href="" class="delete-element"><?php echo __('Remove'); ?></a>
                        <?php endif; ?>
                        </div>
                        
                        <div class="drawer-contents">
                            <div class="element-description"><?php echo html_escape($element->description); ?></div>
                        </div>
                    </li>
                    <?php else: ?>
                        <?php if (!$element->exists()):  ?>
                        <?php echo $this->action(
                            'add-new-element', 'item-types', null,
                            array(
                                'from_post' => true,
                                'elementTempId' => $elementTempId,
                                'elementName' => $element->name,
                                'elementDescription' => $element->description,
                                'elementOrder' => $elementOrder
                            )
                        );
                        ?>
                        <?php else: ?>
                        <?php echo $this->action(
                            'add-existing-element', 'item-types', null,
                            array(
                                'from_post' => true,
                                'elementTempId' => $elementTempId,
                                'elementId' => $element->id,
                                'elementOrder' => $elementOrder
                            )
                        );
                        ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; // end for each $elementInfos ?> 
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
                
                
                
                <?php //echo $this->form->getElement(Omeka_Form_ItemTypes::REMOVE_HIDDEN_ELEMENT_ID); ?>
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