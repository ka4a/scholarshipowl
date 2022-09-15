<?php namespace App\Services;

use App\Entities\ApplicationWinner;
use App\Entities\ScholarshipWinner;
use Google\Cloud\Vision\V1\FaceAnnotation;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Intervention\Image\Image;

class GoogleVision
{
    /**
     * @var ImageAnnotatorClient
     */
    protected $client;

    /**
     * GoogleVision constructor.
     * @param ImageAnnotatorClient $client
     */
    public function __construct(ImageAnnotatorClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param ApplicationWinner $winner
     * @return Image
     */
    public function findWinnerFace(ApplicationWinner $winner)
    {
        return $this->cropFace(\Image::make($winner->getPhoto()->getFile()))->fit(ScholarshipWinner::PHOTO_SIZE);
    }

    /**
     * @param Image $image
     * @return Image
     */
    public function cropFace(Image $image)
    {
        return $this->cropFaceByAnnotation($image, $this->findFace($image));
    }

    /**
     * Find face using Google Vision API.
     * If face not found we try rotate image to different angles and try to find face one more time.
     *
     * @param Image $image
     * @return FaceAnnotation
     */
    protected function findFace(Image $image)
    {
        $faces = $this->client->faceDetection($image->encode()->getEncoded())->getFaceAnnotations();

        if (count($faces) === 0) {
            $faces = $this->client->faceDetection($image->rotate(-90)->encode()->getEncoded())->getFaceAnnotations();
        }

        if (count($faces) === 0) {
            $faces = $this->client->faceDetection($image->rotate(-90)->encode()->getEncoded())->getFaceAnnotations();
        }

        if (count($faces) === 0) {
            $faces = $this->client->faceDetection($image->rotate(-90)->encode()->getEncoded())->getFaceAnnotations();
        }

        if (count($faces) === 0) {
            throw new \LogicException('Face not found');
        }

        if (count($faces) > 1) {
            throw new \LogicException('Found more than 1 face');
        }

        return $faces[0];
    }

    /**
     * Crops image by the coordinates found in FaceAnnotation.
     *
     * @param Image $image
     * @param FaceAnnotation $face
     * @return Image
     */
    protected function cropFaceByAnnotation(Image $image, FaceAnnotation $face)
    {
        /**
         * If face is rolled to the side ( can happen if image was rotated ).
         * Rotate the image by the roll angle and try to find face one more time.
         */
        if (abs($face->getRollAngle()) > 30) {
            $face = $this->findFace($image->rotate($face->getRollAngle()));
        }

        $vertices = $face->getBoundingPoly()->getVertices();

        if (count($vertices) !== 4) {
            throw new \LogicException('Face must have 4 vertexes.');
        }

        $firstCorner = $vertices[0];
        $lastCorner = $vertices[2];

        /**
         * Get coordinates for the face image crop.
         */
        $x = $firstCorner->getX();
        $y = $firstCorner->getY();
        $width = $lastCorner->getX() - $firstCorner->getX();
        $height = $lastCorner->getY() - $firstCorner->getY();

        /**
         * Get padding size in pixels.
         */
        $paddingPercentage = 0.1;
        $padding = intval(array_sum([
            $firstCorner->getX() * $paddingPercentage,
            $firstCorner->getY() * $paddingPercentage,
            ($image->getWidth() - $lastCorner->getX()) * $paddingPercentage,
            ($image->getHeight() - $lastCorner->getY()) * $paddingPercentage
        ]) / 4);

        /**
         * Add padding to the default face image.
         */
        $x = max(0, $x - $padding);
        $y = max(0, $y - $padding);
        $width = min($image->getWidth() - $x, $width + ($padding*2));
        $height = min($image->getHeight() - $y, $height + ($padding*2));

        return $image->crop($width, $height, $x, $y);
    }
}
