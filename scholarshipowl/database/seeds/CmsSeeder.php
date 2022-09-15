<?php


class CmsSeeder extends Seeder {

    public function run() {
        DB::statement("DELETE FROM cms");

        // First 3 Affiliates

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
            "cms_id" => 1,
            "url" => "/about-us",
            "page" => "About Us",
            "title" => "About ScholarshipOwl",
            "keywords" => "about scholarshipowl,scholarshipowl review,pell grants,hispanic scholarships,nursing scholarships,pell grants,minority scholarship,fullbright,fafsa,scholarships and grants,scholarship websites,www.scholarshipsowl.com,scholarship opportunities,student scholarships,scholarship search,scholarship finder,private scholarships,student loans,shcolarship money,apply for scholarships online,college scholarship search,find scholarships,scholarshipowl,scholarshipowl.com,scholarship owl,owl scholarship,scholarships,apply for scholarships,apply to scholarships,scholarship application,scholarship search,college scholarships,free scholarships",
            "description" => "ScholarshipOwl takes higher education financing to the next level, by helping students find, source, apply, and manage all their scholarship from one simple place.",
            "author" => " ",
            )
        );

        DB::table("cms")->insert(array(
            "cms_id" => 2,
            "url" => "/whoweare",
            "page" => "Who we are",
            "title" => "Who We Are",
            "keywords" => "who we are,grants,pell grants,hispanic scholarships,nursing scholarships,pell grants,minority scholarship,fullbright,fafsa,scholarships and grants,scholarship websites,www.scholarshipsowl.com,scholarship opportunities,student scholarships,scholarship search,scholarship finder,private scholarships,student loans,shcolarship money,apply for scholarships online,college scholarship search,find scholarships,scholarshipowl,scholarshipowl.com,scholarship owl,owl scholarship,scholarships,apply for scholarships,apply to scholarships,scholarship application,scholarship search,college scholarships,free scholarships",
            "description" => "Scholarshipowl was created by a group of Tech-savvy geeks with a vision to make higher education more accessible to everyone",
            "author" => " ",
            )
        );

        DB::table("cms")->insert(array(
            "cms_id" => 3,
            "url" => "/whatwedo",
            "page" => "What we do",
            "title" => "What We Do",
            "keywords" => "What We Do,grants,pell grants,hispanic scholarships,nursing scholarships,pell grants,minority scholarship,fullbright,fafsa,scholarships and grants,scholarship websites,www.scholarshipsowl.com,scholarship opportunities,student scholarships,scholarship search,scholarship finder,private scholarships,student loans,shcolarship money,apply for scholarships online,college scholarship search,find scholarships,scholarshipowl,scholarshipowl.com,scholarship owl,owl scholarship,scholarships,apply for scholarships,apply to scholarships,scholarship application,scholarship search,college scholarships,free scholarships",
            "description" => "ScholarshipOwl revolutionizes the way students apply to scholarships by automating the application process for the student.",
            "author" => " ",
            )
        );

        DB::table("cms")->insert(array(
            "cms_id" => 4,
            "url" => "/additional-services",
            "page" => "additional services",
            "title" => "Additional Service ScholarshipOwl",
            "keywords" => "Additional Service ScholarshipOwl,grants,pell grants,hispanic scholarships,nursing scholarships,pell grants,minority scholarship,fullbright,fafsa,scholarships and grants,scholarship websites,www.scholarshipsowl.com,scholarship opportunities,student scholarships,scholarship search,scholarship finder,private scholarships,student loans,shcolarship money,apply for scholarships online,college scholarship search,find scholarships,scholarshipowl,scholarshipowl.com,scholarship owl,owl scholarship,scholarships,apply for scholarships,apply to scholarships,scholarship application,scholarship search,college scholarships,free scholarships",
            "description" => "Besides the awesome core features offered by ScholarshipOwl are students always offered other cool benefits and perks.",
            "author" => " ",
            )
        );

        DB::table("cms")->insert(array(
            "cms_id" => 5,
            "url" => "/premium-services",
            "page" => "Why we charge for the premium service",
            "title" => "Premium Services ScholarshipOwl",
            "keywords" => "Premium Services ScholarshipOwl,grants,pell grants,hispanic scholarships,nursing scholarships,pell grants,minority scholarship,fullbright,fafsa,scholarships and grants,scholarship websites,www.scholarshipsowl.com,scholarship opportunities,student scholarships,scholarship search,scholarship finder,private scholarships,student loans,shcolarship money,apply for scholarships online,college scholarship search,find scholarships,scholarshipowl,scholarshipowl.com,scholarship owl,owl scholarship,scholarships,apply for scholarships,apply to scholarships,scholarship application,scholarship search,college scholarships,free scholarships",
            "description" => "For the extra eager students wanting to get the most out of his scholarship applications do we offer some super amazing additional",
            "author" => " ",
            )
        );


        DB::table("cms")->insert(array(
            "cms_id" => 6,
            "url" => "",
            "page" => "",
            "title" => "",
            "keywords" => "",
            "description" => "",
            "author" => " ",
            )
        );



        DB::table("cms")->insert(array(
                "cms_id" => 8,
                "url" => "/faq",
                "page" => "FAQ",
                "title" => "FAQ",
                "keywords" => "FAQ,frequently asked questions,pell grants,hispanic scholarships,nursing scholarships,pell grants,minority scholarship,fullbright,fafsa,scholarships and grants,scholarship websites,www.scholarshipsowl.com,scholarship opportunities,student scholarships,scholarship search,scholarship finder,private scholarships,student loans,shcolarship money,apply for scholarships online,college scholarship search,find scholarships,scholarshipowl,scholarshipowl.com,scholarship owl,owl scholarship,scholarships,apply for scholarships,apply to scholarships,scholarship application,scholarship search,college scholarships,free scholarships",
                "description" => "Frequently Asked Questions about Scholarships and ScholarshipOwl",
                "author" => " ",
            )
        );


        DB::table("cms")->insert(array(
                "cms_id" => 9,
                "url" => "/our-promise-to-you",
                "page" => "what you get / our promise to you",
                "title" => "What You Get",
                "keywords" => "What You Get,grants,pell grants,hispanic scholarships,nursing scholarships,pell grants,minority scholarship,fullbright,fafsa,scholarships and grants,scholarship websites,www.scholarshipsowl.com,scholarship opportunities,student scholarships,scholarship search,scholarship finder,private scholarships,student loans,shcolarship money,apply for scholarships online,college scholarship search,find scholarships,scholarshipowl,scholarshipowl.com,scholarship owl,owl scholarship,scholarships,apply for scholarships,apply to scholarships,scholarship application,scholarship search,college scholarships,free scholarships",
                "description" => "Find out what you get from ScholarshipOwl",
                "author" => " ",
            )
        );

        DB::table("cms")->insert(array(
                "cms_id" => 13,
                "url" => "/list-your-scholarship",
                "page" => "List your scholarship",
                "title" => "List Your Scholarship",
                "keywords" => "List Your Scholarship,grants,pell grants,hispanic scholarships,nursing scholarships,pell grants,minority scholarship,fullbright,fafsa,scholarships and grants,scholarship websites,www.scholarshipsowl.com,scholarship opportunities,student scholarships,scholarship search,scholarship finder,private scholarships,student loans,shcolarship money,apply for scholarships online,college scholarship search,find scholarships,scholarshipowl,scholarshipowl.com,scholarship owl,owl scholarship,scholarships,apply for scholarships,apply to scholarships,scholarship application,scholarship search,college scholarships,free scholarships",
                "description" => "Do you have a scholarship you want to get listed on ScholarshipOwl? Tell us more.",
                "author" => " ",
            )
        );


        DB::table("cms")->insert(array(
                "cms_id" => 14,
                "url" => "/advertise-with-us",
                "page" => "Advertise with us",
                "title" => "Advertise with us",
                "keywords" => "Advertise with us,advertise with scholarshipowl,scholarship advertising,education advertising,nursing scholarships,scholarship advertising,minority scholarship,fullbright,fafsa,scholarships and grants,scholarship websites,www.scholarshipsowl.com,scholarship opportunities,student scholarships,scholarship search,scholarship finder,private scholarships,student loans,shcolarship money,apply for scholarships online,college scholarship search,find scholarships,scholarshipowl,scholarshipowl.com,scholarship owl,owl scholarship,scholarships,apply for scholarships,apply to scholarships,scholarship application,scholarship search,college scholarships,free scholarships",
                "description" => "Advertise with ScholarshipOwl and reach millions of students with unique targeting opportunities.",
                "author" => " ",
            )
        );


        DB::table("cms")->insert(array(
                "cms_id" => 15,
                "url" => "/resources-and-education",
                "page" => "resources and educational center",
                "title" => "Resources and Education",
                "keywords" => "Resources and Education,grants,pell grants,hispanic scholarships,nursing scholarships,pell grants,minority scholarship,fullbright,fafsa,scholarships and grants,scholarship websites,www.scholarshipsowl.com,scholarship opportunities,student scholarships,scholarship search,scholarship finder,private scholarships,student loans,shcolarship money,apply for scholarships online,college scholarship search,find scholarships,scholarshipowl,scholarshipowl.com,scholarship owl,owl scholarship,scholarships,apply for scholarships,apply to scholarships,scholarship application,scholarship search,college scholarships,free scholarships",
                "description" => "ScholarshipOwl provides all users with an array of resources and educational material to help you get the most success out of your applications",
                "author" => " ",
            )
        );


        DB::table("cms")->insert(array(
                "cms_id" => 16,
                "url" => "/international-students",
                "page" => "international students",
                "title" => "International Students",
                "keywords" => "International Students,grants,pell grants,hispanic scholarships,nursing scholarships,pell grants,minority scholarship,fullbright,fafsa,scholarships and grants,scholarship websites,www.scholarshipsowl.com,scholarship opportunities,student scholarships,scholarship search,scholarship finder,private scholarships,student loans,shcolarship money,apply for scholarships online,college scholarship search,find scholarships,scholarshipowl,scholarshipowl.com,scholarship owl,owl scholarship,scholarships,apply for scholarships,apply to scholarships,scholarship application,scholarship search,college scholarships,free scholarships",
                "description" => "ScholarshipOwl puts a special focus on helping international students studying in the USA, as well as American students studying abroad to find and apply to the right scholarships",
                "author" => " ",
            )
        );


        DB::table("cms")->insert(array(
                "cms_id" => 18,
                "url" => "/what-people-say-about-scholarshipsowl",
                "page" => "What people say about scholarshipowl, Testimonials",
                "title" => "Testimonials",
                "keywords" => "Testimonials,scholarshipowl review,scholarshipowl opinions,hispanic scholarships,nursing scholarships,scholarshipowl opinions,minority scholarship,fullbright,fafsa,scholarships and grants,scholarship websites,www.scholarshipsowl.com,scholarship opportunities,student scholarships,scholarship search,scholarship finder,private scholarships,student loans,shcolarship money,apply for scholarships online,college scholarship search,find scholarships,scholarshipowl,scholarshipowl.com,scholarship owl,owl scholarship,scholarships,apply for scholarships,apply to scholarships,scholarship application,scholarship search,college scholarships,free scholarships",
                "description" => "Don't just take our word for it. See what other think about ScholarshipOwl",
                "author" => " ",
            )
        );


        DB::table("cms")->insert(array(
                "cms_id" => 19,
                "url" => "/affiliates",
                "page" => "affiliates",
                "title" => "Affiliates",
                "keywords" => "Affiliates,partners,scholarshipowl partners,scholarship affiliation,nursing scholarships,scholarshipowl partners,minority scholarship,fullbright,fafsa,scholarships and grants,scholarship websites,www.scholarshipsowl.com,scholarship opportunities,student scholarships,scholarship search,scholarship finder,private scholarships,student loans,shcolarship money,apply for scholarships online,college scholarship search,find scholarships,scholarshipowl,scholarshipowl.com,scholarship owl,owl scholarship,scholarships,apply for scholarships,apply to scholarships,scholarship application,scholarship search,college scholarships,free scholarships",
                "description" => "Partner with us and make money. Website or Blog owners, if you have a newsletter, or are simply well-connected, start monetizing your relevant traffic today.",
                "author" => " ",
            )
        );


        DB::table("cms")->insert(array(
                "cms_id" => 20,
                "url" => "/partnerships",
                "page" => "partnerships",
                "title" => "Partnerships",
                "keywords" => "Partnerships,scholarshipowl partners,pell grants,hispanic scholarships,nursing scholarships,pell grants,minority scholarship,fullbright,fafsa,scholarships and grants,scholarship websites,www.scholarshipsowl.com,scholarship opportunities,student scholarships,scholarship search,scholarship finder,private scholarships,student loans,shcolarship money,apply for scholarships online,college scholarship search,find scholarships,scholarshipowl,scholarshipowl.com,scholarship owl,owl scholarship,scholarships,apply for scholarships,apply to scholarships,scholarship application,scholarship search,college scholarships,free scholarships",
                "description" => "ScholarshipOwl offers a wide variety of partnership opportunities. Contact us or send us your ideas to find out more.",
                "author" => " ",
            )
        );


        DB::table("cms")->insert(array(
                "cms_id" => 21,
                "url" => "/press",
                "page" => "Press",
                "title" => "Press",
                "keywords" => "Press,press release,pell grants,hispanic scholarships,nursing scholarships,pell grants,minority scholarship,fullbright,fafsa,scholarships and grants,scholarship websites,www.scholarshipsowl.com,scholarship opportunities,student scholarships,scholarship search,scholarship finder,private scholarships,student loans,shcolarship money,apply for scholarships online,college scholarship search,find scholarships,scholarshipowl,scholarshipowl.com,scholarship owl,owl scholarship,scholarships,apply for scholarships,apply to scholarships,scholarship application,scholarship search,college scholarships,free scholarships",
                "description" => "ScholarshipOwl in the Press",
                "author" => " ",
            )
        );


        DB::table("cms")->insert(array(
                "cms_id" => 22,
                "url" => "/contact",
                "page" => "Contact us",
                "title" => "Contact us",
                "keywords" => "Contact us,contact scholarshipowl,pell grants,hispanic scholarships,nursing scholarships,pell grants,minority scholarship,fullbright,fafsa,scholarships and grants,scholarship websites,www.scholarshipsowl.com,scholarship opportunities,student scholarships,scholarship search,scholarship finder,private scholarships,student loans,shcolarship money,apply for scholarships online,college scholarship search,find scholarships,scholarshipowl,scholarshipowl.com,scholarship owl,owl scholarship,scholarships,apply for scholarships,apply to scholarships,scholarship application,scholarship search,college scholarships,free scholarships",
                "description" => "Contact us – we love talking to people",
                "author" => " ",
            )
        );

        DB::table("cms")->insert(array(
                "cms_id" => 25,
                "url" => "/refer-a-friend",
                "page" => "refer a friend",
                "title" => "Refer a Friend",
                "keywords" => "Refer a Friend,grants,pell grants,hispanic scholarships,nursing scholarships,pell grants,minority scholarship,fullbright,fafsa,scholarships and grants,scholarship websites,www.scholarshipsowl.com,scholarship opportunities,student scholarships,scholarship search,scholarship finder,private scholarships,student loans,shcolarship money,apply for scholarships online,college scholarship search,find scholarships,scholarshipowl,scholarshipowl.com,scholarship owl,owl scholarship,scholarships,apply for scholarships,apply to scholarships,scholarship application,scholarship search,college scholarships,free scholarships",
                "description" => "At ScholarshipOwl we reward users for bringing their friends. Referring a friend has never been so easy!",
                "author" => " ",
            )
        );


        DB::table("cms")->insert(array(
                "cms_id" => 27,
                "url" => "/ebook",
                "page" => "eBook",
                "title" => "Scholarship eBook",
                "keywords" => "Scholarship eBook,grants,pell grants,hispanic scholarships,nursing scholarships,pell grants,minority scholarship,fullbright,fafsa,scholarships and grants,scholarship websites,www.scholarshipsowl.com,scholarship opportunities,student scholarships,scholarship search,scholarship finder,private scholarships,student loans,shcolarship money,apply for scholarships online,college scholarship search,find scholarships,scholarshipowl,scholarshipowl.com,scholarship owl,owl scholarship,scholarships,apply for scholarships,apply to scholarships,scholarship application,scholarship search,college scholarships,free scholarships",
                "description" => "Take advantage of our easy to read eBook and prepare the proper way for you applications",
                "author" => " ",
            )
        );


        DB::table("cms")->insert(array(
                "cms_id" => 29,
                "url" => "/tips-on-how-to-apply",
                "page" => "Tips on how to apply",
                "title" => "Tips on how to apply",
                "keywords" => "Tips on how to apply,grants,pell grants,hispanic scholarships,nursing scholarships,pell grants,minority scholarship,fullbright,fafsa,scholarships and grants,scholarship websites,www.scholarshipsowl.com,scholarship opportunities,student scholarships,scholarship search,scholarship finder,private scholarships,student loans,shcolarship money,apply for scholarships online,college scholarship search,find scholarships,scholarshipowl,scholarshipowl.com,scholarship owl,owl scholarship,scholarships,apply for scholarships,apply to scholarships,scholarship application,scholarship search,college scholarships,free scholarships",
                "description" => "Get Top Tips on how to apply to scholarships",
                "author" => " ",
            )
        );


        DB::table("cms")->insert(array(
                "cms_id" => 30,
                "url" => "/scholarshipowl-ambassadors",
                "page" => "Ambassadors",
                "title" => "Scholarshipowl Ambassador",
                "keywords" => "Scholarshipowl Ambassador,grants,pell grants,hispanic scholarships,nursing scholarships,pell grants,minority scholarship,fullbright,fafsa,scholarships and grants,scholarship websites,www.scholarshipsowl.com,scholarship opportunities,student scholarships,scholarship search,scholarship finder,private scholarships,student loans,shcolarship money,apply for scholarships online,college scholarship search,find scholarships,scholarshipowl,scholarshipowl.com,scholarship owl,owl scholarship,scholarships,apply for scholarships,apply to scholarships,scholarship application,scholarship search,college scholarships,free scholarships",
                "description" => "Become a ScholarshipOwl Ambassador in your area or high school. Become involved and get rewarded.",
                "author" => " ",
            )
        );


        DB::table("cms")->insert(array(
                "cms_id" => 31,
                "url" => "/High-school-packages",
                "page" => "High School Packages",
                "title" => "High School Packages",
                "keywords" => "High School Packages,grants,pell grants,hispanic scholarships,nursing scholarships,pell grants,minority scholarship,fullbright,fafsa,scholarships and grants,scholarship websites,www.scholarshipsowl.com,scholarship opportunities,student scholarships,scholarship search,scholarship finder,private scholarships,student loans,shcolarship money,apply for scholarships online,college scholarship search,find scholarships,scholarshipowl,scholarshipowl.com,scholarship owl,owl scholarship,scholarships,apply for scholarships,apply to scholarships,scholarship application,scholarship search,college scholarships,free scholarships",
                "description" => "We offer special packages for high schools that want to support all their students and grant them free access to ScholarshipOwl.com",
                "author" => " ",
            )
        );

        DB::table("cms")->insert(array(
                "cms_id" => 32,
                "url" => "/successful-scholarship-essays",
                "page" => "Successful Scholarship Essays",
                "title" => "Successful scholarship essays",
                "keywords" => "Successful scholarship essays,grants,pell grants,hispanic scholarships,nursing scholarships,pell grants,minority scholarship,fullbright,fafsa,scholarships and grants,scholarship websites,www.scholarshipsowl.com,scholarship opportunities,student scholarships,scholarship search,scholarship finder,private scholarships,student loans,shcolarship money,apply for scholarships online,college scholarship search,find scholarships,scholarshipowl,scholarshipowl.com,scholarship owl,owl scholarship,scholarships,apply for scholarships,apply to scholarships,scholarship application,scholarship search,college scholarships,free scholarships",
                "description" => "See what other successful Scholarship recipients wrote in their essays.",
                "author" => " ",
            )
        );



    }
}