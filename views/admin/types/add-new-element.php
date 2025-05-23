<li class="element">
    <div class="flash success sr-only" role="alert">Element successfully added.</div>
    <div class="sortable-item drawer">
        <label class="drawer-name input-text">
        <?php echo __('Element Name'); ?>
        <?php
        echo $this->formText(
            $element_name_name, $element_name_value
        );
        ?>
        </label>
        <?php
        echo $this->formHidden(
            $element_order_name, $element_order_value,
            array('class' => 'element-order')
        );
        ?>
        <button type="button" class="undo-delete" data-action-selector="deleted" title="<?php echo __('Undo'); ?>" aria-label="<?php echo __('Undo'); ?> <?php echo __('Remove'); ?>"><span class="icon" aria-hidden="true"></span></button>
        <button type="button" class="delete-drawer" data-action-selector="deleted" title="<?php echo __('Remove'); ?>" aria-label="<?php echo __('Remove'); ?>"><span class="icon" aria-hidden="true"></span></button>
    </div>
    <div class="drawer-contents opened">
        <label>
            <?php echo __('Required');?>
            <input type='checkbox' name='required[]' value='true' />
        </label>

        <label><?php echo __('Description'); ?>
        <?php
        echo $this->formTextarea(
            $element_description_name, $element_description_value,
            array(
                'rows' => '3',
                'cols'=>'30'
            )
        );
        ?>
        </label>
        <?php if($raw_type != 'text'): ?>
        <label>
        <?php 
            echo __("Allowed Values, comma-separated");
            echo $this->formTextarea($options, '', array('rows'=>'3', 'cols'=>'30'));
        ?>
        </label>
        <?php echo $this->formHidden($type, $raw_type); ?>
        <?php endif; ?>
    </div>
</li>
