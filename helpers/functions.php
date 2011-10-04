<?php

function user_profiles_get_profiles_for_user($user = null, $label = null)
{
    $params = array(
        'subject_record_type' => 'User',
        'object_record_type' => 'UserProfile'
    );
}

function user_profiles_link_to_profile($user, $display_label = null)
{
    $display_label = $display_label ? $display_label : $user->username . "'s Profile";
    echo "<a href='" . html_escape(PUBLIC_BASE_URL . "/user-profiles/profiles/user/id/{$user->id}") . "'>$display_label</a>";
}

