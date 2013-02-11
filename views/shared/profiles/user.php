<?php

if(!is_admin_theme()) {
    queue_css_file('skeleton');
    queue_css_file('admin-theme');
}
queue_css_file('profiles');
queue_js_file('admin-globals');
$head = array('title' => "User Profile | " . $user->name,
              'bodyclass' => '');
echo head($head); 

?>
<script type="text/javascript" charset="utf-8">
//<![CDATA[
// TinyMCE hates document.ready.
jQuery(window).load(function () {
    Omeka.saveScroll();
});
//]]>
</script>
<?php if(!is_admin_theme()) :?>
<div class="container-twelve">
<?php endif;?>


<div id="primary">
<?php echo flash(); ?>
    
    <h1><?php echo $head['title']; ?></h1>

<?php if(empty($profiles) && is_allowed('UserProfiles_Profile', 'editOwn')): ?>

<p><?php echo is_allowed('UserProfiles_Profile', 'editOwn') ? "You have" : $user->username . " has"; ?> not filled out a profile yet.</p>
<?php echo link_to('user-profiles/profile', 'edit', __('Fill out your profile'), array(), array('id'=>current_user()->id)); ?>

<a href="<?php echo url('user-profiles/profiles/edit/id' . current_user()->id);   ?>">Edit</a>


<?php endif; ?>
<section class="seven columns alpha">
<?php foreach($profiles as $profile): ?>
<?php $type = $profile->getProfileType();?>

    <div class="element-set">
        <h2><?php echo html_escape(__($type->label)); ?></h2>
        <?php foreach($profile->getElements() as $element):?>
        <div class="element">
            <div class="field two columns alpha">
                <label><?php echo html_escape(__($element->name)); ?></label>
            </div>
            <?php $i = 0; ?>
            <?php if(get_class($element) == 'Element'): ?>
                <?php foreach ($profile->getElementTextsByRecord($element) as $text):
                $i++;
                if( $i == 1): ?>
                    <div class="element-text five columns omega"><p><?php echo $text->text; ?></p></div>
                <?php else: ?>
                    <div class="element-text five columns offset-by-two"><p><?php echo $text->text; ?></p></div>
                <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <?php $valueObject = $profile->getValueRecordForMulti($element);?>
                <div class="element-text five columns omega">
                    <?php if($valueObject): ?>
                    <?php $values = $valueObject->getValuesForDisplay(); ?>
                    <?php foreach($values as $value): ?>
                    <p><?php echo $value ?></p>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <p></p>
                    <?php endif; ?>
                </div>
            
            <?php endif; ?>
        </div><!-- end element -->
        <?php endforeach; ?>
    </div><!-- end element-set -->

<?php endforeach; ?>
</section>

<?php fire_plugin_hook('user_profiles_append_to_user_page', array($this->user) ); ?>
<?php if(is_allowed('UserProfiles_Profile', 'editOwn') && !empty($profiles)): ?>

<section class="three columns omega">
    <div id='save' class='panel'>
        <?php echo link_to($profiles[0], 'edit', 'Edit profile', array('class'=>'big green button'), array('id'=>$user->id) ); ?>    
    </div>
</section>
<?php endif; ?>
<!--  end primary -->
</div>

</div>
<?php echo foot(); ?>