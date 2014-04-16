<?php

namespace FuentesWorks\NickelTrackerBundle\Entity;

interface TransactionInterface
{
    /**
     * This interface defines the methods needed to properly display the Logs
     * across the system interface
     */


    public function getType();

    public function getAccountName();

    public function getAmount();

    public function getGlobalId();

    public function getDate();

    public function getCategoryId();

    public function getDetails();
}