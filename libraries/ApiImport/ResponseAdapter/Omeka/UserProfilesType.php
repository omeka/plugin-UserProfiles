<?php
class ApiImport_ResponseAdapter_Omeka_UserProfilesType extends ApiImport_ResponseAdapter_GenericAdapter
{
    protected $resourceProperties = array('element_sets' => 'ElementSet');
    
    public function import()
    {
        if(! $this->record) {
            $this->record = new $this->recordType;
        }
        $this->setFromResponseData();

        // Rewrite required_element_ids and required_multielement_ids to
        // make sure they use the local ids.

        $requiredElementIds = json_decode($this->record->required_element_ids, true);
        $localRequiredElementIds = array();
        foreach ($requiredElementIds as $id) {
            // data for getLocalResourceId is an array
            $data = array('id' => $id);
            $localRequiredElementIds[] = $this->getLocalResourceId($data, 'Element');
        }
        $this->record->required_element_ids = json_encode($localRequiredElementIds);
        
        $requiredMultiElementIds = json_decode($this->record->require_multielement_ids, true);
        $localRequiredMultiElementIds = array();
        foreach ($requiredMultiElementIds as $id) {
            // data for getLocalResourceId is an array
            $data = array('id' => $id);
            $localRequiredMultiElementIds[] = $this->getLocalResourceId($data, 'UserProfilesMultiElement');
        }
        $this->record->required_multielement_ids = json_encode($localRequiredMultiElementIds);
        
        try {
            $this->record->save(true);
            $this->addOmekaApiImportRecordIdMap();
        } catch (Exception $e) {
            _log($e);
        }
        return $this->record;
    }    
}