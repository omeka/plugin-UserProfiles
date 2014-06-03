<?php

class Table_UserProfilesMultiValue extends Omeka_Db_Table
{
    public function findByMultiElement($multiElement)
    {
        if(is_numeric($multiElement)) {
            $id = $multiElement;
        } else {
            $id = $multiElement->id;
        }
        $select = $this->getSelect();
        $select->where("multi_id = ?", $id);
        return $this->fetchObject($select);
    }
    
    public function findByMultiElementAndProfile($multiElement, $profile)
    {
        if(is_numeric($multiElement)) {
            $multiElementId = $multiElement;
        } else {
            $multiElementId = $multiElement->id;
        }
        
        if(is_numeric($profile)) {
            $profileId = $profile;
        } else {
            $profileId = $profile->id;
        }
        $select = $this->getSelect();
        $select->where("multi_id = ?", $multiElementId);
        $select->where("profile_id = ?", $profileId);
        return $this->fetchObject($select);
    }
}