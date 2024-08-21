<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\SlugTrait;
use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
class Categories
{
    use CreatedAtTrait;
    use SlugTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom de la catégorie ne peut pas être vide')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Le nom de la catégorie doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le nom de la catégorie ne peut pas contenir plus de {{ limit }} caractères'
    )]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $categoryOrder = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'subCategories')]
    #[ORM\JoinColumn(nullable: true)]
    private ?self $parent = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent', orphanRemoval: true)]
    private Collection $subCategories;

    /**
     * @var Collection<int, Products>
     */
    #[ORM\OneToMany(targetEntity: Products::class, mappedBy: 'categories')]
    private Collection $products;

    /**
     * @var Collection<int, CategoryImage>
     */
    #[ORM\OneToMany(targetEntity: CategoryImage::class, mappedBy: 'categories', orphanRemoval: true, cascade: ['persist'])]
    private Collection $categoryImages;

    public function __construct()
    {
        $this->subCategories = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->categoryImages = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getCategoryOrder(): ?int
    {
        return $this->categoryOrder;
    }

    public function setCategoryOrder(?int $categoryOrder): static
    {
        $this->categoryOrder = $categoryOrder;
        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSubCategories(): Collection
    {
        return $this->subCategories;
    }

    public function addSubCategory(self $subCategory): static
    {
        if (!$this->subCategories->contains($subCategory)) {
            $this->subCategories->add($subCategory);
            $subCategory->setParent($this);
        }
        return $this;
    }

    public function removeSubCategory(self $subCategory): static
    {
        if ($this->subCategories->removeElement($subCategory)) {
            if ($subCategory->getParent() === $this) {
                $subCategory->setParent(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Products>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Products $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setCategories($this);
        }
        return $this;
    }

    public function removeProduct(Products $product): static
    {
        if ($this->products->removeElement($product)) {
            if ($product->getCategories() === $this) {
                $product->setCategories(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, CategoryImage>
     */
    public function getCategoryImages(): Collection
    {
        return $this->categoryImages;
    }

    public function addCategoryImage(CategoryImage $categoryImage): static
    {
        if (!$this->categoryImages->contains($categoryImage)) {
            $this->categoryImages->add($categoryImage);
            $categoryImage->setCategories($this);
        }
        return $this;
    }

    public function removeCategoryImage(CategoryImage $categoryImage): static
    {
        if ($this->categoryImages->removeElement($categoryImage)) {
            if ($categoryImage->getCategories() === $this) {
                $categoryImage->setCategories(null);
            }
        }
        return $this;
    }
}