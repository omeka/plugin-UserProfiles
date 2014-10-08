<?php

/**
 * Combines handling for multielements and multivalues for user profile importing
 * Both need to json_decode the relevant values from API and serialize them,
 * which is a slight difference from the GenericAdapter
 */

class ApiImport_ResponseAdapter_Omeka_UserProfilesMulti extends ApiImport_ResponseAdapter_Omeka_GenericAdapter
{
    
    protected function setFromResponseData()
    {
        $allSkipProperties = array_merge($this->resourceProperties, $this->userProperties, $this->skipProperties);
        foreach($this->responseData as $key=>$value) {
            if(!in_array($key, $allSkipProperties)) {
                if ($key == 'values' || $key == 'options') {
                    $value = json_decode($value, true);
                }
                if(is_array($value)) {
                    $this->record->$key = serialize($value);
                } else {
                    $this->record->$key = $value;
                }
            }
            if(in_array($key, $this->userProperties)) {
                $prop = $key . '_id';
                $this->record->$prop = $this->getLocalUserId($value);
            }
            if(array_key_exists($key, $this->resourceProperties)) {
                $prop = $key . '_id';
                $this->record->$prop = $this->getLocalResourceId($value, $this->resourceProperties[$key]);
            }
        }
    }
}