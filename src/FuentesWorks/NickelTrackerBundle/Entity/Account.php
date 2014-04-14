<?php

namespace FuentesWorks\NickelTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="Accounts")
 */
class Account
{
    #########################
    ##      PROPERTIES     ##
    #########################

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", unique=TRUE)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $accountId;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $balance;

    /**
     * @ORM\Column(type="string", length=1, nullable=false)
     */
    protected $type;


    #########################
    ## OBJECT RELATIONSHIP ##
    #########################

    /**
     * @ORM\OneToMany(targetEntity="TransactionLog", mappedBy="accountId")
     */
    protected $transactionLogs;

    /**
     * @ORM\OneToMany(targetEntity="TransferLog", mappedBy="sourceId")
     */
    protected $sourceTransferLogs;

    /**
     * @ORM\OneToMany(targetEntity="TransferLog", mappedBy="destinationId")
     */
    protected $destinationTransferLogs;

    public function __construct() {
        $this->transactionLogs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sourceTransferLogs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->destinationTransferLogs = new \Doctrine\Common\Collections\ArrayCollection();
    }


    #########################
    ##   SPECIAL METHODS   ##
    #########################


    #########################
    ## GETTERs AND SETTERs ##
    #########################

}