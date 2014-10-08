<?php

class ApiImport_ResponseAdapter_Omeka_UserProfilesProfile extends ApiImport_ResponseAdapter_Omeka_GenericAdapter
{

    protected $resourceProperties = array('type' => 'UserProfilesType');
    
    protected $skipProperties = array('element_texts');
    
    protected $userProperties = array('owner');
    
    public function import()
    {
        if(! $this->record) {
            $this->record = new UserProfilesProfile;
        }
        $this->setFromResponseData();
        $elementTexts = $this->elementTexts();
        $this->record->deleteElementTexts();
        $this->record->addElementTextsByArray($elementTexts);
        try {
            $this->record->setRelationData(array('subject_id'=> $this->record->owner_id, 'object_id' => $this->record->id));
            $relation = $this->record->getRelation();
            $this->record->save(true);
            $relation->save(true);
            $this->addOmekaApiImportRecordIdMap();
        } catch (Exception $e) {
            _log($e);
        }
        return $this->record;
    }

    /**
     * Process the element text data
     * @param array $responseData
     */
    protected function elementTexts($responseData = null)
    {
        $elementTexts = array();
        if(!$responseData) {
            $responseData = $this->responseData;
        }

        foreach($responseData['element_texts'] as $elTextData) {
            $elName = $elTextData['element']['name'];
            $elSet = $elTextData['element_set']['name'];
            $elTextInsertArray = array('text' => $elTextData['text'],
                                       'html' => $elTextData['html']
                                       );
            if (is_null($elTextInsertArray['text'])) {
                $elTextInsertArray['text'] = '';
            }
            $elementTexts[$elSet][$elName][] = $elTextInsertArray;

        }
        return $elementTexts;
    }    
}