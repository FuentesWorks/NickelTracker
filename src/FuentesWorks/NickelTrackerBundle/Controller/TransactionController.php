<?php

namespace FuentesWorks\NickelTrackerBundle\Controller;

use FuentesWorks\NickelTrackerBundle\Entity\TransactionLog;
use FuentesWorks\NickelTrackerBundle\Entity\Account;
use FuentesWorks\NickelTrackerBundle\Entity\Category;

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
            if($status == 'add')
            {
                $msg = array('type' => 'success',
                    'text' => "<strong>Woot!</strong> Successfully registered the transaction!");
                return $this->render('FuentesWorksNickelTrackerBundle:Transaction:list.html.twig',
                        array('transactions' => $transactions,
                              'msg' => $msg));
            } elseif($status == 'delete')
            {
                $msg = array('type' => 'success',
                    'text' => "<strong>Woot!</strong> Successfully deleted the transaction!");
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

        /* @var Account $account */
        $account = $doctrine->getRepository('FuentesWorks\NickelTrackerBundle\Entity\Account')
            ->find( $request->request->get('accountId') );
        /* @var Category $category */
        $category = $doctrine->getRepository('FuentesWorks\NickelTrackerBundle\Entity\Category')
            ->find( $request->request->get('categoryId') );

        $trans = new TransactionLog();
        $trans->setType('I');
        $trans->setAccountId($account);
        $trans->setCategoryId($category);

        $trans->setDate(new \DateTime($request->request->get('date')));
        $trans->setAmount($request->request->get('amount'));
        $trans->setDescription($request->request->get('description'));

        $account->updateBalance($trans);

        $em->persist($trans);
        $em->flush();

        return $this->redirect($this->generateUrl('fuentesworks_nickeltracker_transaction_list', array('status' => 'add')));
    }

    public function newExpenseProcessAction(Request $request)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        /* @var Account $account */
        $account = $doctrine->getRepository('FuentesWorks\NickelTrackerBundle\Entity\Account')
            ->find( $request->request->get('accountId') );
        /* @var Category $category */
        $category = $doctrine->getRepository('FuentesWorks\NickelTrackerBundle\Entity\Category')
            ->find( $request->request->get('categoryId') );

        $trans = new TransactionLog();
        $trans->setType('E');
        $trans->setAccountId($account);
        $trans->setCategoryId($category);

        $trans->setDate(new \DateTime($request->request->get('date')));
        $trans->setAmount($request->request->get('amount'));
        $trans->setDescription($request->request->get('description'));

        $account->updateBalance($trans);

        $em->persist($trans);
        $em->flush();

        return $this->redirect($this->generateUrl('fuentesworks_nickeltracker_transaction_list', array('status' => 'add')));
    }

    public function newTransferProcessAction(Request $request)
    {
        return $this->render('FuentesWorksNickelTrackerBundle:Transaction:list.html.twig');
    }

    public function deleteAction(Request $request)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $globalId = $request->request->get('globalId');

        if(!$globalId)
        {
            // No Global ID fround
            throw $this->createNotFoundException('No GlobalId provided');
        } elseif($globalId[0] == 'T') {
            // TransferLog
            $id = substr($globalId, 1);
            $trans = $doctrine->getRepository('FuentesWorks\NickelTrackerBundle\Entity\TransferLog')
                ->find( $id );
        } elseif($globalId[0] == 'I' or $globalId[0] == 'E') {
            // TransactionLog
            $id = substr($globalId, 1);
            $trans = $doctrine->getRepository('FuentesWorks\NickelTrackerBundle\Entity\TransactionLog')
                ->find( $id );
        } else {
            // Invalid GlobalId
            throw $this->createNotFoundException('Invalid GlobalId provided');
        }

        if(!$trans)
        {
            throw $this->createNotFoundException('Could not find log with ID: ' . $globalId );
        }

        $em->remove($trans);
        $em->flush();

        return $this->redirect($this->generateUrl('fuentesworks_nickeltracker_transaction_list', array('status' => 'delete')));
    }

}