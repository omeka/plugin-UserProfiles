<?php

define('USER_PROFILES_DIR', dirname(__FILE__) );

class UserProfilesPlugin extends Omeka_Plugin_AbstractPlugin
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
                `owner_id` int(10) unsigned NOT NULL ,
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
        
        $sql = "
            CREATE TABLE IF NOT EXISTS `$db->UserProfilesTypeElement` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `profile_type_id` int(10) unsigned NOT NULL,
              `element_id` int(10) unsigned NOT NULL,
              `order` int(10) unsigned DEFAULT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `item_type_id_element_id` (`profile_type_id`,`element_id`),
              KEY `item_type_id` (`item_type_id`),
              KEY `element_id` (`element_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;        
        ";
        
        $db->query($sql);

        $sql = "
            INSERT IGNORE INTO `$db->ElementSet` (
            `id` ,
            `record_type` ,
            `name` ,
            `description`
            )
            VALUES (
            NULL , 'UserProfilesType', 'User Profiles Elements', 'Elements to use with User Profiles'
            );        
        
        ";
        
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
        $tabs['User Profiles'] = array('label'=>'User Profiles', 'uri'=>url("user-profiles") );
        return $tabs;
    }

    public function filterGuestUserLinks($links)
    {
        $user = current_user();
        $links['UserProfiles'] = array('label'=>'My Profiles', 'uri'=>url("/user-profiles/profiles/user/id/{$user->id}"));
        return $links;
    }
    
    public function hookDefineAcl($args)
    {
        $acl = $args['acl'];
        $acl->addResource('UserProfiles_Type');
        $acl->addResource('UserProfiles_Profile');
        
        $roles = array( 'researcher', 'contributor', 'admin', 'super');
        $acl->allow(null, 'UserProfiles_Profile', array('editSelf', 'deleteSelf', 'add', 'user', 'delete-confirm'));

        $acl->allow(null, 
                    'UserProfiles_Profile', 
                    array('edit', 'delete'),         
                    new Omeka_Acl_Assert_Ownership);
        
        $acl->deny(null, 'UserProfiles_Type');
        $acl->allow('super', 'UserProfiles_Type');
        $acl->allow('admin', 'UserProfiles_Type');
    }
}
