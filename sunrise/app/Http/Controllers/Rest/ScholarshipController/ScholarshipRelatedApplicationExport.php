<?php namespace App\Http\Controllers\Rest\ScholarshipController;

use App\Doctrine\QueryIterator;
use App\Entities\Application;
use App\Entities\Field;
use App\Entities\Scholarship;
use App\Entities\ScholarshipField;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use League\Csv\Writer;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ScholarshipRelatedApplicationExport extends ScholarshipRelatedApplicationsAction
{
    /**
     * @param \Pz\Doctrine\Rest\Contracts\RestRequestContract $request
     * @return Response|\Pz\Doctrine\Rest\RestResponse
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     * @throws \League\Csv\CannotInsertRecord
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    protected function handle($request)
    {
        /** @var Scholarship $scholarship */
        $scholarship = $this->base()->findById($request->getId());

        Gate::authorize('restShow', $scholarship);

        $qb = $this->repository()->sourceQueryBuilder($request);

        $this->applyFilter($request, $qb);
        // $this->applyPagination($request, $qb);

        $writer = Writer::createFromFileObject(new \SplTempFileObject());

        /** @var Application[] $applications */
        $this->writeHeaders($writer, $scholarship);
        foreach (QueryIterator::create($qb->getQuery()) as $applications) {
            foreach ($applications as $application) {
                $this->writeApplication($writer, $scholarship, $application);
            }

            $this->repository()
                ->getEntityManager()
                ->clear(Application::class);
        }

        $response = new Response($writer->getContent());
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set(
            'Content-Disposition',
            $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                sprintf(
                    '%s-%s-%s-%s.csv',
                    str_slug($scholarship->getTitle()),
                    $scholarship->getId(),
                    $scholarship->getStart()->format('Y-m-d'),
                    $scholarship->getDeadline()->format('Y-m-d')
                )
            )
        );

        return $response;
    }

    /**
     * @param Writer $writer
     * @param Scholarship $scholarship
     * @throws \League\Csv\CannotInsertRecord
     */
    protected function writeHeaders(Writer $writer, Scholarship $scholarship)
    {
        $excluded = ['requirements'];

        $headers = array_merge([
                'Applied at',
                'Status',
                'Source',
                'ID',
            ],
            array_filter(
                array_map(
                    function(ScholarshipField $field) use ($excluded) {
                        if (in_array($field->getField()->getId(), $excluded)) {
                            return null;
                        }
                        return $field->getField()->getName();
                    },
                    $scholarship->getFields()->toArray()
                ),
                function ($v) { return !empty($v); }
            )
        );

        $writer->insertOne($headers);
    }

    /**
     * @param Writer $writer
     * @param Scholarship $scholarship
     * @param Application $application
     * @throws \League\Csv\CannotInsertRecord
     */
    protected function writeApplication(Writer $writer, Scholarship $scholarship, Application $application)
    {
        $excluded = ['requirements'];

        $row = array_merge([
                $application->getCreatedAt()->format('Y-m-d H:i:s'),
                $application->getStatus()->getName(),
                $application->getSource(),
                $application->getId(),
            ], array_filter(
                array_map(
                    function(ScholarshipField $field) use ($excluded, $application) {
                        $id = $field->getField()->getId();
                        $data = $application->getData();
                        $value = $data[$id] ?? null;

                        if ($field->getField()->getType() === Field::TYPE_OPTION) {
                            foreach ($field->getField()->getOptions() as $id => $option) {
                                if ($id === $value) {
                                    $value = is_array($option) ? $option['name'] : $option;
                                    break;
                                }
                            }
                        }

                        return !in_array($id, $excluded) ? $value : null;
                    },
                    $scholarship->getFields()->toArray()
                ),
                function ($v) { return !empty($v); }
            )
        );

//        die(var_export($row, true));
//        die(var_export(array_filter(
//            array_map(
//                function(ScholarshipField $field) use ($excluded, $application) {
//                    $id = $field->getField()->getId();
//                    $data = $application->getData();
//                    return !in_array($id, $excluded) ? $data[$id] ?? null : null;
//                },
//                $scholarship->getFields()->toArray()
//            ),
//            function ($v) { return !empty($v); }
//        ), true));
        $writer->insertOne($row);
    }
}
