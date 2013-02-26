<?php
class UserProfilesImportContribution extends Omeka_Job_AbstractJob
{
    public function perform()
    {
           $elementSet = new ElementSet();
           $elementSet->record_type = "UserProfilesType";
           $elementSet->name = "Contributor Elements";
           $elementSet->description = "";
           $elementSet->save();
           $importData = $this->_importContributorFields($elementSet);
           $type_id = $importData['type_id'];
           $elementInfos = $importData['elementInfos'];
           $userContributorMap = $this->_importContributors();
           $elementSet = $db->getTable('ElementSet')->findByName('Contributor Elements');
           foreach($contributorUserMap as $userId=>$contributorIds) {
               $contribIds = implode(',' , $contributorIds);
               $sql = "SELECT * FROM $db->ContributionContributorValue WHERE `contributor_id` IN $contribIds";
               $res = $db->query($sql);
               $contributorValues = $res->fetchAll();
               $profile = new UserProfilesProfile();
               $profile->owner_id = $userId;
               $profile->public = true;
               $profile->element_set_id = $elementSet->id;   
               $profile->type_id = $type_id;  
               $profile->setRelationData(array('subject_id'=>$userId));

               $elTextArray = array();
               foreach($contributorValues as $value) {
                   //dig up element_id
                   $fieldId = $value['field_id'];
                   $elementId = $this->_elementIdFromElementInfos($elementInfos, $fieldId);
                   $elTextArray[] = array('element_id' => $elementId, 'text'=>$value['value'], 'html'=>0 );
                   
               }
               $profile->addElementTextsByArray($elTextArray);
               $profile->save();
               release_object($profile);
               /*
               //dig up the items contributed and set the owner
               $sql = "SELECT `item_id` FROM $db->ContributionContributedItems WHERE `contributor_id` IN $contribIds ";
               $res = $db->query($sql);
               $contributedItemIds =  $res->fetchAll();
               $itemTable = $db->getTable('Item');
               $ids = array();
               foreach($contributedItemIds as $row) {
                   $ids[] = $row['item_id'];
               }               
               $idsString = implode(',', $ids);
               $items = $itemTable->findBy(array('range'=>$idsString));
               foreach($items as $item) {
                   $item->owner_id = $userId;
                   $item->save();
                   release_object($item);
               }
               */
           }
    }
    
    
    private function _importContributorFields($elementSet)
    {
        $db = get_db();
    
        //create the ProfileType
        $elementInfos = $this->_contributorFieldsToElementInfos($elementSet->id);
        $profileType = new UserProfilesType();
        $profileType->label = "Contributor Info";
        $profileType->description = "Contributor Info";
        $profileType->element_set_id = $elementSet->id;
        $profileType->public = true;
        $profileType->setElementInfos($elementInfos);
        $profileType->setMultiElementInfos(array());
        $profileType->save();
        return array('type_id'=>$profileType->id, 'elementInfos'=>$elementInfos);
    }
    
    private function _contributorFieldsToElementInfos($elementSetId)
    {
        $db = get_db();
        $sql = "SELECT * FROM $db->ContributionContributorFields";
        $res = $db->query($sql);
        $contributorFields = $res->fetchAll();
        $elementInfos = array();
        foreach($contributorFields as $elementInfo) {
            $element = new Element();
            $element->name = $elementInfo['prompt'];
            $element->element_set_id = $elementSetId;
            $elementInfos[] = array(
                    'element' => $element,
                    'order' => $elementInfo['order'],
                    'description' => $elementInfo['prompt'],
                    'element_set_id' => $elementSetId,
                    'contributor_field_id' =>$elementInfo['id']
            );
        }
    
        return $elementInfos;
    
    }
    
    private function _importContributors()
    {
        $db = get_db();
        //import the contributors to Guest Users
        $sql = "SELECT * FROM $db->ContributionContributors";
        $res = $db->query($sql);
        $data = $res->fetchAll();
        //key: contributor id; value user id
        $contributorUserMap = array();
        $usernameFiller = 11111; //count up for non-unique usernames, and ones too short
        
        $validatorOptions = array(
                'table'   => 'User',
                'field'   => 'username',
                'adapter' => $this->getDb()->getAdapter()
        );
        $uniqueUsernameValidator = new Zend_Validate_Db_NoRecordExists($usernameOptions);
        $emailValidator = new Zend_Validate_EmailAddress();
        foreach($data as $contributor) {
            if($user = $db->getTable('User')->findByEmail($contributor['email'])) {
                $userContributorMap[$user->id][] = $contributor['id'];
            } else {
                $user = new User();
                //create username from email
                $usernameParts = explode('@', $contributor['email']);
                $username = preg_replace("/[^a-zA-Z0-9\s]/", "", $usernameParts[0]);

                if( (strlen($username) < $user::USERNAME_MIN_LENGTH) || !$uniqueUsernameValidator->isValid($username)) {
                    $username = $username . $usernameFiller;
                    $usernameFiller++;
                }
                
                $email = $contributor['email'];
                if(!$emailValidator->isValid($email)) {
                    //can't save as a new user w/o valid unique email, so either create
                    //a fake one, or shove all invalid-email contributors onto one real user (superuser)
                }
                
                
                $user->username = $username;
                $user->name = $contributor['name'];
                $user->email = $email;
                $user->role = "guest";
                $user->active = true;
    
                try {
                    $user->save();
                } catch (Exception $e) {
                    _log($e->getMessage());
                    debug(print_r($contributor, true));
                    $user = $db->getTable('User')->find(1);
                }
                $userContributorMap[$user->id] = array($contributor['id']);
            }
            release_object($user);
        }
        return $userContributorMap;
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