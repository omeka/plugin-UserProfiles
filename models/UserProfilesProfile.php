<?php
require_once(PLUGIN_DIR . '/RecordRelations/includes/models/RelatableRecord.php');

class UserProfilesProfile extends RelatableRecord {
    public $id;
    public $type_id;
    public $values;
    public $added;
    public $modified;
    protected $namespace = SIOC;
    protected $subject_record_type = 'User';
    protected $object_record_type = 'UserProfilesProfile';
    protected $local_part = 'account_of';
    protected $_isSubject = false;


    protected function beforeSave()
    {
        parent::beforeSave();
        $this->values = serialize($this->values);

    }
}