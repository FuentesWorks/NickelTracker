<?php

namespace FuentesWorks\NickelTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FuentesWorks\NickelTrackerBundle\Controller\NickelTrackerController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;

use FuentesWorks\NickelTrackerBundle\Entity\Account;
use FuentesWorks\NickelTrackerBundle\Entity\Category;
use FuentesWorks\NickelTrackerBundle\Entity\TransactionLog;
use FuentesWorks\NickelTrackerBundle\Entity\TransactionInterface;

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

        $repository = $this->getDoctrine()
            ->getRepository('FuentesWorksNickelTrackerBundle:TransferLog');
        $query = $repository->createQueryBuilder('t')
            ->where('t.date >= :month')
            ->setParameter('month', date('01-m-Y'))
            ->orderBy('t.date', 'DESC')
            ->addOrderby('t.transferLogId', 'DESC')
            ->getQuery();
        $transfers = $query->getResult();

        // Merge both arrays and sort
        $transactions = array_merge($transactions, $transfers);
        uasort($transactions, array($this, 'compareFunction'));

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

    private function compareFunction(TransactionInterface $a, TransactionInterface $b){
        if ($a->getDate() == $b->getDate()) {
            return 0;
        }
        //return ($a->getDate() < $b->getDate()) ? -1 : 1; // low to high
        return ($a->getDate() > $b->getDate()) ? -1 : 1; // high to low
    }

}