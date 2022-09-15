<?php

namespace App\Http\Controllers\Admin;


use App\Entity\Account;
use App\Entity\Scholarship;
use App\Entity\Winner;
use App\Facades\EntityManager;
use App\Facades\Storage;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\Request;
use ScholarshipOwl\Http\ViewModel;

class WinnerController extends BaseController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function searchAction()
    {
	    $repo = \EntityManager::getRepository(Winner::class);
        $paginator = $this->paginator();
        $dql = $repo->createQueryBuilder('w');

        $totalCount = (int)(clone($dql))
            ->select('COUNT(w.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $result = $dql->setFirstResult($paginator->getOffset())
                    ->setMaxResults($paginator->getLimit())
                    ->getQuery()
                    ->getResult();


        $data['result'] = $result;
        $data["count"] = $totalCount;
        $data["pagination"]["page"] = $paginator->getPage();
        $data["pagination"]["pages"] = ceil($totalCount / $paginator->getPerPage());
        $params = request()->all();
        unset($params['page']);
        $data["pagination"]["url_params"] = $params;
        $data["pagination"]["url"] = '/admin/winners/search';
        $data['user'] = $this->getLoggedUser();
        $data['active'] = 'winners';
        $data['title'] = 'Search Winners';
        $data['breadcrumb'] = 	[
            "Dashboard" => "/admin/dashboard",
            "Winners" => "/admin/winners",
            "Search Winners" => "/admin/winners/search"
        ];

		return view('admin.winners.search', $data);
	}

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function viewAction($id)
	{
		$data = [
			"user" => $this->getLoggedUser(),
			"breadcrumb" => [
				"Dashboard" => "/admin/dashboard",
				"Winners" => "/admin/winners",
				"Search Winners" => "/admin/winners/search",
			],
			"title" => "View Winner",
			"active" => "winners",
		];

		try {
			$data["winner"] = \EntityManager::getRepository(Winner::class)->find($id);
			$data["breadcrumb"]["View Winner"] = route('admin::winners.view', ['id' => $id]);
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		return view('admin.winners.view', $data);
	}

    /**
     * @param null $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     * @throws \Doctrine\ORM\OptimisticLockException
     */
	public function editAction($id = null, Request $request)
	{
		$model = new ViewModel("admin/winners/edit");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Scholarships" => "/admin/winners",
				"Search Winners" => "/admin/winners/search"
			),
			"active" => "winners",
		);

        $isPost = $request->isMethod('POST');

        if ($isPost) {
            $this->validate($request, [
                'scholarship_id'   => 'entity:Scholarship',
                'account_id'   => 'entity:Account',
                'scholarship_title'   => 'required',
                'amount_won'   => 'required|integer',
                'won_at'   => 'required|date',
                'winner_name'   => 'required',
                'winner_photo'   => 'mimes:jpeg,jpg,png',
                'testimonial_text'   => 'required',
            ], [
                'scholarship_id.entity' => 'Specified scholarship not found',
                'account_id.entity' => 'Specified account not found',
            ]);
        }

        if (empty($id)) {
            $winner = new Winner();
            $data["title"] = "Add Winner";

            $data["breadcrumb"]["Add Winner"] = "/admin/winners/edit";
        }
        else {
            $winner = \EntityManager::getRepository(Winner::class)->find($id);

            $data["title"] = "Edit winner";
            $data["breadcrumb"]["Edit Winner"] = "/admin/winners/edit/$id";
        }

        if ($isPost) {
            if ($scholarshipId = $request->get('scholarship_id')) {
                $winner->setScholarship(
                    \EntityManager::getRepository(Scholarship::class)->find($scholarshipId)
                );
            }
            if ($accountId = $request->get('account_id')) {
                $winner->setAccount(
                    \EntityManager::getRepository(Account::class)->find($accountId)
                );
            }

            $winner->setScholarshipTitle($request->get('scholarship_title'));
            $winner->setAmountWon($request->get('amount_won'));
            $winner->setWonAt(new \DateTime($request->get('won_at')));
            $winner->setWinnerName($request->get('winner_name'));
            $winner->setTestimonialText($request->get('testimonial_text'));
            $winner->setTestimonialVideo($request->get('testimonial_video'));
            $winner->setPublished((bool)$request->get('published'));

            if ($image = $request->file('winner_photo')) {
                if ($prevFilePath = $winner->getWinnerPhoto()) {
                    $prevGcPath = strstr($prevFilePath, '/winners/winner_photo');
                    \Storage::disk('gcs')->delete($prevGcPath);
                }

                $gcPath = '/winners/winner_photo/'.uniqid().'.'.$image->getClientOriginalExtension();
                \Storage::disk('gcs')->put(
                    $gcPath,
                    file_get_contents($request->file('winner_photo')),
                    Filesystem::VISIBILITY_PUBLIC
                );

                $winner->setWinnerPhoto(\App\Facades\Storage::public($gcPath));
            }

            \EntityManager::persist($winner);
            \EntityManager::flush($winner);

            $request->session()->flash('message', 'Winner saved');
        }

        $data['winner'] = $winner;
		$model->setData($data);

		return $model->send();
	}

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     */
	public function deleteAction($id)
    {
        $winner = \EntityManager::getRepository(Winner::class)->find($id);

        if ($winner) {
            \EntityManager::remove($winner);
            \EntityManager::flush($winner);
        }

        return \Redirect::to(route('admin::winners.search'))->with('message', sprintf('Winner [ %d ] deleted', $id));
    }

}
