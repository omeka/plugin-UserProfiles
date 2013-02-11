<?php
class UserProfiles_TypesController extends Omeka_Controller_AbstractActionController
{

    protected $_elementSet;
    
    public function init()
    {
        $this->_helper->db->setDefaultModelName('UserProfilesType');
        $this->_browseRecordsPerPage = get_option('per_page_admin');        
    }

    public function indexAction()
    {
        $this->redirect('user-profiles/types/browse');
        return;
    }

    public function addNewElementAction()
    {
        if ($this->_getParam('from_post') == 'true') {
            $elementTempId = $this->_getParam('elementTempId');
            $elementName = $this->_getParam('elementName');
            $elementDescription = $this->_getParam('elementDescription');
            $elementOrder = $this->_getParam('elementOrder');
        } else {
            $elementTempId = '' . time();
            $elementName = '';
            $elementDescription = '';
            $elementOrder = intval($this->_getParam('elementCount')) + 1;
        }
    
        $stem = Omeka_Form_ItemTypes::NEW_ELEMENTS_INPUT_NAME . "[$elementTempId]";
        $elementNameName = $stem . '[name]';
        $elementDescriptionName = $stem . '[description]';
        $elementOrderName = $stem . '[order]';
    
        $elementData = array('element_name_name' => $elementNameName,
                'element_name_value' => $elementName,
                'element_description_name' => $elementDescriptionName,
                'element_description_value' => $elementDescription,
                'element_order_name' => $elementOrderName,
                'element_order_value' => $elementOrder,
        );
        $multistem = "new-multi[$elementTempId]";
        $type = $this->getParam('type');
        $elementData['options'] = $stem . "[options]";
        $elementData['type'] = $stem . "[type]";
        $elementData['raw_type'] = $type;
        $this->view->assign($elementData);
    }
    
    
    public function addAction()
    {
        // Handle edit vocabulary form.
        $profileType = new UserProfilesType();
        $this->view->profileType = $profileType;
        if ($this->_getParam('submit')) {
            $profileType->label = $this->_getParam('name');
            $profileType->description = $this->_getParam('description');
            $elementSet = new ElementSet();
            $elementSet->name = $profileType->label . " Elements";
            $elementSet->description = "Elements for " . $profileType->label;
            $elementSet->record_type = 'UserProfilesType';
            $elementSet->save();
            $this->_elementSet = $elementSet;
            $profileType->element_set_id = $elementSet->id;
            $elementInfos = $this->_getElementInfos();
            $profileType->setElementInfos($elementInfos);
            $multiInfos = $this->_getMultiElementInfos();
            $profileType->setMultiElementInfos($multiInfos);
            if($profileType->save() ) {
	            $this->_helper->flashMessenger('The profile type was successfully added.', 'success');
	            $this->redirect('user-profiles');
            } else {
            	$errors = $profileType->getErrors();
            	foreach($errors as $error) {
            		$this->flashError($error);
            	}
            }
        }
    }

    public function editAction()
    {
        $typeId = $this->_getParam('id');
        $profileType = $this->_helper->db->getTable('UserProfilesType')->find($typeId);
        $this->view->profileType = $profileType;
        
        $this->_setViewElementInfos($profileType);
        // Handle edit vocabulary form.
        if ($this->_getParam('submit')) {

            $profileType->label = $this->_getParam('name');
            $profileType->description = $this->_getParam('description');
            $this->_elementSet = $profileType->ElementSet;
            $elementInfos = $this->_getElementInfos();
            $profileType->setElementInfos($elementInfos);
            $multiInfos = $this->_getMultiElementInfos();
            $profileType->setMultiElementInfos($multiInfos);
            if($profileType->save() ) {
                $this->_helper->flashMessenger(__('The profile type ' . $profileType->label . ' was successfully edited.'), 'success');
            } else {
                $errors = $profileType->getErrors();
                $this->_helper->flashMessenger($errors, 'error');
            }
            // Redirect to browse.
            $this->redirect('user-profiles');
        }
    }

    public function deleteAction()
    {
        if (!$this->getRequest()->isPost()) {
            $this->_forward('method-not-allowed', 'error', 'default');
            return;
        }

        $record = $this->_helper->db->getTable('UserProfilesType')->find($this->_getParam('id'));

        $form = $this->_getDeleteForm();

        if ($form->isValid($_POST)) {
            //delete the profiles of this type, and their relations
            $profilesToDelete = $this->_helper->db->getTable('UserProfilesProfile')->findBy(array('type_id' => $record->id));
            foreach($profilesToDelete as $profile) {
                $profile->deleteWithRelation();
            }
            $record->delete();
        } else {
            $this->_forward('error');
            return;
        }

        $successMessage = $this->_getDeleteSuccessMessage($record);
        if ($successMessage != '') {
            $this->flashSuccess($successMessage);
        }
        $this->redirect('user-profiles');


    }

    public function browseAction()
    {
        $types = $this->_helper->db->getTable('UserProfilesType')->findAll();
        $this->view->types = $types;
    }

    protected function _parseValues($values)
    {
        if(empty($values)) {
            return null;
        }
        return array_map('trim', explode("\n", $values));
    }

    protected function _getMultiElementInfos()
    {
        $multiElementTable = $this->_helper->db->getTable('UserProfilesMultiElement');
        if(isset($_POST['multielements'])) {
            foreach($_POST['multielements'] as $elementId=>$info) {
                $multiInfos[] = array(
                        'multielement' => $multiElementTable->find($elementId),
                        'order' => $info['order'],
                        'description' => $info['description'],
                        'options' => $info['options']
                        
                        );
            }
        }
        
        $newElements = $_POST['new-elements'];
        foreach ($newElements as $tempId => $info) {
            if (empty($info['name']) || !isset($info['type'])) {
                continue;
            }
        
            $multiEl = new UserProfilesMultiElement();
            $multiEl->element_set_id = $this->_elementSet->id;
            $multiEl->setName($info['name']);
            $multiEl->setDescription($info['description']);
            $multiEl->order = $info['order'];
            $multiEl->type = $info['type'];
            $multiInfos[] = array(
                    'multielement' => $multiEl,
                    'order' => $info['order'],
                    'description' => $info['description'],
                    'options' => $info['options']
                    
                    
                    );
        
        }
        return $multiInfos;        
    }
    
    protected function _getElementInfos()
    {
        $elementTable = $this->_helper->db->getTable('Element');   
        if (isset($_POST['elements'])) {
            $currentElements = $_POST['elements'];
            foreach ($currentElements as $elementId => $info) {
                $elementInfos[] = array(
                        'element' => $elementTable->find($elementId),
                        'temp_id' => null,
                        'description' => $info['description'],
                        'order' => $info['order']
                );
            }
        }        
        
        $newElements = $_POST['new-elements'];
        foreach ($newElements as $tempId => $info) {
            debug(print_r($info, true));
            if (empty($info['name']) || isset($info['type'])) {
                continue;
            }
        
            $element = new Element;
            $element->element_set_id = $this->_elementSet->id;
            $element->setName($info['name']);
            $element->setDescription($info['description']);
            $element->order = null;
        
            $elementInfos[] = array(
                    'element' => $element,
                    'temp_id' => $tempId,
                    'order' => $info['order']
            );
        }        
        
        return $elementInfos;
        
    }

    private function _checkForDuplicateElements($elementInfos)
    {
        // Check for duplicate elements and throw an exception if a duplicate is found
        $elementIds = array();
        $elementNames = array();
        foreach($elementInfos as $elementInfo) {
            $element = $elementInfo['element'];
    
            // prevent duplicate item type element ids
            if ($element->id) {
                if (in_array($element->id, $elementIds)) {
                    throw new Omeka_Validate_Exception(__('The profile type cannot have more than one "%s" element.', $element->name));
                }
            }
    
            // prevent duplicate item type element names
            if ($element->name) {
                if (in_array($element->name, $elementNames)) {
                    throw new Omeka_Validate_Exception(__('The profile type cannot have more than one "%s" element.', $element->name));
                }
            }
        }
    }
    
    private function _removeElementsFromProfileType($profileType)
    {
        if(empty($_POST['remove-elements'])) {
            return;
        }
        $elementTable = get_db()->getTable('Element');
        $elementIds = explode(',', $_POST['remove-elements']);
        foreach($elementIds as $elementId) {
            $elementId = intval(trim($elementId));
            if ($elementId) {
                if ($element = $elementTable->find($elementId)) {
                    $profileType->removeElement($element);
                }
            }
        }
    }
    
    private function _setViewElementInfos($profileType)
    {
        $elementInfos = array();
        $elementOrder = 1;
        foreach($profileType->Elements as $element) {
            $elementInfo = array(
                    'element' => $element,
                    'temp_id' => null,
                    'order' => $elementOrder,
            );
            $elementInfos[] = $elementInfo;
            $elementOrder++;
        }        
        $this->view->elementInfos = $elementInfos;
    }
    
    
}