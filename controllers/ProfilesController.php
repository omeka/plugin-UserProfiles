<?php
class UserProfiles_ProfilesController extends Omeka_Controller_Action
{
        
    public function init()
    {
        $this->_modelClass = 'UserProfilesProfile';
    }
    
    public function editAction()
    {
        $profileTypes = $this->_getProfileTypes();
        $userId = $this->_getParam('id');
        $this->view->user = get_user_by_id($userId);
        $userProfiles = $this->getTable()->findByUserId($userId, true);
        if ($this->_getParam('user_profile_submit')) {
            //profiles come in as an array keyed by profile type
            if($profiles = $this->_getParam('user_profiles') ) {
                $typeIds = array_keys($profiles);
                foreach($typeIds as $typeId) {
                    if(!isset($userProfiles[$typeId])) {
                        $currProfile = new UserProfilesProfile();
                        $currProfile->setRelationData(array('subject_id'=>$userId));
                        $currProfile->type_id = $typeId;
                        $currProfile->values = $profiles[$typeId];
                        $userProfiles[] = $currProfile;
                    } else {
                        $currProfile = $userProfiles[$typeId];
                        $currProfile->type_id = $typeId;
                        $currProfile->values = $profiles[$typeId];
                    }
                    $currProfile->save();
                    //saving serializes the values, so unserialize them for delivery back to the view
                    $currProfile->values = unserialize($currProfile->values);
                }
            }
            fire_plugin_hook('user_profiles_save', $_POST);
        }

        $this->view->profiles = $userProfiles;
        $this->view->profile_types = $profileTypes;
    }
    
    public function userAction()
    {
        
        $profileTypes = $this->_getProfileTypes();
        $userId = $this->_getParam('id');
        $userProfiles = $this->getTable()->findByUserId($userId, true);
        $this->view->user = $this->getTable('User')->find($userId);
        if(empty($this->view->user)) {
            $this->flash("That doesn't seem to be a valid user or user id.");
        }
        $this->view->profiles = $userProfiles;
        $this->view->profile_types = $profileTypes;
        $this->view->filtered_html = apply_filters('user_profiles_user_page', '', $userId);
    }
    
    protected function _getProfileTypes()
    {
        $db = get_db();
        $profileTypes = $db->getTable('UserProfilesType')->findAll();
        foreach($profileTypes as $type) {
            $type->fields = unserialize($type->fields);
        }
        return $profileTypes;
    }
}