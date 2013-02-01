<?php

class UserProfilesEnumerationElement extends Element
{
    public $type; // enum: 'radio', 'select', 'multiselect', 'checkbox'    
    public $allowed_values; // serialized array of allowed values
    
}