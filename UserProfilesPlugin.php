<?php

if(class_exists('Omeka_Plugin_Abstract')) {

class UserProfilesPlugin extends Omeka_Plugin_Abstract
{

    protected $_hooks = array(
    	'install',
        'uninstall',
        'define_acl'
        );

    protected $_filters = array( 'admin_navigation_main' );

    protected $_options = null;

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

} else {

class UserProfilesPlugin
{

    protected $_hooks = array(
    	'install',
        'uninstall',
        'define_acl',
        'public_append_to_items_show'
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
            'UserProfiles_Types' => array('add', 'edit', 'delete'),
            'UserProfiles_Profiles' => array('edit', 'editSelf', 'delete')
        );
        $acl->loadResourceList($resourceList);

        $acl->deny(null, 'UserProfiles_Types');
        $acl->deny(null, 'UserProfiles_Profiles');
        $acl->allow('super', 'UserProfiles_Types');
        $acl->allow('admin', 'UserProfiles_Types');

        $acl->allow(array('contributor', 'researcher', 'admin', 'super'),
        				'UserProfiles_Profiles',
                        array('edit', 'editSelf', 'delete'),
                        new UserProfiles_OwnershipAclAssertion()
                        );
// */
    }

    public function publicAppendToItemsShow()
    {
        include USER_PROFILES_DIR . '/public_items_show.php';


    }

    public function __construct()
    {
        $this->_db = Omeka_Context::getInstance()->getDb();
        $this->_addHooks();
        $this->_addFilters();
    }

    /**
     * Set options with default values.
     *
     * Plugin authors may want to use this convenience method in their install
     * hook callback.
     */
    protected function _installOptions()
    {
        $options = $this->_options;
        if (!is_array($options)) {
            return;
        }
        foreach ($options as $name => $value) {
            // Don't set options without default values.
            if (!is_string($name)) {
                continue;
            }
            set_option($name, $value);
        }
    }

    /**
     * Delete all options.
     *
     * Plugin authors may want to use this convenience method in their uninstall
     * hook callback.
     */
    protected function _uninstallOptions()
    {
        $options = self::$_options;
        if (!is_array($options)) {
            return;
        }
        foreach ($options as $name => $value) {
            delete_option($name);
        }
    }

    /**
     * Validate and add hooks.
     */
    private function _addHooks()
    {
        $hookNames = $this->_hooks;
        if (!is_array($hookNames)) {
            return;
        }
        foreach ($hookNames as $hookName) {
            $functionName = Inflector::variablize($hookName);
            if (!is_callable(array($this, $functionName))) {
                throw new Omeka_Plugin_Exception('Hook callback "' . $functionName . '" does not exist.');
            }
            add_plugin_hook($hookName, array($this, $functionName));
        }
    }

    /**
     * Validate and add filters.
     */
    private function _addFilters()
    {
        $filterNames = $this->_filters;
        if (!is_array($filterNames)) {
            return;
        }
        foreach ($filterNames as $filterName) {
            $functionName = Inflector::variablize($filterName);
            if (!is_callable(array($this, $functionName))) {
                throw new Omeka_Plugin_Exception('Filter callback "' . $functionName . '" does not exist.');
            }
            add_filter($filterName, array($this, $functionName));
        }
    }
}



}
