<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Winners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('winner', function (Blueprint $table) {
            $table->unsignedInteger('id', true);

            $table->unsignedInteger('scholarship_id')->nullable(true);
            $table->string('scholarship_title');

            $table->unsignedInteger('account_id')->nullable(true);

            $table->date('won_at');
            $table->integer('amount_won');

            $table->string('winner_name');
            $table->string('winner_photo');

            $table->text('testimonial_text');
            $table->string('testimonial_video')->nullable(true);

            $table->tinyInteger('published');

            $table->foreign('scholarship_id')
                ->references('scholarship_id')
                ->on('scholarship');

            $table->foreign('account_id')
                ->references('account_id')
                ->on('account');
        });


        foreach ($this->getData() as $item) {
            $gcPath = "/winners/winner_photo/{$item['winnerPicture']}.png";
            $localPath = base_path('public')."/assets/img/winners/{$item['winnerPicture']}.png";
            \Storage::disk('gcs')->put(
                $gcPath, file_get_contents($localPath), \Illuminate\Contracts\Filesystem\Filesystem::VISIBILITY_PUBLIC
            );
            $publicPhotoPath = \App\Facades\Storage::public($gcPath);

            \DB::statement("
                INSERT INTO winner
                (amount_won, scholarship_title, winner_name, won_at, testimonial_text, testimonial_video, winner_photo, published)
                VALUES
                (
                    :amount_won,
                    :scholarship_title,
                    :winner_name,
                    str_to_date('{$item['winnerDate']}', '%M %d %Y'),
                    :testimonial_text,
                    :testimonial_video,
                    :winner_photo,
                    1
                )
            ", [
                'amount_won' => $item['winningAmount'],
                'scholarship_title' => $item['scholarshipName'],
                'winner_name' => $item['winnerName'],
                'testimonial_text' => $item['winnerStory'],
                'testimonial_video' => !empty($item['winnerVideoLink']) ? $item['winnerVideoLink'] : null,
                'winner_photo' => $publicPhotoPath,
            ]);
        }
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('winner');
    }
    
    private function getData()
    {
        return [
          [
            'winningAmount' => 1000,
            'scholarshipName' => "You Deserve it Scholarship",
            'doubleWinner' => "",
            'winnerName' => "Natalie C.",
            'winnerDate' => "June 1 2018",
            'winnerStory' => "It is with the deepest appreciation that I accept the You Deserve It Scholarship for the month of June. After discovering the Scholarship Owl website through my own online research into college payment options, I was ecstatic to find a multiplicity of scholarships curated specifically for me. This particular scholarship will help me to lessen the burden of college debt as I pursue a youth ministry degree at a four-year university. I am excited to continue using this resource to help pay for my college and I once again thank you for this opportunity.",
            'winnerVideoLink' => "https://www.youtube.com/embed/xh3LW9USAa0",
            'winnerPicture' => "natalie-c-29",
            'id' => 30
          ], [
            'winningAmount' => 1000,
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerName' => "Tyler S.",
            'winnerDate' => "March 1 2018",
            'winnerStory' => "Hello, my name is Tyler S. I am 18 years old and will be graduating from Tavares High School on May 18th, 2018. I am very blessed to have won this scholarship knowing the cost of college is very expensive. This scholarship is certainly going to help my family and I pay towards the balance owed for college. I discovered ScholarshipOwl via the internet. I looked up scholarships that I could apply for, and ScholarshipOwl was the first to pop up. Thank you for choosing me as a recipient of this wonderful scholarship. I am looking forward to pursuing my business degree at Florida Southern College.",
            'winnerVideoLink' => "https://www.youtube.com/embed/sD4sffotNPE",
            'winnerPicture' => "tyler-s-28",
            'id' => 29
          ], [
            'winningAmount' => 1000,
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerName' => "Chyna G.",
            'winnerDate' => "April 1 2018",
            'winnerStory' => "I want to start by saying thank you for giving me the opportunity to continue schooling with these additional funds, this gives me the opportunity to not worry about loans or any other bills that I would've had to cover. Now that I received a scholarship I will be continuing school for nursing. I applied on ScholarshipOwl when I was 16 now I am 19 soon to be 20 and I can honestly say the most will come in handy now. It has truly been a blessing knowing that this was not a scam and once again thank you ScholarshipOwl, I've always doubted myself when it came down to getting any scholarships because I used to think that everywhere I applied was a scam. So once again thank you for the opportunity!",
            'winnerVideoLink' => "https://www.youtube.com/embed/wMg4dhngydY",
            'winnerPicture' => "chyna-g-27",
            'id' => 28
          ], [
            'winningAmount' => 1000,
            'winnerName' => "Anahi G.",
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerDate' => "February 1 2018",
            'winnerStory' => "<p>At the beginning of senior year I signed up to join the College Bound program at my school. Ever since I joined, I have received a lot of help from my college bound representative, Jessica Ramirez. Every month she emails us a list of available scholarships and encourages us to apply to as many as we can. For the month of February, I decided to apply to as many scholarships that I could. One of those links from the recommended scholarships that Jessica sent recommended me to apply and sign up for Scholarship Owl to find more scholarships that match me and so I did. Thanks to those scholarship websites I was able to apply and have the great opportunity to come across the $1,000 You Deserve It Scholarship. I am very glad that my college bound representative always encourages us to keep applying to as many scholarships as we can because they can open many doors for us. I have been very excited about receiving the news of winning this $1,000 scholarship because I know it will help me a lot financially in college when continuing my education. The same day I received the news of winning this scholarship I went into my college bound representative’s office to thank her so much and give her the amazing news and she was so excited and happy for me.</p> <p>After high school I plan to continue my education and go into a four year university. I would like to go into the criminal justice system and become an FBI agent. My interest in this major sparked when I got accepted to participate in a crime scene investigation internship (YEIP) at a community park. Here, I learned about fingerprinting, group work and crime scenes. All these things made me want to pursue this career. I am attending CSULB and entering with the major of pre-criminology/criminal justice to hopefully in the future pursue my dream job and become an FBI agent.</p>",
            'winnerVideoLink' => "https://www.youtube.com/embed/5ldl_A-LjFA",
            'winnerPicture' => "anahi-g-26",
            'id' => 27
          ], [
            'winningAmount' => 1000,
            'winnerName' => "Mary J.",
            'scholarshipName' => "Double Your Scholarship",
            'winnerDate' => "February 1 2018",
            'winnerStory' => "As a full time student and part time employee, I was finding it very difficult to look into scholarships. I was asking myself, \"Where do I look? How should I apply ? What is worth applying for?\" Shortly into my ignorant Google-raid I fell upon ScholarshipOwl. After only a few minutes it was easy to decide to sign up because I had 256 scholarships to chose from all listed on one page instead of 256 different websites. I applied for three that I found had interesting essay prompts and in under a month was selected as the winner of one for $1,000. Fairly quickly after that I was selected for another one, matching the $1,000! It's easy to fall in love with this website as a student and I plan to continue applying for scholarships through it. I'm a student perusing a Veterinary Technician degree and these scholarships will help financially so that I can focus on studying rather than worrying about bills. I hope to take my degree into Wildlife Rehabilitation where I can pursue my passion of helping animals.",
            'winnerVideoLink' => "https://www.youtube.com/embed/y--CudnVlXU",
            'winnerPicture' => "mary-j-25",
            'id' => 26
          ], [
            'winningAmount' => 1000,
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerName' => "Adrien G.",
            'winnerDate' => "January 1 2018",
            'winnerStory' => "My name is Adrien G I am an AVID student which stands for Advanced Via Independent Determination. The reason I share that information is because as homework my teacher has my fellow students and myself to search and apply to scholarships in this case I won and this was the 2nd scholarship I applied to. I feel very happy and shocked this really worked im waiting for the money which will come through after I send this and a video summarizing my written testimony. I plan on getting a degree in criminology sociology psychology or anything dealing with getting ahead on a career in law enforcement. Thank you so very much Scholarshipowl.",
            'winnerVideoLink' => "https://www.youtube.com/embed/TaK49vpXMXQ",
            'winnerPicture' => "adrien-g-24",
            'id' => 25
          ], [
            'winningAmount' => 700,
            'winnerName' => "Javon D.",
            'scholarshipName' => "Double Your Scholarship",
            'winnerDate' => "January 1 2018",
            'winnerStory' => "Well...when I first wanted to go to college in my freshman year of high school my initial thought was, “how am I going to pay for it .” After picking my jaw off the ground from seeing my tuition price I began to research for the first time some scholarships that I can reasonably apply for. There were some from colleges that were $50,000 worth but the requirements were way over my qualifications, so I’m result of all that searching I stumbled across scholarship owl. It was simple. Sign up, give them your basic information, apply for scholarships. I had never found anything so intuitive. I’m result I applied for about 70 scholarships and received one. It was for $700 and I was so excited and overwhelmed with joy. Now that I have one this award I wish to apply it accordingly and further my future of education. Thank you scholarship owl.",
            'winnerVideoLink' => "https://www.youtube.com/embed/HCMjmKfPCFw",
            'winnerPicture' => "javon-d-23",
            'id' => 24
          ], [
            'winningAmount' => 1000,
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerName' => "Shelby F.",
            'winnerDate' => "December 1 2017",
            'winnerStory' => "Testimonial' => Hello, My name is Shelby Felder and I recently won the $1,000 You Deserve It Scholarship! Ever since I really started applying for scholarships, Scholarship Owl was one of my \"go-to's\" for quick and simple scholarships when I didn't necessarily have the time to write an essay or anything else for that matter. It was always so simple and easy to enter a little bit of information about me and sometimes write a little paragraph that was needed, so doing that on a website like this one became a routine. I obviously never expected to win one of these scholarships because I know that thousands of people apply for them. I'm glad I was in the hallway at school when I received the call because I would have missed out on this opportunity had I not been. I was utterly shocked because I didn't know if it was real or not, and I am so thankful to the people that work for this company and gave me the opportunity to win. I plan on attending Stephen F. Austin State University and majoring in Interdisciplinary Studies aka Elementary Education. I am extremely excited to move on to the next chapter of my life and I wouldn't have been able to do that without the grace of scholarship companies like this one. Thank you!",
            'winnerVideoLink' => "https://www.youtube.com/embed/2VSZ5iBSP3o",
            'winnerPicture' => "shelby-f-26",
            'id' => 23
          ], [
            'winningAmount' => 1000,
            'winnerName' => "Genoveva D.",
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerDate' => "November 1 2017",
            'winnerStory' => "My career choice isn't usually considered with a very high opinion. When I tell people I want to do theatre, I usually get an empathetic smile and a few words of encouragement on such a tough business. What people don't realize is that they are inspired and motivated by not just theatre but all the arts on a daily basis. Most of the time that inspiration is not with the best intentions but other times it inspires diversity, natural beauty, and tough conversations. I am an acting major because I believe in the effect it can have on society. I want to use that effect to spread awareness to issues that are usually hard to talk about so they get shoved under the rug. I want to pull the rug off from underneath people and have them be engrossed in the dirt society has created. Thanks to ScholarshipOwl, I am one step closer to achieving my life dream. After spending hours looking for scholarships, I happen to come across their website and not only is it super easy to use but they have a wide range of scholarships that anyone can be eligible to apply. I am unbelievably thankful for having such an amazing organization believe in me enough to offer me a scholarship. As a result, I am able to pay for school without being buried under loans. They saw something in me and I promise that I won't disappoint.",
            'winnerVideoLink' => "",
            'winnerPicture' => "genoveva-d-22",
            'id' => 22
          ], [
            'winningAmount' => 1000,
            'scholarshipName' => "Double Your Scholarship",
            'winnerName' => "Mollijoy C.",
            'winnerDate' => "October 1 2017",
            'winnerStory' => 'Allow me to introduce myself,
              My Name is Mollijoy, I live in Northern California, near Yosemite national park in a very
              Rural area. There are very limited opportunity’s in our community for education, scholarships,
              and support for continuing education. I am a first Generation graduate, in my entire family. My
              mother and father did not even complete high school. So when I was a awarded the grand prize
              from scholarship owls scholarship offer, to enter the SABR Security “Keep Schools Safe
              Scholarship” for 15,000 I was so incredibly excited! I have been focused on college and
              achieving my education since I was in my early teens. When you are doing it alone it is difficult
              to see how it will ever come together.
              I started working when I was 15 and a half, and graduated high school early so that I could
              begin college. I have worked more than one job, from delivering pizzas, to entering pageant’s to
              try and win scholarships.( I won Mrs. congeniality)
              When you’re on your own, it can be difficult to keep your head up and pushing forward in the
              goals you would like to obtain without just giving up. But With scholarshipowls help and the
              grand prize I look forward to achieving my Masters in Science of Educational Leadership. And
              who knows maybe I’ll just go all the way and get my doctorate. Because now I know that
              anything is possible. Thank you again
              Thank you scholarshipowl for doubling my blessing and money
              Yours.
              Molli C',
            'winnerVideoLink' => "https://www.youtube.com/embed/4Jy5AlOX_20",
            'winnerPicture' => "mollijoy-c-20",
            'id' => 21
          ], [
            'winningAmount' => 1000,
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerName' => "Bradee D.",
            'winnerDate' => "October 1 2017",
            'winnerStory' => 'I found this site while searching for scholarships, and it\'s been everything I\'ve needed! Winning the
              "You Deserve It" scholarship was really a godsend to me, exactly what I needed to help me finish out
              the semester! This will be a great way to keep away the debt, and since I\'ve got medical school in my
              future, I\'m gonna need all the help I can get!',
            'winnerVideoLink' => "https://www.youtube.com/embed/zhJt2ag9_xg",
            'winnerPicture' => "bradee-b-19",
            'id' => 20
          ], [
            'winningAmount' => 1000,
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerName' => "Sarah S.",
            'winnerDate' => "September 1 2017",
            'winnerStory' => "I found out about Scholarship Owl through my high school college and career counselor. I was worried about having to pay for college on my own and she provided me with great resources such as scholarship owl. I applied for as many scholarships as I qualified for including the You Deserve it scholarship. We I found out I got the scholarship it just took another huge load of stress about school expenses off my shoulder. This is gonna help me to achieve getting my Bachelor of Fine Arts degree in Musical Theatre in New York, New York. Thank you Scholarship Owl!",
            'winnerVideoLink' => "https://www.youtube.com/embed/IAYBvDz2gYE",
            'winnerPicture' => "sarah-s-21",
            'id' => 19
          ], [
            'winningAmount' => 1000,
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerName' => "Daniel V.",
            'winnerDate' => "August 1 2017",
            'winnerStory' => 'My name is Daniel and I live in San Antonio, TX. I’m currently pursuing a Bachelor\'s degree in Computer
              Science at the University of Texas at San Antonio. I found ScholarshipOwl by simply searching for scholarships
              and decided to give it a shot, and I’m very glad I did. Winning this “You Deserve It” scholarship is going to
              keep me away from student loans and to further my education through funding my tuition. I really am happy for
              this opportunity, thanks ScholarshipOwl!',
            'winnerVideoLink' => "https://www.youtube.com/embed/hGI-JjQo_7U",
            'winnerPicture' => "daniel-v-18",
            'id' => 18
          ], [
            'winningAmount' => 1000,
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerName' => "Sheuriah R.",
            'winnerDate' => "July 1 2017",
            'winnerStory' => 'I\'m very excited about winning 1,000 dollars from “ You Deserve It Scholarship”. Winning this
              money mean alot to me and it will help me pay for college. My counselor in highschool always
              encouraged students to apply for scholarships. I never knew applying for a scholarship was so easy.
              I never believed that scholarship was real on the internet , but I gave it a try. I searched the
              internet for scholarships and the third site was “ You Deserve It” which really caught my eye.
              I applied and the following week I got a call giving me following instructions. I really appreciate
              this scholarship. I will work very hard in the future to be successful as Sheuriah Ringwood the
              Nurse Practitioner !',
            'winnerVideoLink' => "https://www.youtube.com/embed/rJSgij2Ekog",
            'winnerPicture' => "sheuriah-r-17",
            'id' => 17
          ], [
            'winningAmount' => 1000,
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerName' => "Kayla G.",
            'winnerDate' => "June 1 2017",
            'winnerStory' => 'I found ScholarshipOwl while searching for scholarships online. I instantly signed up for
              ScholarshipOwl because it made it super quick and easy to find and apply for scholarships.
              I applied for the ‘You Deserve It’ scholarship for that exact reason: Is was super easy to
              do and only took about a minute out of my day. I’m so excited about winning this scholarship
              because it will help afford my future plans of continuing my education at a 4-year university.
              Thanks, ScholarshipOwl!',
            'winnerVideoLink' => "https://www.youtube.com/embed/_z3u0jb78PY",
            'winnerPicture' => "kayla-g-16",
            'id' => 16
          ], [
            'winningAmount' => 1000,
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerName' => "Maria R.",
            'winnerDate' => "May 1 2017",
            'winnerStory' => 'When I was in High School I received a scholarship hand out that listed all of websites that
              provided us with opportunities. Scholarship owl was one of the websites so I decided to give it
              a try and found the “You deserve it scholarship.” As a Latino student helping my parents with my
              payments for college is important. This scholarship will help me pay for my tuition to be able to
              graduate with my degree in Bilingual Education within the next year.',
            'winnerVideoLink' => "https://www.youtube.com/embed/C-RWJ3dTOko",
            'winnerPicture' => "maria-r-15",
            'id' => 15
          ], [
            'winningAmount' => 1000,
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerName' => "Jacoby G.",
            'winnerDate' => "April 1 2017",
            'winnerStory' => 'Thank you so much for the "You Deserve It" scholarship!
              As a Landscape Architect student, I spend just as many hours in classes as I do working in the studio. To pay for my out of state tuition, I have to work a part time job and there are many weeks that I have to pull all nighters to keep up my school work while working job shifts. My record is 88 hours of no sleep so this scholarship will really help me get some more sleep in while keeping my school work done. I am so grateful for you ScholarshipOwl!!',
            'winnerVideoLink' => "",
            'winnerPicture' => "jacoby-g-14",
            'id' => 14
          ], [
            'winningAmount' => 1000,
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerName' => "Isabella M.",
            'winnerDate' => "March 1 2017",
            'winnerStory' => 'My name is Isabel moffat and I will be a freshman in college next year studying Physician\'s
              Assistant Studies and I am very grateful to ScholarshipOwl for giving me this opportunity
              to further my education.',
            'winnerVideoLink' => "https://www.youtube.com/embed/xDpOCcbTAEc",
            'winnerPicture' => "isabella-m-13",
            'id' => 13
          ], [
            'winningAmount' => 1000,
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerName' => "Gladys A.",
            'winnerDate' => "February 1 2017",
            'winnerStory' => 'I\'am Gladys Acosta and I live in Houston, Texas. I\'am more than thankful to be able to get
              this opportunity that will be beneficial for when I go to college.I found out about this
              website at my school and I\'am very happy because I get to be a part of this. When I get out
              of school and graduate with my associates degree, I plan to be a nursing assistant because
              I love helping others. I know that with a lot of hard work and motivation i will be able to
              accomplish this.',
            'winnerVideoLink' => "https://www.youtube.com/embed/WX6uorXWbSA",
            'winnerPicture' => "gladys-a-12",
            'id' => 12
          ], [
            'winningAmount' => 1000,
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerName' => "Leon K.",
            'winnerDate' => "January 1 2017",
            'winnerStory' => 'My name is Leon K. and I will be attempting to get a Bachelor\'s degree in Computer
              Science. ScholarshipOwl is helping me greatly by providing me with this
              opportunity to pay for college and is providing me with great assistance in not
              having to take out student loans. I am planning to use this money to
              invest in my future and accomplish all my goals. Thank you ScholorshipOwl, I
              really appreciate it!',
            'winnerVideoLink' => "https://www.youtube.com/embed/P2mDf9aJWpA",
            'winnerPicture' => "leon-k-11",
            'id' => 11
          ], [
            'winningAmount' => 1000,
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerName' => "Nashira T.",
            'winnerDate' => "December 1 2016",
            'winnerStory' => "Hi my name is Nashira in the fall I plan on attending a four college or a nursing university and thanks to ScholarshipOwl I'm a step closer to saving up enough money to get there.",
            'winnerVideoLink' => "https://www.youtube.com/embed/5gGxSJfqDqg",
            'winnerPicture' => "nashira-t-10",
            'id' => 10
          ], [
            'winningAmount' => 1000,
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerName' => "Olivia M.",
            'winnerDate' => "November 1 2016",
            'winnerStory' => 'I am very excited that I was blessed with this scholarship from ScholarshipOwl!
              I will be using this scholarship to help  my education at the University of Alabama
              studying science and mathematics. Thanks to you, I will be able to pursue my lifelong
              dreams of becoming a successful neurosurgeon.',
            'winnerVideoLink' => "",
            'winnerPicture' => "olivia-m-9",
            'id' => 9
          ], [
            'winningAmount' => 1000,
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerName' => "Ashley C.",
            'winnerDate' => "October 1 2016",
            'winnerStory' => "My name is Ashley and I will be attending UT Chattanooga in the Fall of 2017. I am very grateful for the money that is being provided for me to fullfil my dreams of attending this school.",
            'winnerVideoLink' => "",
            'winnerPicture' => "ashley-c-8",
            'id' => 8
          ], [
            'winningAmount' => 1000,
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerName' => "Jordan F.",
            'winnerDate' => "September 1 2016",
            'winnerStory' => "Do not ever give up on pursuing scholarships that can help boost your education! Be industrious, put forth your best effort, and see how life will surprise you in wondrous ways.",
            'winnerVideoLink' => "https://www.youtube.com/embed/OdzDwg5cgxU",
            'winnerPicture' => "jordan-f-7",
            'id' => 7
          ], [
            'winningAmount' => 1000,
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerName' => "Sasha R.",
            'winnerDate' => "August 1 2016",
            'winnerStory' => 'Like any college student trying to get into their dream school that is miles away,
              I spent countless hours working on scholarships and I stumbled upon ScholarshipOwl
              and decided to apply to the "You Deserve It" scholarship to see if I could get lucky.
              I was completely ecstatic when I was told that I won and it gave me more confidence to
              pursue my dream as a female movie director. With a win like this, I will be able to stay
              in Savannah College of Art and Design and take as many film classes as I can in order to
              break the barrier and get more women in the film industry. Thank you so much ScholarshipOwl!',
            'winnerVideoLink' => "https://www.youtube.com/embed/MqIqfvBHC2w",
            'winnerPicture' => "sasha-r-6",
            'id' => 6
          ], [
            'winningAmount' => 1000,
            'winnerName' => "Kehinde B.",
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerDate' => "July 1 2016",
            'winnerStory' => "Thanks Scholarship owl, I am eternally grateful for this money. I will use it wisely.",
            'winnerVideoLink' => "",
            'winnerPicture' => "kehinde-b-5",
            'id' => 5
          ], [
            'winningAmount' => 1000,
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerName' => "Tailer F.",
            'winnerDate' => "May 1 2016",
            'winnerStory' => "My name is Tailer F. and I am from salt lake city, Utah. I will be attending the University of Utah, studying Pre-Medicine. I am so blessed and thankful to have received this scholarship. I am ready to get a start on my career, and with your help it has become a little less stressful. Thank you!!!",
            'winnerVideoLink' => "",
            'winnerPicture' => "tailer-f-4",
            'id' => 4
          ], [
            'winningAmount' => 1000,
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerName' => "Eshe B.",
            'winnerDate' => "April 1 2016",
            'winnerStory' => 'Excited to have won this scholarship, I\'ve never won a scholarship before which is absolutely amazing. It gives me more motivation
                to keep applying since I always thought that maybe I\'d never win anything. Aside of my newfound luck, thanks so much scholarship owl!',
            'winnerVideoLink' => "",
            'winnerPicture' => "eshe-b-3",
            'id' => 3
          ], [
            'winningAmount' => 1000,
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerName' => "Martina F.",
            'winnerDate' => "March 1 2016",
            'winnerStory' => "I am very grateful for the $1000 award. It will be very helpful in the next year to cover some important college expenses.",
            'winnerVideoLink' => "https://www.youtube.com/embed/P2OxJIXDV7I",
            'winnerPicture' => "martina-f-2",
            'id' => 2
          ], [
            'winningAmount' => 1000,
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerName' => "Ashley P.",
            'winnerDate' => "February 1 2016",
            'winnerStory' => "My name is Ashley P., I graduated from Bulkeley High School and I will be attending the University Of Connecticut. Scholarship Owl has offered me an amazing opportunity with this scholarship. Thank you very much for making the application process easy and efficient!",
            'winnerVideoLink' => "",
            'winnerPicture' => "ashley-p-1",
            'id' => 1
          ], [
            'winningAmount' => 1000,
            'scholarshipName' => "You Deserve it Scholarship",
            'winnerName' => "Kasey W.",
            'winnerDate' => "November 1 2015",
            'winnerStory' => '<p>Kasey is a Sophomore at Ohio Dominican University in Columbus, Ohio, majoring in Computer
              Sciences with a minor in Business. When she graduates she hopes to land a job as a software
              engineer at Google or Microsoft and eventually apply to graduate school.</p>
        
              <p>Kasey, who grew up in Columbus, Ohio, and is the first in her family to go to college, will
                use the scholarship to cover part of her tuition. “I am really grateful for this
                opportunity,” said Kasey. “This scholarship will enable me to pay off some of my loans and
                focus on my career goals.”</p>
        
                <p>"You Deserve It!" Scholarship Giveaway was launched in October 2015 as a means to give back
                  to the student community</p>',
            'winnerVideoLink' => "",
            'winnerPicture' => "kasey-w-0",
            'id' => 0
          ],
        ];
    }
}
