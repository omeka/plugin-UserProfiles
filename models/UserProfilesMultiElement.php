<?php

class UserProfilesMultiElement extends Element
{
    public $type; // enum: 'radio', 'select', 'multiselect', 'checkbox'    
    public $options; // serialized array of allowed values
    
    
    public function getOptions()
    {
        return unserialize($this->options);
        
    }
    
    public function setOptions($options)
    {
        if(!is_array($options)) {
            $options = explode(',', $options);
        }
        $options = array_map('trim', $options);
        $this->options = serialize($options);
    }
    
}