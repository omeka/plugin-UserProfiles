<?php


class UserProfiles_Test_AppTestCase extends Omeka_Test_AppTestCase
{
    public function setUp()
    {
        parent::setUp();
        $pluginHelper = new Omeka_Test_Helper_Plugin;
        $pluginHelper->setUp('UserProfiles');
        $pluginHelper->setUp('RecordRelations');
        $user = $this->_getDefaultUser();
        $this->_authenticateUser($user);
        $this->_setupType();
        $this->_setupProfile();
    }
    
    public function _setupType()
    {
        $type = new UserProfilesType();
        $type->description = "Type Description";
        $fields = array(
                        array('label' => 'Text Field', 'type' => 'text', 'order' => 0),
                        array('label' => 'Int Field', 'type' => 'int', 'order' => 1)
        
                        );
        $type->fields = $fields;
        $type->save();
        
    }
    
    public function _setupProfile()
    {
        $profile = new UserProfilesProfile();
        $profile->setRelationData(
                array(
        			'subject_id' => 1,
                    'user_id' => 1,
                    )
                );
        $values = array(
            array('Text Field' => 'Text Field Value'),
            array('Int Field' => 'Int Field Value')
        );
        $profile->values = $values;
        $profile->type_id = 1;
        $this->assertFalse($profile->isSubject());
        $profile->save();
        
    }
    
    
}

