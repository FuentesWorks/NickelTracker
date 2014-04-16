<?php

namespace FuentesWorks\NickelTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use FuentesWorks\NickelTrackerBundle\Entity\TransactionLog;
use FuentesWorks\NickelTrackerBundle\Entity\TransferLog;
use FuentesWorks\NickelTrackerBundle\Entity\TransactionInterface;

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
    protected $balance = 0;

    /**
     * D: debit, C: credit, S: savings, M: cash
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
        $this->transactionLogs = new ArrayCollection();
        $this->sourceTransferLogs = new ArrayCollection();
        $this->destinationTransferLogs = new ArrayCollection();
    }

    /**
     * Add transactionLogs
     *
     * @param TransactionLog $transactionLogs
     * @return Account
     */
    public function addTransactionLog(TransactionLog $transactionLogs)
    {
        $this->transactionLogs[] = $transactionLogs;

        return $this;
    }

    /**
     * Remove transactionLogs
     *
     * @param TransactionLog $transactionLogs
     * @return Account
     */
    public function removeTransactionLog(TransactionLog $transactionLogs)
    {
        $this->transactionLogs->removeElement($transactionLogs);

        return $this;
    }

    /**
     * Get transactionLogs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTransactionLogs()
    {
        return $this->transactionLogs;
    }

    /**
     * Add sourceTransferLogs
     *
     * @param TransferLog $sourceTransferLogs
     * @return Account
     */
    public function addSourceTransferLogs(TransferLog $sourceTransferLogs)
    {
        $this->sourceTransferLogs[] = $sourceTransferLogs;

        return $this;
    }

    /**
     * Remove sourceTransferLogs
     *
     * @param TransferLog $sourceTransferLogs
     * @return Account
     */
    public function removeSourceTransferLogs(TransferLog $sourceTransferLogs)
    {
        $this->sourceTransferLogs->removeElement($sourceTransferLogs);

        return $this;
    }

    /**
     * Get sourceTransferLogs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSourceTransferLogs()
    {
        return $this->sourceTransferLogs;
    }

    /**
     * Add destinationTransferLogs
     *
     * @param TransferLog $destinationTransferLogs
     * @return Account
     */
    public function addDestinationTransferLogs(TransferLog $destinationTransferLogs)
    {
        $this->destinationTransferLogs[] = $destinationTransferLogs;

        return $this;
    }

    /**
     * Remove destinationTransferLogs
     *
     * @param TransferLog $destinationTransferLogs
     * @return Account
     */
    public function removeDestinationTransferLogs(TransferLog $destinationTransferLogs)
    {
        $this->destinationTransferLogs->removeElement($destinationTransferLogs);

        return $this;
    }

    /**
     * Get destinationTransferLogs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDestinationTransferLogs()
    {
        return $this->destinationTransferLogs;
    }


    #########################
    ##   SPECIAL METHODS   ##
    #########################

    public function updateBalance(TransactionInterface $trans)
    {
        if($trans->getType() == 'T')
        {
            // Transfer, check the direction
            /* @var TransferLog $trans */
            if($trans->getSourceId()->getAccountId() == $this->accountId)
            {
                // This account is the source, therefore it's negative
                $this->balance -= $trans->getAmount();
            } elseif($trans->getDestinationId()->getAccountId() == $this->accountId) {
                // This account is the destination, therefore it's positive
                $this->balance += $trans->getAmount();
            } else {
                throw new \RuntimeException('TransferLog does not affect account: ' . $this->accountId);
            }
        } else {
            // Transaction, check type
            /* @var TransactionLog $trans */
            if($trans->getType() == 'I')
            {
                // Income
                $this->balance += $trans->getAmount();
            } elseif($trans->getType() == 'E') {
                // Expense
                $this->balance -= $trans->getAmount();
            } else {
                // Unrecognized type
                throw new \RuntimeException('Invalid TransactionLog type: ' . $trans->getType());
            }
        }
    }

    public function revertBalance(TransactionInterface $trans)
    {
        if($trans->getType() == 'T')
        {
            // Transfer, check the direction
            /* @var TransferLog $trans */
            if($trans->getSourceId()->getAccountId() == $this->accountId)
            {
                // This account is the source, therefore it's negative
                $this->balance += $trans->getAmount();
            } elseif($trans->getDestinationId()->getAccountId() == $this->accountId) {
                // This account is the destination, therefore it's positive
                $this->balance -= $trans->getAmount();
            } else {
                throw new \RuntimeException('TransferLog does not affect account: ' . $this->accountId);
            }
        } else {
            // Transaction, check type
            /* @var TransactionLog $trans */
            if($trans->getType() == 'I')
            {
                // Income
                $this->balance -= $trans->getAmount();
            } elseif($trans->getType() == 'E') {
                // Expense
                $this->balance += $trans->getAmount();
            } else {
                // Unrecognized type
                throw new \RuntimeException('Invalid TransactionLog type: ' . $trans->getType());
            }
        }
    }

    /**
     * Get the human readable account type
     * @return string
     */
    public function getTypeName()
    {
        switch($this->type)
        {
            case 'D':
                $name = 'debit';
                break;
            case 'C':
                $name = 'credit';
                break;
            case 'S':
                $name = 'savings';
                break;
            case 'M':
                $name = 'cash';
                break;
            default:
                $name = 'error';
                break;
        }

        return $name;
    }


    #########################
    ## GETTERs AND SETTERs ##
    #########################

    /**
     * @param integer $accountId
     * @return Account
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * @param integer $balance
     * @return Account
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * @return integer
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param string $name
     * @return Account
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $type
     * @return Account
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


}