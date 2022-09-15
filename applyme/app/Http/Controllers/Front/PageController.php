<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\freeCoachingSessionContactRequest;
use App\Mail\Contact;
use Illuminate\Support\Facades\Mail;
use Mautic\Api\Contacts;
use Mautic\Api\Segments;
use Spatie\Newsletter\Newsletter;

class PageController extends Controller
{
    /**
     * Home page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('front.pages.index');
    }

    /**
     * Features page.
     *
     * @return \Illuminate\Http\Response
     */
    public function features()
    {
        return view('front.pages.features.index');
    }

    /**
     * Courses page.
     * (College Preparation Program)
     *
     * @return \Illuminate\Http\Response
     */
    public function courses()
    {
        return view('front.pages.features.courses');
    }

    /**
     * Admissions Coaching page.
     *
     * @return \Illuminate\Http\Response
     */
    public function admissionsCoaching()
    {
        return view('front.pages.features.admissions-coaching');
    }

    /**
     * Essay Assistance page.
     *
     * @return \Illuminate\Http\Response
     */
    public function essayAssistance()
    {
        return view('front.pages.features.essay-assistance');
    }

    /**
     * Interview Preparation page.
     *
     * @return \Illuminate\Http\Response
     */
    public function interviewPreparation()
    {
        return view('front.pages.features.interview-preparation');
    }

    /**
     * Personalized Scholarships List.
     *
     * @return \Illuminate\Http\Response
     */
    public function personalizedScholarshipsList()
    {
        return view('front.pages.features.personalized-scholarships-list');
    }

    /**
     * Guidance For Parents.
     *
     * @return \Illuminate\Http\Response
     */
    public function guidanceForParents()
    {
        return view('front.pages.features.guidance-for-parents');
    }

    /**
     * FAQ page.
     *
     * @return \Illuminate\Http\Response
     */
    public function faq()
    {
        return view('front.pages.faq');
    }

    /**
     * Contact page.
     *
     * @return \Illuminate\Http\Response
     */
    public function getContact()
    {
        return view('front.pages.contact-us');
    }

    /**
     * Handle a contact POST request.
     *
     * @param  \App\Http\Requests\ContactRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function postContact(ContactRequest $request)
    {
        $data = [
            'name'    => $request->name,
            'email'   => $request->email,
            'content' => $request->message,
        ];

        Mail::to(config('mail.contact-us'))->send(new Contact($data));

        return redirect()->route('front.contact.get')->with('success', 'Your message has been sent!');;
    }

    /**
     * Handle a free coaching session contact AJAX POST request.
     * Add the lead to Mautic.
     *
     * @param  \App\Http\Requests\freeCoachingSessionRequest  $request
     * @param  \Mautic\Api\Contacts  $contactApi
     * @param  \Mautic\Api\Segments  $segmentApi
     * @return \Illuminate\Http\Response
     */
    public function postContactCoaching(
        freeCoachingSessionContactRequest $request,
        Contacts $contactApi,
        Segments $segmentApi
    )
    {
        $data = [
            'email'     => $request->email,
            'ipAddress' => $_SERVER['REMOTE_ADDR']
        ];

        $response = $contactApi->create($data);

        $response = $segmentApi->addContact(config('mautic.segment'), $response['contact']['id']);

        return response()->json([
            'success' => 'Contact successfully added'
        ]);
    }

    /**
     * About us page.
     *
     * @return \Illuminate\Http\Response
     */
    public function aboutUs()
    {
        return view('front.pages.about-us');
    }

    /**
     * Privacy Policy page.
     *
     * @return \Illuminate\Http\Response
     */
    public function privacyPolicy()
    {
        return view('front.pages.privacy-policy');
    }

    /**
     * Terms of use page.
     *
     * @return \Illuminate\Http\Response
     */
    public function termsOfUse()
    {
        return view('front.pages.terms-of-use');
    }

    /**
     * Pricing page.
     *
     * @return \Illuminate\Http\Response
     */
    public function pricing()
    {
        return view('front.pages.pricing');
    }

    /**
     * Sitemap page.
     *
     * @return \Illuminate\Http\Response
     */
    public function sitemap()
    {
        return view('front.pages.sitemap');
    }

    /**
     * Partners page.
     *
     * @return \Illuminate\Http\Response
     */
    public function partners()
    {

    }

    /**
     * Benefits page.
     *
     * @return \Illuminate\Http\Response
     */
    public function benefits()
    {

    }
}
