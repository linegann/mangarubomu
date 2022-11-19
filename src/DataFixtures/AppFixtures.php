<?php

namespace App\DataFixtures;

use App\Entity\Membre;
use App\Entity\Album;
use App\Entity\Manga;
use App\Entity\Character;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private static function mangasDataGenerator()
    {
        yield ["Shonen", null];
        yield ["Seinen", null];
        yield ["Shojo", null];
        yield ["Bungou Stray Dogs", "Seinen"];
        yield ["Jujutsu Kaisen", "Shonen"];
        yield ["Komi can't communicate", "Shonen"];
        yield ["Chainsaw Man", "Shonen"];
        yield ["Persona 5", "Seinen"];
        yield ["Hunter x Hunter", "Shonen"];
        yield ["Jojo's Bizarre Adventure", "Seinen"];
        yield ["Classroom of the Elite", "Seinen"];
    }

    private static function membresDataGenerator()
    {
        yield ["linegann", "Cringe Lord"];
        yield ["Ayris", "VP au bord du gouffre"];
        yield ["Sulray", "Eliott"];
    }

    private static function albumsDataGenerator()
    {
        yield ["Album de linegann", "linegann"];
        yield ["Album de Ayris", "Ayris"];
        yield ["Album de Sulray", "Sulray"];
    }

    private static function charactersDataGenerator()
    {
        yield ["Osamu Dazai", "Male", "Album de linegann", "Bungou Stray Dogs"];
        yield ["Gojo Satoru", "Male", "Album de linegann", "Jujutsu Kaisen"];
        yield ["Power", "Female", "Album de linegann", "Chainsaw Man"];
        yield ["Denji", "Male", "Album de linegann", "Chainsaw Man"];
        yield ["Makima", "Female", "Album de Ayris", "Chainsaw Man"];
        yield ["Joker", "Female", "Album de Ayris", "Persona 5"];
        yield ["Kurapika", "Male", "Album de linegann", "Hunter x Hunter"];
        yield ["Hisoka", "Male", "Album de linegann", "Hunter x Hunter"];
        yield ["Jolyne Cujoh", "Female", "Album de Sulray", "Jojo's Bizarre Adventure"];
        yield ["Gyro Zeppeli", "Male", "Album de Sulray", "Jojo's Bizarre Adventure"];
        yield ["Komi Shouko", "Female", "Album de linegann", "Komi can't communicate"];
        yield ["Kiyotaka Ayanokouji", "Male", "Album de Ayris", "Classroom of the Elite"];
        
    }

    public function load(ObjectManager $manager): void
    {
        $membreRepo = $manager->getRepository(Membre::class);
        $albumRepo = $manager->getRepository(Album::class);
        $mangaRepo = $manager->getRepository(Manga::class);
        $characterRepo = $manager->getRepository(Character::class);

        foreach (self::membresDataGenerator() as [$name, $description]) {
            $membre = new Membre();
            $membre->setName($name);
            $membre->setDescription($description);
            $manager->persist($membre);
        }
        $manager->flush();

        foreach (self::albumsDataGenerator() as [$title, $membre]) {
            $membre = $membreRepo->findOneBy(['name' => $membre]);
            $album = new Album();
            $album ->setTitle($title);
            $membre->addAlbum($album);
            $manager->persist($album);
        }
        $manager->flush();

        foreach (self::charactersDataGenerator() as [$name, $gender, $albumTitle, $manga]) {
            $album = $albumRepo->findOneBy(["title" => $albumTitle]);
            $character = new Character();
            $character->setName($name);
            $character->setGender($gender);
            $character->setManga($manga);
            $album->addCharacter($character);
            $manager->persist($album);
            $manager->persist($character);
        }
        $manager->flush();
    }
}
