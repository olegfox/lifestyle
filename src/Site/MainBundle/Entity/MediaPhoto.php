<?php

namespace Site\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Site\MainBundle\Entity\MediaPhoto
 *
 * @ORM\Table(name="media_photo")
 * @ORM\Entity(repositoryClass="Site\MainBundle\Entity\Repository\MediaPhotoRepository")
 */
class MediaPhoto
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="text", nullable=false)
     */
    protected $link;

    /**
     * @ORM\ManyToOne(targetEntity="Media", inversedBy="photos")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id")
     **/
    protected $media;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return MediaPhoto
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set media
     *
     * @param \Site\MainBundle\Entity\Media $media
     * @return MediaPhoto
     */
    public function setMedia(\Site\MainBundle\Entity\Media $media = null)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return \Site\MainBundle\Entity\Media 
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @ORM\PreRemove
     */
    public function removeUpload()
    {
        if(file_exists($this->getLink())){
            unlink($this->getLink());
        }
    }
}
