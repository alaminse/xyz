@extends('layouts.frontend')
@section('title', $page_title)
@section('content')

    @include('frontend.includes.bradcaump')

    <section class="htc__login__container text-center ptb--80 bg__white">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                    <div class="login__area__wrap">
                        <div class="login__inner">
                            <div class="res__title">
                                <h2>Please Sign Up</h2>
                                <div class="res__right">
                                    <h4>Already have an account?</h4>
                                    <div class="sign__btn">
                                        <a class="htc__sign__btn" href="#">Sign In</a>
                                    </div>
                                </div>
                            </div>
                            <div class="login__form__box">
                                <div class="login__form first__last__name">
                                    <input type="text" placeholder="Your Fast Name">
                                    <input type="text" placeholder="Your Last Name">
                                </div>
                                <div class="login__form">
                                    <input type="email" placeholder="Email Address">
                                </div>
                                <div class="login__btn">
                                    <a class="htc__btn btn--theme" href="#">register</a>
                                </div>
                            </div>
                            <div class="login__social__link">
                                <h2>Contact With Social Network</h2>
                                <ul class="htc__social__btn">
                                    <li><a href="https://www.facebook.com/devitems/?ref=bookmarks" target="_blank">
                                            <i class="icon ion-social-facebook"></i><span>facebook</span>
                                        </a></li>
                                    <li><a href="https://plus.google.com/" target="_blank">
                                            <i class="icon ion-social-googleplus"></i><span>google +</span>
                                        </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
