<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  /**
   * Indicates the response was unexpected
   *
   * @see      xp://peer.http.HttpUtil
   * @purpose  Exception
   */
  class UnexpectedResponseException extends XPException {
    public
      $statuscode = 0;

    /**
     * Constructor
     *
     * @param   string message
     * @param   int statuscode
     */
    public function __construct($message, $statuscode= 0) {
      parent::__construct($message);
      $this->statuscode= $statuscode;
    }

    /**
     * Set statuscode
     *
     * @param   int statuscode
     */
    public function setStatusCode($statuscode) {
      $this->statuscode= $statuscode;
    }

    /**
     * Get statuscode
     *
     * @return  int
     */
    public function getStatusCode() {
      return $this->statuscode;
    }
    
    /**
     * Return compound message of this exception.
     *
     * @return  string
     */
    public function compoundMessage() {
      return sprintf(
        'Exception %s (statuscode %d: %s)',
        $this->getClassName(),
        $this->statuscode,
        $this->message
      );
    }
  }
?>
