<?php

namespace FuentesWorks\NickelTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use FuentesWorks\NickelTrackerBundle\TransactionInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="TransactionLogs")
 */
class TransactionLog implements TransactionInterface
{
    protected $transactionLogId;

    protected $type;

    protected $amount;

    protected $description;

    protected $date;

    protected $accountId;

    protected $categoryId;


    #########################
    ##   SPECIAL METHODS   ##
    #########################

    public function getAccountName()
    {
        return $this->getAccountId()->getName();
    }

    public function getType()
    {
        return $this->type;
    }


    #########################
    ## GETTERs AND SETTERs ##
    #########################

    /**
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="payPalLogs")
     * @ORM\JoinColumn(name="clientId", referencedColumnName="clientId")
     */
    protected $clientId;

    /**
     * Set clientId
     *
     * @param Client $clientId
     * @return PayPalLog
     */
    public function setClientId(Client $clientId = null)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Get clientId
     *
     * @return Client
     */
    public function getClientId()
    {
        return $this->clientId;
    }

}