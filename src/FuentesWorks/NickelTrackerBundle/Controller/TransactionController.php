<?php

namespace FuentesWorks\NickelTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FuentesWorks\NickelTrackerBundle\Entity\Transaction;
use FuentesWorks\NickelTrackerBundle\Entity\Account;
use FuentesWorks\NickelTrackerBundle\Entity\Category;

class TransactionController extends NickelTrackerController
{
    public function listAction(Request $request, $status)
    {
        // Load Recent Transactions
        $repository = $this->getDoctrine()
            ->getRepository('FuentesWorksNickelTrackerBundle:Transaction');
        $query = $repository->createQueryBuilder('t')
            ->setMaxResults(50)
            ->orderBy('t.date', 'DESC')
            ->addOrderby('t.transactionId', 'ASC')
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

    public function filterAction(Request $request)
    {
        if($request->getMethod() != 'POST') {
            return $this->redirect($this->generateUrl('fuentesworks_nickeltracker_transaction_list'));
        }

        // Get parameters from request
        $accountId = $request->request->get('accountId');
        $categoryId = $request->request->get('categoryId');
        $fromDate = $request->request->get('fromDate');
        $toDate = $request->request->get('toDate');

        // Parameters pass down
        $params['accountId'] = $accountId;
        $params['categoryId'] = $categoryId;
        $params['fromDate'] = $fromDate;
        $params['toDate'] = $toDate;

        /* @var \Doctrine\ORM\QueryBuilder $qbTransactions */
        $qbTransactions = $this->getDoctrine()
            ->getRepository('FuentesWorksNickelTrackerBundle:Transaction')
            ->createQueryBuilder('t')
            ->orderBy('t.date', 'DESC')
            ->addOrderby('t.transactionId', 'ASC')
            ->setMaxResults(50);

        if($accountId) {
            $qbTransactions->andWhere('t.sourceAccountId = :accountId OR t.destinationAccountId = :accountId')
                ->setParameter('accountId', $accountId);
        }

        if($categoryId) {
            $qbTransactions->andWhere('t.categoryId = :categoryId')
                ->setParameter('categoryId', $categoryId);
        }

        if($fromDate) {
            $qbTransactions->andWhere('t.date >= :fromDate')
                ->setParameter('fromDate', $fromDate);
        }

        if($toDate) {
            $qbTransactions->andWhere('t.date <= :toDate')
                ->setParameter('toDate', $toDate);
        }

        $transactions = $qbTransactions->getQuery()->getResult();

        return $this->render('FuentesWorksNickelTrackerBundle:Transaction:list.html.twig',
            array('transactions' => $transactions,
                  'params' => $params));
    }

    public function searchAction(Request $request)
    {
        if($request->getMethod() != 'POST') {
            return $this->redirect($this->generateUrl('fuentesworks_nickeltracker_transaction_list'));
        }

        // Get parameters from request
        $search = $request->request->get('search');
        $keywords = explode(' ', $search);

        if(!$search) {
            // No search terms were inputted
            return $this->redirect($this->generateUrl('fuentesworks_nickeltracker_transaction_list'));
        }

        /* @var \Doctrine\ORM\QueryBuilder $qbTransactions */
        $qbTransactions = $this->getDoctrine()
            ->getRepository('FuentesWorksNickelTrackerBundle:Transaction')
            ->createQueryBuilder('t')
            ->orderBy('t.date', 'DESC')
            ->addOrderby('t.transactionId', 'ASC')
            ->setMaxResults(50);

        foreach($keywords as $index => $keyword)
        {
            $qbTransactions->orWhere('t.description LIKE :keyword' . $index . ' OR t.details LIKE :keyword' . $index )
                ->setParameter('keyword' . $index, '%' . $keyword . '%');
        }

        $transactions = $qbTransactions->getQuery()->getResult();

        return $this->render('FuentesWorksNickelTrackerBundle:Transaction:list.html.twig',
            array('transactions' => $transactions,
                'search' => $search));
    }

    public function viewAction(Request $request, $gid, $mode)
    {
        $doctrine = $this->getDoctrine();
        $message = array();

        if( !in_array($mode, array('view', 'edit')) )
        {
            // Invalid mode selected
            throw $this->createNotFoundException('Invalid view mode selected');
        }

        if(!$gid || !in_array($gid[0], Transaction::getAvailableTypes()) )
        {
            // No Global ID fround
            throw $this->createNotFoundException('No GlobalId provided or invalid');
        }

        $id = substr($gid, 1);
        /** @var Transaction $trans */
        $trans = $doctrine->getRepository('FuentesWorks\NickelTrackerBundle\Entity\Transaction')
            ->find( $id );

        if(!$trans)
        {
            throw $this->createNotFoundException('Could not find log with ID: ' . $gid );
        }

        return $this->render('FuentesWorksNickelTrackerBundle:Transaction:details.html.twig',
            array('transaction' => $trans, 'message' => $message, 'mode' => $mode));
    }

    public function editProcessAction(Request $request)
    {

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
        //$parameters = array('accountId', 'categoryId', 'date', 'amount', 'description');
        $parameters = array('accountId', 'date', 'amount', 'description');
        foreach($parameters as $param)
        {
            if(!$request->request->has($param) || !$request->request->get($param))
            {
                // TODO: Use FlashBags!
                return $this->redirect($this->generateUrl('fuentesworks_nickeltracker_new_income', array('status' => 'error')));
            }
        }

        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        /* @var Account $account */
        $account = $doctrine->getRepository('FuentesWorks\NickelTrackerBundle\Entity\Account')
            ->find( $request->request->get('accountId') );
        /* @var Category $category */
        //$category = $doctrine->getRepository('FuentesWorks\NickelTrackerBundle\Entity\Category')
        //    ->find( $request->request->get('categoryId') );

        $trans = new Transaction();
        $trans->setType('I');
        $trans->setSourceAccountId($account);
        $trans->setDestinationAccountId(null);
        $trans->setCategoryId(null);

        $trans->setDate(new \DateTime($request->request->get('date')));
        $trans->setAmount($request->request->get('amount'));
        $trans->setDescription($request->request->get('description'));
        $trans->setDetails($request->request->get('details'));

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
                // TODO: Use FlashBags!
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

        $trans = new Transaction();
        $trans->setType('E');
        $trans->setSourceAccountId($account);
        $trans->setDestinationAccountId(null);
        $trans->setCategoryId($category);

        $trans->setDate(new \DateTime($request->request->get('date')));
        $trans->setAmount($request->request->get('amount'));
        $trans->setDescription($request->request->get('description'));
        $trans->setDetails($request->request->get('details'));

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
        $source = $doctrine->getRepository('\FuentesWorks\NickelTrackerBundle\Entity\Account')
            ->find( $request->request->get('sourceId') );
        /* @var Account $destination */
        $destination = $doctrine->getRepository('\FuentesWorks\NickelTrackerBundle\Entity\Account')
            ->find( $request->request->get('destinationId') );

        if(!$source || !$destination){
            // Could not load accounts
            return $this->redirect($this->generateUrl('fuentesworks_nickeltracker_new_transfer', array('status' => 'error')));
        }

        $trans = new Transaction();
        $trans->setType('T');
        $trans->setSourceAccountId($source);
        $trans->setDestinationAccountId($destination);
        $trans->setCategoryId(null);

        $trans->setDate(new \DateTime($request->request->get('date')));
        $trans->setAmount($request->request->get('amount'));
        $trans->setDescription($request->request->get('description'));
        $trans->setDetails($request->request->get('details'));

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

        $id = substr($globalId, 1);
        /* @var Transaction $trans */
        $trans = $doctrine->getRepository('FuentesWorks\NickelTrackerBundle\Entity\Transaction')
            ->find( $id );

        if(!$trans)
        {
            throw $this->createNotFoundException('Could not find log with ID: ' . $globalId );
        }

        if($trans->getType() == 'T') {
            // Revert balance changes
            $source = $trans->getSourceAccountId();
            $source->revertBalance($trans);

            $destination = $trans->getDestinationAccountId();
            $destination->revertBalance($trans);
        } else {
            // Revert balance changes
            $source = $trans->getSourceAccountId();
            $source->revertBalance($trans);
        }

        $em->remove($trans);
        $em->flush();

        return $this->redirect($this->generateUrl('fuentesworks_nickeltracker_transaction_list', array('status' => 'del')));
    }

}