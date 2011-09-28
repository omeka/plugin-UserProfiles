<?php

class UserProfilesType extends Omeka_Record {
    
    public $id;
    public $label;
    public $description;
    public $fields;
    

    protected function _validate()
    {
   
        foreach($this->fields as $index=>$field) {
          
            $this->fields[$index]['valid'] = true;
            //label must be set
            if(empty($field['label'])) {
                $this->addError('field (Empty label)', "Fields must have labels");
                $this->fields[$index]['valid'] = false;
            }
            
            
            switch($field['type']) {
                
                case 'Text':
                case 'Textarea':
                    if(!empty($field['values'])) {
                        $this->addError('field '. $field['label'], "Text fields should not have restrictions on values");
                        $this->fields[$index]['valid'] = false;
                    }
                    break;
                    
                case 'Radio':
                case 'Select':
                case 'Checkbox':
                case 'MultiCheckbox':
                    //enumerations must have a list of allowed values
                    if(empty($field['values'])) {
                        $this->addError('field '. $field['label'], "Allowed values must be set in field " . $field['label']);
                        $this->fields[$index]['valid'] = false;
                    }
                    //no duplicates
                    $unique = array_unique($field['values']);
                    if(count($field['values']) != count($unique)) {
                        $this->addError('field '. $field['label'], "Allowed values must be unique in field " . $field['label']);
                        $this->fields[$index]['valid'] = false;
                    }
                    break;
                    
                default:
                    $this->addError('field '. $field['label'], "Type must be set in field " . $field['label']);
                    
                    break;
                
                
                
            }
    
            
          // */
        }
       
    }
//  */
    protected function afterValidate()
    {
        $this->fields = serialize($this->fields);
    }
    
}