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
        //need to run through setting all the data and order, then reorder elements before saving to avoid collisions
        //first, set all the orders to null
        $allExtantElements = $this->getAllElements();
        foreach($allExtantElements as $element) {
            $element->order = null;
            $element->save();
        }
        
        //then, run through the elements to rebuild order
        $allElements = array();
        foreach($this->_elementInfos as $elementInfo) {
            $element=$elementInfo['element'];
            $element->order = $elementInfo['order'];
            $allElements[] = $element;
        }
        
        foreach($this->_multiInfos as $multiInfo) {
            $multiEl = $multiInfo['multielement'];
            $multiEl->order = $multiInfo['order'];
            $multiEl->setOptions($multiInfo['options']);
            $allElements[] = $multiEl;
        }
        
        //and make sure the orders are sequential
        usort($allElements, array($this, '_sortElements'));
        $order = 1;
        foreach($allElements as $element) {
            $element->order = $order;
            $element->save();
            $order++;
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
        $allElements = array_merge($elements, $multiElements);
        usort($allElements, array($this, '_sortElements')); 
        return $allElements;  
    }
    
    
    public function setElementInfos($elementInfos)
    {
        $this->_elementInfos = $elementInfos;
    }
    
    public function setMultiElementInfos($multiInfos)
    {
        $this->_multiInfos = $multiInfos;
    }
    
    private function _sortElements($a, $b)
    {
        if($a->order == $b->order) {
            return 0;
        }
        
        return ($a->order < $b->order) ? -1 : 1;
    }
    
    private function _loadElements()
    {
        $this->_elements = $this->getTable('Element')->findBy(array('element_set_id'=>$this->id));
    }
    

}