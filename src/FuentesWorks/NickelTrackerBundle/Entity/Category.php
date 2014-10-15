<?php

namespace FuentesWorks\NickelTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use FuentesWorks\NickelTrackerBundle\Entity\Transaction;

/**
 * @ORM\Entity
 * @ORM\Table(name="Categories")
 */
class Category
{
    #########################
    ##      PROPERTIES     ##
    #########################

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", unique=TRUE)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $categoryId;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $budget = 0;


    #########################
    ## OBJECT RELATIONSHIP ##
    #########################

    /**
     * @ORM\OneToMany(targetEntity="Transaction", mappedBy="categoryId")
     */
    protected $transactions;

    public function __construct() {
        $this->transactions = new ArrayCollection();
    }

    /**
     * Add transactions
     *
     * @param Transaction $transactions
     * @return Category
     */
    public function addTransactionLog(Transaction $transactions)
    {
        $this->transactions[] = $transactions;

        return $this;
    }

    /**
     * Remove transactions
     *
     * @param Transaction $transactions
     * @return Category
     */
    public function removeTransaction(Transaction $transactions)
    {
        $this->transactions->removeElement($transactions);

        return $this;
    }

    /**
     * Get transactions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTransactions()
    {
        return $this->transactions;
    }


    #########################
    ##   SPECIAL METHODS   ##
    #########################


    #########################
    ## GETTERs AND SETTERs ##
    #########################

    /**
     * @param integer $budget
     * @return Category
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * @return integer
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * @param integer $categoryId
     * @return Category
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * @return integer
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @param string $name
     * @return Category
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


}