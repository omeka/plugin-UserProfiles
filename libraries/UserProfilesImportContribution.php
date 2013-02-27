<?php
class UserProfilesImportContribution extends Omeka_Job_AbstractJob
{
    public function perform()
    {
       $db = get_db();
       $elementSet = new ElementSet();
       $elementSet->record_type = "UserProfilesType";
       $elementSet->name = "Contributor Elements";
       $elementSet->description = "";
       $elementSet->save();
       $importData = $this->_importContributorFields($elementSet);
       $type_id = $importData['type_id'];
       $contribFieldElementMap = $importData['contribFieldElementMap'];
       $userContributorMap = unserialize(get_option('contribution_user_contributor_map'));
       foreach($userContributorMap as $userId=>$contributorIds) {
           $contribIds = implode(',' , $contributorIds);
           $sql = "SELECT * FROM $db->ContributionContributorValue WHERE `contributor_id` IN ($contribIds)";
           $res = $db->query($sql);
           $contributorValues = $res->fetchAll();
           $profile = new UserProfilesProfile();
           $profile->owner_id = $userId;
           $profile->public = true;
           $profile->element_set_id = $elementSet->id;   
           $profile->type_id = $type_id;  
           $profile->setRelationData(array('subject_id'=>$userId));
           $profile->save();
           $elTextArray = array();
           foreach($contributorValues as $value) {
               //dig up element_id
               $fieldId = $value['field_id'];
               $elementId = $contribFieldElementMap[$fieldId];
               $elementText = new ElementText();
               $elementText->element_id = $elementId;
               $elementText->text = $value['value'];
               $elementText->html = 0;
               $elementText->record_type = 'UserProfilesProfile';
               $elementText->record_id = $profile->id;
               $elementText->save();               
           }
           
           release_object($profile);
       }
    }
    
    private function _importContributorFields($elementSet)
    {
        $db = get_db();
        //create the ProfileType
        $contribFieldElementMap = $this->_contributorFieldsToElements($elementSet->id);
        $profileType = new UserProfilesType();
        $profileType->label = "Contributor Info";
        $profileType->description = "Contributor Info";
        $profileType->element_set_id = $elementSet->id;
        $profileType->public = true;
        
        $profileType->setElementInfos(array());
        $profileType->setMultiElementInfos(array());
        $profileType->save();
        return array('type_id'=>$profileType->id, 'contribFieldElementMap'=>$contribFieldElementMap);
    }
    
    private function _contributorFieldsToElements($elementSetId)
    {
        $db = get_db();
        $sql = "SELECT * FROM $db->ContributionContributorFields";
        $res = $db->query($sql);
        $contributorFields = $res->fetchAll();
        $contribFieldElementMap = array();
        foreach($contributorFields as $index=>$elementInfo) {
            $element = new Element();
            $element->name = $elementInfo['prompt'];
            $element->element_set_id = $elementSetId;
            $element->order = $index;
            $element->description = $elementInfo['prompt'];
            $element->save();
            $contribFieldElementMap[$elementInfo['id']] = $element->id;
        }
        return $contribFieldElementMap;
    }
    
    private function _elementIdFromElementInfos($elementInfos, $contributionFieldId)
    {
        foreach($elementInfos as $info) {
            if($info['contribution_field_id'] == $contributionFieldId) {
                $element = $info['element'];
                return $element->id;
            }
        }
        return false;
    }    
}