<?php
queue_css_file('profiles');
queue_css_file('skeleton');
$css = "
.field label {
  clear: left;
  float: left;
  font-weight: bold;
  line-height: 1.5em;
  margin: 0 0 10px;
  min-width: 120px;
}

.field input[type='submit'] {
    display:block;
}

#section-nav, #section-nav ul {
  list-style-type: none;
  margin: -20px 0 16px;
  padding: 0;
}

#section-nav li {
    display: inline-block;
}

#section-nav li.active {
    font-weight: bold;
}

";

queue_js_file('elements');
queue_js_file('admin-globals');


queue_css_string($css);
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
});

jQuery(document).bind('omeka:elementformload', function (event) {
    console.log('binding');
    Omeka.Elements.makeElementControls(event.target, <?php echo js_escape(url('user-profiles/profiles/element-form')); ?>,'UserProfilesProfile'<?php if ($id = metadata('userprofilesprofile', 'id')) echo ', '.$id; ?>);
    Omeka.Elements.enableWysiwyg(event.target);
});
//]]>
</script>
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
<p>
    <?php user_profiles_link_to_profile($user, $user->username . "'s public profile"); ?>
</p>
<?php echo flash(); ?>

<div id="primary" class="ten columns alpha">


<form method="post" action="">
<section class="user-profiles-profile seven columns alpha">
<h1>Edit your <?php echo $userprofilestype->label; ?> profile</h1>

<p class="user-profiles-profile-description">
    <?php echo $userprofilestype->description; ?>
</p>
    <?php echo element_form($userprofilestype->Elements, $userprofilesprofile);?>
</section>

<section class="three columns omega">
    <div id='save' class='panel'>
        <input type="submit" value='Save Changes to <?php echo $userprofilestype->label; ?>' name='submit' class='big green button'/>    
    </div>
</section>
</form>
</div>

<?php echo foot(); ?>