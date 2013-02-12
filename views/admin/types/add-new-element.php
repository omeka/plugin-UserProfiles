<li class="element">
    <div class="sortable-item">
        <?php
        echo $this->formText(
            $element_name_name, $element_name_value,
            array('placeholder' => __('Element Name'))
        );
        ?>
        <?php
        echo $this->formHidden(
            $element_order_name, $element_order_value,
            array('class' => 'element-order')
        );
        ?>
        <a href="" class="delete-element"><?php echo __('Remove'); ?></a>
    </div>
    <div class="drawer-contents">
        <label style="float:left;">Required</label><input type='checkbox' name='required[]' value='true' />

        <?php
        echo $this->formTextarea(
            $element_description_name, $element_description_value,
            array(
                'placeholder' => __('Element Description'),
                'rows' => '3',
                'cols'=>'30'
            )
        );
        ?>
        <?php if($raw_type != 'text') {
            echo $this->formTextarea($options, '', array('placeholder'=>__("Allowed Values, comma-separated"), 'rows'=>'3', 'cols'=>'30'));
            echo $this->formHidden($type, $raw_type);
        }
        ?>
    </div>
</li>
