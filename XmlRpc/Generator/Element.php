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
 * XML element definition for Polycast_XmlRpc_Generator_Abstract.
 */
class Polycast_XmlRpc_Generator_Element
{
    /**
     * Element name.
     * @var string
     */
    protected $_name = null;
    
    /**
     * Content of the element. This can be Polycast_XmlRpc_Generator_Element, 
     * Polycast_XmlRpc_Value or string types.
     * @var array
     */
    protected $_children = array();
    
    /**
     * Constructor.
     * @param string $name
     * @param string | Polycast_XmlRpc_Value $value
     * @param array | Polycast_XmlRpc_Generator_Element $children
     */
    public function __construct($name, array $children = array()) 
    {
        $this->_name = $name;
        $this->_children = $children;
    }
    
    /**
     * Get the name
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }
    
    /**
     * Get content of this element.
     * @return array
     */
    public function getChildren()
    {
        return $this->_children;
    }
}