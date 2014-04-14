<?php

namespace FuentesWorks\NickelTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use FuentesWorks\NickelTrackerBundle\TransactionInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="TransferLogs")
 */
class TransferLog implements TransactionInterface
{
    protected $transferLogId;

    protected $amount;

    protected $description;

    protected $date;

    protected $sourceId;

    protected $destinationId;


    #########################
    ##   SPECIAL METHODS   ##
    #########################

    public function getAccountName()
    {
        $source = $this->getSouceId()->getName();
        $destination = $this->getDestinationId()->getName();

        return $source . ' => ' . $destination;
    }

    public function getType()
    {
        return 'T';
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