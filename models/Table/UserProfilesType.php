<?php

class Table_UserProfilesType extends Omeka_Db_Table
{
    public function getSelect()
    {
        $select = parent::getSelect();
        $permissions = new Omeka_Db_Select_PublicPermissions('UserProfiles_Type');
        $permissions->apply($select, 'user_profiles_types');
        return $select;
    }    
    
}