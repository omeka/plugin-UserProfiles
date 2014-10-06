<?php
class Api_UserProfilesType extends Omeka_Record_Api_AbstractRecordAdapter implements Zend_Acl_Resource_Interface
{
    public function getRepresentation(Omeka_Record_AbstractRecord $type)
    {
        $representation = array(
                'id'          => $type->id,
                'url'         => self::getResourceUrl("/user_profiles_types/{$type->id}"),
                'label'       => $type->label,
                'description' => $type->description,
                'required_element_ids' => $type->required_element_ids,
                'required_multielement_ids' => $type->required_multielement_ids,
                'required'    => (bool) $type->required,
                'public'      => (bool) $type->public
                );
        $representation['element_set'] = array(
                    'id'  => $type->element_set_id,
                    'url' => self::getResourceUrl("/element_sets/{$type->element_set_id}") 
                );
        return $representation;
    }
    
    public function getResourceId()
    {
        return 'UserProfiles_Type';
    }
}