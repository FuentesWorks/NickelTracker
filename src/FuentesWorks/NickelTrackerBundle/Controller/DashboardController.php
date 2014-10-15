<?php

namespace FuentesWorks\NickelTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FuentesWorks\NickelTrackerBundle\Controller\NickelTrackerController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;

use FuentesWorks\NickelTrackerBundle\Entity\Account;
use FuentesWorks\NickelTrackerBundle\Entity\Category;
use FuentesWorks\NickelTrackerBundle\Entity\Transaction;

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

        // Load Recent Transactions
        $repository = $this->getDoctrine()
            ->getRepository('FuentesWorksNickelTrackerBundle:Transaction');
        $query = $repository->createQueryBuilder('t')
            ->setMaxResults(25)
            ->orderBy('t.date', 'DESC')
            ->addOrderby('t.transactionId', 'ASC')
            ->getQuery();
        $recent_transactions = $query->getResult();

        // Load this Month's Transactions
        $repository = $this->getDoctrine()
            ->getRepository('FuentesWorksNickelTrackerBundle:Transaction');
        $query = $repository->createQueryBuilder('t')
            ->where('t.date >= :startMonth')
            ->andWhere('t.date <= :endMonth')
            ->setParameter('startMonth', date('Y-m-01'))
            ->setParameter('endMonth', date('Y-m-t'))
            ->orderBy('t.date', 'DESC')
            ->addOrderby('t.transactionId', 'DESC')
            ->getQuery();
        $month_transactions = $query->getResult();

        // Calculate Dashboard parameters
        $dashboard = array();

        $dashboard['income'] = 0;
        $dashboard['expense'] = 0;

        /* @var Transaction $transaction */
        foreach($month_transactions as $transaction)
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
            } elseif($account->getType() == 'D'){
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
            array('transactions' => $recent_transactions,
                  'accounts' => $accounts,
                  'categories' => $categories,
                  'dashboard' => $dashboard));
    }
}