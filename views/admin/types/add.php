<?php

queue_css_file('user-profiles');
queue_js_file('userprofiles');

$head = array('title' => __('Add Profile Type'));
echo head($head);
?>

<?php include_once(USER_PROFILES_DIR . '/views/admin/form.php'); ?>

<?php echo foot(); ?>