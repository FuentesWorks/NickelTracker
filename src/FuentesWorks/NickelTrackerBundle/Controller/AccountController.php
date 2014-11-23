<?php

namespace FuentesWorks\NickelTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FuentesWorks\NickelTrackerBundle\Controller\NickelTrackerController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FuentesWorks\NickelTrackerBundle\Entity\Account;
use FuentesWorks\NickelTrackerBundle\Entity\Transaction;

class AccountController extends NickelTrackerController
{
    public function listAction()
    {
        $accounts = $this->getDoctrine()->getRepository('FuentesWorksNickelTrackerBundle:Account')
            ->findAll();

        $totals = array('D' => 0, 'C' => 0, 'S' => 0, 'M' => 0);
        $total = 0;
        foreach($accounts as $a){
            /** @var Account $a */
            $totals[$a->getType()] += $a->getBalance();
            $total += $a->getBalance();
        }

        return $this->render('FuentesWorksNickelTrackerBundle:Account:list.html.twig',
            array('accounts' => $accounts, 'totals' => $totals, 'total' => $total));
    }

    public function newAction()
    {
        $account = new Account();
        $account->setName('Untitled Account');

        return $this->render('FuentesWorksNickelTrackerBundle:Account:detail.html.twig',
            array('account' => $account,
                  'mode' => 'new'));
    }

    public function newProcessAction(Request $request)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        $account = new Account();
        $account->setName($request->request->get('name'));
        $account->setType($request->request->get('type'));

        if($request->request->get('type') == 'C'){
            $account->setCreditLimit($request->request->get('creditLimit'));
        } else {
            $account->setCreditLimit(null);
        }

        $em->persist($account);
        $em->flush();

        $msg = array('type' => 'success',
                     'text' => "<strong>Woot!</strong> Account created successfully!");

        return $this->render('FuentesWorksNickelTrackerBundle:Account:list.html.twig',
            array('msg' => $msg));
    }

    public function viewAction($id)
    {
        $account = $this->getDoctrine()->getRepository('FuentesWorksNickelTrackerBundle:Account')
            ->find($id);

        if(!$account)
        {
            $msg = array('type' => 'warning',
                    'text' => "<strong>Woah!</strong> Could not load account with id <strong>" . $id . "</strong>!");
            return $this->render('FuentesWorksNickelTrackerBundle:Account:list.html.twig',
                array('msg' => $msg));
        }

        return $this->render('FuentesWorksNickelTrackerBundle:Account:detail.html.twig',
            array('account' => $account,
                  'mode' => 'view'));
    }

    public function editAction($id)
    {
        $account = $this->getDoctrine()->getRepository('FuentesWorksNickelTrackerBundle:Account')
            ->find($id);

        if(!$account)
        {
            $msg = array('type' => 'warning',
                'text' => "<strong>Woah!</strong> Could not load account with id <strong>" . $id . "</strong>!");
            return $this->render('FuentesWorksNickelTrackerBundle:Account:list.html.twig',
                array('msg' => $msg));
        }

        return $this->render('FuentesWorksNickelTrackerBundle:Account:detail.html.twig',
            array('account' => $account,
                'mode' => 'edit'));
    }

    public function editProcessAction(Request $request)
    {
        $id = $request->request->get('accountId');

        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        $account = $doctrine->getRepository('FuentesWorksNickelTrackerBundle:Account')
            ->find($id);

        if(!$account)
        {
            $msg = array('type' => 'warning',
                'text' => "<strong>Woah!</strong> Could not load account with id <strong>" . $id . "</strong>!");
            return $this->render('FuentesWorksNickelTrackerBundle:Account:list.html.twig',
                array('msg' => $msg));
        }

        $account->setName($request->request->get('name'));
        $account->setType($request->request->get('type'));

        if($request->request->get('type') == 'C'){
            $account->setCreditLimit($request->request->get('creditLimit'));
        } else {
            $account->setCreditLimit(null);
        }

        $em->persist($account);
        $em->flush();

        $msg = array('type' => 'success',
            'text' => "<strong>Woot!</strong> Account created successfully!");

        return $this->render('FuentesWorksNickelTrackerBundle:Account:list.html.twig',
            array('msg' => $msg));
    }

    public function deleteAction(Request $request)
    {
        $id = $request->request->get('accountId');

        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        $account = $doctrine->getRepository('FuentesWorksNickelTrackerBundle:Account')
            ->find($id);

        if(!$account)
        {
            $msg = array('type' => 'warning',
                'text' => "<strong>Woah!</strong> Could not load account with id <strong>" . $id . "</strong>!");
            return $this->render('FuentesWorksNickelTrackerBundle:Account:list.html.twig',
                array('msg' => $msg));
        }

        $em->remove($account);
        $em->flush();

        $msg = array('type' => 'success',
                'text' => "<strong>Woot!</strong> Account deleted successfully!");
        return $this->render('FuentesWorksNickelTrackerBundle:Account:list.html.twig',
            array('msg' => $msg));

    }

    /**
     * Recalculate account balances in case they are out of sync with
     * the transaction history
     * @param Request $request
     * @return Response
     */
    public function recalculateBalancesAction(Request $request)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        // 1. Load all the existing accounts
        $accounts = $doctrine->getRepository('FuentesWorksNickelTrackerBundle:Account')
            ->findAll();

        // 2. Initialize the balances array..
        $balances = array();
        /* @var Account $account */
        foreach($accounts as $account)
        {
            $balances[ $account->getAccountId() ] = 0;
        }

        // 3. First we'll parse the TransactionLogs
        // TODO: Implement this in DQL Query Builder (OOP'd)
        $sql = <<<ENDSQL
SELECT
    t.sourceAccountId as `account`,
    SUM(CASE
			WHEN t.type = 'I' THEN t.amount
			WHEN t.type = 'E' THEN t.amount*-1
			ELSE 0
		END) as `amount`
FROM Transactions as t
GROUP BY t.sourceAccountId;
ENDSQL;

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $transactions = $stmt->fetchAll();

        foreach($transactions as $transaction)
        {
            $balances[ $transaction['account'] ] += $transaction['amount'];
        }


        // 4. Then we'll all transfers
        $transfers = $doctrine->getRepository('FuentesWorksNickelTrackerBundle:Transaction')
            ->findBy(array('type' => 'T'));

        /* @var Transaction $transfer */
        foreach($transfers as $transfer)
        {
            $balances[ $transfer->getSourceAccountId()->getAccountId() ] -= $transfer->getAmount();
            $balances[ $transfer->getDestinationAccountId()->getAccountId() ] += $transfer->getAmount();
        }

        // 5. Finally, assign the recalculated balances to the Accounts
        /* @var Account $account */
        foreach($accounts as $account)
        {
            $account->setBalance( $balances[$account->getAccountId()] );
        }

        // Flush
        $em->flush();

        // Display success message
        $msg = array('type' => 'success',
        'text' => "<strong>Woot!</strong> Recalculated balances successfully!");
        return $this->render('FuentesWorksNickelTrackerBundle:Account:list.html.twig',
            array('msg' => $msg));
    }

}