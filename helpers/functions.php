<?php

function user_profiles_link_to_user_profile($user, $action = null, $text = null, $props = array(), $queryParams = array())
{
    if(empty($text)) {
        $text = $user->name;
    }
    $profiles = get_db()->getTable('UserProfilesProfile')->findByUserId($user->id);
    return link_to($profiles[0], $action, $text, $props, $queryParams);
}