<?php
/**
 * Created by PhpStorm.
 * User: vadimkrutov
 * Date: 15/07/16
 * Time: 09:56
 */

namespace App\Entity\Annotations;
use App\Entity\Account;
use Doctrine\Common\Annotations\AnnotationReader;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class Restricted
{
    
}


