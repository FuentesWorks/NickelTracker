<?php

namespace FuentesWorks\NickelTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use FuentesWorks\NickelTrackerBundle\Entity\TransactionInterface;
use FuentesWorks\NickelTrackerBundle\Entity\Account;
use FuentesWorks\NickelTrackerBundle\Entity\Category;

/**
 * @ORM\Entity
 * @ORM\Table(name="TransactionLogs")
 */
class TransactionLog implements TransactionInterface
{
    #########################
    ##      PROPERTIES     ##
    #########################

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", unique=TRUE)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $transactionLogId;

    /**
     * I: income, E: expense
     * @ORM\Column(type="string", length=1, nullable=false)
     */
    protected $type;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $amount = 0;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $description;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    protected $date;


    #########################
    ##   SPECIAL METHODS   ##
    #########################

    public function getAccountName()
    {
        return $this->getAccountId()->getName();
    }

    public function getGlobalId()
    {
        return $this->type . $this->transactionLogId;
    }


    #########################
    ## OBJECT RELATIONSHIP ##
    #########################

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="transactionLogs")
     * @ORM\JoinColumn(name="accountId", referencedColumnName="accountId")
     */
    protected $accountId;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="transactionLogs")
     * @ORM\JoinColumn(name="categoryId", referencedColumnName="categoryId")
     */
    protected $categoryId;

    /**
     * Set accountId
     *
     * @param Account $accountId
     * @return TransactionLog
     */
    public function setAccountId(Account $accountId = null)
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * Get accountId
     *
     * @return Account
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * Set categoryId
     *
     * @param Category $categoryId
     * @return TransactionLog
     */
    public function setCategoryId(Category $categoryId = null)
    {
        $this->categoryId= $categoryId;

        return $this;
    }

    /**
     * Get categoryId
     *
     * @return Category
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }


    #########################
    ## GETTERs AND SETTERs ##
    #########################

    /**
     * @param integer $amount
     * @return TransactionLog
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
     * @param string $type
     * @return TransactionLog
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

    /**
     * @param \DateTime $date
     * @return TransactionLog
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
     * @return TransactionLog
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
     * @param integer $transactionLogId
     * @return TransactionLog
     */
    public function setTransactionLogId($transactionLogId)
    {
        $this->transactionLogId = $transactionLogId;

        return $this;
    }

    /**
     * @return integer
     */
    public function getTransactionLogId()
    {
        return $this->transactionLogId;
    }


}