<?php

namespace FuentesWorks\NickelTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use FuentesWorks\NickelTrackerBundle\Entity\Account;
use FuentesWorks\NickelTrackerBundle\Entity\Category;

/**
 * @ORM\Entity
 * @ORM\Table(name="Transactions")
 */
class Transaction
{
    #########################
    ##      PROPERTIES     ##
    #########################

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", unique=TRUE)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $transactionId;

    /**
     * I: income, E: expense, T: transfer
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

    public function getSourceAccountName()
    {
        return $this->getSourceAccountId()->getName();
    }

    public function getDestinationAccountName()
    {
        if($this->destinationAccountId){
            return $this->getDestinationAccountId()->getName();
        } else {
            return 'N/A';
        }
    }

    public function getGlobalId()
    {
        return $this->type . $this->transactionId;
    }


    #########################
    ## OBJECT RELATIONSHIP ##
    #########################

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="sourceTransactions")
     * @ORM\JoinColumn(name="sourceAccountId", referencedColumnName="accountId")
     */
    protected $sourceAccountId;

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="destinationTransactions")
     * @ORM\JoinColumn(name="destinationAccountId", referencedColumnName="accountId", nullable=true)
     */
    protected $destinationAccountId;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="transactions")
     * @ORM\JoinColumn(name="categoryId", referencedColumnName="categoryId", nullable=true)
     */
    protected $categoryId;

    /**
     * Set sourceAccountId
     *
     * @param Account $accountId
     * @return Transaction
     */
    public function setSourceAccountId(Account $accountId = null)
    {
        $this->sourceAccountId = $accountId;

        return $this;
    }

    /**
     * Get sourceAccountId
     *
     * @return Account
     */
    public function getSourceAccountId()
    {
        return $this->sourceAccountId;
    }

    /**
     * Set destinationAccountId
     *
     * @param Account $accountId
     * @return Transaction
     */
    public function setDestinationAccountId(Account $accountId = null)
    {
        $this->destinationAccountId = $accountId;

        return $this;
    }

    /**
     * Get destinationAccountId
     *
     * @return Account
     */
    public function getDestinationAccountId()
    {
        return $this->destinationAccountId;
    }

    /**
     * Set categoryId
     *
     * @param Category $categoryId
     * @return Transaction
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
     * @return Transaction
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
     * @return Transaction
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
     * @return Transaction
     */
    public function setDate(\DateTime $date)
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
     * @return Transaction
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
     * @return Transaction
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
     * @return integer
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }


}