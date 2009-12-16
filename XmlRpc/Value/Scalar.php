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
            $generator = $this->getGenerator();
            $element = new Polycast_XmlRpc_Generator_Element('value', array(
                    new Polycast_XmlRpc_Generator_Element($this->_type, array(
                        $this->getValue()
                    )
                ))
            );
            $this->_as_xml = $generator->generateXml($element);
            
            
//            $xml = new XmlWriter();
//            $xml->openMemory();
//            $xml->startDocument('1.0', 'UTF-8');
//            $xml->startElement('value');
//            $xml->startElement($this->_type);
//            $xml->text($this->getValue());
//            $xml->endElement(); // type
//            $xml->endElement(); // value
//            $this->_as_xml = $this->_stripXmlDeclaration($xml->flush());
        }

        return $this->_as_xml;
    }
}

