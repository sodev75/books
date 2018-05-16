<?php
/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is licensed exclusively to Inovia Team.
 *
 * @copyright  Copyright (c) 2018 Inovia Team (http://www.inovia.fr)
 * @license      All rights reserved
 *
 */

namespace App\DataFixtures;


use App\Entity\Book;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadBookData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $book = new Book();
        $book->setTitle('Bel-Ami');
        $book->setAuthor('Guy de Maupassant');
        $book->setIsbn('2266163744');
        $book->setGoogleBooksId('cWpXPgAACAAJ');
        $book->setLinkSmallImageBook('http://books.google.com/books/content?id=cWpXPgAACAAJ&printsec=frontcover&img=1&zoom=5&source=gbs_api');
        $book->setIsInTheLibrary(true);
        $book->setMainCategory('Fiction');
        $book->setPageCount(408);
        $book->setProposedBy('Sophie B');
        $book->setPublisher('Distribooks');
        $book->setSubject('Le monde est une mascarade où le succès va de préférence aux crapules. La réussite, les honneurs, les femmes et le pouvoir: le monde n\'a guère changé. On rencontre toujours - moins les moustaches - dans les salles de rédaction ou ailleurs, de ces jeunes aventuriers de l\'arrivisme et du sexe. Comme Flaubert, mais en riant, Maupassant disait de son personnage, l\'odieux Duroy : \" Bel-Ami, c\'est moi.\" Et pour le cynisme, la fureur sensuelle, l\'athéisme, la peur de la mort, ils se ressemblaient assez. Mais Bel-Ami ne savait pas écrire, et devenait l\'amant et le négrier d\'une femme talentueuse et brillante. Maupassant, lui, était un immense écrivain. Universel, déjà, mais par son réalisme, ses obsessions et ses névroses, encore vivant aujourd\'hui.');
        $book->updateTimestamps();
        $manager->persist($book);
        $manager->flush();
        $this->addReference('book1', $book);
    }

    public function getOrder()
    {
        return 1;
    }


}