<?php
class UserProfiles_ProfilesController extends Omeka_Controller_AbstractActionController
{

    public function init()
    {
        $this->_helper->db->setDefaultModelName('UserProfilesProfile');
        $this->_browseRecordsPerPage = get_option('per_page_admin');
    }

    protected function _redirectAfterDelete($record)
    {
        $this->_helper->redirector('user');
    }    
    
    public function editAction()
    {
        $this->view->addHelperPath(USER_PROFILES_DIR . '/helpers', 'UserProfiles_View_Helper_');
        $allTypes = $this->_helper->db->getTable('UserProfilesType')->findAll();
        $typeId = $this->getParam('type');
        
        //if no typeId
        if(!$typeId) {
            $typeId = $allTypes['0']->id;
        }
        $profileType = $this->_helper->db->getTable('UserProfilesType')->find($typeId);
        
        $userId = $this->_getParam('id');
        if($userId) {
            $user = $this->_helper->db->getTable('User')->find($userId);
        } else {
            $user = current_user();
            $userId = $user->id;            
        }      
        $this->view->user = $user; 

        $userProfile = $this->_helper->db->getTable()->findByUserIdAndTypeId($userId, $typeId);
        if(!$userProfile) {
            $userProfile = new UserProfilesProfile();
            $userProfile->setOwner($user);
            $userProfile->type_id = $typeId;
            $userProfile->setRelationData(array('subject_id'=>$userId));
            
        }
        
        if(!is_allowed($userProfile, 'edit')) {
            throw new Omeka_Controller_Exception_403; 
        }
        if($this->_getParam('submit') ) {
            $userProfile->setPostData($_POST);
            if($userProfile->save(false)) {
                fire_plugin_hook('user_profiles_save', array('post'=>$_POST, 'profile'=>$userProfile, 'type'=>$profileType));
                $this->redirect('user-profiles/profiles/user/id/'. $userId);
            } else {
                $this->_helper->flashMessenger($userProfile->getErrors());
            }
            
        }
        $this->view->userprofilesprofile = $userProfile;
        $this->view->userprofilestype = $profileType;
        $this->view->profile_types = apply_filters('user_profiles_type', $allTypes);
    }

    public function userAction()
    {
        $allTypes = $this->_helper->db->getTable('UserProfilesType')->findAll();
        $userId = $this->_getParam('id');
        
        $typeId = $this->getParam('type');
        
        //if no typeId, as happens on the public side, give the first profile typeId
        if(!$typeId) {
            $typeId = $allTypes['0']->id;
        }
        $profileType = $this->_helper->db->getTable('UserProfilesType')->find($typeId);
                
        if($userId) {
            $user = $this->_helper->db->getTable('User')->find($userId);
        } else {
            $user = current_user();
            $userId = $user->id;            
        }
        $userProfile = $this->_helper->db->getTable()->findByUserIdAndTypeId($userId, $typeId);
        $this->view->user = $user;
        $this->view->userprofilesprofile = $userProfile;
        $this->view->userprofilestype = $profileType;
        $this->view->profile_types = apply_filters('user_profiles_type', $allTypes);     
    }
    
    public function elementFormAction()
    {
        $elementId = (int)$_POST['element_id'];
        $recordType = $_POST['record_type'];
        $recordId  = (int)$_POST['record_id'];
         
        // Re-index the element form posts so that they are displayed in the correct order
        // when one is removed.
        $_POST['Elements'][$elementId] = array_merge($_POST['Elements'][$elementId]);
    
        $element = $this->_helper->db->getTable('Element')->find($elementId);
        $record = $this->_helper->db->getTable($recordType)->find($recordId);
    
        if (!$record) {
            $record = new $recordType;
        }
    
        $this->view->assign(compact('element', 'record'));
    }    
    
}