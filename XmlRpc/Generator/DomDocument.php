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
 * XML generator adapter based on DOMDocument.
 */
class Polycast_XmlRpc_Generator_DomDocument extends Polycast_XmlRpc_Generator_Abstract
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
        $dom   = new DOMDocument('1.0');
        $this->_generateXml($dom, $element);
        return $dom->saveXML();
    }

    /**
     * Builds the document recursively.
     * 
     * @param DOMDocument $dom
     * @param Polycast_XmlRpc_Generator_Element $element
     * @param DOMElement $rootNode
     * @return void
     */
    protected function _generateXml(DOMDocument $dom, 
        Polycast_XmlRpc_Generator_Element $element, DOMElement $rootNode = null)
    {
        $node = $dom->createElement($element->getName());
        if(is_null($rootNode)) {
            $dom->appendChild($node);
        } else {
            $rootNode->appendChild($node);
        }

        foreach($element->getChildren() as $child) {
        
            if($child instanceof Polycast_XmlRpc_Generator_Element) {
                /* @var $child Polycast_XmlRpc_Generator_Element */
                $this->_generateXml($dom, $child, $node);
            } 
            elseif($child instanceof Polycast_XmlRpc_Value) {
                /* @var $child Polycast_XmlRpc_Value */
                $importNode = new DOMDocument();
                $importNode->loadXML($child->saveXML());
                $node->appendChild($dom->importNode(
                    $importNode->documentElement, true
                ));
            }
            else {
                $node->appendChild(
                    $dom->createTextNode($child)
                );
            }
        }
    }
}
