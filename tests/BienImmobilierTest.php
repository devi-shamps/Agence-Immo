<?php

namespace App\Tests;

use App\Entity\BienImmobilier;
use App\Entity\Piece;
use App\Entity\TypePiece;
use PHPUnit\Framework\TestCase;

class BienImmobilierTest extends TestCase
{
    public function testSurfaceHabitable(): void
    {
        $bienImmobilier = new BienImmobilier();

        $pieceHabitable = new Piece();
        $pieceHabitable->setSurface(20);
        $typePieceHabitable = new TypePiece();
        $typePieceHabitable->setSurfaceHabitable(true);
        $pieceHabitable->setTypePiece($typePieceHabitable);

        $pieceHabitable2 = new Piece();
        $pieceHabitable2->setSurface(20);
        $typePieceHabitable2 = new TypePiece();
        $typePieceHabitable2->setSurfaceHabitable(true);
        $pieceHabitable2->setTypePiece($typePieceHabitable2);

        $bienImmobilier->addPiece($pieceHabitable2);
        $bienImmobilier->addPiece($pieceHabitable);

        $this->assertEquals(40, $bienImmobilier->surfaceHabitable());
    }

    public function testSurfaceNonHabitable(): void
    {
        $bienImmobilier = new BienImmobilier();

        $pieceNonHabitable = new Piece();
        $pieceNonHabitable->setSurface(30);
        $typePieceNonHabitable = new TypePiece();
        $typePieceNonHabitable->setSurfaceHabitable(false);
        $pieceNonHabitable->setTypePiece($typePieceNonHabitable);

        $bienImmobilier->addPiece($pieceNonHabitable);

        $this->assertEquals(30, $bienImmobilier->surfaceNonHabitable());
    }
}
