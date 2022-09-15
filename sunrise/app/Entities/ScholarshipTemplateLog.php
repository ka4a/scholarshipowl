<?php namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Loggable\Entity\MappedSuperclass\AbstractLogEntry;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;

/**
 * @ORM\Table(
 *  options={"row_format":"DYNAMIC"},
 *  indexes={
 *      @ORM\Index(name="log_class_lookup_idx", columns={"object_class"}),
 *      @ORM\Index(name="log_date_lookup_idx", columns={"logged_at"}),
 *      @ORM\Index(name="log_user_lookup_idx", columns={"username"}),
 *      @ORM\Index(name="log_version_lookup_idx", columns={"object_id", "object_class", "version"})
 *  }
 * )
 * @ORM\Entity(repositoryClass="Gedmo\Loggable\Entity\Repository\LogEntryRepository")
 */
class ScholarshipTemplateLog extends AbstractLogEntry implements JsonApiResource
{
    /**
     * @return string
     */
    static public function getResourceKey()
    {
        return 'scholarship_template_log';
    }
}
