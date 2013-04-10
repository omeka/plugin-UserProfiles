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
        $html .= "<ul>";
        foreach($types as $type) {
            $url = PUBLIC_BASE_URL . "/user-profiles/profiles/user/id/{$user->id}?type=" . $type->id;
            
            $html .= "<li><a href='$url'>{$type->label}</a></li>";
        }    
        $html .= "</ul>";
        return $html;
    }
}