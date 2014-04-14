<?php

namespace FuentesWorks\NickelTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    public function newIncomeAction(Request $request)
    {
        return $this->render('FuentesWorksNickelTrackerBundle:Transaction:list.html.twig');
    }

    public function newExpenseAction(Request $request)
    {
        return $this->render('FuentesWorksNickelTrackerBundle:Transaction:list.html.twig');
    }

    public function newTransferAction(Request $request)
    {
        return $this->render('FuentesWorksNickelTrackerBundle:Transaction:list.html.twig');
    }

}