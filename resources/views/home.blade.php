@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">About Vasudev</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <div class="content">
                        <h5 class="subtitle m-b-md">
                            <p>We've used our expertise and experience in digital communications and mix of the best technologies to bring you one of the most powerful communication and customer engagement solutions. We can flood you with our data on uptime or latency, but that does not show how hard we work for it. We just say that we make sure that each communication gets through - with highest quality possible.</p>
                            <p>Priyang Haribhai Patel, Founder : VASUDEV Group</p>                        
                        </h5>

                        <p>
                            <div id="header-contacts">
                                <div class="header-contacts-inner row">
                                    <div class="phone col-sm-3">
                                        <i class="fa fa-phone" aria-hidden="true"></i> &nbsp;&nbsp;<a href="tel:+91 908 145 6969 " rel="nofollow" onclick="gtag('event', 'click', {'event_category': 'headerphone','event_label': 'phoneclick','value' : '1'});">+91 908 145 6969 </a>
                                    </div>                                    
                                    <div class="email col-sm-3">
                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                        &nbsp;&nbsp;<a href="mailto:smscare@vasudev.com" onclick="gtag('event', 'click', {'event_category': 'headeremail','event_label': 'emailclick','value' : '1'});">smscare@vasudev.com</a>
                                    </div>
                                    <div class="sms col-sm-4">
                                        <i class="fa fa-mobile" aria-hidden="true"></i>&nbsp;&nbsp;SMS <strong>Vasudev</strong> to <strong>+91 908 145 6969</strong>
                                    </div>
                                </div>
                            </div>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection