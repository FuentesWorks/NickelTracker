<?php

namespace FuentesWorks\NickelTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FuentesWorks\NickelTrackerBundle\Controller\NickelTrackerController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends NickelTrackerController
{
    public function homeAction()
    {
        $repository = $this->getDoctrine()
            ->getRepository('FuentesWorksNickelTrackerBundle:TransactionLog');
        $query = $repository->createQueryBuilder('t')
            ->setMaxResults(20)
            ->orderBy('t.date', 'DESC')
            ->getQuery();
        $transactions = $query->getResult();

        return $this->render('FuentesWorksNickelTrackerBundle:Dashboard:dashboard.html.twig',
            array('transactions' => $transactions));
    }

}