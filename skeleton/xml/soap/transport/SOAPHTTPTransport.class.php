<?php
/* Diese Klasse ist Teil des XP-Frameworks
 *
 * $Id$
 */

  uses(
    'xml.soap.transport.SOAPTransport', 
    'xml.soap.SOAPFaultException', 
    'peer.http.HttpConnection'
  );
  
  /**
   * Kapselt den Transport von SOAP-Nachrichten �ber HTTP
   *
   * @see xml.soap.SOAPClient
   */
  class SoapHttpTransport extends SoapTransport {
    var
      $_conn,
      $_action;
      
    /**
     * Constructor
     *
     * @access  public
     * @param   string url Die URL
     */  
    function __construct($url) {
      $this->_conn= &new HttpConnection($url);
      parent::__construct();
    }
    
    /**
     * Destructor
     *
     * @access  public
     */
    function __destruct() {
      $this->_conn->__destruct();
      parent::__destruct();
    }

    /**
     * Die SOAP-Message absenden
     *
     * @access  public
     * @param   xml.soap.SOAPMessage message Die zu verschickende Nachricht
     * @throws  IllegalArgumentException, wenn message keine SOAPMessage ist
     */
    function &send(&$message) {
      if (!is_a($message, 'SOAPMessage')) return throw(new IllegalArgumentException(
        'parameter "message" must be a xml.soap.SOAPMessage'
      ));

      // Action
      $this->action= $message->action;

      // Post XML
      return $this->_conn->post(
        new RequestData($message->getSource(0)),
        array(
          new Header('SOAPAction', '"'.$message->action.'#'.$message->method.'"'),
          new Header('Content-Type', 'text/xml; charset='.$message->getEncoding()),
        )
      );
   }
   
    /**
     * Die SOAP-Antwort auswerten
     *
     * @access  public
     * @return  xml.soap.SOAPMessage Die Antwort
     */
   function retreive(&$response) {
   
      // R�ckgabe auswerten
      $answer= &new SOAPMessage();
      
      // Auf das Encoding achten!
      if (NULL !== ($content_type= $response->getHeader('Content-Type'))) {
        @list($type, $charset)= explode('; charset=', $content_type);
        if (!empty($charset)) $answer->setEncoding($charset);
      }
      
      $answer->action= $this->action;
      try(); {
        $xml= '';
        while ($buf= $response->readData()) $xml.= $buf;
        $answer->fromString($xml);
      } if (catch('Exception', $e)) {
        return throw($e);
      }
      
      // Nach Fault checken
      if (200 != $response->getStatusCode()) {
        if (NULL !== ($fault= $answer->getFault())) {
          return throw(new SOAPFaultException($fault));
        } else {
          return throw(new Exception('Unexpected return code: '.$response->getStatusCode()));
        }
      }
      
      return $answer;
   }
 
 }
?>
