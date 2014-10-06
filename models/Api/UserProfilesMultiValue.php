<?php
class Api_UserProfilesMultiValue extends Omeka_Record_Api_AbstractRecordAdapter implements Zend_Acl_Resource_Interface
{

    public function getRepresentation(Omeka_Record_AbstractRecord $value)
    {
        $representation = array(
                'id'     => $value->id,
                'url'    => self::getResourceUrl("/user_profiles_multivalues/{$value->id}"),
                'values' => json_encode(unserialize($value->values))
                );
        $representation['profile_type'] = array(
                'id'        => $value->profile_type_id,
                'url'       => self::getResourceUrl("/user_profiles_type/{$value->profile_type_id}"),
                'resource'  => 'user_profiles_types'
                );
        $representation['profile'] = array(
                'id'        => $value->profile_id,
                'url'       => self::getResourceUrl("/user_profiles/{$value->profile_id}"),
                'resource'  => 'user_profiles'
                );
        $representation['multi'] = array(
                'id'        => $value->multi_id,
                'url'       => self::getResourceUrl("/user_profiles_multielements/{$value->multi_id}"),
                'resource'  => 'user_profiles_multielements'
                );

        return $representation;
    }

    public function getResourceId()
    {
        return 'UserProfiles_MultiValue';
    }
}