<?php

function user_profiles_get_profiles_for_user($user = null)
{
    if(is_null($user)) {
        $user = current_user();
    }
    $params = UserProfilesProfile::defaultParams();
    $params['subject_id'] = $user->id;
    return get_db()->getTable('RecordRelationsRelation')->findObjectRecordsByParams($params);
}

function user_profiles_get_type_for_profile($profile)
{
    return get_db()->getTable('UserProfilesType')->find($profile->type_id);
}


function user_profiles_link_to_profile($user = null, $display_label = null)
{
    if(is_null($user)) {
        $user = current_user();
    }
    $display_label = $display_label ? $display_label : $user->username . "'s Profile";
    echo "<a href='" . html_escape(PUBLIC_BASE_URL . "/user-profiles/profiles/user/id/{$user->id}") . "'>$display_label</a>";
}

function user_profiles_link_to_profile_edit($user = null, $display_label = null)
{
    if(is_null($user)) {
        $user = current_user();
    }
    $display_label = $display_label ? $display_label : "Edit Your Profile";
    echo "<a href='" . html_escape(ADMIN_BASE_URL . "/user-profiles/profiles/edit/id/{$user->id}") . "'>$display_label</a>";
}