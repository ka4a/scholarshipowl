<?php namespace Test\Entity;

use App\Entity\AccountFile;
use App\Entity\AccountFileCategory;
use App\Entity\AccountFileType;
use App\Testing\TestCase;
use App\Testing\Traits\EntityGenerator;

use Illuminate\Contracts\Filesystem\Filesystem;
use Mockery as m;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AccountFileTest extends TestCase
{
    use EntityGenerator;

    public function setUp(): void
    {
        parent::setUp();

        self::$truncate[] = 'account_file';
    }

    public function testFileUploadingCreateAndDelete()
    {
        $file = new UploadedFile(__FILE__, 'AccountFileTest.php');
        $fileName = '15ba4b9b3a1218.php';
        $content = file_get_contents($file);
        $account = $this->generateAccount();
        $cloudPath = '/account-files/'. $account->getAccountId() .'/other/'. $fileName;

        $this->cloudMock = m::mock(Filesystem::class)
            ->shouldReceive('put')->once()->with($cloudPath, $content, Filesystem::VISIBILITY_PRIVATE)->andReturn(true)
            ->shouldReceive('delete')->once()->with($cloudPath)
            ->getMock();

        $accountFile = new AccountFile($file, $account, $fileName);
        $this->em->persist($accountFile);
        $this->em->flush($accountFile);

        $file = $accountFile->getFile();
        $this->assertEquals($content, file_get_contents($file));
        $this->assertEquals(AccountFileType::TEXT, $accountFile->getType()->getId());

        $this->assertDatabaseHas('account_file', [
            'file_name' => $fileName,
            'real_name' => $file->getFilename(),
            'category_id' => AccountFileCategory::OTHER,
            'account_id' => $account->getAccountId(),
            'type_id' => AccountFileType::TEXT,
        ]);

        $this->em->remove($accountFile);
        $this->em->flush($accountFile);

        $this->assertDatabaseMissing('account_file', [
            'file_name' => $fileName,
            'real_name' => $file->getFilename(),
            'category_id' => AccountFileCategory::OTHER,
            'account_id' => $account->getAccountId(),
            'type_id' => AccountFileType::TEXT,
        ]);
    }

    public function testFileRenamingAndMoving()
    {
        $file = new UploadedFile(__FILE__, 'AccountFileTest.php');
        $fileName = '15ba4b9b3a1218.php';
        $account = $this->generateAccount();
        $cloudPath = '/account-files/'. $account->getAccountId() .'/other/'. $fileName;
        $renamePath = '/account-files/'. $account->getAccountId() .'/other/test.php';
        $categoryRenamePath = '/account-files/'. $account->getAccountId() .'/essay/test.php';

        $this->cloudMock = m::mock(Filesystem::class)
            ->shouldReceive('put')->once()->andReturn(true)
            ->shouldReceive('exists')->twice()->andReturn(false)
            ->shouldReceive('move')->once()->with($cloudPath, $renamePath)
            ->shouldReceive('move')->once()->with($renamePath, $categoryRenamePath)
            ->getMock();

        $accountFile = new AccountFile($file, $account, $fileName);
        $this->em->persist($accountFile);
        $this->em->flush($accountFile);
        $this->assertDatabaseHas('account_file', [
            'file_name' => $fileName,
            'category_id' => AccountFileCategory::OTHER,
            'account_id' => $account->getAccountId(),
            'real_name' => $file->getFilename()
        ]);

        $accountFile->setFileName('test.php');
        $this->em->flush($accountFile);
        $this->assertDatabaseHas('account_file', [
            'file_name' => 'test.php',
            'category_id' => AccountFileCategory::OTHER,
            'account_id' => $account->getAccountId(),
        ]);

        $accountFile->setCategory(AccountFileCategory::ESSAY);
        $this->em->flush($accountFile);
        $this->assertDatabaseHas('account_file', [
            'file_name' => 'test.php',
            'category_id' => AccountFileCategory::ESSAY,
            'account_id' => $account->getAccountId(),
        ]);
    }
}
