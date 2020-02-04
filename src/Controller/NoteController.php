<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\User;
use App\Form\NoteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class NoteController extends AbstractController
{
  /**
   * @Route("/note/delete/{id}", name="note_delete")
   * @param Note $note
   * @return RedirectResponse
   */
    public function delete(Note $note)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($note);
        $entityManager->flush();

        return $this->redirectToRoute('note');
    }

  /**
   * @Route("/note/add", name="note_add")
   * @param Request $request
   * @return RedirectResponse|Response
   */
    public function add(Request $request)
    {
      /** @var User $user */
      $user = $this->getUser();

      $note = new Note();
      $note->setUser($user);
      $note->setEssay('');
      $note->setTitle('');

      $form = $this->createForm(NoteType::class, $note);

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $note = $form->getData();

         $entityManager = $this->getDoctrine()->getManager();
         $entityManager->persist($note);
         $entityManager->flush();

        return $this->redirectToRoute('note');
      }

      return $this->render('note/add.html.twig', [
        'form' => $form->createView(),
      ]);
    }

  /**
   * @Route("/note/add/{id}", name="note_update")
   * @param Request $request
   * @param Note $note
   * @return RedirectResponse|Response
   */
    public function update(Request $request, Note $note)
    {
      $form = $this->createForm(NoteType::class, $note);

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();

  //      $note = $form->getData();
        $entityManager->flush();

        return $this->redirectToRoute('note');
      }

      return $this->render('note/add.html.twig', [
        'form' => $form->createView(),
      ]);
    }

  /**
   * @Route("/note", name="note")
   */
    public function index()
    {
        $user = $this->getUser();
        $notes = $user->getNotes();

        return $this->render('note/index.html.twig', [
            'controller_name' => 'NoteController',
            'notes' => $notes
        ]);
    }
}
