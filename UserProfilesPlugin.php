<?php
define('USER_PROFILES_DIR', dirname(__FILE__) );
class UserProfilesPlugin extends Omeka_Plugin_Abstract
{
    
    protected $_hooks = array(
    	'install',
        'uninstall',
        'define_acl'
        );
                            
    protected $_filters = array( 'admin_navigation_main' );

    protected $_options = null;
    
    public function install()
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
    
    public function uninstall()
    {
        $db = get_db();
        $sql = "DROP TABLE IF EXISTS `$db->UserProfilesProfile` ";
        $db->query($sql);
    
        $sql = "DROP TABLE IF EXISTS `$db->UserProfilesType` ";
        $db->query($sql);
    }
    
    public function adminNavigationMain($tabs)
    {
        $tabs['User Profiles'] = uri("user-profiles");
        return $tabs;
    }
    
    public function defineAcl($acl)
    {
        $resourceList = array(
            'UserProfiles_Types' => array('add', 'edit', 'delete')
        );
        $acl->loadResourceList($resourceList);
        
        $acl->deny(null, 'UserProfiles_Types');
        $acl->allow('super', 'UserProfiles_Types');
        $acl->allow('admin', 'UserProfiles_Types');
    }
}