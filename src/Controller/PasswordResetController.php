<?php

namespace App\Controller;

use App\Entity\Factory\UserFactory;
use App\Entity\User;
use App\Form\Data\PasswordResetData;
use App\Form\Data\PasswordResetRequestData;
use App\Form\Data\UserRegistrationData;
use App\Form\Type\PasswordResetRequestType;
use App\Form\Type\PasswordResetType;
use App\Form\Type\UserRegistrationType;
use App\Repository\UserRepository;
use App\Security\Authenticator\SiteLoginFormAuthenticator;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Message;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class PasswordResetController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/przypomnij-haslo", name="site_request_password_reset")
     *
     * @param  Request         $request
     * @param  MailerInterface $mailer
     *
     * @return Response
     */
    public function request(Request $request, MailerInterface $mailer): Response
    {
        if (null !== $this->getUser()) {
            return $this->redirectToRoute('site_homepage');
        }

        $form = $this->createForm(PasswordResetRequestType::class, new PasswordResetRequestData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var PasswordResetRequestData $data */
            $data = $form->getData();

            $user = $this->userRepository->findOneByEmail($data->email);

            if ($user instanceof User) {
                $user->setPasswordResetToken(uniqid());

                $email = (new TemplatedEmail())
                    ->from($this->getParameter('mailer.from'))
                    ->to($user->getEmail())
                    ->subject('Kawiarnia Kaff - resetowanie hasła')
                    ->htmlTemplate('email/password_reset_request.html.twig')
                    ->context([
                        'token' => $user->getPasswordResetToken(),
                    ]);

                $mailer->send($email);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();
            }

            $this->addFlash('success', 'Jeśli podany adres email istnieje w bazie, została wysłana wiadomość z dalszymi instrukcjami.');

            return $this->redirectToRoute('site_homepage');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Niepoprawne dane formularza!');
        }

        return $this->render('site/password_reset_request.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/przypomnij-haslo/{token}", name="site_password_reset")
     */
    public function reset(Request $request, UserPasswordEncoderInterface $passwordEncoder, string $token)
    {
        $user = $this->userRepository->findOneByPasswordResetToken($token);

        if (!$user instanceof User) {
            $this->addFlash('error', 'Token resetu hasła wygasł. Przejdź ponownie przez proces przypomnienia hasła.');

            return $this->redirectToRoute('site_homepage');
        }

        $form = $this->createForm(PasswordResetType::class, new PasswordResetData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var PasswordResetData $data */
            $data = $form->getData();

            $user->setPasswordResetToken(null);
            $user->setPassword($passwordEncoder->encodePassword($user, $data->password));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('success', 'Pomyślnie zmieniono hasło.');

            return $this->redirectToRoute('site_login');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Niepoprawne dane formularza!');
        }

        return $this->render('site/password_reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
