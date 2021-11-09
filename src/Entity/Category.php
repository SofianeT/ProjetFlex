<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Cours::class, mappedBy="category")
     */
    private $Namecours;

    public function __construct()
    {
        $this->Namecours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Cours[]
     */
    public function getNamecours(): Collection
    {
        return $this->Namecours;
    }

    public function addNamecour(Cours $namecour): self
    {
        if (!$this->Namecours->contains($namecour)) {
            $this->Namecours[] = $namecour;
            $namecour->setCategory($this);
        }

        return $this;
    }

    public function removeNamecour(Cours $namecour): self
    {
        if ($this->Namecours->removeElement($namecour)) {
            // set the owning side to null (unless already changed)
            if ($namecour->getCategory() === $this) {
                $namecour->setCategory(null);
            }
        }

        return $this;
    }
}
