<?php

class UserProfilesMultiElement extends Element
{
    public $type; // enum: 'radio', 'select', 'multiselect', 'checkbox'    
    public $options; // serialized array of allowed values
    
    
    public function getOptions()
    {
        $options = unserialize($this->options);
        if(!is_array($options)) {
            $options = array();
        } 
        return $options;
    }
    
    public function setOptions($options)
    {
        if(empty($options)) {
            $this->options = serialize($options);
            return false;
        }
        if(!is_array($options)) {
            $options = explode(',', $options);
        }
        $options = array_map('trim', $options);
        $this->options = serialize($options);
        return true;
    }
    
}