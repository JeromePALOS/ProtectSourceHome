<?php

namespace PS\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Finder\Exception\AccessDeniedException;

use PS\HomeBundle\Entity\Information;

use PS\HomeBundle\Form\InformationType;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {

		$em = $this->getDoctrine()->getManager();
		$information = new Information();

		$form = $this->get('form.factory')->create(InformationType::class, $information);
		
		
		if ($request->isMethod('POST')) {
			if ($form->handleRequest($request)->isValid()){
				
				
				$information->setTypeInformation('text');
				$em->persist($information);
				$em->flush();

				$request->getSession()->getFlashBag()->add('notice', 'Information added, thanks for your contribution.');

			}
				
				 
		}

        return $this->render('@PSHome\Default\index.html.twig', array(
			'form' => $form->createView(),
		));
    }
}
