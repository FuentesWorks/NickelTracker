<?php

namespace FuentesWorks\NickelTrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use FuentesWorks\NickelTrackerBundle\Entity\TransactionLog;

/**
 * @ORM\Entity
 * @ORM\Table(name="Categories")
 * @ORM\OrderBy({"name" = "ASC"})
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
     * @ORM\OneToMany(targetEntity="TransactionLog", mappedBy="categoryId")
     */
    protected $transactionLogs;

    public function __construct() {
        $this->transactionLogs = new ArrayCollection();
    }

    /**
     * Add transactionLogs
     *
     * @param TransactionLog $transactionLogs
     * @return Category
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
     * @return Category
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