<?php


class UserProfiles_IndexController extends Omeka_Controller_AbstractActionController
{
    
    public function init()
    {
        $this->_helper->db->setDefaultModelName('UserProfilesProfile');
        $this->_browseRecordsPerPage = get_option('per_page_admin');        
    }
    
    public function indexAction()
    {
        $db = $this->_helper->db;
        $profileTypes = $db->getTable('UserProfilesType')->findAll();
        $this->view->types = $profileTypes;
        $this->_helper->viewRenderer->renderScript('index.php');
        
    }
    
}