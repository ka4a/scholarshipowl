<?php namespace App\Entities\Passport;

use Doctrine\ORM\Mapping as ORM;

/**
 * OauthPersonalAccessClients
 *
 * @ORM\Table(name="oauth_personal_access_clients", indexes={@ORM\Index(name="oauth_personal_access_clients_client_id_index", columns={"client_id"})})
 * @ORM\Entity
 */
class OauthPersonalAccessClients
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="client_id", type="integer", nullable=false)
     */
    private $clientId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;


}

