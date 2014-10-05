<?php
class Api_UserProfilesProfile extends Omeka_Record_Api_AbstractRecordAdapter implements Zend_Acl_Resource_Interface
{
    
    public function getRepresentation(Omeka_Record_AbstractRecord $profile)
    {
        $representation = array(
                'id'       => $profile->id,
                'added'    => self::getDate($profile->added),
                'modified' => self::getDate($profile->modified),
                'public'   => (bool) $profile->public
                );
        $representation['type'] = array(
                'id'       => $profile->type_id,
                'url'      => self::getResourceUrl("/user_profiles_types/{$profile->type_id}"),
                'resource' => 'user_profiles_types'
                );
        $representation['owner'] = array(
                'id'       => $profile->owner_id,
                'url'      => self::getResourceUrl("/users/{$profile->owner_id}"),
                'resource' => 'users'
                
                );
        
        $representation['element_texts'] = $this->getElementTextRepresentations($profile);
        return $representation;
    }
    
    public function getResourceId()
    {
        return 'UserProfiles_Profile';
    }
}