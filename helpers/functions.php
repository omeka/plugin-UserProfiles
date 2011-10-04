<?php

function user_profiles_get_profiles_for_user($user = null)
{
    $params = array(
        'subject_record_type' => 'User',
        'object_record_type' => 'UserProfilesProfile',
        'property_id' => record_relations_property_id(SIOC, 'account_of'),
        'subject_id' => $user->id
    );
    return get_db()->getTable('RecordRelationsRelation')->findObjectRecordsByParams($params);
}

function user_profiles_get_type_for_profile($profile)
{
    return get_db()->getTable('UserProfilesType')->find($profile->type_id);
}


function user_profiles_link_to_profile($user, $display_label = null)
{
    $display_label = $display_label ? $display_label : $user->username . "'s Profile";
    echo "<a href='" . html_escape(PUBLIC_BASE_URL . "/user-profiles/profiles/user/id/{$user->id}") . "'>$display_label</a>";
}

