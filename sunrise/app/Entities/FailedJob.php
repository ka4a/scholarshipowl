<?php namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * FailedJobs
 *
 * @ORM\Table(name="failed_jobs")
 * @ORM\Entity
 */
class FailedJob
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="connection", type="text", length=65535, nullable=false)
     */
    private $connection;

    /**
     * @var string
     *
     * @ORM\Column(name="queue", type="text", length=65535, nullable=false)
     */
    private $queue;

    /**
     * @var string
     *
     * @ORM\Column(name="payload", type="text", length=0, nullable=false)
     */
    private $payload;

    /**
     * @var string
     *
     * @ORM\Column(name="exception", type="text", length=0, nullable=false)
     */
    private $exception;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="failed_at", type="datetime", nullable=false)
     */
    private $failedAt;


}
