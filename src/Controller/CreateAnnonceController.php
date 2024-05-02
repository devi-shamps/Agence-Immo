<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\BienImmobilier;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CreateAnnonceController extends AbstractController
{
    #[Route('/createannonce', name: 'app_create_annonce')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $nouvelleAnnonce = new Annonce();
        $nouvelleAnnonce->setTitre('Maison à loué pour la fin de l\'année');
        $nouvelleAnnonce->setDate(new \DateTime('2021-12-31'));
        $nouvelleAnnonce->setPrixM2Habitable(100);

        $bienImmobilier = $doctrine->getRepository(BienImmobilier::class)->find(1);
        $nouvelleAnnonce->setBienImmobilier($bienImmobilier);

        $titre = $nouvelleAnnonce->getTitre();
        $prix = $nouvelleAnnonce->prix();
        $adresse = $bienImmobilier->getRue() . ' ' . $bienImmobilier->getVille() . ' ' . $bienImmobilier->getCodePostal();
        $surfaceHabitable = $bienImmobilier->surfaceHabitable();
        $surfaceNonHabitable = $bienImmobilier->surfaceNonHabitable();
        $pieces = $bienImmobilier->getPieces();

        return $this->render('create_annonce/index.html.twig', [
            'controller_name' => 'CreateAnnonceController',
            'titre' => $titre,
            'prix' => $prix,
            'surfaceHabitable' => $surfaceHabitable,
            'surfaceNonHabitable' => $surfaceNonHabitable,
            'pieces' => $pieces,
            'adresse' => $adresse,
        ]);
    }
}
