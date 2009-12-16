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
 * @var Polycast_XmlRpc_Generator_Abstract
 */
require_once 'Polycast/XmlRpc/Generator/Abstract.php';

/**
 * XML generator adapter based on XMLWriter.
 */
class Polycast_XmlRpc_Generator_XmlWriter extends Polycast_XmlRpc_Generator_Abstract
{
    /**
     * Return the XML-RPC XML code representing an 
     * Polycast_XmlRpc_Generator_Element.
     * 
     * @param Polycast_XmlRpc_Generator_Element $element
     * @return string
     */
    public function generateXml(Polycast_XmlRpc_Generator_Element $element)
    {
        $writer = new XMLWriter();
        $writer->openMemory();
        $writer->startDocument('1.0', $this->getEncoding());

        $this->_generateXml($writer, $element);
        return $this->_stripXmlDeclaration($writer->flush());    
    }

    /**
     * Recursively walk through the element tree and write elements to the
     * XMLWriter instance.
     * 
     * @param XMLWriter $writer
     * @param Polycast_XmlRpc_Generator_Element $element
     * @return void
     */
    protected function _generateXml(XMLWriter $writer, 
        Polycast_XmlRpc_Generator_Element $element)
    {
        $writer->startElement($element->getName());

        foreach($element->getChildren() as $child) {
            
            if($child instanceof Polycast_XmlRpc_Generator_Element) {
                /* @var $child Polycast_XmlRpc_Generator_Element */
                $this->_generateXml($writer, $child);
            } 
            elseif($child instanceof Polycast_XmlRpc_Value) {
                /* @var $child Polycast_XmlRpc_Value */
                $writer->writeRaw($child->saveXml());
            }
            else {
                $writer->text($child);
            }
        }
        $writer->endElement();
    }
}