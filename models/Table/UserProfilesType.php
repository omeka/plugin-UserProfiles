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

    protected function _getColumnPairs()
    {
        $alias = $this->getTableAlias();
        return array($alias . '.id', $alias . '.label');        
    }
    
    /**
     * For required profile types, this digs up the types that are incomplete for the current user
     */
    
    public function getIncompleteProfileTypes()
    {
        $user = current_user();
        if(!$user) {
            return false;
        }
        $db = $this->getDb();
        //need to start with parent's select, since this getSelect filters by public permissions
        $select = parent::getSelect();
        $select->where('required = 1');
        $requiredTypes = $this->fetchObjects($select);
        $incompleteTypes = array();
        $profilesTable = $db->getTable('UserProfilesProfile');
        foreach($requiredTypes as $type) {
            $count = $profilesTable->count(array('type_id'=>$type->id));
            if($count == 0) {
                $incompleteTypes[] = $type;
            }
        }
        return $incompleteTypes; 
    }
}