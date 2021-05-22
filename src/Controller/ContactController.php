<?php

namespace App\Controller;

use App\Form\Data\ContactData;
use App\Form\Type\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/kontakt", name="site_contact")
     */
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class, new ContactData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ContactData $data */
            $data = $form->getData();

            $email = (new TemplatedEmail())
                ->from($this->getParameter('mailer.from'))
                ->to($this->getParameter('mailer.contact_recipent'))
                ->replyTo($data->email)
                ->subject('Kawiarnia Kaff - wiadomość z formularza kontaktowego')
                ->htmlTemplate('email/contact.html.twig')
                ->context([
                    'sender' => $data->email,
                    'content' => $data->content,
                ]);

            $mailer->send($email);

            $this->addFlash('success', 'Wiadomość została wysłana!');

            return $this->redirectToRoute('site_homepage');
        }

        return $this->render('site/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
