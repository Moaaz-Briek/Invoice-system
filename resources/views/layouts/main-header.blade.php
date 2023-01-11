<!-- main-header opened -->
			<div class="main-header sticky side-header nav nav-item">
				<div class="container-fluid">
					<div class="main-header-left ">
					</div>
					<div class="main-header-right">
						<div class="nav nav-item  navbar-nav-right ml-auto">
                            @can('الاشعارات')
							<div class="dropdown nav-item main-header-notification">
								<a class="new nav-link" href="#">
								<svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg><span class=" pulse"></span></a>
								<div class="dropdown-menu">
									<div class="menu-header-content bg-primary text-right">
										<div class="d-flex">
											<h6 class="dropdown-title mb-1 tx-15 text-white font-weight-semibold">الإشعارات</h6>
											<span class="badge badge-pill badge-warning mr-auto my-auto float-left"><a href="{{route('MarkAsRead_all')}}">تعيين الكل مقروء</a></span>
										</div>
										<p class="dropdown-title-text subtext mb-0 text-white op-6 pb-0 tx-12" style="display: inline-block">الإشعارات الغير مقروءة : </p>
                                        <h6 style="color: yellow; display: inline-block" id="notifications_count" )>{{auth()->user()->unreadNotifications->count() }}</h6>
									</div>
                                    <div id="unreadNotifications">
                                    @foreach(auth()->user()->unreadNotifications as $notification)
									<div class="main-notification-list Notification-scroll">
										<a class="d-flex p-3 border-bottom" href="{{url('MarkAsRead')}}/{{$notification->id}}">
											<div class="notifyimg bg-primary">
												<i class="la la-check-circle text-white"></i>
											</div>
											<div class="mr-3">
												<h5 class="notification-label mb-1">تمت إضافة فاتورة بواسطة :
                                                    {{$notification->data['user']}} </h5>
												<div class="notification-subtext">
                                                    {{date_format($notification->created_at, 'A g:i')}} </div>
											</div>
											<div class="mr-auto" >
												<i class="las la-angle-left text-left text-muted"></i>
											</div>
										</a>
									</div>
                                    @endforeach
                                    </div>
								</div>
							</div>
                            @endcan
							<div class="dropdown main-profile-menu nav nav-item nav-link">
								<a class="profile-user d-flex" href=""><img alt="" src="{{URL::asset('assets/img/faces/6.jpg')}}"></a>
								<div class="dropdown-menu">
									<div class="main-header-profile bg-primary p-3">
										<div class="d-flex wd-100p">
											<div class="main-img-user"><img alt="" src="{{URL::asset('assets/img/faces/6.jpg')}}" class=""></div>
											<div class="mr-3 my-auto">
												<h6>{{Auth::User()->name}}</h6><span>{{Auth::User()->email}}</span>
											</div>
										</div>
									</div>
									<a class="dropdown-item" href="{{route('logout')}}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"> <i class="bx bx-log-out"></i> Sign Out</a>
                                    <form id="logout-form" action="{{route('logout')}}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
<!-- /main-header -->
