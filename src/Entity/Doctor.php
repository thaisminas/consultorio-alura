<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\This;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DoctorRepository")
 */
class Doctor implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $crm;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Speciality")
     * @ORm\JoinColumn(nullable=false)
     */
    private $speciality;




    public function getId(): int
    {
        return $this->id;
    }


    public function getCrm(): int
    {
        return $this->crm;
    }

    public function setCrm(int $crm): void
    {
        $this->crm = $crm;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSpeciality(): ?Speciality
    {
        return $this->speciality;
    }

    public function setSpeciality(?Speciality $speciality): void
    {
        $this->speciality = $speciality;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'crm' => $this->getCrm(),
            'name' => $this->getName(),
            'specialityId' => $this->getSpeciality()->getId(),
            '_link' => [
                [
                    'rel' => 'self',
                    'path' => '/medicos/' . $this->getId(),
                ],
                [
                    'rel' => 'especialidade',
                    'path' => '/especialidade' . $this->getSpeciality()->getId()
                ]
            ]
        ];
    }
}