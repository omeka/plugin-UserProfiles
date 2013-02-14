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
                `public` tinyint(1) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        $db->query($sql);

        $sql = "
            CREATE TABLE IF NOT EXISTS `$db->UserProfilesType` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `label` text,
                `description` text,
    			`element_set_id` int(10) unsigned NOT NULL,
    			`required_element_ids` text NOT NULL,
                `required_multielement_ids` text NOT NULL,
                `public` tinyint(1) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        $db->query($sql);
        
        $sql = "
            CREATE TABLE IF NOT EXISTS `$db->UserProfilesMultiElement` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `name` text COLLATE utf8_unicode_ci NOT NULL,
              `description` text COLLATE utf8_unicode_ci NOT NULL,
              `type` enum('radio','select','multiselect','checkbox') COLLATE utf8_unicode_ci NOT NULL,
              `options` text COLLATE utf8_unicode_ci NOT NULL,
              `element_set_id` int(10) unsigned NOT NULL,
              `order` int(11) DEFAULT NULL,
              `comment` text COLLATE utf8_unicode_ci,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='A parallel to Elements for checkboxes, radio, selects ' ;      

        ";
        
        $db->query($sql);

        $sql = "
            CREATE TABLE IF NOT EXISTS `$db->UserProfilesMultiValue` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `profile_type_id` int(10) unsigned NOT NULL,
              `profile_id` int(10) unsigned NOT NULL,
              `values` text COLLATE utf8_unicode_ci NOT NULL,
              `multi_id` int(10) unsigned NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;            
        
        
        ";
        
        $db->query($sql);
  
        set_option('user_profiles_required_elements', serialize(array()));
        set_option('user_profiles_required_multielements', serialize(array()));
    }

    public function hookUninstall()
    {
        $db = get_db();
        //Delete all elements, elementsets, and elementtexts UP is using
        $types = $db->getTable('UserProfilesType')->findAll();
        debug(count($types));
        foreach($types as $type) {
            $type->getElementSet()->delete();
        }
        
        $sql = "DROP TABLE IF EXISTS `$db->UserProfilesProfile` ";
        $db->query($sql);

        $sql = "DROP TABLE IF EXISTS `$db->UserProfilesType` ";
        $db->query($sql);
        
        $sql = "DROP TABLE IF EXISTS `$db->UserProfilesMultiValue` ";
        $db->query($sql);

        $sql = "DROP TABLE IF EXISTS `$db->UserProfilesMultiElement` ";
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
        $firstProfileTypes = $this->_db->getTable('UserProfilesType')->findBy(array(), 1);
        if(!empty($firstProfileTypes)) {
            $type = $firstProfileTypes[0];
            $links['UserProfiles'] = array('label'=>'My Profiles', 'uri'=>url("/user-profiles/profiles/user/id/{$user->id}?type={$type->id}"));
                        
        }
        return $links;
    }
    
    public function hookDefineAcl($args)
    {
        $acl = $args['acl'];
        $acl->addResource('UserProfiles_Type');
        $acl->addResource('UserProfiles_Profile');
                
        $acl->allow(null,
                'UserProfiles_Profile',
                array('edit', 'delete'),
                new Omeka_Acl_Assert_Ownership);
                
        $acl->allow(null, 'UserProfiles_Profile', array('user'));
        $acl->allow(array('super', 'admin', 'contributor', 'researcher', 'guest'), 'UserProfiles_Profile', array('add', 'editSelf', 'delete-confirm', 'showSelfNotPublic', 'deleteSelf'));

        $acl->allow(array('admin', 'super', 'researcher'), 'UserProfiles_Profile', array('showNotPublic'));
        $acl->allow(array('admin', 'super'), 'UserProfiles_Profile', array('deleteAll'));
        
        $acl->deny(null, 'UserProfiles_Type');
        $acl->allow(array('super', 'admin'), 'UserProfiles_Type');
        $acl->allow(array('super', 'admin', 'contributor', 'researcher', 'guest'), 'UserProfiles_Type', array('showNotPublic'));
    }
}
