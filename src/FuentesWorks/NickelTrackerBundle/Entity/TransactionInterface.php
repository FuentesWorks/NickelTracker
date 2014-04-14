<?php

namespace FuentesWorks\NickelTrackerBundle;

interface TransactionInterface
{
    /**
     * This interface defines the methods needed to properly display the Logs
     * across the system interface
     */


    public function getType();

    public function getAccountName();
}