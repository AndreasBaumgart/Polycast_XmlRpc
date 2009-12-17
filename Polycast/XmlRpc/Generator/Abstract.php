<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Polycast_XmlRpc
 * @subpackage Generator
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */  

/**
 * @var Polycast_XmlRpc_Generator_Element
 */
require_once 'Polycast/XmlRpc/Generator/Element.php';

/**
 * @var Polycast_XmlRpc_Value
 */
require_once 'Polycast/XmlRpc/Value.php';

/**
 * Abstract XML generator adapter.
 */
abstract class Polycast_XmlRpc_Generator_Abstract
{
    /**
     * XML output encoding. 
     * Default is UTF-8.
     * @var string
     */
    protected $_encoding = 'UTF-8';
    
    /**
     * Sets the XML output encoding.
     * @param string $encoding
     * @return Polycast_XmlRpc_Generator_Abstract Fluent interface
     */
    public function setEncoding($encoding)
    {
        $this->_encoding = $encoding;
        return $this;
    }
    
    /**
     * Get the XML output encoding.
     * @return string
     */
    public function getEncoding()
    {
        return $this->_encoding;
    }

    /**
     * Generate the XML fragment representing the passed element.
     * @param Polycast_XmlRpc_Generator_Element $data
     * @return string
     */
    abstract public function generateXml(Polycast_XmlRpc_Generator_Element $data);
}