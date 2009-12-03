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
 * @package    Zend_XmlRpc
 * @subpackage Value
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Scalar.php 16208 2009-06-21 19:19:26Z thomas $
 */


/**
 * Polycast_XmlRpc_Value
 */
require_once 'Polycast/XmlRpc/Value.php';


/**
 * @category   Zend
 * @package    Zend_XmlRpc
 * @subpackage Value
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class Polycast_XmlRpc_Value_Scalar extends Polycast_XmlRpc_Value
{

    /**
     * Return the XML code that represent a scalar native MXL-RPC value
     *
     * @return string
     */
    public function saveXML()
    {
        if (!$this->_as_xml) {   // The XML code was not calculated yet
//            $dom   = new DOMDocument('1.0');
//            $value = $dom->appendChild($dom->createElement('value'));
//            $type  = $value->appendChild($dom->createElement($this->_type));
//            $type->appendChild($dom->createTextNode($this->getValue()));
//
//            $this->_as_dom = $value;
//            $this->_as_xml = $this->_stripXmlDeclaration($dom);
            
            $val = $this->_escapeXmlEntities($this->getValue());
            $this->_as_xml = '<value><' . $this->_type . '>' . $val 
                . '</' . $this->_type . '></value>';
        }

        return $this->_as_xml;
    }
}
