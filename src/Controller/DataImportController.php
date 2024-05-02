<?php

namespace App\Controller;

use App\Entity\BienImmobilier;
use App\Entity\Piece;
use App\Entity\TypePiece;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use RandomLib\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class DataImportController extends AbstractController
{
    #[Route('/dataimport/users', name: 'app_data_import')]
    public function index(ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $usersjson = file_get_contents('./users.json');
        $users = json_decode($usersjson,true);

        foreach ($users as $user) {
            $newUser = new User();
            $newUser->setNom($user['nom']);
            $newUser->setEmail($user['email']);
            $newUser->setTel($user['tel']);
            $newUser->setCarteAgentImmo($user['carteAgentImmo']);

            $factory = new Factory;
            $generator = $factory->getMediumStrengthGenerator();
            $randomInt = $generator->generateInt(5, 15);
            $randomString = $generator->generateString(8, 'abcdef');
            $mdpGenerer = $randomString . $randomInt;

            $createdUsers[$newUser->getNom()] = $mdpGenerer;

            //Création du mot de passe Hasher
            $hashedPassword = $passwordHasher->hashPassword(
                $newUser,
                $mdpGenerer
            );
            $newUser->setPassword($hashedPassword);

            $doctrine->getManager()->persist($newUser);
            $doctrine->getManager()->flush();
        }

        return $this->render('data_import/index.html.twig', [
            'controller_name' => 'DataImportController',
            'createdUsers' => $createdUsers,

        ]);
    }

    #[Route('/dataimport/biensimmo', name: 'app_data_import_biensimmo')]
    public function importbienimmo(ManagerRegistry $doctrine): Response
    {
        $biensimmojson = file_get_contents('./biensimmo.json');
        $biensimmo = json_decode($biensimmojson,true);

        foreach ($biensimmo as $bienimmo) {
            $newBienImmo = new BienImmobilier();

            $user = $doctrine->getRepository(User::class)->findOneBy(['id' => $bienimmo['user_id']]);
            $newBienImmo->setUser($user);
            $newBienImmo->setRue($bienimmo['rue']);
            $newBienImmo->setVille($bienimmo['ville']);
            $newBienImmo->setCodePostal($bienimmo['code_postal']);

            $doctrine->getManager()->persist($newBienImmo);
            $doctrine->getManager()->flush();
        }

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    #[Route('/dataimport/pieces', name: 'app_data_import_pieces')]
    public function importpieces(ManagerRegistry $doctrine): Response
    {
        $piecesjson = file_get_contents('./pieces.json');
        $pieces = json_decode($piecesjson,true);

        foreach ($pieces as $piece) {
            $newPiece = new Piece();
            $newPiece->setSurface($piece['surface']);
            $typepieces = $doctrine->getRepository(TypePiece::class)->findOneBy(['id' => $piece['type_piece_id']]);
            $newPiece->setTypePiece($typepieces);
            $bienimmos = $doctrine->getRepository(BienImmobilier::class)->findOneBy(['id' => $piece['bien_immobilier_id']]);
            $newPiece->setBienImmobilier($bienimmos);


            $doctrine->getManager()->persist($newPiece);
            $doctrine->getManager()->flush();
        }

        return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
    }
}
