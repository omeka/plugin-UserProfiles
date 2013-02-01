<?php


class Table_UserProfilesProfile extends Omeka_Db_Table
{
    protected $_alias = 'upp';

    public function findByUserId($userId, $keyByType = false)
    {
        $db = $this->getDb();
        $accountOfId = $this->getAccountOfId();
        $params =  array(//'property_id' => $accountOfId,  //commented out to try Institutions profile
                    'subject_id' => $userId,
                   // 'subject_record_type' => 'User', //commented out to try Institutions profile
                    'object_record_type' => 'UserProfilesProfile',
                    );
        $profiles = $db->getTable('RecordRelationsRelation')->findObjectRecordsByParams($params);
        if($keyByType) {
            $rekeyed = array();
            foreach($profiles as $profile) {
                $rekeyed[$profile->type_id] = $profile;
            }
            return $rekeyed;
        }
        return $profiles;
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


    public function applySearchFilters($select, $params)
    {
        if(empty($params)) {
            return $select;
        }
        $paramNames = array('id',
                            'type_id'
                            );
        foreach($paramNames as $paramName) {
            if (isset($params[$paramName])) {
                if(is_array($params[$paramName])) {
                    $select->where('upp.' . $paramName . ' = ?', $params[$paramName]);
                } else {
                    $select->where('upp.' . $paramName . ' = ?', array($params[$paramName]));
                }

            }
        }
        return $select;
    }

    protected function recordFromData($data)
    {
        //unserialize the values column
        $data['values'] = unserialize($data['values']);
        return parent::recordFromData($data);
    }


    public function getAccountOfId()
    {
        $prop =  get_db()->getTable('RecordRelationsProperty')->findByVocabAndPropertyName(SIOC, 'account_of');
        return $prop->id;
    }
}