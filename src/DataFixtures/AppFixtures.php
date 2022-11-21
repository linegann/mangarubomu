<?php

namespace App\DataFixtures;

use App\Entity\Membre;
use App\Entity\Album;
use App\Entity\Manga;
use App\Entity\Character;
use App\Entity\Team;
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
        yield ["Saiki Kusuo no Sai Nan", "Shonen"];
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
        yield ["Osamu Dazai", "Male", "Album de linegann", ["Seinen", "Bungou Stray Dogs"]];
        yield ["Gojo Satoru", "Male", "Album de linegann", ["Jujutsu Kaisen", "Shonen"]];
        yield ["Power", "Female", "Album de linegann", ["Chainsaw Man", "Shonen"]];
        yield ["Denji", "Male", "Album de linegann", ["Chainsaw Man", "Shonen"]];
        yield ["Makima", "Female", "Album de Ayris", ["Chainsaw Man", "Shonen"]];
        yield ["Joker", "Female", "Album de Ayris", ["Persona 5", "Seinen"]];
        yield ["Kurapika", "Male", "Album de linegann", ["Hunter x Hunter", "Shonen"]];
        yield ["Hisoka", "Male", "Album de linegann", ["Hunter x Hunter", "Shonen"]];
        yield ["Jolyne Cujoh", "Female", "Album de Sulray", ["Jojo's Bizarre Adventure", "Seinen"]];
        yield ["Gyro Zeppeli", "Male", "Album de Sulray", ["Jojo's Bizarre Adventure", "Seinen"]];
        yield ["Komi Shouko", "Female", "Album de linegann", ["Komi can't communicate", "Shonen"]];
        yield ["Kiyotaka Ayanokouji", "Male", "Album de Ayris", ["Classroom of the Elite", "Seinen"]];
        yield ["Saiki Kusuo", "Male", "Album de Sulray", ["Saiki Kusuo no Sai Nan", "Shonen"]];
        
    }

    private static function teamsDataGenerator()
    {
        yield ["Les zouzous", true, ["Osamu Dazai", "Gojo Satoru", "Kurapika"], "linegann"];
        yield ["La meilleure", true, ["Makima"], "Ayris"];
        yield ["Le S", true, ["Gyro Zeppeli"], "Sulray"];
    }

    public function load(ObjectManager $manager): void
    {
        $membreRepo = $manager->getRepository(Membre::class);
        $albumRepo = $manager->getRepository(Album::class);
        $mangaRepo = $manager->getRepository(Manga::class);
        $characterRepo = $manager->getRepository(Character::class);

        foreach (self::mangasDataGenerator() as [$label, $parentName]) {
            $manga = new Manga();
            $manga->setLabel($label);
            if ($parentName !== null) {
                $parent = $mangaRepo->findOneBy(['label' => $parentName]);
                $manga->setParent($parent);
            }
            $manager->persist($manga);
            $manager->flush();
        }

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

        foreach (self::charactersDataGenerator() as [$name, $gender, $albumTitle, $mangas]) {
            $character = new Character();
            $character->setName($name);
            $character->setGender($gender);
            if ($albumTitle !== null) {
                $album = $albumRepo->findOneBy(["title" => $albumTitle]);
                $album->addCharacter($character);
            }
            $manager->persist($album);

            foreach ($mangas as $mangaLabel) {
                $manga = $mangaRepo->findOneBy(['label' => $mangaLabel]);
                $manga->addCharacter($character);
                $character->addManga($manga);
            }
            $manager->persist($manga);
            $manager->persist($character);

        }
        $manager->flush();

        foreach (self::teamsDataGenerator() as [$description, $published, $characters, $creatorName]) {
            $creator = $membreRepo->findOneBy(['name' => $creatorName]);
            $team = new Team();
            $team->setDescription($description);
            $team->setPublished($published);
            $team->setCreator($creator);

            foreach ($characters as $characterName) {
                $character = $characterRepo->findOneBy(['name' => $characterName]);
                $character->addTeam($team);
                $team->addCharacter($character);
                $manager->persist($character);
                $manager->persist($team);
            }
        }
        $manager->flush();
    }
}
