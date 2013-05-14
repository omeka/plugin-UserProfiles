<?php

/**
 * Extends ElementInput to provide a different name stem, so user profiles form can be added to other forms
 * @author patrickmj
 *
 */

class UserProfiles_View_Helper_ProfileElementInput extends Omeka_View_Helper_ElementInput
{
    
    
    /**
     * Display one form input for an Element.
     *
     * @param Element $element The Element to display the input for.
     * @param Omeka_Record_AbstractRecord $record The record to display the
     * input for.
     * @param int $index The index of this input. (starting at zero).
     * @param string $value The default value of this input.
     * @param bool $isHtml Whether this input's value is HTML.
     * @return string
     */
    public function profileElementInput(Element $element, Omeka_Record_AbstractRecord $record, $index = 0, $value = '', $isHtml = false)
    {
        $this->_element = $element;
        $this->_record = $record;
    
        $inputNameStem = "Elements[" . $this->_element->id . "][$index]";
    
        $components = array(
                'input' => $this->_getInputComponent($inputNameStem, $value),
                'form_controls' => $this->_getControlsComponent(),
                'html_checkbox' => $this->_getHtmlCheckboxComponent($inputNameStem, $isHtml),
                'html' => null
        );
    
        $filterName = array('ElementInput',
                get_class($this->_record),
                $this->_element->set_name,
                $this->_element->name);
    
        // Plugins should apply a filter to this HTML in order to display it in a certain way.
        $components = apply_filters($filterName,
                $components,
                array('input_name_stem' => $inputNameStem,
                        'value' => $value,
                        'record' => $this->_record,
                        'element' => $this->_element,
                        'index' => $index,
                        'is_html' => $isHtml));
        
        if ($components['html'] !== null) {
            return strval($components['html']);
        }
        $html = '<div class="input-block">'
        . '<div class="input">'
        . $components['input']
        . '</div>'
        . $components['form_controls']
        . $components['html_checkbox']
        . '</div>';
    
        return $html;
    }    
}