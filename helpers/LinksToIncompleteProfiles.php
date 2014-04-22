<?php

class UserProfiles_View_Helper_LinksToIncompleteProfiles extends Zend_View_Helper_Abstract
{

    public function linksToIncompleteProfiles()
    {
        $user = current_user();
        $html = '';
        $types = get_db()->getTable('UserProfilesType')->getIncompleteProfileTypes();
        if(!$types) {
            return $html;
        }
        $html = "<div class='userprofiles required-profile error'>";
                $html .= "<p>" . __("The site builders ask that you fill out profile info to help make connections.") . "</p>";
        $html .= "<ul>";
        foreach($types as $type) {
            $url = PUBLIC_BASE_URL . "/user-profiles/profiles/user/id/{$user->id}?type=" . $type->id;
            $html .= "<li><a href='$url'>{$type->label}</a></li>";
        }
        $html .= "</ul>";
        $html .= "</div>";
        return $html;
    }
}