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

.inputs .input-block {
  margin: 0 0 20px;
}

.input-block {
    overflow:auto;

}

.inputs textarea {
    -moz-box-sizing: border-box;
    width: 100%;
}
textarea {
    -moz-box-sizing: border-box;
    border: 1px solid #D8D8D8;
    border-radius: 0 0 0 0;
    box-shadow: 0 0 0.375em #D6D6D6 inset;
    color: #666666;
    font-size: 14px;
    padding: 5px 10px;
    width: 100%;
}


";

queue_js_file('admin-globals');
queue_js_file('tiny_mce', 'javascripts/vendor/tiny_mce');
queue_js_file('elements');

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
    Omeka.saveScroll();
});

jQuery(document).bind('omeka:elementformload', function (event) {
    Omeka.Elements.makeElementControls(event.target, <?php echo js_escape(url('user-profiles/profiles/element-form')); ?>,'UserProfilesProfile'<?php if ($id = metadata('userprofilesprofile', 'id')) echo ', '.$id; ?>);
    Omeka.Elements.enableWysiwyg(event.target);
});
//]]>
</script>

<div class="container container-twelve">
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
<section id="edit-form" class="seven columns alpha">
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
</div>
<?php echo foot(); ?>