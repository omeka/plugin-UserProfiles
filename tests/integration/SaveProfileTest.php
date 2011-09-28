<?php

class UserProfiles_RelationTest extends UserProfiles_Test_AppTestCase {

    
    public function testRelation() {
        
        $profile = get_db()->getTable('UserProfilesProfile')->find(1);
        $this->assertEquals($profile->type_id, 1);
        $rel = get_db()->getTable('RecordRelationsRelation')->find(1);
        $this->assertEquals($rel->id, 1);
        $this->assertEquals($rel->subject_record_type, 'User');
        $this->assertEquals($rel->object_record_type, 'UserProfilesProfile');
        $this->assertEquals($rel->property_id, 2);
        // */
    }
  
    
}