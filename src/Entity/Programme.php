<?php

namespace App\Entity;

use App\Controller\Dto\ProgrammeDto;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class Programme
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Regex("/^[A-Z]+/")
     */
    public string $name = '';

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    public string $description = '';

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     */
    private \DateTime $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     */
    private \DateTime $endDate;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="trainer_id", referencedColumnName="id")
     */
    private ?User $trainer;

    /**
     * @ORM\ManyToOne(targetEntity="Room")
     * @ORM\JoinColumn(name="room_id", referencedColumnName="id")
     * @Assert\NotBlank
     */
    private Room $room;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="programmes")
     * @ORM\JoinTable(name="programmes_customers")
     * @Assert\Collection
     */
    private Collection $customers;

    /**
     * @ORM\Column(type="boolean")
     */
    public bool $isOnline = false;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): \DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTime $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getTrainer(): ?User
    {
        return $this->trainer;
    }

    public function setTrainer(?User $trainer): self
    {
        $this->trainer = $trainer;

        return $this;
    }

    public function getRoom(): Room
    {
        return $this->room;
    }

    public function setRoom(Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function setCustomers(Collection $customers): self
    {
        $this->customers = $customers;

        return $this;
    }

    public function addCustomer(User $customer): self
    {
        if ($this->customers->contains($customer)) {
            return $this;
        }

        $this->customers->add($customer);
        $customer->addProgramme($this);

        return $this;
    }

    public function removeCustomer(User $customer): self
    {
        if (!$this->customers->contains($customer)) {
            return $this;
        }

        $this->customers->removeElement($customer);
        $customer->removeProgramme($this);

        return $this;
    }

    public static function createFromDto(ProgrammeDto $programmeDto): self
    {
        $programme = new self();
        $programme->name = $programmeDto->name;
        $programme->description = $programmeDto->description;
        $programme->setStartDate($programmeDto->startDate);
        $programme->setEndDate($programmeDto->endDate);
        $programme->setTrainer($programmeDto->trainer);
        $programme->setRoom($programmeDto->room);
        $programme->setCustomers($programmeDto->customers);
        $programme->isOnline = $programmeDto->isOnline;

        return $programme;
    }
}
