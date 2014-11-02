<?php

namespace FuentesWorks\NickelTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FuentesWorks\NickelTrackerBundle\Entity\Transaction;
use FuentesWorks\NickelTrackerBundle\Entity\Account;
use FuentesWorks\NickelTrackerBundle\Entity\Category;

class ReportController extends NickelTrackerController
{
    public function listAction()
    {
        return $this->render('FuentesWorksNickelTrackerBundle:Report:list.html.twig');
    }

    public function monthlyBalanceAction(Request $request)
    {
        $sql = <<<ENDSQL
SELECT
    YEAR(t.date) as `year`,
    MONTH(t.date) as `month`,
    SUM(CASE WHEN t.type = 'I' THEN t.amount ELSE 0 END) as `income`,
    SUM(CASE WHEN t.type = 'E' THEN t.amount ELSE 0 END) as `expense`
FROM Transactions as t
GROUP BY YEAR(t.date), MONTH(t.date)
ENDSQL;

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();

        return $this->render('FuentesWorksNickelTrackerBundle:Report:monthlyBalance.html.twig');
    }


}