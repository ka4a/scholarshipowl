<?php namespace Test\Http\Controller\Index;

use App\Entity\AccountFile;
use App\Testing\TestCase;
use App\Testing\Traits\EntityGenerator;

use Mockery as m;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

        $file = new UploadedFile(__FILE__, 'AccountFileTest.php');
        $fileName = '15ba4b9b3a1218.php';
        $content = file_get_contents($file);
        $account = $this->generateAccount();
        $accountFile = new AccountFile($file, $account, $fileName);

        $this->em->persist($accountFile);
        $this->em->flush();

        $this->actingAs($account);
        $resp = $this->get(route('account-file', ltrim($accountFile->getPath(), '/'), false));

        $this->assertTrue($resp->getStatusCode() === 200);
        $this->assertInstanceOf(BinaryFileResponse::class, $resp->baseResponse);
        $this->assertEquals($content, file_get_contents($resp->baseResponse->getFile()));
    }

}
