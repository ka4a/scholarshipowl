<?php namespace App\Services\ScholarshipManager;

use App\Contracts\LegalContentContract;
use App\Entities\Scholarship;
use App\Entities\ScholarshipContent;
use App\Entities\ScholarshipTemplateContent;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;
use Illuminate\View\View;

class ContentManager
{
    const AFFIDAVIT_PDF_TITLE = 'Winner Affidavit';

    /**
     * Generate scholarship content from the templates content.
     *
     * @param Scholarship               $scholarship
     * @param ScholarshipContent|null   $content
     * @return ScholarshipContent
     * @throws \Exception
     */
    public function generateScholarshipContent(Scholarship $scholarship, ScholarshipContent $content = null)
    {
        $template = $scholarship->getTemplate();
        $affidavitContent = $template->getContentByType(LegalContentContract::TYPE_AFFIDAVIT)->getContent();
        $termsOfUseContent = $template->getContentByType(LegalContentContract::TYPE_TERMS_OF_USE)->getContent();
        $privacyPolicyContent = $template->getContentByType(LegalContentContract::TYPE_PRIVACY_POLICY)->getContent();

        if (is_null($content)) {
            $content = new ScholarshipContent();
        }

        $content->setAffidavit($this->replaceTags($affidavitContent, $scholarship));
        $content->setTermsOfUse($this->replaceTags($termsOfUseContent, $scholarship));
        $content->setPrivacyPolicy($this->replaceTags($privacyPolicyContent, $scholarship));

        return $content;
    }

    /**
     * @param Scholarship $scholarship
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \Exception
     * @throws \Throwable
     */
    public function downloadAffidavit(Scholarship $scholarship)
    {

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk();
        $affidavitPath = sprintf('scholarships/%s/affidavit.pdf', $scholarship->getId());

        if (!$disk->exists($affidavitPath)) {

            $affidavitHtml = $this->replaceTags(
                $scholarship->getContent()->getContentByType(LegalContentContract::TYPE_AFFIDAVIT),
                $scholarship
            );

            $domPDF = $this->prepareHTMLtoPDF($affidavitHtml, static::AFFIDAVIT_PDF_TITLE);

            if (!$disk->put($affidavitPath, $domPDF->output())) {
                throw new \RuntimeException('Failed save PDF file on cloud!');
            }

        }

        return $disk->download($affidavitPath);
    }

    /**
     * @param string $html
     * @param string $title
     * @return Dompdf
     * @throws \Throwable
     */
    public function prepareHTMLtoPDF($html, $title = 'Title')
    {
        /** @var View $html */
        $html = view('html-to-pdf', [
            'content' => $html,
            'title' => $title
        ]);

        $domPdf = new Dompdf();
        $domPdf->loadHtml($html->render());
        $domPdf->render();
        return $domPdf;
    }

    /**
     * @param string        $content
     * @param Scholarship   $scholarship
     * @return mixed
     * @throws \Exception
     */
    protected function replaceTags($content, Scholarship $scholarship)
    {
        $template = $scholarship->getTemplate();
        $organisation = $template->getOrganisation();

        $start = new \DateTime($scholarship->getStart()->format('Y-m-d H:i:s'), $scholarship->getTimezoneObj());
        $deadline = new \DateTime($scholarship->getDeadline()->format('Y-m-d H:i:s'), $scholarship->getTimezoneObj());
        $dateFormat = 'l jS \of F Y h:i:s A T';

        $tags = [
            'scholarship_id' => $scholarship->getId(),
            'scholarship_url' => sprintf(
                '<a href="%s">%s</a>',
                $scholarship->getPublicUrl(),
                $scholarship->getPublicUrl()
            ),
            'scholarship_pp_url' => sprintf(
                '<a href="%s">%s</a>',
                $scholarship->getPublicPPUrl(),
                $scholarship->getPublicPPUrl()
            ),
            'scholarship_amount' => sprintf("$%s", (int) $scholarship->getAmount()),
            'scholarship_awards' => $scholarship->getAwards(),
            'scholarship_start' => $start->format($dateFormat),
            'scholarship_deadline' => $deadline->format($dateFormat),
            'scholarship_timezone' => $start->format('T'),

            'organisation_name' => $organisation->getName(),
            'organisation_business_name' => $organisation->getBusinessName(),
            'organisation_phone' => sprintf(
                '<a href="tel:%s">%s</a>',
                phone_format_us($organisation->getPhone()),
                phone_format_us($organisation->getPhone())
            ),
            'organisation_email' => sprintf(
                '<a href="mailto:%s">%s</a>',
                $organisation->getEmail(),
                $organisation->getEmail()
            ),
            'organisation_website' => sprintf(
                '<a href="%s">%s</a>',
                $organisation->getWebsite(),
                $organisation->getWebsite()
            ),
            'organisation_address' => sprintf(
                "<span>%s</span>&nbsp;<span>%s</span><br/><span>%s %s, %s %s</span>",
                $organisation->getAddress(),
                $organisation->getAddress2(),
                $organisation->getCountry()->getName(),
                $organisation->getCity(),
                $organisation->getState() ? $organisation->getState()->getAbbreviation() : null,
                $organisation->getZip()
            ),
        ];

        return str_replace(
            array_map(function($tag) { return "[$tag]"; }, array_keys($tags)),
            array_values($tags),
            $content
        );
    }
}
