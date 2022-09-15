<?php namespace Test\Http\Controller\RestMobile;

use App\Entity\AccountFile;
use App\Testing\TestCase;
use App\Testing\Traits\EntityGenerator;

use Mockery as m;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileControllerTest extends TestCase
{
    use EntityGenerator;

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testReturnAccountFileAccess()
    {
        static::$truncate[] = 'account_file';

        $file = new File(__FILE__);
        $content = file_get_contents($file);
        $account = $this->generateAccount();
        $accountFile = new AccountFile($file, $account);

        $this->em->persist($accountFile);
        $this->em->flush();

        $this->actingAs($account);

        $token = \JWTAuth::fromUser($account);
        $headers =  ['Authorization' => 'Bearer '.$token];

        $resp = $this->get(route('rest-mobile::v1.file.accountFileDownload', $accountFile->getId(), false), $headers);

        $this->assertTrue($resp->getStatusCode() === 200);
        $this->assertInstanceOf(BinaryFileResponse::class, $resp->baseResponse);
        $this->assertArrayHasKey('content-disposition', $resp->headers->all());

        $respShow = $this->get(route('rest-mobile::v1.file.accountFileShow', $accountFile->getId(), false), $headers);
        $this->assertTrue($respShow->getStatusCode() === 200);
        $this->assertInstanceOf(BinaryFileResponse::class, $respShow->baseResponse);
        //show response doesn't have content-disposition header
        $this->assertArrayNotHasKey('content-disposition', $respShow->headers->all());

    }

}
