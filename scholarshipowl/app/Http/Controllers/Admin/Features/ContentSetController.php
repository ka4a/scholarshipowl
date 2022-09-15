<?php namespace App\Http\Controllers\Admin\Features;

use App\Entity\FeatureContentSet;
use App\Http\Middleware\FeatureAbTestsMiddleware;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\Request;

class ContentSetController extends BaseFeatureSetController
{
    protected $entity = FeatureContentSet::class;
    protected $indexRoute = 'admin::features.content_sets.index';
    /**
     * @var array
     */
    protected $rules = [
        'name'                       => 'required|max:255',
        'homepage_header'            => 'required',
        'register_header'            => 'required',
        'register_heading_text'      => 'required',
        'register_subheading_text'   => 'required',
        'register_hide_footer'       => 'boolean',
        'register_cta_text'          => 'required',
        'select_apply_now'           => 'required|max:255',
        'select_hide_checkboxes'     => 'boolean',
        'applicationSentTitle'       => 'max:255',
        'applicationSentDescription' => '',
        'applicationSentContent'     => '',
        'noCreditsTitle'             => 'max:255',
        'noCreditsDescription'       => '',
        'noCreditsContent'           => '',
        'upgradeBlockText'           => '',
        'upgradeBlockLinkUpgrade'    => '',
        'upgradeBlockLinkVip'        => '',

        'hp_double_promotion_flag'   => '',
        'hp_ydi_flag'                => '',
        'hp_cta_text'                => '',
        'register2_heading_text'     => '',
        'register2_subheading_text'  => '',
        'register2_cta_text'         => '',

        'register3_heading_text'     => '',
        'register3_subheading_text'  => '',
        'register3_cta_text'         => '',

        'register_illustration'      => '',
        'register2_illustration'     => '',
        'register3_illustration'     => '',

        'pp_header_text'             => '',
        'pp_header_text2'             => '',
        'pp_carousel_items_cnt'      => ['regex:/^(?:[8-9]|\d\d\d*|0)$/'],

    ];

    protected $rulesMessage = [
        'pp_carousel_items_cnt.regex' => "The carousel's items count must be at least 8 or 0."
    ];
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $this->addBreadcrumb('Features', 'features.index');
        $this->addBreadcrumb('Content Sets', 'features.content_sets.index');

        return $this->view('Blocks List', 'admin.features.content_sets.index', [
            'contentSets' => $this->repository->findAll(),
        ]);
    }

    /**
     * @param Request $request
     * @param null    $id
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $id = null)
    {
        /** @var FeatureContentSet $contentSet */
        $contentSet = $id !== null ? $this->repository->findById($id) : null;

        if ($request->isMethod(Request::METHOD_POST)) {
            if (!$contentSet || $contentSet->getName() != $request->get('name')) {
                $this->rules['name'] .=  '|unique:'.FeatureContentSet::class;
            }

            $this->validate($request, $this->rules, $this->rulesMessage);
            $data = $request->only(array_keys($this->rules));

            if ($contentSet) {
                $contentSet->hydrate($data);
                $this->em->flush($contentSet);
                \Cache::tags(FeatureAbTestsMiddleware::FEATURE_SET_CACHE_TAG)->flush();
            } else {
                $contentSet = new FeatureContentSet($data);

                $this->em->persist($contentSet);
                $this->em->flush($contentSet);
            }

            $imagesFields = [
                'register2_illustration' => "setRegister2Illustration",
                'register_illustration' => 'setRegisterIllustration',
                'register3_illustration' => 'setRegister3Illustration'
            ];

            foreach ($imagesFields as $field => $setter) {
                if ($image = $request->file($field)) {
                    try {
                        $path = '/contentseti/illustration/'.$field."_".uniqid().'.'.$request->file($field)->getClientOriginalExtension();
                        \Storage::disk('gcs')->put($path, file_get_contents($request->file($field)), Filesystem::VISIBILITY_PUBLIC);
                        $fullPath = \Storage::disk('gcs')->url($path);
                        $contentSet->{$setter}($fullPath);
                        $this->em->persist($contentSet);
                        $this->em->flush($contentSet);
                    } catch (\Exception $e) {
                        handle_exception($e);
                        return \Redirect::back()->withErrors(sprintf('Can\'t update content set %s!', $id));
                    }
                } elseif($request->get($field.'-remove')) {
                    $contentSet->{$setter}('');
                    $this->em->persist($contentSet);
                    $this->em->flush($contentSet);
                }
            }

            return \Redirect::route('admin::features.content_sets.edit', $contentSet->getId())
                ->with('message', sprintf('Content set \'%s\' saved!', $contentSet));
        }

        $this->addBreadcrumb('Features', 'features.index');
        $this->addBreadcrumb('Content sets', 'features.content_sets.index');
        $this->addPostBreadcrumb('features.content_sets.edit', $id);

        return $this->view(($id ? 'Edit' : 'Create') . ' content set', 'admin.features.content_sets.edit', [
            'contentSet' => $contentSet,
        ]);
    }

    /**
     * @param $id
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        try {
            /** @var FeatureContentSet $set */
            $this->em->remove($block = $this->repository->findById($id));
            $this->em->flush();
        } catch (ForeignKeyConstraintViolationException $e) {
            return \Redirect::back()->withErrors(sprintf('Content set %s used by someone!', $id));
        }

        return \Redirect::route('admin::features.content_sets.index')
            ->with('message', sprintf('Content set \'%s\' removed!', $block));
    }
}
