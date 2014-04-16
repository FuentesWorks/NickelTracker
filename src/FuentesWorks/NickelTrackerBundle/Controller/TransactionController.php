<?php

namespace FuentesWorks\NickelTrackerBundle\Controller;

use FuentesWorks\NickelTrackerBundle\Entity\TransactionInterface;
use FuentesWorks\NickelTrackerBundle\Entity\TransactionLog;
use FuentesWorks\NickelTrackerBundle\Entity\TransferLog;
use FuentesWorks\NickelTrackerBundle\Entity\Account;
use FuentesWorks\NickelTrackerBundle\Entity\Category;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends NickelTrackerController
{
    public function listAction(Request $request, $status)
    {
        // Load Recent Transtactions
        $repository = $this->getDoctrine()
            ->getRepository('FuentesWorksNickelTrackerBundle:TransactionLog');
        $query = $repository->createQueryBuilder('t')
            ->setMaxResults(50)
            ->orderBy('t.date', 'DESC')
            ->addOrderby('t.transactionLogId', 'DESC')
            ->getQuery();
        $transactions = $query->getResult();

        $repository = $this->getDoctrine()
            ->getRepository('FuentesWorksNickelTrackerBundle:TransferLog');
        $query = $repository->createQueryBuilder('t')
            ->setMaxResults(50)
            ->orderBy('t.date', 'DESC')
            ->addOrderby('t.transferLogId', 'DESC')
            ->getQuery();
        $transfers = $query->getResult();

        // Merge both arrays and sort
        $transactions = array_merge($transactions, $transfers);
        uasort($transactions, array($this, 'compareFunction'));


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

    public function newIncomeAction(Request $request, $status)
    {
        if($status == 'error')
        {
            $msg = array('type' => 'danger',
                'text' => "<strong>Woah!</strong> One or more values are invalid. Please try again.");
            return $this->render('FuentesWorksNickelTrackerBundle:Transaction:new-income.html.twig',
                array('msg' => $msg));
        }
        return $this->render('FuentesWorksNickelTrackerBundle:Transaction:new-income.html.twig');
    }

    public function newExpenseAction(Request $request, $status)
    {
        if($status == 'error')
        {
            $msg = array('type' => 'danger',
                'text' => "<strong>Woah!</strong> One or more values are invalid. Please try again.");
            return $this->render('FuentesWorksNickelTrackerBundle:Transaction:new-expense.html.twig',
                array('msg' => $msg));
        }
        return $this->render('FuentesWorksNickelTrackerBundle:Transaction:new-expense.html.twig');
    }

    public function newTransferAction(Request $request, $status)
    {
        if($status == 'error')
        {
            $msg = array('type' => 'danger',
                'text' => "<strong>Woah!</strong> One or more values are invalid. Please try again.");
            return $this->render('FuentesWorksNickelTrackerBundle:Transaction:new-transfer.html.twig',
                array('msg' => $msg));
        }
        return $this->render('FuentesWorksNickelTrackerBundle:Transaction:new-transfer.html.twig');
    }

    public function newIncomeProcessAction(Request $request)
    {
        // Validate parameters
        $parameters = array('accountId', 'categoryId', 'date', 'amount', 'description');
        foreach($parameters as $param)
        {
            if(!$request->request->has($param) || !$request->request->get($param))
            {
                return $this->redirect($this->generateUrl('fuentesworks_nickeltracker_new_income', array('status' => 'error')));
            }
        }

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
        // Validate parameters
        $parameters = array('accountId', 'categoryId', 'date', 'amount', 'description');
        foreach($parameters as $param)
        {
            if(!$request->request->has($param) || !$request->request->get($param))
            {
                return $this->redirect($this->generateUrl('fuentesworks_nickeltracker_new_expense', array('status' => 'error')));
            }
        }

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
        // Validate parameters
        $parameters = array('sourceId', 'destinationId', 'date', 'amount', 'description');
        foreach($parameters as $param)
        {
            if(!$request->request->has($param) || !$request->request->get($param))
            {
                return $this->redirect($this->generateUrl('fuentesworks_nickeltracker_new_transfer', array('status' => 'error')));
            }
        }

        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        /* @var Account $source */
        $source = $doctrine->getRepository('FuentesWorks\NickelTrackerBundle\Entity\Account')
            ->find( $request->request->get('sourceId') );
        /* @var Account $destination */
        $destination = $doctrine->getRepository('FuentesWorks\NickelTrackerBundle\Entity\Account')
            ->find( $request->request->get('destinationId') );

        $trans = new TransferLog();
        $trans->setSourceId($source);
        $trans->setDestinationId($destination);

        $trans->setDate(new \DateTime($request->request->get('date')));
        $trans->setAmount($request->request->get('amount'));
        $trans->setDescription($request->request->get('description'));

        $source->updateBalance($trans);
        $destination->updateBalance($trans);

        $em->persist($trans);
        $em->flush();

        return $this->redirect($this->generateUrl('fuentesworks_nickeltracker_transaction_list', array('status' => 'add')));
    }

    public function deleteAction(Request $request)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $globalId = $request->request->get('globalId');

        if(!$globalId || !in_array($globalId[0], array('T', 'I', 'E')) )
        {
            // No Global ID fround
            throw $this->createNotFoundException('No GlobalId provided or invalid');
        }

        if($globalId[0] == 'T') {
            // TransferLog
            $id = substr($globalId, 1);
            /* @var TransferLog $trans */
            $trans = $doctrine->getRepository('FuentesWorks\NickelTrackerBundle\Entity\TransferLog')
                ->find( $id );

            // Revert balance changes
            $source = $trans->getSourceId();
            $source->revertBalance($trans);

            $destination = $trans->getDestinationId();
            $destination->revertBalance($trans);

        } else {
            // TransactionLog
            $id = substr($globalId, 1);
            /* @var TransactionLog $trans */
            $trans = $doctrine->getRepository('FuentesWorks\NickelTrackerBundle\Entity\TransactionLog')
                ->find( $id );

            // Revert balance changes
            $source = $trans->getAccountId();
            $source->revertBalance($trans);
        }

        if(!$trans)
        {
            throw $this->createNotFoundException('Could not find log with ID: ' . $globalId );
        }

        $em->remove($trans);
        $em->flush();

        return $this->redirect($this->generateUrl('fuentesworks_nickeltracker_transaction_list', array('status' => 'del')));
    }

    private function compareFunction(TransactionInterface $a, TransactionInterface $b){
        if ($a->getDate() == $b->getDate()) {
            return 0;
        }
        return ($a->getDate() < $b->getDate()) ? -1 : 1;
    }

}