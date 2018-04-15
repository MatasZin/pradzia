<?php

namespace App\Controller;

use App\Entity\Services;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ServiceController extends Controller {
    /**
     * @Route("/Services", name="Services")
     * @Method({"GET", "POST"})
     */
    public function index()
    {
        $services = $this->getDoctrine()->getRepository(Services::class)->findAll();
        return $this->render('Services/index.html.twig', array ('services' =>$services));
    }

    /**
     * @Route("/Services/new", name="new_service")
     * Method({"GET", "POST"})
     */
    public function new(Request $request){
        $service = new Services();
        $form = $this->createFormBuilder($service)
            ->add('title', TextType::class, array(
                'attr'=>array('class' => 'simple-input')))
            ->add('price', NumberType::class, array(
                'required' => false,
                'attr'=>array('class' => 'simple-input')))
            ->add('description', TextareaType::class,array(
                'required' => false,
                'attr' =>array('class' => 'simple-input')))
            ->add('save', SubmitType::class, array(
                'label' => 'Create',
                'attr' => array('class' => 'modern')))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $service = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($service);
            $entityManager->flush();
            return $this->redirectToRoute('Services');
        }

        return $this->render('Services/new.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/Services/edit/{id}", name="edit_service")
     * Method({"GET", "POST"})
     */
    public function edit(Request $request, $id){
        $service = new Services();
        $service=$this->getDoctrine()->getRepository(Services::class)->find($id);

        $form = $this->createFormBuilder($service)
            ->add('title', TextType::class, array(
                'attr'=>array('class' => 'simple-input')))
            ->add('price', NumberType::class, array(
                'required' => false,
                'attr'=>array('class' => 'simple-input')))
            ->add('description', TextType::class,array(
                'required' => false,
                'attr' =>array('class' => 'simple-input')))
            ->add('save', SubmitType::class, array(
                'label' => 'Confirm',
                'attr' => array('class' => 'modern')))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('Services');
        }

        return $this->render('Services/edit.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/Services/remove{id}", name="remove_service")
     * @Method({"GET"})
     */
    public function remove($id) {
        $service = $this->getDoctrine()->getRepository(Services::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($service);
        $entityManager->flush();
        return $this->index();
    }

    /**
     * @Route("/Services/{id}", name="service_show")
     */
    public function show($id)
    {
        $service=$this->getDoctrine()->getRepository(Services::class)->find($id);
        return $this->render('Services/show.html.twig', array ('service' => $service));
    }
}