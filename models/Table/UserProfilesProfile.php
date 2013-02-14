<?php


class Table_UserProfilesProfile extends Omeka_Db_Table
{

    public function getSelect()
    {
        $select = parent::getSelect();
        $permissions = new Omeka_Db_Select_PublicPermissions('UserProfiles_Profile');
        $permissions->apply($select, 'user_profiles_profiles');
        return $select;
    }    
    
    public function findByUserId($userId)
    {
        $db = $this->getDb();
        $params =  array(
                'subject_id' => $userId,
                'object_record_type' => 'UserProfilesProfile',
        );
        $profiles = $db->getTable('RecordRelationsRelation')->findObjectRecordsByParams($params);        
        return $profiles;
    }
    
    
    public function findByUserIdAndTypeId($userId, $typeId)
    {
        $db = $this->getDb();
        $params =  array(
                    'subject_id' => $userId,
                    'object_record_type' => 'UserProfilesProfile',
                    );
        $profiles = $db->getTable('RecordRelationsRelation')->findObjectRecordsByParams($params, array(), array('type_id'=>$typeId));
        if(empty($profiles)) {
            return false;
        }        
        return $profiles[0];
    }

    public function findUserByProfileId($profileId)
    {
        $db = $this->getDb();
        $accountOfId = $this->getAccountOfId();

        $params =  array(// 'property_id' => $accountOfId,  //commented out to try Institutions profile
                    'object_id' => $profileId,
               //     'subject_record_type' => 'User',  //commented out to try Institutions profile
                    'object_record_type' => 'UserProfilesProfile',
                    );
        $users = $db->getTable('RecordRelationsRelation')->findSubjectRecordsByParams($params);
        return $user;
    }

    public function getAccountOfId()
    {
        $prop =  get_db()->getTable('RecordRelationsProperty')->findByVocabAndPropertyName(SIOC, 'account_of');
        return $prop->id;
    }
}