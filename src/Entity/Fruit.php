<?php

namespace App\Entity;

use App\Repository\FruitRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: FruitRepository::class)]
#[ORM\Table(name: '`fruits`')]
class Fruit
{
  #[ORM\Id]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255, unique: false)]
  private ?string $genus = null;

  #[ORM\Column(length: 255, unique: false)]
  private ?string $name = null;

  #[ORM\Column(length: 255, unique: false)]
  private ?string $family = null;

  #[ORM\Column(length: 255, unique: false)]
  private ?string $itemorder = null;

  #[ORM\Column]
  private ?float $carbohydrates = null;

  #[ORM\Column]
  private ?float $protein   = null;

  #[ORM\Column]
  private ?float $fat = null;

  #[ORM\Column]
  private ?float $calories = null;

  #[ORM\Column]
  private ?float $sugar = null;
  
  #[ORM\Column]
  private ?bool $favourite = false;

  private $fruits;

  public function __construct()
  {
    $this->fruits = new ArrayCollection();
  }


  public function setFruit(array $fruit): self
  {
    $this->id = $fruit['id'];
    $this->genus = $fruit['genus'];
    $this->family = $fruit['family'];
    $this->itemorder = $fruit['order'];
    $this->name = $fruit['name'];
    $this->carbohydrates = $fruit['nutritions']['carbohydrates'];
    $this->protein = $fruit['nutritions']['protein'];
    $this->fat = $fruit['nutritions']['fat'];
    $this->calories = $fruit['nutritions']['calories'];
    $this->sugar = $fruit['nutritions']['sugar'];
    return $this;
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getGenus(): string
  {
    return $this->genus;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function getFamily(): string
  {
    return $this->family;
  }

  public function getOrder(): string
  {
    return $this->itemorder;
  }

  public function getCarbohydrates(): float
  {
    return $this->carbohydrates;
  }

  public function getProtein(): float
  {
    return $this->protein;
  }

  public function getFat(): float
  {
    return $this->fat;
  }

  public function getCalories(): float
  {
    return $this->calories;
  }

  public function getSugar(): float
  {
    return $this->sugar;
  }

  public function getFavourite(): bool
  {
    return $this->favourite;
  }

  public function setFavourite(bool $isFavourite): self
  {
    $this->favourite = $isFavourite;

    return $this;
  }
}
