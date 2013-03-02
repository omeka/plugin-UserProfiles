<?php 
class UserProfiles_View_Helper_LinkToOwnerProfile extends Omeka_View_Helper_ElementForm
{
    public function linkToOwnerProfile($args)
    {
        if(isset($args['owner'])) {
            $owner = $args['owner'];
        }
        
        if(!isset($args['owner']) && isset($args['item'])) {
            $item = $args['item'];
            $owner = $item->getOwner();
        }
        
        if(!isset($args['type'])) {
            //get the first type
            $types = get_db()->getTable('UserProfilesType')->findBy(array(), 1);
            if(!isset($types[0])) {
                return '';
            }
            $type = $types[0];
        }
        
        if(isset($args['text'])) {
           $text = $args['text']; 
        } else {
            $text = '';
        }  

        $html = "<div id='user-profiles-link-to-owner'>";
        $html .= "$text <a href='" . url('user-profiles/profiles/user/id/' . $owner->id . '/type/' . $type->id) . "'>{$owner->name}</a>";
        $html .= "</div>";
        return $html;
    }
}