<?php
// src/ESCPHomeBundle/Entity/SubmitFile
namespace PS\HomeBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="files")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Files
	{
		/**
		 * @ORM\Column(name="id", type="integer")
		 * @ORM\Id
		 * @ORM\GeneratedValue(strategy="AUTO")
		 */
		private $id;
	  
		/**
		 * @ORM\Column(name="extension", type="string", length=255)
		 */
		private $extension;

		/**
		 * @ORM\Column(name="name", type="string", length=255)
		 */
		private $name;
	  
		/**
		 * @ORM\Column(name="alt", type="string", length=255)
		 */
		private $alt;
		

	  
	  /**
	   * @var UploadedFile  
	* @Assert\File(
	   *     maxSize = "10M",
		 *     mimeTypes = {
				"image/jpeg", 
				"image/gif", 
				"image/png", 
				"image/svg+xml", 
				
				"application/csv",
				"application/x-csv",
				"text/csv",
				"text/comma-separated-values",
				"text/x-comma-separated-values",
				"text/tab-separated-values",
				"text/plain",
				
				"application/pdf", 
				"application/PDX", 
				
				"application/x-rar-compressed", 
				"application/zip", 
				"application/gzip", 
				"application/x-tar", 
				"application/x-7z-compressed", 
				"application/bacnet-xdd+zip",
				"application/prs.hpub+zip",
				"application/vnd.imagemeter.folder+zip",
				"application/vnd.imagemeter.image+zip",
				
				"application/mp4", 
				"video/x-msvideo",
				"audio/ogg",
				"video/ogg",
				"application/ogg",
				"audio/x-wav",
				"video/mp4",
				"video/quicktime",
				"video/avi",
				
				"application/msword",
				"application/vnd.openxmlformats-officedocument.wordprocessingml.document",
				"application/vnd.openxmlformats-officedocument.wordprocessingml.template",
				"application/vnd.ms-word.document.macroEnabled.12",
				
				"application/vnd.ms-excel",
				
				"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
				"application/vnd.openxmlformats-officedocument.spreadsheetml.template",
				"application/vnd.ms-excel.sheet.macroEnabled.12",
				"application/vnd.ms-excel.template.macroEnabled.12",
				"application/vnd.ms-excel.addin.macroEnabled.12",
				"application/vnd.ms-excel.sheet.binary.macroEnabled.12",
				
				"application/vnd.ms-powerpoint",
				
				"application/vnd.openxmlformats-officedocument.presentationml.presentation",
				"application/vnd.openxmlformats-officedocument.presentationml.template",
				"application/vnd.openxmlformats-officedocument.presentationml.slideshow",
				"application/vnd.ms-powerpoint.addin.macroEnabled.12",
				"application/vnd.ms-powerpoint.presentation.macroEnabled.12",
				"application/vnd.ms-powerpoint.template.macroEnabled.12",
				"application/vnd.ms-powerpoint.slideshow.macroEnabled.12",
				
				"application/vnd.ms-access",
				
				
				"application/vnd.oasis.opendocument.presentation",
				"application/vnd.oasis.opendocument.spreadsheet",
				"application/vnd.oasis.opendocument.text",
				"application/epub+zip",
				"image/x-icon",
				"video/mpeg",


			   },
				
		 *     mimeTypesMessage = "Le fichier choisi ne correspond pas à un fichier valide",
		 *     notFoundMessage = "Le fichier n'a pas été trouvé sur le disque",
		 *     uploadErrorMessage = "Erreur dans l'upload du fichier"
		 * )
		 */
		
		private $file;
		// On ajoute cet attribut pour y stocker le nom du fichier temporairement   * @Assert\File(
	   
		private $tempFilename;
	  
	  
	 
	  
	  
		/**
		 * @ORM\PrePersist()
		 * @ORM\PreUpdate()
		 */
		public function preUpload(){
			// Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
			if (null === $this->file) {
			  return;
			}
			// Le nom du fichier est son id, on doit juste stocker également son name
			$this->extension = $this->file->guessextension();
			// Et on génère l'attribut alt de la balise <img>, à la valeur du nom du fichier sur le PC de l'internaute
			$this->alt = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $this->file->getClientOriginalName());
			
			$this->name = $this->alt;
		  }
		  
		  
		  /**
		   * @ORM\PostPersist()
		   * @ORM\PostUpdate()
		   */
		  public function upload()
		  {
			// Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
			if (null === $this->file) {
			  return;
			}
			// Si on avait un ancien fichier (attribut tempFilename non null), on le supprime
			if (null !== $this->tempFilename) {
			  $oldFile = $this->getUploadRootDir().'/['. $this->getId() .']'.$this->tempFilename;
			  if (file_exists($oldFile)) {
				unlink($oldFile);
			  }
			}
			// On déplace le fichier envoyé dans le répertoire de notre choix
			$this->file->move(
			  $this->getUploadRootDir(), // Le répertoire de destination
			  '['. $this->getId() .']' . $this->name  // Le nom du fichier à créer, ici « id.name »
			);
		  }
		  
		  
		  /**
		   * @ORM\PreRemove()
		   */
		  public function preRemoveUpload()
		  {
			// On sauvegarde temporairement le nom du fichier, car il dépend de l'id
			$this->tempFilename = $this->getUploadRootDir().'/['. $this->getId() .']'.$this->name;
		  }
		  
		  
		  /**
		   * @ORM\PostRemove()
		   */
		  public function removeUpload()
		  {
			// En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
			if (file_exists($this->tempFilename)) {
			  // On supprime le fichier
			  unlink($this->tempFilename);
			}
		  }
		  
		  
		  public function getUploadDir()
		  {
	

			// On retourne le chemin relatif vers l'image pour un navigateur (relatif au répertoire /web donc)
			return 'uploads/file/';
		  }
		  
		  
		  protected function getUploadRootDir()
		  {
			// On retourne le chemin relatif vers l'image pour notre code PHP
			return __DIR__.'/../../../../web/'.$this->getUploadDir();
		  }
		  
		  
		  public function getWebPath()
		  {
			return $this->getUploadDir().'/['. $this->getId() .']'.$this->name;
		  }
		  
		  
		  /**
		   * @return int
		   */
		  public function getId()
		  {
			return $this->id;
		  }
		  
		  
		  /**
		   * @param string $name
		   */
		  public function setname($name)
		  {
			$this->name = $name;
		  }
		  
		  
		  /**
		   * @return string
		   */
		  public function getname()
		  {
			return $this->name;
		  }
		  
		  
		  /**
		   * @param string $alt
		   */
		  public function setAlt($alt)
		  {
			$this->alt = $alt;
		  }
		  
		  
		  /**
		   * @return string
		   */
		  public function getAlt()
		  {
			return $this->alt;
		  }
		  
		  
		  /**
		   * @return UploadedFile
		   */
		  public function getFile()
		  {
			return $this->file;
		  }
		  
		  
		  /**
		   * @param UploadedFile $file
		   */
		  // On modifie le setter de File, pour prendre en compte l'upload d'un fichier lorsqu'il en existe déjà un autre
		  public function setFile(UploadedFile $file)
		  {
			$this->file = $file;
			// On vérifie si on avait déjà un fichier pour cette entité
			if (null !== $this->name) {
			  // On sauvegarde l'name du fichier pour le supprimer plus tard
			  $this->tempFilename = $this->name;
			  // On réinitialise les valeurs des attributs url et alt
			  $this->name = null;
			  $this->alt = null;
			}
		  }



		/**
		 * Set extension
		 *
		 * @param string $extension
		 *
		 * @return Files
		 */
		public function setExtension($extension)
		{
			$this->extension = $extension;
		
			return $this;
		}

		/**
		 * Get extension
		 *
		 * @return string
		 */
		public function getExtension()
		{
			return $this->extension;
		}
		    /**
     * Set files
     *
     * @param \PS\HomeBundle\Entity\Files $files
     *
     * @return Information
     */
    public function setFiles(\PS\HomeBundle\Entity\Files $files)
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
	
	

}
