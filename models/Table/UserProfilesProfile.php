<?php


class Table_UserProfilesProfile extends Omeka_Db_Table
{
    protected $_alias = 'upp';

    
    public function findByUserIdAndType($userId, $type)
    {
        $db = $this->getDb();
        $params =  array(
                    'subject_id' => $userId,
                    'object_record_type' => 'UserProfilesProfile',
                    );
        debug(print_r($params, true));
        $profiles = $db->getTable('RecordRelationsRelation')->findObjectRecordsByParams($params);        
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