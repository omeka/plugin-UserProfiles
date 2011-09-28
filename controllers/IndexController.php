<?php


class UserProfiles_IndexController extends Omeka_Controller_Action
{
    
    public function init()
    {
        $this->_modelClass = 'UserProfilesProfile';
        
    }
    
    public function indexAction()
    {
        $db = $this->getDb();
        $profileTypes = $this->getTable('UserProfilesType')->findAll();
        $this->view->types = $profileTypes;
        $this->_helper->viewRenderer->renderScript('index.php');
        
    }
    
}