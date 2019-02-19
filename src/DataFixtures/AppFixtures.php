<?php

namespace App\DataFixtures;

use App\Entity\Core\Dsm5Category;
use App\Entity\Core\Dsm5Disorder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $handle = fopen(__DIR__.'/data/DSM5Categories.csv', 'r');

        if($handle !== false) {
            while ($data = fgetcsv($handle, 0, '|')) {
                $dsm5Category = new Dsm5Category();
                $dsm5Category->setName($data[0]);

                $manager->persist($dsm5Category);
                $manager->flush();
            }
        }

        fclose($handle);

        $handle = fopen(__DIR__.'/data/DSM5Codes.csv', 'r');

        if($handle !== false) {
            while ($data = fgetcsv($handle, 0, '|')) {
                $category = $manager->getRepository('App\Entity\Core\Dsm5Category')->find($data[4]);

                $dsm5Disorder = new Dsm5Disorder();
                $dsm5Disorder->setDsmId($data[0]);
                $dsm5Disorder->setIcd9($data[1]);
                $dsm5Disorder->setIcd10($data[2]);
                $dsm5Disorder->setDescription($data[3]);
                $dsm5Disorder->setDsm5Category($category);

                $manager->persist($dsm5Disorder);
                $manager->flush();


            }
        }

        fclose($handle);
    }
}
