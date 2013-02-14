<?php
if(!is_admin_theme()) {
    queue_css_file('admin-theme');
    queue_css_file('profiles');
    queue_css_file('admin-skeleton');
}

queue_js_file('admin-globals');
queue_js_file('tiny_mce', 'javascripts/vendor/tiny_mce');
queue_js_file('elements');

$head = array('title' => 'Edit Profile', 'content_class' => 'horizontal-nav');
echo head($head);

?>
<script type="text/javascript" charset="utf-8">
//<![CDATA[
// TinyMCE hates document.ready.
jQuery(window).load(function () {

    Omeka.wysiwyg({
        mode: "none",
        forced_root_block: ""
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
<?php if(!is_admin_theme()) :?>
<div class="container-twelve">
<?php endif;?>

<ul id='section-nav' class='navigation tabs'>
<?php 

$typesNav = array();
foreach($profile_types as $type) {
    $typesNav[$type->label] = array('label'=>$type->label, 'uri'=>url('user-profiles/profiles/edit/id/' . $user->id .'?type='.$type->id));
}

echo nav($typesNav, 'user_profiles_types_user_edit');
?>
</ul>
<?php if(count($profile_types) > 1): ?>
<p class='warning'>Save changes before moving to edit a new profile type.</p>
<?php endif; ?>

<?php echo flash(); ?>

<div id="primary" class="ten columns alpha">


<form method="post" action="">
<section id="edit-form" class="seven columns alpha">
<h1>Edit your <?php echo $userprofilestype->label; ?> profile</h1>

<p class="user-profiles-profile-description">
    <?php echo $userprofilestype->description; ?>
</p>
    <?php foreach($userprofilestype->Elements as $element):?>
    <?php echo $this->profileElementForm($element, $userprofilesprofile); ?>
    <?php endforeach; ?>
</section>

<section class="three columns omega">
    <div id='save' class='panel'>
        <input type="submit" value='Save Changes to <?php echo $userprofilestype->label; ?>' name='submit' class='big green button'/>
        <?php if($userprofilesprofile->exists()): ?>
        <a href="<?php echo url('user-profiles/profiles/delete-confirm/id/' . $userprofilesprofile->id); ?>" class="big red button delete-confirm">Delete</a>
        <?php endif; ?>
        <div class="public">
            <?php if($userprofilestype->public == 0): ?>
            <p>This profile type is private</p>
            <input type="hidden" value="0" name="public" />
            <?php else: ?>
            <label for="public">Public:</label> 
            <input type="hidden" value="0" name="public" />
            <input type="checkbox" value="1" id="public" name="public" <?php echo  $userprofilesprofile->public ? "checked='checked'" : ""; ?> />
            <?php endif; ?>                    
        </div>        
    </div>
</section>
</form>
</div>
<?php if(!is_admin_theme()) :?>
</div>
<?php endif; ?>
<?php echo foot(); ?>