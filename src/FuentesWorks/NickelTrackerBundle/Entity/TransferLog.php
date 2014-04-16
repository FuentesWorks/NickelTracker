<?php

namespace FuentesWorks\NickelTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use FuentesWorks\NickelTrackerBundle\Entity\TransactionInterface;
use FuentesWorks\NickelTrackerBundle\Entity\Account;

/**
 * @ORM\Entity
 * @ORM\Table(name="TransferLogs")
 */
class TransferLog implements TransactionInterface
{
    #########################
    ##      PROPERTIES     ##
    #########################

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", unique=TRUE)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $transferLogId;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $amount = 0;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $description;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    protected $details;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    protected $date;


    #########################
    ##   SPECIAL METHODS   ##
    #########################

    public function getAccountName()
    {
        $source = $this->getSourceId()->getName();
        $destination = $this->getDestinationId()->getName();

        return $source . ' => ' . $destination;
    }

    public function getType()
    {
        return 'T';
    }

    public function getGlobalId()
    {
        return 'T' . $this->transferLogId;
    }

    public function getCategoryId()
    {
        return false;
    }


    #########################
    ## OBJECT RELATIONSHIP ##
    #########################

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="sourceTransferLogs")
     * @ORM\JoinColumn(name="sourceId", referencedColumnName="accountId")
     */
    protected $sourceId;

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="destinationTransferLogs")
     * @ORM\JoinColumn(name="destinationId", referencedColumnName="accountId")
     */
    protected $destinationId;

    /**
     * Set sourceId
     *
     * @param Account $sourceId
     * @return TransactionLog
     */
    public function setSourceId(Account $sourceId = null)
    {
        $this->sourceId = $sourceId;

        return $this;
    }

    /**
     * Get sourceId
     *
     * @return Account
     */
    public function getSourceId()
    {
        return $this->sourceId;
    }

    /**
     * Set destinationId
     *
     * @param Account $destinationId
     * @return TransactionLog
     */
    public function setDestinationId(Account $destinationId = null)
    {
        $this->destinationId = $destinationId;

        return $this;
    }

    /**
     * Get destinationId
     *
     * @return Account
     */
    public function getDestinationId()
    {
        return $this->destinationId;
    }


    #########################
    ## GETTERs AND SETTERs ##
    #########################

    /**
     * @param integer $amount
     * @return TransferLog
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param \DateTime $date
     * @return TransferLog
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param string $description
     * @return TransferLog
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $details
     * @return TransactionLog
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param integer $transferLogId
     * @return TransferLog
     */
    public function setTransferLogId($transferLogId)
    {
        $this->transferLogId = $transferLogId;

        return $this;
    }

    /**
     * @return integer
     */
    public function getTransferLogId()
    {
        return $this->transferLogId;
    }





}