<?php

class UserProfilesType extends Omeka_Record_AbstractRecord {

    public $id;
    public $label;
    public $description;
    public $element_set_id;
    private $_elements;
    private $_elementInfos;
    private $_multiInfos;
    protected $_related = array('ElementSet' => 'getElementSet', 'Elements'=>'getAllElements');    
    
    public function init()
    {
        parent::init();        
    }

    protected function afterSave($args) {
        foreach($this->_elementInfos as $elementInfo) {
            $element=$elementInfo['element'];
            $element->order = $elementInfo['order'];
            $element->save();
        }
        
        foreach($this->_multiInfos as $multiInfo) {
            $multiEl = $multiInfo['multielement'];
            $multiEl->order = $multiInfo['order'];
            $multiEl->setOptions($multiInfo['options']);
            $multiEl->save();
        }
            
    }
    
    public function getElementSet()
    {
        return $this->_db->getTable('ElementSet')->find($this->element_set_id);
    }
    
    /*
     * Do a weird splice of regular elements and UserProfilesMultiElements
     */
    
    public function getAllElements()
    {
        $elements = $this->ElementSet->getElements();
        $multiElements = $this->_db->getTable('UserProfilesMultiElement')->findBy(array('element_set_id'=>$this->element_set_id));
        return array_merge($elements, $multiElements); 
    }
    
    public function setElementInfos($elementInfos)
    {
        $this->_elementInfos = $elementInfos;
    }
    
    public function setMultiElementInfos($multiInfos)
    {
        $this->_multiInfos = $multiInfos;
    }
    
    private function _loadElements()
    {
        $this->_elements = $this->getTable('Element')->findBy(array('element_set_id'=>$this->id));
    }
    

}