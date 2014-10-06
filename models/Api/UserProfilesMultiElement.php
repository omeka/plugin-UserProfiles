<?php
class Api_UserProfilesMultiElement extends Omeka_Record_Api_AbstractRecordAdapter implements Zend_Acl_Resource_Interface
{

    public function getRepresentation(Omeka_Record_AbstractRecord $multiElement)
    {
        $representation = array(
                'id'          => $multiElement->id,
                'url'         => self::getResourceUrl("/user_profiles_multielements/{$multiElement->id}"),
                'name'        => $multiElement->name,
                'description' => $multiElement->description,
                'type'        => $multiElement->type,
                'options'     => json_encode(unserialize($multiElement->options)),
                'order'       => $multiElement->order,
                'comment'     => $multiElement->comment
                );

        $representation['element_set'] = array(
                'id'       => $multiElement->element_set_id,
                'url'      => self::getResourceUrl("/element_sets/{$multiElement->element_set_id}"),
                'resource' => 'element_sets'
                );
        return $representation;
    }

    public function getResourceId()
    {
        return 'UserProfiles_MultiElement';
    }
}