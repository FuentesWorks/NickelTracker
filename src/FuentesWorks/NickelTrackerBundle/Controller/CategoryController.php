<?php

namespace FuentesWorks\NickelTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FuentesWorks\NickelTrackerBundle\Controller\NickelTrackerController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FuentesWorks\NickelTrackerBundle\Entity\Category;

class CategoryController extends NickelTrackerController
{
    public function listAction()
    {
        // Just render the template, the $categories list is automatically injected.
        return $this->render('FuentesWorksNickelTrackerBundle:Category:list.html.twig');
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

        $msg = array('type' => 'success',
            'text' => "<strong>Woot!</strong> Category created successfully!");

        return $this->render('FuentesWorksNickelTrackerBundle:Category:list.html.twig',
            array('msg' => $msg));
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

        $msg = array('type' => 'success',
            'text' => "<strong>Woot!</strong> Category deleted successfully!");
        return $this->render('FuentesWorksNickelTrackerBundle:Category:list.html.twig',
            array('msg' => $msg));

    }



}