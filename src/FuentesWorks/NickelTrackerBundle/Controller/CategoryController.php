<?php

namespace FuentesWorks\NickelTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FuentesWorks\NickelTrackerBundle\Controller\NickelTrackerController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FuentesWorks\NickelTrackerBundle\Entity\Category;
use FuentesWorks\NickelTrackerBundle\Entity\TransactionLog;

class CategoryController extends NickelTrackerController
{
    public function listAction($status)
    {
        // Load categories
        $repository = $this->getDoctrine()
            ->getRepository('FuentesWorksNickelTrackerBundle:Category');
        $query = $repository->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC')
            ->getQuery();
        $categories = $query->getResult();

        // Load this Month's Expenses
        $repository = $this->getDoctrine()
            ->getRepository('FuentesWorksNickelTrackerBundle:TransactionLog');
        $query = $repository->createQueryBuilder('t')
            ->where('t.date >= :month')
            ->andwhere("t.type = 'E'")
            ->setParameter('month', date('Y-m-01'))
            ->orderBy('t.date', 'DESC')
            ->addOrderby('t.transactionLogId', 'DESC')
            ->getQuery();
        $transactions = $query->getResult();

        $balance = array();
        foreach($transactions as $transaction)
        {
            /* @var TransactionLog $transaction */
            if($transaction->getCategoryId())
            {
                if(array_key_exists($transaction->getCategoryId()->getCategoryId(), $balance)) {
                    $balance[$transaction->getCategoryId()->getCategoryId()] += $transaction->getAmount();
                } else {
                    $balance[$transaction->getCategoryId()->getCategoryId()] = $transaction->getAmount();
                }
            }
        }

        if($status == 'ok') {
            $msg = array('type' => 'success',
                'text' => "<strong>Woot!</strong> Category created successfully!");
        } elseif($status == 'del') {
            $msg = array('type' => 'success',
                'text' => "<strong>Woot!</strong> Category deleted successfully!");
        } else {
            $msg = null;
        }

        return $this->render('FuentesWorksNickelTrackerBundle:Category:list.html.twig',
            array('categories' => $categories, 'balance' => $balance, 'msg' => $msg));
    }

    public function newAction()
    {
        $category = new Category();
        $category->setName('Untitled Category');

        return $this->render('FuentesWorksNickelTrackerBundle:Category:detail.html.twig',
            array('category' => $category,
                'mode' => 'new'));
    }

    public function newProcessAction(Request $request)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        $category = new Category();
        $category->setName($request->request->get('name'));
        $category->setBudget($request->request->get('budget'));

        $em->persist($category);
        $em->flush();

        $msg = array('type' => 'success',
            'text' => "<strong>Woot!</strong> Category created successfully!");

        return $this->render('FuentesWorksNickelTrackerBundle:Category:list.html.twig',
            array('msg' => $msg));
    }

    public function viewAction($id)
    {
        $category = $this->getDoctrine()->getRepository('FuentesWorksNickelTrackerBundle:Category')
            ->find($id);

        if(!$category)
        {
            $msg = array('type' => 'warning',
                'text' => "<strong>Woah!</strong> Could not load category with id <strong>" . $id . "</strong>!");
            return $this->render('FuentesWorksNickelTrackerBundle:Category:list.html.twig',
                array('msg' => $msg));
        }

        return $this->render('FuentesWorksNickelTrackerBundle:Category:detail.html.twig',
            array('category' => $category,
                'mode' => 'view'));
    }

    public function editAction($id)
    {
        $category = $this->getDoctrine()->getRepository('FuentesWorksNickelTrackerBundle:Category')
            ->find($id);

        if(!$category)
        {
            $msg = array('type' => 'warning',
                'text' => "<strong>Woah!</strong> Could not load category with id <strong>" . $id . "</strong>!");
            return $this->render('FuentesWorksNickelTrackerBundle:Category:list.html.twig',
                array('msg' => $msg));
        }

        return $this->render('FuentesWorksNickelTrackerBundle:Category:detail.html.twig',
            array('category' => $category,
                'mode' => 'edit'));
    }

    public function editProcessAction(Request $request)
    {
        $id = $request->request->get('categoryId');

        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        $category = $doctrine->getRepository('FuentesWorksNickelTrackerBundle:Category')
            ->find($id);

        if(!$category)
        {
            $msg = array('type' => 'warning',
                'text' => "<strong>Woah!</strong> Could not load category with id <strong>" . $id . "</strong>!");
            return $this->render('FuentesWorksNickelTrackerBundle:Category:list.html.twig',
                array('msg' => $msg));
        }

        $category->setName($request->request->get('name'));
        $category->setBudget($request->request->get('budget'));

        $em->persist($category);
        $em->flush();

        return $this->redirect($this->generateUrl('fuentesworks_nickeltracker_category_list', array('status' => 'ok')));
    }

    public function deleteAction(Request $request)
    {
        $id = $request->request->get('categoryId');

        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        $category = $doctrine->getRepository('FuentesWorksNickelTrackerBundle:Category')
            ->find($id);

        if(!$category)
        {
            $msg = array('type' => 'warning',
                'text' => "<strong>Woah!</strong> Could not load category with id <strong>" . $id . "</strong>!");
            return $this->render('FuentesWorksNickelTrackerBundle:Category:list.html.twig',
                array('msg' => $msg));
        }

        $em->remove($category);
        $em->flush();

        return $this->redirect($this->generateUrl('fuentesworks_nickeltracker_category_list', array('status' => 'del')));

    }



}