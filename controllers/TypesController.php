<?php
class UserProfiles_TypesController extends Omeka_Controller_Action
{

    public function init()
    {
        $this->_modelClass = 'UserProfilesType';
    }

    public function indexAction()
    {
        $this->redirect->gotoSimple('browse');
        return;
    }

    public function addAction()
    {
        // Handle edit vocabulary form.
        $profileType = new UserProfilesType();
        if ($this->_getParam('submit_add_type')) {
            $profileType->fields = $this->_assembleFields();
            $profileType->label = $this->_getParam('type_label');
            $profileType->description = $this->_getParam('type_description');
            if($profileType->save() ) {
	            // Redirect to browse.
	            $this->flashSuccess('The profile type was successfully added.');
	            $this->redirect->gotoSimple('browse');
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
        $profileType = $this->getTable('UserProfilesType')->find($typeId);

        // Handle edit vocabulary form.
        if ($this->_getParam('submit_edit_type')) {

            //@TODO: disallow editing keys if profiles have already been created
            $profileType->fields = $this->_assembleFields();
            $profileType->label = $this->_getParam('type_label');
            $profileType->description = $this->_getParam('type_description');

            if($profileType->save() ) {
                $this->flashSuccess('The profile type was successfully edited.');
            } else {
                $errors = $profileType->getErrors();
                $this->flashError($errors);
            }
            // Redirect to browse.
            //$this->redirect->gotoSimple('browse');
        }

        $this->view->fields = unserialize($profileType->fields) ;
        $this->view->label = $profileType->label;
        $this->view->description = $profileType->description;
    }

    public function deleteAction()
    {
        if (!$this->getRequest()->isPost()) {
            $this->_forward('method-not-allowed', 'error', 'default');
            return;
        }

        $record = $this->findById();

        $form = $this->_getDeleteForm();

        if ($form->isValid($_POST)) {
            //delete the profiles of this type, and their relations
            $profilesToDelete = $this->getTable('UserProfilesProfile')->findBy(array('type_id' => $record->id));
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
        $this->redirect->goto('browse');


    }

    public function browseAction()
    {
        $types = $this->getTable('UserProfilesType')->findAll();
        $this->view->types = $types;
    }

    protected function _assembleFields()
    {
        //put the field data into an array structure
        $labels = $this->_getParam('field_labels');
        $descriptions = $this->_getParam('field_descriptions');
        $types = $this->_getParam('field_types');
        $values = $this->_getParam('field_values');
        $fields = array();
        foreach($labels as $index=>$label) {
            $newField = array(
            					'order' => $index,
                                'label' => $label,
                                'description' => $descriptions[$index],
                                'type' =>  $types[$index],
                                'values' => $this->_parseValues($values[$index])
                             );
            //ignore an untouched field
            if( ! (empty($newField['label'])
                && empty($newField['description'])
                && empty($newField['type'])
                && empty($newField['values']) ) ) {
                    $fields[] = $newField;
                }
        }

        return $fields;
    }

    protected function _parseValues($values)
    {
        if(empty($values)) {
            return null;
        }
        return array_map('trim', explode('|', $values));
    }

    private function _getTypeForm()
    {
        require_once USER_PROFILES_DIR . '/forms/Type.php';
        $form = new UserProfiles_Form_Type();
        return $form;
    }

}