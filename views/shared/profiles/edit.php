<?php

$isAdminTheme = is_admin_theme();

if(!$isAdminTheme) {
    queue_css_file('profiles');
}

queue_js_file('admin-globals');
queue_js_file('admin-elements');
queue_js_file('tinymce.min', 'javascripts/vendor/tinymce');

$head = array('title' => __('Edit Profile'), 'content_class' => 'horizontal-nav');
echo head($head);

?>
<script type="text/javascript" charset="utf-8">
//<![CDATA[
// TinyMCE hates document.ready.
jQuery(document).ready(function () {

    Omeka.wysiwyg({
        selector: false,
        forced_root_block: false
    });
    // Must run the element form scripts AFTER reseting textarea ids.
    jQuery(document).trigger('omeka:elementformload');
    Omeka.saveScroll();
});

jQuery(document).bind('omeka:elementformload', function (event) {
    Omeka.Elements.makeElementControls(event.target, <?php echo js_escape(url('user-profiles/profiles/element-form')); ?>,'UserProfilesProfile'<?php if ($id = metadata('userprofilesprofile', 'id')) echo ', '.$id; ?>);
    Omeka.Elements.enableWysiwyg(event.target);
});
//]]>

</script>

<ul id='section-nav' class='user-profiles navigation tabs'>
<?php
$typesNav = array();
foreach($profile_types as $type) {
    $navArray = array('label'=>$type->label, 'uri'=>url('user-profiles/profiles/edit/id/' . $user->id .'/type/'.$type->id));
    $typesNav[$type->label] = $navArray ;
}

echo nav($typesNav, 'user_profiles_types_user_edit');
?>
</ul>
<?php if(count($profile_types) > 1): ?>
<p class='warning'><?php echo __("Save changes before moving to edit a new profile type."); ?></p>
<?php endif; ?>

<?php echo flash(); ?>

<form method="post" action="" class="user-profile-edit <?php echo (!$isAdminTheme) ? 'container-twelve' : ''; ?>">
<section id="edit-form" class="<?php echo ($isAdminTheme) ? 'seven alpha' : 'twelve'; ?> columns user-profiles">

<h1><?php echo __('Edit your %s profile', $userprofilestype->label); ?></h1>

<p class="user-profiles-profile-description">
    <?php echo $userprofilestype->description; ?>
</p>
    <?php foreach($userprofilestype->Elements as $element):?>
    <?php echo $this->profileElementForm($element, $userprofilesprofile); ?>
    <?php endforeach; ?>
</section>

<section class="<?php echo ($isAdminTheme) ? 'three omega' : 'twelve'; ?> columns">
    <div id='save' class='panel'>
        <input type="submit" value='<?php echo __('Save Changes'); ?>' name='submit' class='big green button'/>
        <?php if($userprofilesprofile->exists()): ?>
        <a href="<?php echo url('user-profiles/profiles/delete-confirm/id/' . $userprofilesprofile->id); ?>" class="big red button delete-confirm"><?php echo __('Delete'); ?></a>
        <?php endif; ?>
        <div class="public">
            <?php if($userprofilestype->public == 0): ?>
            <p><?php echo __('This profile type is private'); ?></p>
            <input type="hidden" value="0" name="public" />
            <?php else: ?>
            <label for="public"><?php echo __('Public'); ?></label>
            <input type="hidden" value="0" name="public" />
            <input type="checkbox" value="1" id="public" name="public" <?php echo  $userprofilesprofile->public ? "checked='checked'" : ""; ?> />
            <?php endif; ?>
        </div>
    </div>
</section>
</form>
<?php echo foot(); ?>