<?php


class CmsSeederDynamicPages extends Seeder {

    public function run() {
    //        DB::table("cms")->insert(array(
    //                "cms_id" => ,
    //            "url" => ,
    //            "page" => ,
    //            "title" => ,
    //            "keywords" => ,
    //            "description" => ,
    //            "author" => ,
    //            )
    //        );

		DB::table("cms")->insert(array(
				"cms_id" => 33,
				"url" => "/register",
				"page" => "Register",
				"title" => "ScholarshipOwl - hundreds of scholarships one click away",
				"keywords" => "students, education, scholarship consultants, apply for scholarship, graduate debt free, financial aid, account managers",
				"description" => "Scholarship Owl is a collection of dedicated professionals looking to make finding money easier for students.",
				"author" => " ",
			)
		);

		DB::table("cms")->insert(array(
				"cms_id" => 34,
				"url" => "/register2",
				"page" => "Register 2",
				"title" => "ScholarshipOwl - hundreds of scholarships one click away",
				"keywords" => "students, education, scholarship consultants, apply for scholarship, graduate debt free, financial aid, account managers",
				"description" => "Scholarship Owl is a collection of dedicated professionals looking to make finding money easier for students.",
				"author" => " ",
			)
		);

		DB::table("cms")->insert(array(
				"cms_id" => 35,
				"url" => "/register3",
				"page" => "Register 3",
				"title" => "ScholarshipOwl - hundreds of scholarships one click away",
				"keywords" => "students, education, scholarship consultants, apply for scholarship, graduate debt free, financial aid, account managers",
				"description" => "Scholarship Owl is a collection of dedicated professionals looking to make finding money easier for students.",
				"author" => " ",
			)
		);

		DB::table("cms")->insert(array(
				"cms_id" => 36,
				"url" => "/my-account",
				"page" => "My Account",
				"title" => "ScholarshipOwl - hundreds of scholarships one click away",
				"keywords" => "students, education, scholarship consultants, apply for scholarship, graduate debt free, financial aid, account managers",
				"description" => "Scholarship Owl is a collection of dedicated professionals looking to make finding money easier for students.",
				"author" => " ",
			)
		);

		DB::table("cms")->insert(array(
				"cms_id" => 37,
				"url" => "/select",
				"page" => "Select",
				"title" => "ScholarshipOwl - hundreds of scholarships one click away",
				"keywords" => "students, education, scholarship consultants, apply for scholarship, graduate debt free, financial aid, account managers",
				"description" => "Scholarship Owl is a collection of dedicated professionals looking to make finding money easier for students.",
				"author" => " ",
			)
		);

		DB::table("cms")->insert(array(
				"cms_id" => 38,
				"url" => "/my-applications",
				"page" => "My Applications",
				"title" => "ScholarshipOwl - hundreds of scholarships one click away",
				"keywords" => "students, education, scholarship consultants, apply for scholarship, graduate debt free, financial aid, account managers",
				"description" => "Scholarship Owl is a collection of dedicated professionals looking to make finding money easier for students.",
				"author" => " ",
			)
		);
		DB::table("cms")->insert(array(
				"cms_id" => 39,
				"url" => "/mailbox",
				"page" => "Mailbox",
				"title" => "ScholarshipOwl - hundreds of scholarships one click away",
				"keywords" => "students, education, scholarship consultants, apply for scholarship, graduate debt free, financial aid, account managers",
				"description" => "Scholarship Owl is a collection of dedicated professionals looking to make finding money easier for students.",
				"author" => " ",
			)
		);
    }
}