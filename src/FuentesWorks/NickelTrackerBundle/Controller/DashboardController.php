<?php

namespace FuentesWorks\NickelTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FuentesWorks\NickelTrackerBundle\Controller\NickelTrackerController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends NickelTrackerController
{
    public function homeAction()
    {
        $transactions = array();

        return $this->render('FuentesWorksNickelTrackerBundle:Dashboard:dashboard.html.twig',
            array('transactions' => $transactions));
    }

}