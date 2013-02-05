<?php

class UserProfilesType extends Omeka_Record_AbstractRecord {

    public $id;
    public $label;
    public $description;
    public $element_set_id;
    private $_elements;
    private $_elementInfos;
    protected $_related = array('ElementSet' => 'getElementSet', 'Elements'=>'getElements');    
    
    public function init()
    {
        parent::init();        
    }

    protected function afterSave($args) {
        debug('after save');
        foreach($this->_elementInfos as $elementInfo) {
            $element=$elementInfo['element'];
            $element->save();
        }
    }
    
    public function getElementSet()
    {
        return $this->_db->getTable('ElementSet')->find($this->element_set_id);
    }
    
    public function getElements()
    {
        return $this->ElementSet->getElements();
    }
    
    public function setElementInfos($elementInfos)
    {
        $this->_elementInfos = $elementInfos;
    }
    
    
    
    private function _loadElements()
    {
        $this->_elements = $this->getTable('Element')->findBy(array('element_set_id'=>$this->id));
    }
    

}