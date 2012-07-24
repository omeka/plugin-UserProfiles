<?php

class UserProfilesPlugin extends Omeka_Plugin_Abstract
{

    protected $_hooks = array(
    	'install',
        'uninstall',
        'define_acl'
        );

    protected $_filters = array( 
            'admin_navigation_main',

            );

    protected $_options = null;

    public function setUp()
    {
        if(plugin_is_active('GuestUser')) {
            $this->_filters[] = 'guest_user_links';
        }
        parent::setUp();
    }
    
    public function hookInstall()
    {
        $db = get_db();
        $sql = "
            CREATE TABLE IF NOT EXISTS `$db->UserProfilesProfile` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `type_id` int(10) unsigned NOT NULL ,
                `added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
                `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
    			`values` text  ,
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        $db->query($sql);

        $sql = "
            CREATE TABLE IF NOT EXISTS `$db->UserProfilesType` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `label` text,
                `description` text,
    			`fields` text ,
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        $db->query($sql);
    }

    public function hookUninstall()
    {
        $db = get_db();
        $sql = "DROP TABLE IF EXISTS `$db->UserProfilesProfile` ";
        $db->query($sql);

        $sql = "DROP TABLE IF EXISTS `$db->UserProfilesType` ";
        $db->query($sql);
    }

    public function filterAdminNavigationMain($tabs)
    {
        $tabs['User Profiles'] = uri("user-profiles");
        return $tabs;
    }

    public function filterGuestUserLinks($links)
    {
        $user = current_user();
        
        $links[] = "<a href='" . html_escape(PUBLIC_BASE_URL . "/user-profiles/profiles/user/id/{$user->id}") . "'>My Profile</a>";
        return $links;
    }
    
    public function hookDefineAcl($acl)
    {
        $acl->addResource('UserProfiles_Type');
        $acl->addResource('UserProfiles_Profile');
        
        $roles = array( 'researcher', 'contributor', 'admin', 'super');
        $acl->allow(null, 'UserProfiles_Profile', array('editSelf', 'deleteSelf', 'add', 'user'));

        $acl->allow(null, 
                    'UserProfiles_Profile', 
                    array('edit', 'delete'),         
                    new Omeka_Acl_Assert_Ownership);
        
        $acl->deny(null, 'UserProfiles_Type');
        $acl->allow('super', 'UserProfiles_Type');
        $acl->allow('admin', 'UserProfiles_Type');
    }
}
