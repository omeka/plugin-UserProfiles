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
        $this->view->user = $this->getTable('User')->find($userId);
        $userProfiles = $this->getTable()->findByUserId($userId, true);
        if ($this->_getParam('user_profile_submit')) {
            //profiles come in as an array keyed by profile type
            if($profiles = $this->_getParam('user_profiles') ) {
                $typeIds = array_keys($profiles);
                foreach($typeIds as $typeId) {
                    if(!isset($userProfiles[$typeId])) {
                        $currProfile = new UserProfilesProfile();
                        $currProfile->setRelationData(array('subject_id'=>$userId, 'public'=>true));
                        $currProfile->type_id = $typeId;
                        $currProfile->values = $profiles[$typeId];
                        $userProfiles[$typeId] = $currProfile;
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

    public function deleteAction()
    {
        if (!$this->getRequest()->isPost()) {
            $this->_forward('method-not-allowed', 'error', 'default');
            return;
        }

        $record = $this->findById();

        $form = $this->_getDeleteForm();

        if ($form->isValid($_POST)) {
            $record->delete();
        } else {
            $this->_forward('error');
            return;
        }

        $successMessage = $this->_getDeleteSuccessMessage($record);
        if ($successMessage != '') {
            $this->flashSuccess($successMessage);
        }
        $user = current_user();
        $this->redirect->gotoUrl('user-profiles/profiles/edit/id/' . $user->id);
        //$this->redirect->goto('edit/id/' . $user->id );
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