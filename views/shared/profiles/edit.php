<?php
queue_css_file('profiles');
queue_css_file('admin-skeleton');
if(!is_admin_theme()) {
    queue_css_file('admin-theme');
}

queue_js_file('admin-globals');
queue_js_file('tiny_mce', 'javascripts/vendor/tiny_mce');
queue_js_file('elements');

$head = array('title' => 'Edit Profile', 'content_class' => 'horizontal-nav');
echo head($head);

$types = apply_filters('user_profiles_type', $profile_types);
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
<div class="container container-twelve">
<?php endif;?>

<ul id='section-nav' class='navigation tabs'>
<?php 

$typesNav = array();
foreach($types as $type) {
    $typesNav[$type->label] = array('label'=>$type->label, 'uri'=>url('user-profiles/profiles/edit/id/' . $user->id .'?type='.$type->id));
}

echo nav($typesNav, 'user_profiles_types_user_edit');
?>
</ul>
<p class='warning'>Save changes before moving to edit a new profile type.</p>
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
        <?php echo link_to($userprofilesprofile, 'user', 'View profile', array('class'=>'big blue button')); ?>    
    </div>
</section>
</form>
</div>
<?php if(!is_admin_theme()) :?>
</div>
<?php endif; ?>
<?php echo foot(); ?>