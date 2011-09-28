<?php


class UserProfiles_AccountOfTest extends UserProfiles_Test_AppTestCase
{

    public function testGetPropertyId() {
        $id = get_db()->getTable('UserProfilesProfile')->getAccountOfId();
        $this->assertEquals($id, 2);
    }
    
    public function testAccountOf() {
        $prop = get_db()->getTable('RecordRelationsProperty')->findByVocabAndPropertyName(SIOC, 'account_of');
        $this->assertEquals($prop->id, 2);
    }
    
    public function testGetUsersProfiles() {
        $profiles = get_db()->getTable('UserProfilesProfile')->findByUserId(1);
        foreach($profiles as $profile) {
            $this->assertEquals('UserProfilesProfile', get_class($profile) );
            $this->assertEquals(1, $profile->id);
            $this->assertNotNull($profile->values);
        }

    }
}

