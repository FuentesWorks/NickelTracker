<?php

namespace FuentesWorks\NickelTrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NickelTrackerController extends Controller
{
    // Extend the original render function to _always_ include the
    //  $accounts and $categories arrays
    /**
     * Renders a view.
     *
     * @param string   $view       The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response   A response instance
     *
     * @return Response A Response instance
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        if(!array_key_exists('accounts', $parameters)){
            // Accounts have not been defined, load them from the DB
            $accounts = $this->getDoctrine()->getRepository('FuentesWorksNickelTrackerBundle:Account')
                    ->findAll();

            $parameters = array_merge($parameters, array('accounts' => $accounts));
        }

        if(!array_key_exists('categories', $parameters))
        {
            $categories = $this->getDoctrine()->getRepository('FuentesWorksNickelTrackerBundle:Category')
                ->findAll();
            $parameters = array_merge($parameters, array('categories' => $categories));
        }

        return $this->container->get('templating')->renderResponse($view, $parameters, $response);
    }

}