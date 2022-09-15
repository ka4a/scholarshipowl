@extends('layouts.front')

@section('meta-title', "Contact Us | Apply.me")

@section('meta-description', "Looking for answers? We're here to help. Contact us through any of the following ways.")

@section('page-name', 'page-contact-us')

@section('content')

    <section class="Banner">
        <div class="container">
            <div class="row Banner__content">
                <div class="col-xs-12">
                    <h1 class="Banner__title text-center">Contact Us</h1>
                    <hr class="Util--spacer-trans-micro">
                    <div class="Banner__text text-center">
                        Here are a few ways to get in touch with us
                    </div>
                </div>
            </div>
        </div>
    </section>

    <ol class="breadcrumb">
        <li><a href="{{ route('front.index') }}" title="">Home</a></li>
        <li class="active">Contact Us</li>
    </ol>

    <section class="Section Section--light-primary">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-7">

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <h2 class="Util--text-primary">Contact Form</h2>
                    <hr class="Util--spacer-trans-small">

                    {!! Form::open([
                        'route'      => 'front.contact.post',
                        'class'      => 'form',
                        'novalidate' => 'novalidate']) !!}

                        @include('front.components.form._contact')

                    {!! Form::close() !!}
                    <hr class="Util--spacer-trans-medium visible-xs">
                </div>

                <div class="col-xs-12 col-md-5">
                    <h2 class="Util--text-primary">Contact Information</h2>
                    <hr class="Util--spacer-trans-small">

                    <table class="table">
                        <tbody>
                            <tr>
                                <td class="Util--border-none">Email:</td>
                                <td class="Util--border-none">contact@apply.me</td>
                            </tr>
                            <tr>
                                <td class="Util--border-none">Phone:</td>
                                <td class="Util--border-none">
                                    1-800-494-4908<br>
                                    Mon–Fri, 10a.m.–7p.m. EST.
                                </td>
                            </tr>
                            <tr>
                                <td class="Util--border-none">Address:</td>
                                <td class="Util--border-none">
                                    Apply Me, Inc.<br>
                                    427 N Tatnall St #91572<br>
                                    Wilmington, Delaware 19801-2230<br>
                                    Tax ID:37-1872831
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <hr class="Util--spacer-trans-micro">

                    <div class="text-center">
                        <a
                            href="{{ route('front.index') }}"
                            title="">
                            <img src="imgs/icons/am-revert.svg" alt="">
                        </a>
                    </div>
                </div>
            </div>

            <hr class="Util--spacer-trans-small">

            <div class="row">
                <div class="col-xs-12">
                    <ul class="list-inline text-center social-icons">
                        <li><a href="https://www.facebook.com/applyme" target="_blank"><i class="fab fa-facebook-square fa-3x"></i></a></li>
                        <li><a href="https://www.pinterest.com/applyme" target="_blank"><i class="fab fa-pinterest-square fa-3x"></i></a></li>
                        <li><a href="https://twitter.com/applymeapp" target="_blank"><i class="fab fa-twitter-square fa-3x"></i></a></li>
                        <li><a href="https://www.instagram.com/applyme" target="_blank"><i class="fab fa-instagram fa-3x"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

@stop
