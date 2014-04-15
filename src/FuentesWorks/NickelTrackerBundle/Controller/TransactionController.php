<?php

namespace FuentesWorks\NickelTrackerBundle\Controller;

use FuentesWorks\NickelTrackerBundle\Entity\TransactionLog;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends NickelTrackerController
{
    public function listAction(Request $request, $status)
    {
        $repository = $this->getDoctrine()
            ->getRepository('FuentesWorksNickelTrackerBundle:TransactionLog');

        $query = $repository->createQueryBuilder('t')
            ->setMaxResults(50)
            ->orderBy('t.date', 'DESC')
            ->getQuery();

        $transactions = $query->getResult();


        if($status)
        {
            if($status == 'ok')
            {
                $msg = array('type' => 'success',
                    'text' => "<strong>Woot!</strong> Successfully registered the transaction!");
                return $this->render('FuentesWorksNickelTrackerBundle:Transaction:list.html.twig',
                        array('transactions' => $transactions,
                              'msg' => $msg));
            }
        }

        return $this->render('FuentesWorksNickelTrackerBundle:Transaction:list.html.twig',
            array('transactions' => $transactions));
    }

    public function newIncomeAction(Request $request)
    {
        return $this->render('FuentesWorksNickelTrackerBundle:Transaction:new-income.html.twig');
    }

    public function newExpenseAction(Request $request)
    {
        return $this->render('FuentesWorksNickelTrackerBundle:Transaction:new-expense.html.twig');
    }

    public function newTransferAction(Request $request)
    {
        return $this->render('FuentesWorksNickelTrackerBundle:Transaction:new-transfer.html.twig');
    }

    public function newIncomeProcessAction(Request $request)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        $trans = new TransactionLog();

        $accountId = $request->request->get('accountId');
        $account = $em->getReference('FuentesWorks\NickelTrackerBundle\Entity\Account', $accountId);
        $categoryId = $request->request->get('categoryId');
        $category = $em->getReference('FuentesWorks\NickelTrackerBundle\Entity\Category', $categoryId);

        $trans->setType('I');
        $trans->setAccountId($account);
        $trans->setCategoryId($category);

        $trans->setDate(new \DateTime($request->request->get('date')));
        $trans->setAmount($request->request->get('amount'));
        $trans->setDescription($request->request->get('description'));

        $em->persist($trans);
        $em->flush();



        return $this->redirect($this->generateUrl('fuentesworks_nickeltracker_transaction_list', array('msg' => 'ok')));
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