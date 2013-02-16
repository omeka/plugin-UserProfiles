
<?php $view = get_view();?>


<div class="field">
    <div class="two columns alpha">
        <label><?php echo __('Link items to owner?')?></label>    
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __('Add a link from items to the person who added them.')?></p>
        <div class="input-block">        
        <?php echo $view->formCheckbox('user_profiles_link_to_owner', null, array('checked' => (bool) get_option('user_profiles_link_to_owner') ? 'checked' : '')); ?>
        </div>
    </div>
</div>