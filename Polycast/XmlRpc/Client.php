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
 * @subpackage Client
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Client.php 17759 2009-08-22 21:26:21Z lars $
 */


/**
 * For handling the HTTP connection to the XML-RPC service
 * @see Zend_Http_Client
 */
require_once 'Zend/Http/Client.php';

/**
 * Enables object chaining for calling namespaced XML-RPC methods.
 * @see Polycast_XmlRpc_Client_ServerProxy
 */
require_once 'Polycast/XmlRpc/Client/ServerProxy.php';

/**
 * Introspects remote servers using the XML-RPC de facto system.* methods
 * @see Polycast_XmlRpc_Client_ServerIntrospection
 */
require_once 'Polycast/XmlRpc/Client/ServerIntrospection.php';

/**
 * Represent a native XML-RPC value, used both in sending parameters
 * to methods and as the parameters retrieve from method calls
 * @see Polycast_XmlRpc_Value
 */
require_once 'Polycast/XmlRpc/Value.php';

/**
 * XML-RPC Request
 * @see Polycast_XmlRpc_Request
 */
require_once 'Polycast/XmlRpc/Request.php';

/**
 * XML-RPC Response
 * @see Polycast_XmlRpc_Response
 */
require_once 'Polycast/XmlRpc/Response.php';

/**
 * XML-RPC Fault
 * @see Polycast_XmlRpc_Fault
 */
require_once 'Polycast/XmlRpc/Fault.php';


/**
 * An XML-RPC client implementation
 *
 * @category   Zend
 * @package    Zend_XmlRpc
 * @subpackage Client
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Polycast_XmlRpc_Client
{
    /**
     * Full address of the XML-RPC service
     * @var string
     * @example http://time.xmlrpc.com/RPC2
     */
    protected $_serverAddress;

    /**
     * HTTP Client to use for requests
     * @var Zend_Http_Client
     */
    protected $_httpClient = null;

    /**
     * Introspection object
     * @var Zend_Http_Client_Introspector
     */
    protected $_introspector = null;

    /**
     * Request of the last method call
     * @var Polycast_XmlRpc_Request
     */
    protected $_lastRequest = null;

    /**
     * Response received from the last method call
     * @var Polycast_XmlRpc_Response
     */
    protected $_lastResponse = null;

    /**
     * Proxy object for more convenient method calls
     * @var array of Polycast_XmlRpc_Client_ServerProxy
     */
    protected $_proxyCache = array();

    /**
     * Flag for skipping system lookup
     * @var bool
     */
    protected $_skipSystemLookup = false;

    /**
     * Create a new XML-RPC client to a remote server
     *
     * @param  string $server      Full address of the XML-RPC service
     *                             (e.g. http://time.xmlrpc.com/RPC2)
     * @param  Zend_Http_Client $httpClient HTTP Client to use for requests
     * @return void
     */
    public function __construct($server, Zend_Http_Client $httpClient = null)
    {
        if ($httpClient === null) {
            $this->_httpClient = new Zend_Http_Client();
        } else {
            $this->_httpClient = $httpClient;
        }

        $this->_introspector  = new Polycast_XmlRpc_Client_ServerIntrospection($this);
        $this->_serverAddress = $server;
    }


    /**
     * Sets the HTTP client object to use for connecting the XML-RPC server.
     *
     * @param  Zend_Http_Client $httpClient
     * @return Zend_Http_Client
     */
    public function setHttpClient(Zend_Http_Client $httpClient)
    {
        return $this->_httpClient = $httpClient;
    }


    /**
     * Gets the HTTP client object.
     *
     * @return Zend_Http_Client
     */
    public function getHttpClient()
    {
        return $this->_httpClient;
    }


    /**
     * Sets the object used to introspect remote servers
     *
     * @param  Polycast_XmlRpc_Client_ServerIntrospection
     * @return Polycast_XmlRpc_Client_ServerIntrospection
     */
    public function setIntrospector(Polycast_XmlRpc_Client_ServerIntrospection $introspector)
    {
        return $this->_introspector = $introspector;
    }


    /**
     * Gets the introspection object.
     *
     * @return Polycast_XmlRpc_Client_ServerIntrospection
     */
    public function getIntrospector()
    {
        return $this->_introspector;
    }


   /**
     * The request of the last method call
     *
     * @return Polycast_XmlRpc_Request
     */
    public function getLastRequest()
    {
        return $this->_lastRequest;
    }


    /**
     * The response received from the last method call
     *
     * @return Polycast_XmlRpc_Response
     */
    public function getLastResponse()
    {
        return $this->_lastResponse;
    }


    /**
     * Returns a proxy object for more convenient method calls
     *
     * @param $namespace  Namespace to proxy or empty string for none
     * @return Polycast_XmlRpc_Client_ServerProxy
     */
    public function getProxy($namespace = '')
    {
        if (empty($this->_proxyCache[$namespace])) {
            $proxy = new Polycast_XmlRpc_Client_ServerProxy($this, $namespace);
            $this->_proxyCache[$namespace] = $proxy;
        }
        return $this->_proxyCache[$namespace];
    }

    /**
     * Set skip system lookup flag
     *
     * @param  bool $flag
     * @return Polycast_XmlRpc_Client
     */
    public function setSkipSystemLookup($flag = true)
    {
        $this->_skipSystemLookup = (bool) $flag;
        return $this;
    }

    /**
     * Skip system lookup when determining if parameter should be array or struct?
     *
     * @return bool
     */
    public function skipSystemLookup()
    {
        return $this->_skipSystemLookup;
    }

    /**
     * Perform an XML-RPC request and return a response.
     *
     * @param Polycast_XmlRpc_Request $request
     * @param null|Polycast_XmlRpc_Response $response
     * @return void
     * @throws Polycast_XmlRpc_Client_HttpException
     */
    public function doRequest($request, $response = null)
    {
        $this->_lastRequest = $request;

        iconv_set_encoding('input_encoding', 'UTF-8');
        iconv_set_encoding('output_encoding', 'UTF-8');
        iconv_set_encoding('internal_encoding', 'UTF-8');

        $http = $this->getHttpClient();
        if($http->getUri() === null) {
            $http->setUri($this->_serverAddress);
        }

        $http->setHeaders(array(
            'Content-Type: text/xml; charset=utf-8',
            'Accept: text/xml',
        ));

        if ($http->getHeader('user-agent') === null) {
            $http->setHeaders(array('User-Agent: Polycast_XmlRpc_Client'));
        }

        $xml = $this->_lastRequest->__toString();
        $http->setRawData($xml);
        $httpResponse = $http->request(Zend_Http_Client::POST);

        if (! $httpResponse->isSuccessful()) {
            /**
             * Exception thrown when an HTTP error occurs
             * @see Polycast_XmlRpc_Client_HttpException
             */
            require_once 'Polycast/XmlRpc/Client/HttpException.php';
            throw new Polycast_XmlRpc_Client_HttpException(
                                    $httpResponse->getMessage(),
                                    $httpResponse->getStatus());
        }

        if ($response === null) {
            $response = new Polycast_XmlRpc_Response();
        }
        $this->_lastResponse = $response;
        $this->_lastResponse->loadXml($httpResponse->getBody());
    }

    /**
     * Send an XML-RPC request to the service (for a specific method)
     *
     * @param  string $method Name of the method we want to call
     * @param  array $params Array of parameters for the method
     * @return mixed
     * @throws Polycast_XmlRpc_Client_FaultException
     */
    public function call($method, $params=array())
    {
        if (!$this->skipSystemLookup() && ('system.' != substr($method, 0, 7))) {
            // Ensure empty array/struct params are cast correctly
            // If system.* methods are not available, bypass. (ZF-2978)
            $success = true;
            try {
                $signatures = $this->getIntrospector()->getMethodSignature($method);
            } catch (Polycast_XmlRpc_Exception $e) {
                $success = false;
            }
            if ($success) {
                $validTypes = array(
                    Polycast_XmlRpc_Value::XMLRPC_TYPE_ARRAY,
                    Polycast_XmlRpc_Value::XMLRPC_TYPE_BASE64,
                    Polycast_XmlRpc_Value::XMLRPC_TYPE_BOOLEAN,
                    Polycast_XmlRpc_Value::XMLRPC_TYPE_DATETIME,
                    Polycast_XmlRpc_Value::XMLRPC_TYPE_DOUBLE,
                    Polycast_XmlRpc_Value::XMLRPC_TYPE_I4,
                    Polycast_XmlRpc_Value::XMLRPC_TYPE_INTEGER,
                    Polycast_XmlRpc_Value::XMLRPC_TYPE_NIL,
                    Polycast_XmlRpc_Value::XMLRPC_TYPE_STRING,
                    Polycast_XmlRpc_Value::XMLRPC_TYPE_STRUCT,
                );
                $params = (array)$params;
                foreach ($params as $key => $param) {
                    $type = Polycast_XmlRpc_Value::AUTO_DETECT_TYPE;
                    foreach ($signatures as $signature) {
                        if (!is_array($signature)) {
                            continue;
                        }
                        if (isset($signature['parameters'][$key])) {
                            $type = $signature['parameters'][$key];
                            $type = in_array($type, $validTypes) ? $type : Polycast_XmlRpc_Value::AUTO_DETECT_TYPE;
                        }
                    }
                    $params[$key] = Polycast_XmlRpc_Value::getXmlRpcValue($param, $type);
                }
            }
        }

        $request = $this->_createRequest($method, $params);

        $this->doRequest($request);

        if ($this->_lastResponse->isFault()) {
            $fault = $this->_lastResponse->getFault();
            /**
             * Exception thrown when an XML-RPC fault is returned
             * @see Polycast_XmlRpc_Client_FaultException
             */
            require_once 'Polycast/XmlRpc/Client/FaultException.php';
            throw new Polycast_XmlRpc_Client_FaultException($fault->getMessage(),
                                                        $fault->getCode());
        }

        return $this->_lastResponse->getReturnValue();
    }

    /**
     * Create request object
     *
     * @return Polycast_XmlRpc_Request
     */
    protected function _createRequest($method, $params)
    {
        return new Polycast_XmlRpc_Request($method, $params);
    }
}
