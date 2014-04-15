<?php

namespace FuentesWorks\NickelTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FuentesWorks\NickelTrackerBundle\Controller\NickelTrackerController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FuentesWorks\NickelTrackerBundle\Entity\Account;

class AccountController extends NickelTrackerController
{
    public function listAction()
    {
        // Just render the template, the $accounts list is automatically injected.
        return $this->render('FuentesWorksNickelTrackerBundle:Account:list.html.twig');
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

}