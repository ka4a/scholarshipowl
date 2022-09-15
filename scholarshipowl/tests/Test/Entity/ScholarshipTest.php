<?php namespace Test\Entity;

use App\Entity\AccountFileCategory;
use App\Entity\AccountFileType;
use App\Entity\ScholarshipFile;
use App\Testing\TestCase;
use App\Testing\Traits\EntityGenerator;
use Illuminate\Http\UploadedFile;
use Mockery as m;

class ScholarshipTest extends TestCase
{
    use EntityGenerator;

    public function testUploadLogoOnNewScholarship()
    {
        $logo = UploadedFile::fake()->create('test.png', 1 * 1024);
        $scholarship = $this->generateScholarship();
        $scholarship->setLogoFile($logo);

        \Image::shouldReceive('make')
            ->once()
            ->with($logo)
            ->andReturn(
                m::mock()->shouldReceive('resize')
                    ->once()
                    ->andReturn(
                        m::mock()->shouldReceive('save')
                            ->once()
                            ->with($logo)
                            ->getMock()
                    )
                    ->getMock()
            );

        $this->cloudMock->shouldReceive('put')
            ->with(
                m::on(function($value) use ($scholarship) {
                    $this->assertNotNull($scholarship->getScholarshipId());
                    $this->assertContains($scholarship->getScholarshipId().'_test.png', $value);
                    return true;
                }),
                m::any(),
                'public'
            );

        $this->em->persist($scholarship);
        $this->em->flush($scholarship);

        $scholarship->setTitle('sfsfsf');
        $this->em->persist($scholarship);
        $this->em->flush($scholarship);
    }

    public function testUploadLogoOnScholarshipUpdate()
    {
        $logo = UploadedFile::fake()->create('test.png', 1 * 1024);
        $scholarship = $this->generateScholarship();
        \EntityManager::persist($scholarship);
        \EntityManager::flush($scholarship);

        \Image::shouldReceive('make')
            ->once()
            ->with($logo)
            ->andReturn(
                m::mock()->shouldReceive('resize')
                    ->once()
                    ->andReturn(
                        m::mock()->shouldReceive('save')
                            ->once()
                            ->with($logo)
                            ->getMock()
                    )
                    ->getMock()
            );

        $this->cloudMock->shouldReceive('put')
            ->with(
                m::on(function($value) { return strpos($value, 'test.png') !== false; }),
                m::any(),
                'public'
            );

        $scholarship->setLogoFile($logo);

        \EntityManager::flush($scholarship);

        $scholarship->setTitle('sfsfsf');
        \EntityManager::persist($scholarship);
        \EntityManager::flush($scholarship);
    }

    public function testScholarshipFilesAddingToScholarship()
    {
        static::$truncate[] = 'scholarship_file';
        static::$truncate[] = 'scholarship_file_account_file_type';

        $scholarship = $this->generateScholarship();
        $scholarshipFile = new ScholarshipFile('Test', 10, AccountFileCategory::ESSAY, [AccountFileType::TEXT, AccountFileType::IMAGE]);

        $scholarship->addScholarshipFile($scholarshipFile);
        \EntityManager::flush($scholarship);

        $this->assertDatabaseHas('scholarship_file', [
            'scholarship_file_id' => 1,
            'scholarship_id' => $scholarship->getScholarshipId(),
            'description' => 'Test',
            'max_size' => 10,
            'category_id' => AccountFileCategory::ESSAY,
        ]);

        $this->assertDatabaseHas('scholarship_file_account_file_type', ['scholarship_file_id' => 1, 'file_type_id' => AccountFileType::TEXT]);
        $this->assertDatabaseHas('scholarship_file_account_file_type', ['scholarship_file_id' => 1, 'file_type_id' => AccountFileType::IMAGE]);
    }
}
