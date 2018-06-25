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
				
				
				
				$em->persist($information);
				$em->flush();

				$request->getSession()->getFlashBag()->add('notice', 'Information added, thanks for your contribution.');

				return $this->redirectToRoute('ps_home_homepage');
				
			}else{
				$validator = $this->get('validator');
				$listErrors = $validator->validate($information);

				// Si $listErrors n'est pas vide, on affiche les erreurs
				if(count($listErrors) > 0) {
					$request->getSession()->getFlashBag()->add('notice', (string) $listErrors);
				  // $listErrors est un objet, sa méthode __toString permet de lister joliement les erreurs
				 
				}
			}
				
				 
		}

        return $this->render('@PSHome\Default\index.html.twig', array(
			'form' => $form->createView(),
		));
    }
}
