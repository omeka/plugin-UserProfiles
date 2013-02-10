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
    
}