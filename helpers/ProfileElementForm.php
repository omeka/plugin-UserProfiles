<?php

class UserProfiles_View_Helper_ProfileElementForm extends Omeka_View_Helper_ElementForm
{
    
    
    /*
     * The big overrides to the parent class are signalled by the conditionals around $isUserProfilesMultiElement
     * 
     */
    
    public function profileElementForm(Element $element, Omeka_Record_AbstractRecord $record, $options = array())
    {

        $divWrap = isset($options['divWrap']) ? $options['divWrap'] : true;
        $extraFieldCount = isset($options['extraFieldCount']) ? $options['extraFieldCount'] : null;
        $isUserProfilesMultiElement = (get_class($element) == 'UserProfilesMultiElement') ? true : false;
        $this->_element = $element;
        
        $record->loadElementsAndTexts();
        $this->_record = $record;
        
        // Filter the components of the element form display
        $labelComponent = $this->_getLabelComponent();
        if($isUserProfilesMultiElement) {
            $inputsComponent = $this->_getMultiComponent();
        } else {
            $inputsComponent = $this->_getInputsComponent($extraFieldCount);
        }
        
        $descriptionComponent = $this->_getDescriptionComponent();
        $commentComponent = $this->_getCommentComponent();
        if($isUserProfilesMultiElement) {
            $addInputComponent = '';            
        } else {
            $addInputComponent = $this->view->formSubmit('add_element_' . $this->_element['id'],
                    __('Add Input'),
                    array('class'=>'add-element'));            
        }

        $components = array(
                'label' => $labelComponent,
                'inputs' => $inputsComponent,
                'description' => $descriptionComponent,
                'comment' => $commentComponent,
                'add_input' => $addInputComponent,
                'html' => null
        );
        
        $elementSetName = $element->getElementSet()->name;
        $recordType = get_class($record);
        $filterName = array('ElementForm', $recordType, $elementSetName, $element->name);
        $components = apply_filters(
                $filterName,
                $components,
                array('record' => $record,
                        'element' => $element,
                        'options' => $options)
        );
        
        if ($components['html'] !== null) {
            return strval($components['html']);
        }
        
        // Compose html for element form
        $html = $divWrap ? '<div class="field" id="element-' . html_escape($element->id) . '">' : '';
        
        $html .= '<div class="two columns alpha">';
        $html .= $components['label'];
        $html .= $components['add_input'];
        $html .= '</div>';
        
        $html .= '<div class="inputs five columns omega">';
        $html .= $components['description'];
        $html .= $components['comment'];
        $html .= $components['inputs'];
        $html .= '</div>'; // Close 'inputs' div
        
        $html .= $divWrap ? '</div>' : ''; // Close 'field' div
        
        return $html;        
    }    
    
    protected function _getMultiComponent()
    {
        $inputNameStem = "MultiElements[" . $this->_element->id . "]";
        $html = '';
        // switch around the elements input type: select, multiselect, checkbox, radio
        $options = $this->_element->getOptions();
        $values = $this->_getValuesForMulti(); //record is a profile, so dig up the Multi values for that profile
        
        //need to reproduce the inputNameStem that comes from the dodged view->inputElement code
        switch($this->_element->type) {
            case 'radio':
                $html .= $this->view->formRadio($inputNameStem, $values, null, $options);
                break;
                
            case 'checkbox':
                $html .= $this->view->formMultiCheckbox($inputNameStem, $values, null, $options);
                break;
                
            case 'multiselect':
                $html .= $this->view->formSelect($inputNameStem, $values, array('multiple'=>true), $options);
                break;
                
            case 'select':
                $html .= $this->view->formSelect($inputNameStem, $values, null, $options);
                break;
            default:
        }
        return $html;
    }   

    protected function _getValuesForMulti()
    {
        return $this->_record->getValuesForMulti($this->_element);
        
        
    }
    
    protected function _getLabelComponent()
    {
        $requiredElements = $this->_record->getProfileType()->required_element_ids;
        $requiredMultiElements = $this->_record->getProfileType()->required_multielement_ids; 
        
        if(in_array($this->_element->id, $requiredElements) || in_array($this->_element->id, $requiredMultiElements)) {
            $required = " (Required)";
        } else {
            $required = '';
        }
        
        
        
        return '<label>' . __($this->_getFieldLabel()) . $required . '</label>';
    }    
    
}