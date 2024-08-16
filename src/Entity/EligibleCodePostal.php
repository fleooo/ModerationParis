<?php

namespace App\Entity;

use App\Repository\EligibleCodePostalRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EligibleCodePostalRepository::class)]
class EligibleCodePostal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 5)]
    private ?string $codePostal = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }
}
