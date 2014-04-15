<?php

namespace FuentesWorks\NickelTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    public function newIncomeAction(Request $request)
    {
        $doctrine = $this->getDoctrine();

        // Load all accounts and categories
        $accounts = $doctrine->getRepository('FuentesWorksNickelTrackerBundle:Account')
            ->findAll();
        $categories = $doctrine->getRepository('FuentesWorksNickelTrackerBundle:Category')
            ->findAll();

        return $this->render('FuentesWorksNickelTrackerBundle:Transaction:new-income.html.twig',
            array('accounts' => $accounts, 'categories' => $categories));
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