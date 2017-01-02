<?php

/**
 * Contains a class describing a batch recipient delivery report.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms\Api;

/**
 * A delivery report for an individual batch recipient.
 */
class BatchRecipientDeliveryReport
{

    /**
     * @var string the batch identifier
     */
    private $_batchId;

    /**
     * @var string the recipient
     */
    private $_recipient;

    /**
     * @var int the delivery code
     */
    private $_code;

    /**
     * @var DeliveryStatus the delivery status
     */
    private $_status;

    /**
     * @var string|null the status message
     */
    private $_statusMessage;

    /**
     * @var string|null the recipient's operator
     */
    private $_operator;

    /**
     * @var \DateTime the time at delivery
     */
    private $_statusAt;

    /**
     * @var \DateTime|null the time of delivery as reported by operator
     */
    private $_operatorStatusAt;

    /**
     * Get the batch identifier.
     *
     * @return string the batch identifier
     */
    public function getBatchId()
    {
        return $this->_batchId;
    }

    /**
     * Set the batch identifier.
     *
     * @param string $batchId the batch identifier
     *
     * @return void
     */
    public function setBatchId($batchId)
    {
        $this->_batchId = $batchId;
    }

    /**
     * Get the batch recipient address.
     *
     * @return string recipient MSISDN
     */
    public function getRecipient()
    {
        return $this->_recipient;
    }

    /**
     * Set the batch recipient address.
     *
     * @param string $recipient recipient MSISDN
     *
     * @return void
     */
    public function setRecipient($recipient)
    {
        $this->_recipient = $recipient;
    }

    /**
     * Get the delivery code.
     *
     * @return int delivery code
     */
    public function getCode()
    {
        return $this->_code;
    }

    /**
     * Set the delivery code.
     *
     * @param int $code delivery code
     *
     * @return void
     */
    public function setCode($code)
    {
        $this->_code = $code;
    }

    /**
     * Get the delivery status.
     *
     * @return string delivery status
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * Set the delivery status.
     *
     * @param string $status delivery status
     *
     * @return void
     */
    public function setStatus($status)
    {
        $this->_status = $status;
    }

    /**
     * Get textual delivery status message, if available.
     *
     * @return string|null the status message
     */
    public function getStatusMessage()
    {
        return $this->_statusMessage;
    }

    /**
     * Set textual delivery status message.
     *
     * @param string|null $statusMessage the status message
     *
     * @return void
     */
    public function setStatusMessage($statusMessage)
    {
        $this->_statusMessage = $statusMessage;
    }

    /**
     * Get the recipient operator MCCMNC, if available.
     *
     * @return string|null the recipient's operator
     */
    public function getOperator()
    {
        return $this->_operator;
    }

    /**
     * Set the recipient operator MCCMNC
     *
     * @param string|null $operator the recipient's operator
     *
     * @return void
     */
    public function setOperator($operator)
    {
        $this->_operator = $operator;
    }

    /**
     * Get time when the delivery reached its final state.
     *
     * @return \DateTime the time at final state
     */
    public function getStatusAt()
    {
        return $this->_statusAt;
    }

    /**
     * Set time when the delivery reached its final state.
     *
     * @param \DateTime $statusAt the time at final state
     *
     * @return void
     */
    public function setStatusAt($statusAt)
    {
        $this->_statusAt = $statusAt;
    }

    /**
     * Get time when the delivery reached final state as reported by
     * operator, if available.
     *
     * @return \DateTime|null the time at operator final state
     */
    public function getOperatorStatusAt()
    {
        return $this->_operatorStatusAt;
    }

    /**
     * Set time when the delivery reached final state as reported by
     * operator.
     *
     * @param \DateTime|null $operatorStatusAt the time at operator
     *                                         final state
     *
     * @return void
     */
    public function setOperatorStatusAt($operatorStatusAt)
    {
        $this->_operatorStatusAt = $operatorStatusAt;
    }

}

?>