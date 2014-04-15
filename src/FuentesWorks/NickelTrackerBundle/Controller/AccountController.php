<?php

namespace FuentesWorks\NickelTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FuentesWorks\NickelTrackerBundle\Controller\NickelTrackerController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends NickelTrackerController
{
    public function listAction()
    {
        // Just render the template, the $accounts list is automatically injected.
        return $this->render('FuentesWorksNickelTrackerBundle:Account:list.html.twig');
    }

}