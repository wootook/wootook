<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.txt
 * @see http://wootook.org/
 *
 * Copyright (c) 2011-Present, Grégory PLANCHAT <g.planchat@gmail.com>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing Wootook.
 *
 */

namespace Wootook\Core\Form;

use Wootook\Core\Exception as CoreException,
    Wootook\Core\Model,
    Wootook\Core\Form\Element,
    Wootook\Core\Mvc\Controller\Response,
    Wootook\Core\Mvc\Controller\Request,
    Wootook\Core\Profiler;


/**
 *
 * Enter description here ...
 * @author Greg
 *
 */
class Form
{
    /**
     * Form elements container
     *
     * @var array
     */
    protected $_elements = array();

    /**
     * current MVC request management instance
     *
     * @var Request\Http
     */
    protected $_request = null;

    /**
     * Form element instance loader
     *
     * @var ElementLoader
     */
    protected $_elementLoader = null;

    /**
     * Form field validator instance loader
     *
     * @var ValidatorLoader
     */
    protected $_validatorLoader = null;

    /**
     * Session storage instance, used for messaging purposes
     *
     * @var Model\Session
     */
    protected $_session = null;

    /**
     * @param Model\Session $session
     * @param array $elements
     */
    public function __construct(Model\Session $session, Array $elements = array())
    {
        $this->_session = $session;

        $this->_elementLoader = new ElementLoader($this, array(
            'Wootook\\Core\\Form\\Element' => 'Wootook/Core/Form/Element'
            ));

        $this->_validatorLoader = new ValidatorLoader($this, array(
            'Wootook\\Core\\Form\\Validator' => 'Wootook/Core/Form/Validator'
            ));

        $this->addElement('__formkey', 'text', array('form_key' => 'formKey'));

        foreach ($elements as $elementName => $elementConfig) {
            if (is_string($elementConfig)) {
                $this->addElement($elementName, $elementConfig);
            } else if ($elementConfig instanceof Element\BaseElement) {
                $this->addElement($elementName, $elementConfig);
            } else {
                if (isset($elementConfig['validators'])) {
                    $this->addElement($elementName, $elementConfig['type'], $elementConfig['validators']);
                } else {
                    $this->addElement($elementName, $elementConfig['type']);
                }
            }
        }
    }

    /**
     * Validated the form field values
     *
     * @return bool
     */
    public function validate()
    {
        foreach ($this->_elements as $element) {
            if (!$element->validate()) {
                return false;
            }
        }
        return true;
    }

    /**
     * Add a field element to the current form
     *
     * @param string $name
     * @param string|Element\BaseElement $type
     * @return Form
     */
    public function addElement($name, $type = 'text', Array $validators = array())
    {
        if ($type instanceof Element\BaseElement) {
            $this->_elements[$name] = $type;
            $this->_elements[$name]->setForm($this);
        } else {
            $element = $this->_elementLoader->load($type);

            if ($element === null) {
                    Profiler\ErrorProfiler::getSingleton()
                        ->addException(new CoreException\RuntimeException(sprintf('Element %1$s (type: %2$s) could not be created.', $name, $type)));
                return $this;
            }
            $element->setForm($this);
            $this->_elements[$name] = $element;
        }
        $this->_elements[$name]->setName($name);

        foreach ($validators as $validatorName => $validatorType) {
            if ($validatorType instanceof Element\BaseElement) {
                $this->_elements[$name]->addValidator($validatorType, $validatorName);
            } else {
                $validator = $this->_validatorLoader->load($validatorType);

                if ($validator === null) {
                    Profiler\ErrorProfiler::getSingleton()
                        ->addException(new CoreException\RuntimeException(sprintf('Validator %1$s (type: %2$s) could not be created.', $validatorName, $validatorType)));
                    return $this;
                }
                $this->_elements[$name]->addValidator($validator, $validatorName);
            }
        }

        return $this;
    }

    /**
     * Returns the form field element instance
     *
     * @param string $name
     * @return Element\BaseElement
     */
    public function getElement($name)
    {
        if (!isset($this->_elements[$name])) {
            return null;
        }

        return $this->_elements[$name];
    }

    /**
     * Get the currently used session instance
     *
     * @return Model\Session
     */
    public function getSession()
    {
        return $this->_session;
    }

    /**
     * Use a new MVC request handler instance
     *
     * @param Request\Http $request
     * @return Form
     */
    public function setRequest(Request\Http $request)
    {
        $this->_request = $request;

        return $this;
    }

    /**
     * Get the currently used MVC request handler
     *
     * @return Request\Http
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * Return the validated and filtered form data
     *
     * @return array
     */
    public function getData()
    {
        $request = $this->getRequest();
        if ($request === null) {
            return array();
        }
        if ($request->isPost()) {
            return $request->getAllPostData();
        }
        return $request->getAllQueryData();
    }

    /**
     * Populates form fields using the data provided.
     *
     * @param array $datas
     */
    public function populate(Array $datas = array())
    {
        $request = $this->getRequest();

        foreach ($this->_elements as $elementName => $element) {
            if (isset($datas[$elementName])) {
                $element->populate($datas[$elementName]);
            } else if ($request->isPost()) {
                $element->populate($request->getPost($elementName));
            } else {
                $element->populate($request->getQuery($elementName));
            }
        }
    }
}
