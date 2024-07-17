<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoriesFixtures extends Fixture
{
    private $counter = 1;

    public function __construct(private SluggerInterface $slugger){}

    public function load(ObjectManager $manager): void
    {
        $parent = $this->createCategory('Avec Moderations',null, $manager);

        $this->createCategory('Vodkas',$parent, $manager);
        $this->createCategory('Whiskys',$parent, $manager);
        $this->createCategory('Rhums',$parent, $manager);
        $this->createCategory('Gins',$parent,$manager);
        $this->createCategory('Vins',$parent,$manager);
        $this->createCategory('Cocktails',$parent, $manager);
        $this->createCategory('Bieres',$parent, $manager);
        $this->createCategory('Champagnes',$parent, $manager);
        $this->createCategory('Tequilas',$parent, $manager);
        $this->createCategory('Aperitifs',$parent, $manager);
        $this->createCategory('Puff 0%',$parent, $manager);
        $this->createCategory('Puff 1.7%',$parent, $manager);
        
        $parent = $this->createCategory('Sans Moderations',null, $manager);

        $this->createCategory('Softs',$parent, $manager);
        $this->createCategory('Snacks',$parent, $manager);
        $this->createCategory('Depannages',$parent, $manager);

        $manager->flush();
    }
    public function createCategory(string $name, Categories $parent = null, ObjectManager $manager)
    {
        $category = new Categories();
        $category->setName($name);
        $category->setSlug($this->slugger->slug($category->getName())->lower());
        $category->setParent($parent);
        $manager->persist($category);

        $this->addReference('cat-'.$this->counter, $category);
        $this->counter++;

        return $category;
    }
}
