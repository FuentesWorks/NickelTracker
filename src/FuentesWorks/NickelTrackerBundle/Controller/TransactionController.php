<?php

namespace FuentesWorks\NickelTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    public function newIncomeAction(Request $request)
    {
        return $this->render('FuentesWorksNickelTrackerBundle:Transaction:new-income.html.twig');
    }

    public function newExpenseAction(Request $request)
    {
        return $this->render('FuentesWorksNickelTrackerBundle:Transaction:list.html.twig');
    }

    public function newTransferAction(Request $request)
    {
        return $this->render('FuentesWorksNickelTrackerBundle:Transaction:list.html.twig');
    }

    public function newIncomeProcessAction(Request $request)
    {
        return $this->render('FuentesWorksNickelTrackerBundle:Transaction:list.html.twig');
    }

    public function newExpenseProcessAction(Request $request)
    {
        return $this->render('FuentesWorksNickelTrackerBundle:Transaction:list.html.twig');
    }

    public function newTransferProcessAction(Request $request)
    {
        return $this->render('FuentesWorksNickelTrackerBundle:Transaction:list.html.twig');
    }

}