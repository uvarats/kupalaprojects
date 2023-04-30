<?php

namespace App\Entity;

use App\Repository\EducationGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: EducationGroupRepository::class)]
class EducationGroup
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?UuidInterface $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'educationGroup', targetEntity: EducationSubGroup::class, cascade: ['persist', 'remove'])]
    private Collection $subGroups;

    public function __construct()
    {
        $this->subGroups = new ArrayCollection();
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, EducationSubGroup>
     */
    public function getSubGroups(): Collection
    {
        return $this->subGroups;
    }

    public function addSubGroup(EducationSubGroup $subGroup): self
    {
        if (!$this->subGroups->contains($subGroup)) {
            $this->subGroups->add($subGroup);
            $subGroup->setEducationGroup($this);
        }

        return $this;
    }

    public function removeSubGroup(EducationSubGroup $subGroup): self
    {
        if ($this->subGroups->removeElement($subGroup)) {
            // set the owning side to null (unless already changed)
            if ($subGroup->getEducationGroup() === $this) {
                $subGroup->setEducationGroup(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return (string)$this->getName();
    }


}
