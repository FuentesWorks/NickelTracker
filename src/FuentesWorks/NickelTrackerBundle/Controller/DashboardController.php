<?php

namespace FuentesWorks\NickelTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    public function homeAction()
    {
        $accounts = array();
        $categories = array();
        $transactions = array();

        return $this->render('FuentesWorksNickelTrackerBundle:Dashboard:dashboard.html.twig',
            array('accounts' => $accounts,
                  'categories' => $categories,
                  'transactions' => $transactions
            ));
    }

}