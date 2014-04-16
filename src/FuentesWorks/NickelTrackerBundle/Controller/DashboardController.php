<?php

namespace FuentesWorks\NickelTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FuentesWorks\NickelTrackerBundle\Controller\NickelTrackerController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FuentesWorks\NickelTrackerBundle\Entity\Account;
use FuentesWorks\NickelTrackerBundle\Entity\Category;
use FuentesWorks\NickelTrackerBundle\Entity\TransactionLog;

class DashboardController extends NickelTrackerController
{
    public function homeAction()
    {
        $doctrine = $this->getDoctrine();

        // Load all accounts and
        $accounts = $this->getDoctrine()->getRepository('FuentesWorksNickelTrackerBundle:Account')
            ->findAll();
        $categories = $this->getDoctrine()->getRepository('FuentesWorksNickelTrackerBundle:Category')
            ->findAll();

        // Load Recent Transtactions
        $repository = $this->getDoctrine()
            ->getRepository('FuentesWorksNickelTrackerBundle:TransactionLog');
        $query = $repository->createQueryBuilder('t')
            ->where('t.date >= :month')
            ->setParameter('month', date('01-m-Y'))
            ->orderBy('t.date', 'DESC')
            ->addOrderby('t.transactionLogId', 'DESC')
            ->getQuery();
        $transactions = $query->getResult();

        // Calculate Dashboard parameters
        $dashboard = array();

        $dashboard['income'] = 0;
        $dashboard['expense'] = 0;

        /* @var TransactionLog $transaction */
        foreach($transactions as $transaction)
        {
            if($transaction->getType() == 'I'){
                $dashboard['income'] += $transaction->getAmount();
            } elseif($transaction->getType() == 'E'){
                $dashboard['expense'] += $transaction->getAmount();
            }
        }

        $dashboard['cash'] = 0;
        $dashboard['bank'] = 0;

        /* @var Account $account */
        foreach($accounts as $account)
        {
            if($account->getType() == 'M') {
                $dashboard['cash'] += $account->getBalance();
            } elseif($account->getType() == 'S' || $account->getType() == 'D'){
                $dashboard['bank'] += $account->getBalance();
            }
        }

        $dashboard['budget'] = 0;
        /* @var Category $category */
        foreach($categories as $category)
        {
            $dashboard['budget'] += $category->getBudget();
        }

        return $this->render('FuentesWorksNickelTrackerBundle:Dashboard:dashboard.html.twig',
            array('transactions' => $transactions,
                  'accounts' => $accounts,
                  'categories' => $categories,
                  'dashboard' => $dashboard));
    }

}