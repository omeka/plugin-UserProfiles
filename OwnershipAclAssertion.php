<?php

class UserProfiles_OwnershipAclAssertion implements Zend_Acl_Assert_Interface
{
    /**
     * Assert whether or not the ACL should allow access.
     */
    public function assert(Zend_Acl $acl,
                           Zend_Acl_Role_Interface $role = null,
                           Zend_Acl_Resource_Interface $resource = null,
                           $privilege = null)
    {
        $request = Omeka_Context::getInstance()->getRequest();
        return current_user()->id == $request->getParam('id');
        return false;
    }
}