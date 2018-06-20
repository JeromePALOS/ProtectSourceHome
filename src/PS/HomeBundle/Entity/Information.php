<?php

namespace PS\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Information
 *
 * @ORM\Table(name="information")
 * @ORM\Entity(repositoryClass="PS\HomeBundle\Repository\InformationRepository")
 * @ORM\HasLifecycleCallbacks()
 
 */
class Information
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


	
	
    /**
     * @var string
     *
     * @ORM\Column(name="typeInformation", type="string", length=255, nullable=true)
     */
    private $typeInformation;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=255, nullable=true)
     */
    private $statut;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    private $text;

    /**
	* @ORM\OneToOne(targetEntity="PS\HomeBundle\Entity\Files", cascade={"persist", "remove"})
	* @Assert\Valid()
	*/
	private $files;
	    /**
     * @var string
     *
     * @ORM\Column(name="keyProject", type="text")
     */
    private $keyProject;

	
	public function __construct(){
		$this->setStatut("Wait");
	}
	
	
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set typeInformation
     *
     * @param string $typeInformation
     *
     * @return Information
     */
    public function setTypeInformation($typeInformation)
    {
        $this->typeInformation = $typeInformation;

        return $this;
    }

    /**
     * Get typeInformation
     *
     * @return string
     */
    public function getTypeInformation()
    {
        return $this->typeInformation;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return Information
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Information
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }



    /**
     * Set files
     *
     * @param \PS\HomeBundle\Entity\Files $files
     *
     * @return Information
     */
    public function setFiles(\PS\HomeBundle\Entity\Files $files = null)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * Get files
     *
     * @return \PS\HomeBundle\Entity\Files
     */
    public function getFiles()
    {
        return $this->files;
    }


    /**
     * Set keyProject
     *
     * @param string $keyProject
     *
     * @return Information
     */
    public function setKeyProject($keyProject)
    {
        $this->keyProject = $keyProject;

        return $this;
    }

    /**
     * Get keyProject
     *
     * @return string
     */
    public function getKeyProject()
    {
        return $this->keyProject;
    }
}
