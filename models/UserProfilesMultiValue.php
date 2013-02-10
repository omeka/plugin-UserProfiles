<?php

class UserProfilesMultiValue extends Omeka_Record_AbstractRecord
{
    public $profile_type_id;
    public $profile_id;
    public $multi_id;
    public $values;

    public function getValues()
    {
        return unserialize($this->values);
    }
    
    public function setValues($values)
    {
        $this->values = serialize($values);
        
    }
    
    public function getValuesForDisplay()
    {
        $options = $this->getTable('UserProfilesMultiElement')->find($this->multi_id)->getOptions();
        $values = $this->getValues();
        $displayValues = array();
        if(is_array($values)) {
            foreach($values as $value) {
                if(isset($options[$value])) {
                    $displayValues[] = $options[$value];
                }
            }            
        } else {
                if(isset($options[$values])) {
                    $displayValues[] = $options[$values];
                }
        }

        
        return $displayValues;
    }
}