<?php

namespace FuentesWorks\NickelTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use FuentesWorks\NickelTrackerBundle\Entity\Transaction;

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
     * @ORM\OneToMany(targetEntity="Transaction", mappedBy="sourceAccountId")
     */
    protected $sourceTransactions;

    /**
     * @ORM\OneToMany(targetEntity="Transaction", mappedBy="destinationAccountId")
     */
    protected $destinationTransactions;

    public function __construct() {
        $this->sourceTransactions = new ArrayCollection();
        $this->destinationTransactions = new ArrayCollection();
    }

    /**
     * Add sourceTransactions
     *
     * @param Transaction $sourceTransactions
     * @return Account
     */
    public function addSourceTransaction(Transaction $sourceTransactions)
    {
        $this->sourceTransactions[] = $sourceTransactions;

        return $this;
    }

    /**
     * Remove sourceTransactions
     *
     * @param Transaction $sourceTransactions
     * @return Account
     */
    public function removeSourceTransaction(Transaction $sourceTransactions)
    {
        $this->sourceTransactions->removeElement($sourceTransactions);

        return $this;
    }

    /**
     * Get sourceTransactions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSourceTransactions()
    {
        return $this->sourceTransactions;
    }

    /**
     * Add destinationTransactions
     *
     * @param Transaction $destinationTransactions
     * @return Account
     */
    public function addDestinationTransaction(Transaction $destinationTransactions)
    {
        $this->destinationTransactions[] = $destinationTransactions;

        return $this;
    }

    /**
     * Remove sourceTransactions
     *
     * @param Transaction $destinationTransactions
     * @return Account
     */
    public function removeDestinationTransaction(Transaction $destinationTransactions)
    {
        $this->destinationTransactions->removeElement($destinationTransactions);

        return $this;
    }

    /**
     * Get destinationTransactions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDestinationTransactions()
    {
        return $this->destinationTransactions;
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