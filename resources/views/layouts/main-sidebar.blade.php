@php
    $ids = parentId();
    $authUser = \App\Models\User::find($ids);
    $subscription = \App\Models\Subscription::find($authUser->subscription);
    $settings = settings();
    $user = \Auth::user();
    $companyLogo = $settings['company_logo'] ?? 'default-logo.png'; // Provide a fallback
@endphp
<!-- main-sidbar -->
<style>
    .side-menu__item i {
        font-size: 18px !important;
        padding-left: 0px;
    }

    .app-sidebar .slide-menu a:before {
        font-family: 'feather' !important;
        position: absolute;
        top: 9px;
        left: 27px;
        font-size: 9px;
        color: #6d7790;
        display: none;
    }

    .slide-item i {
        font-size: 12px !important;
        padding-right: 10px !important;
    }

	.slide-item {    
    	padding: 0 0 0 45px !important;    
	}
</style>

<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll">
    <div class="main-sidebar-header active">
        <a class="desktop-logo logo-light active" href="">
            {{-- <img src="" class="main-logo" alt="logo"> --}}
            <img src="{{ URL::asset('assets/logo/' . $companyLogo) }}" class="main-logo" alt="logo">
        </a>

        <a class="desktop-logo logo-dark active" href="">
            <img src="{{ URL::asset('assets/img/faces/logo.jpg') }}" class="main-logo dark-theme" alt="logo">
        </a>
    </div>

    <div class="main-sidemenu">
        <div class="app-sidebar__user clearfix">
            <div class="dropdown user-pro-body">
                <div class="">
                    @if (\Auth::user())
                        <img alt="user-img" class="avatar avatar-xl brround"
                            src="{{ URL::asset(\Auth::user()->profile ?? 'assets/img/media/admin.jpg') }}">
                    @else
                        <img alt="user-img" class="avatar avatar-xl brround"
                            src="{{ URL::asset('assets/img/media/admin.jpg') }}">
                    @endif
                    <span class="avatar-status profile-status bg-green"></span>
                </div>
                <div class="user-info">
                    <h4 class="font-weight-semibold mt-3 mb-0">
                        {{ \Auth::user()->full_name }}
                    </h4>
                    <span class="mb-0 text-muted">{{ \Auth::user()->type }}</span>
                </div>
            </div>
        </div>
        @if (\Auth::user()->type !== 'technician')
            <ul class="side-menu">
                <li class="side-item side-item-category">Main</li>
                @if (Gate::check('show dashboard '))
                    <li class="slide">
                        <a class="side-menu__item" href="/">
                            <i class="fas fa-tachometer-alt pr-3"></i>
                            <span class="side-menu__label">Dashboard</span>
                        </a>
                    </li>
                @endif

                @if (Gate::check('show chat'))
                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('chat') }}">
                            <i class="far fa-comment-dots pr-3"></i>
                            <span class="side-menu__label">Chat</span>
                        </a>
                    </li>
                @endif

                @if (Gate::check('show mail'))
                    <li class="slide">
                        <a class="side-menu__item" href="{{ url('mail') }}">
                            <i class="far fa-envelope pr-3"></i>
                            <span class="side-menu__label">Mail</span>
                        </a>
                    </li>
                @endif

                <li class="side-item side-item-category">Business Mangement</li>
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                        <i class="fas fa-chart-line pr-3"></i>
                        <span class="side-menu__label">Sales Managment</span>
                        <i class="angle fe fe-chevron-down"></i>
                    </a>
                    <ul class="slide-menu">
                        @if (Gate::check('show customer'))
                            <li>
                                <a class="slide-item" href="{{ route('client.index') }}">
                                    <i class="fas fa-user-plus"></i>
                                    Customer
                                </a>
                            </li>
                        @endif

                        @if (Gate::check('show booking'))
                            <li>
                                <a class="slide-item" href="{{ route('booking.index') }}">
                                    <i class="fas fa-calendar-check"></i>
                                    Booking
                                </a>
                            </li>
                        @endif

                           <li>
                                <a class="slide-item" href="{{ route('inspection.index') }}">
                                    <i class="fas fa-calendar-check"></i>
                                    Inspection
                                </a>
                            </li>
                      
                       
                        @if (Gate::check('show quotation'))
                            <li>
                                <a class="slide-item" href="{{ route('estimation.index') }}">
                                    <i class="fas fa-sticky-note"></i>
                                    Quotation
                                </a>
                            </li>
                        @endif
                          @if (Gate::check('show work order '))
                            <li>
                                <a class="slide-item" href="{{ route('workorder.index') }}">
                                    <i class="fas fa-toolbox"></i>
                                    WorkOrder
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                        <i class="fas fa-dollar-sign pr-3"></i>
                        <span class="side-menu__label">Account Managment</span>
                        <i class="angle fe fe-chevron-down"></i>
                    </a>
                    <ul class="slide-menu">
                        @if (Gate::check('show invoice '))
                            <li>
                                <a class="slide-item" href="{{ route('invoice.index') }}">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                    Invoice
                                </a>
                            </li>
                        @endif

                        @if (Gate::check('show payment '))
                            <li>
                                <a class="slide-item" href="{{ route('payment.index') }}">
                                    <i class="fab fa-paypal"></i>
                                    Payment
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                        <i class="fas fa-clipboard-list pr-3"></i>
                        <span class="side-menu__label">Inventory Managment</span>
                        <i class="angle fe fe-chevron-down"></i>
                    </a>
                    <ul class="slide-menu">
                        @if (Gate::check('show service & part '))
                            <li>
                                <a class="slide-item" href="{{ route('services-parts.index') }}">
                                    <i class="fas fa-sitemap"></i>
                                    Item master
                                </a>
                            </li>
                        @endif
                        @if (Gate::check('show service '))
                            <li>
                                <a class="slide-item" href="{{ route('service.index') }}">
                                    <i class="fas fa-server"></i>
                                    Services
                                </a>
                            </li>
                        @endif
                        @if (Gate::check('show adjustment '))
                            <li>
                                <a class="slide-item" href="{{ route('adjustment_item.index') }}">
                                    <i class="fas fa-sort-amount-up"></i>
                                    Adjustments
                                </a>
                            </li>
                        @endif
                        @if (Gate::check('show adjustment report '))
                            <li>
                                <a class="slide-item" href="{{ route('servicepartadjustment.index') }}">
                                    <i class="fas fa-list-ol"></i>
                                    Adjustment Report
                                </a>
                            </li>
                        @endif
                        @if (Gate::check('show inventory '))
                            <li>
                                <a class="slide-item" href="{{ route('inventory.index') }}">
                                    <i class="fas fa-clipboard-list"></i>
                                    Inventory Details
                                </a>
                            </li>
                        @endif
                        @if (Gate::check('show service group '))
                            <li>
                                <a class="slide-item" href="{{ route('service-group.index') }}">
                                    <i class="fas fa-layer-group"></i>
                                    Service Group
                                </a>
                            </li>
                        @endif
                        @if (Gate::check('show warehouse '))
                            <li>
                                <a class="slide-item" href="{{ route('warehouse.index') }}">
                                    <i class="fas fa-warehouse"></i>Warehouse
                                </a>
                            </li>
                        @endif
                        <!-- @if (Gate::check('item with vehicle '))
                            <li>
                                <a class="slide-item" href="{{ route('servicepartwithvehicle.index') }}">
                                    <i class="fas fa-tools"></i>
                                    Item with Vehicle
                                </a>
                            </li>
                        @endif -->
                        @if (Gate::check('show transfer order '))
                            <li>
                                <a class="slide-item" href="{{ route('transfer.index') }}">
                                    <i class="fas fa-arrow-alt-circle-right"></i>
                                    Transfer Order
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                        <i class="fas fa-cubes pr-3"></i>
                        <span class="side-menu__label">Resource Managment</span>
                        <i class="angle fe fe-chevron-down"></i>
                    </a>
                    <ul class="slide-menu">
                        @if (Gate::check('show vehicle '))
                            <li>
                                <a class="slide-item" href="{{ route('vehicle.index') }}">
                                    <i class="fas fa-truck"></i>
                                    Equipment Master
                                </a>
                            </li>
                        @endif
                        @if (Gate::check('show technician '))
                            <li>
                                <a class="slide-item" href="{{ route('technician.index') }}">
                                    <i class="fas fa-user-secret"></i>
                                    Technician & Supervisor 
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                        <i class="fas fa-clock pr-3"></i>
                        <span class="side-menu__label">Warrranty Managment</span>
                        <i class="angle fe fe-chevron-down"></i>
                    </a>
                    <ul class="slide-menu">
                        @if (Gate::check('show warranty registration '))
                            <li>
                                <a class="slide-item" href="{{ route('warrentyRegistration.index') }}">
                                    <i class="fas fa-copy"></i>
                                    Warranty Registration
                                </a>
                            </li>
                        @endif
                        @if (Gate::check('show warranty claim '))
                            <li>
                                <a class="slide-item" href="{{ route('warrentyitems.index') }}">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Warranty Claim
                                </a>
                            </li>
                        @endif
                        @if (Gate::check('show warranty extend '))
                            <li>
                                <a class="slide-item" href="{{ route('warrentyextend.index') }}">
                                    <i class="far fa-calendar-check"></i>
                                    Warranty Extend
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                        <i class="fas fa-users-cog pr-3"></i>
                        <span class="side-menu__label">Master Setting</span>
                        <i class="angle fe fe-chevron-down"></i>
                    </a>
                    <ul class="slide-menu">
                        @if (Gate::check('show vehicle make '))
                            <li>
                                <a class="slide-item" href="{{ route('vehiclemake.index') }}">
                                    <i class="fas fa-ambulance"></i>
                                    Equipment Make
                                </a>
                            </li>
                        @endif
                        @if (Gate::check('show vehicle model '))
                            <li>
                                <a class="slide-item" href="{{ route('vehiclemodel.index') }}">
                                    <i class="fas fa-tractor"></i>
                                    Equipment Model
                                </a>
                            </li>
                        @endif
                        @if (Gate::check('show skill '))
                            <li>
                                <a class="slide-item" href="{{ route('skill.index') }}">
                                    <i class="fas fa-snowboarding"></i>
                                    Skill
                                </a>
                            </li>
                        @endif
                        @if (Gate::check('show skill group '))
                            <li>
                                <a class="slide-item" href="{{ route('skill-group.index') }}">
                                    <i class="far fa-snowflake"></i>
                                    Skill group
                                </a>
                            </li>
                        @endif
                        @if (Gate::check('show uom '))
                            <li>
                                <a class="slide-item" href="{{ route('uom.index') }}">
                                    <i class="fas fa-feather-alt"></i>
                                    UOM
                                </a>
                            </li>
                        @endif
                        @if (Gate::check('show regionalspecs '))
                            <li>
                                <a class="slide-item" href="{{ route('regionalspecs.index') }}">
                                    <i class="fas fa-globe-europe"></i>
                                    Regional Specs
                                </a>
                            </li>
                        @endif
                        <!-- @if (Gate::check('customer group '))
                            <li>
                                <a class="slide-item" href="{{ route('customergroup.index') }}">
                                    <i class="fas fa-users"></i>
                                    Customer Group
                                </a>
                            </li>
                        @endif -->
                        @if (Gate::check('show enginespecs '))
                            <li>
                                <a class="slide-item" href="{{ route('enginespecification.index') }}">
                                    <i class="fab fa-searchengin"></i>
                                    Engine Specification
                                </a>
                            </li>
                        @endif
                        @if (Gate::check('show brand '))
                            <li>
                                <a class="slide-item" href="{{ route('brand.index') }}">
                                    <i class="fas fa-random"></i>
                                    Brand
                                </a>
                            </li>
                        @endif
                        @if (Gate::check('show origin '))
                            <li>
                                <a class="slide-item" href="{{ route('origin.index') }}">
                                    <i class="fas fa-flag-usa"></i>
                                    Origin
                                </a>
                            </li>
                        @endif
                        @if (Gate::check('show item category '))
                            <li>
                                <a class="slide-item" href="{{ route('categories.index') }}">
                                    <i class="fas fa-tools"></i>
                                    Item Category
                                </a>
                            </li>
                        @endif
                        @if (Gate::check('show customer template '))
                            <li>
                                <a class="slide-item" href="{{ route('customer-template.index') }}">
                                    <i class="fas fa-portrait"></i>
                                    Customer Template
                                </a>
                            </li>
                        @endif

                        @if (Gate::check('show shift '))
                            <li>
                                <a class="slide-item" href="{{ route('shiftmasters.index') }}">
                                    <i class="fas fa-sync-alt"></i>
                                    Shift Masters
                                </a>
                            </li>
                        @endif
                         <li>
                                <a class="slide-item" href="{{ route('inspection.groupindex') }}">
                                    <i class="fas fa-calendar-check"></i>
                                    Inspection Group
                                </a>
                            </li>
                             <li>
                                <a class="slide-item" href="{{ route('inspection.templatesindex') }}">
                                    <i class="fas fa-calendar-check"></i>
                                    Inspection Template
                                </a>
                            </li>
                    </ul>
                </li>

                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                        <i class="fas fa-cog pr-3"></i>
                        <span class="side-menu__label">Settings</span>
                        <i class="angle fe fe-chevron-down"></i>
                    </a>
                    <ul class="slide-menu">
                        @if (Gate::check('show general settings'))
                            <li>
                                <a class="slide-item" href="{{ route('setting.general') }}">
                                    <i class="fas fa-sliders-h"></i>
                                    {{ __('General Setting') }}
                                </a>
                            </li>
                        @endif

                        @if (Gate::check('show company settings'))
                            <li>
                                <a class="slide-item" href="{{ route('setting.company') }}">
                                    <i class="fas fa-users-cog"></i>
                                    {{ __('Company Setting') }}
                                </a>
                            </li>
                        @endif

                        @if (Gate::check('show email settings'))
                            <li>
                                <a class="slide-item" href="{{ route('setting.smtp') }}">
                                    <i class="fas fa-envelope"></i>
                                    {{ __('Email Setting') }}
                                </a>
                            </li>
                        @endif

                        @if (Gate::check('show payment settings'))
                            <li>
                                <a class="slide-item" href="{{ route('setting.payment') }}">
                                    <i class="fas fa-money-check-alt"></i>
                                    {{ __('Payment Setting') }}
                                </a>
                            </li>
                        @endif

                        @if (Gate::check('show seo settings'))
                            <li>
                                <a class="slide-item" href="{{ route('setting.site.seo') }}">
                                    <i class="fas fa-home"></i>
                                    {{ __('Site SEO Setting') }}
                                </a>
                            </li>
                        @endif

						@if (Gate::check('show inventory setup'))
							<li>
								<a class="slide-item" href="{{ route('inventory.setup') }}">
									<i class="fas fa-clipboard-list"></i>
									Inventory Setup
								</a>
							</li>
                        @endif

                        @if (Gate::check('show google recaptcha settings'))
                            <li>
                                <a class="slide-item" href="{{ route('setting.google.recaptcha') }}">
                                    <i class="fab fa-google-wallet"></i>
                                    {{ __('ReCaptcha Setting') }}
                                </a>
                            </li>
                        @endif

						@if (Gate::check('show technician logs settings'))
							<li>
								<a class="slide-item" href="{{ route('technicianlog.index') }}">
									<i class="fas fa-cogs"></i>
									Technician Logs Setting
								</a>
							</li>
                        @endif

						{{-- @if (Gate::check('show module setup'))
							<li class="sub-slide  is-expanded">
								<a class="sub-side-menu__item" style="padding-left: 50px !important;" data-toggle="sub-slide" href="#">
									<i class="fas fa-cog pr-3"></i>
									<span class="side-menu__label">Module Setup</span>
									<i class="angle fe fe-chevron-down"></i>
								</a>

								<ul class="sub-slide-menu">
									<li>
										<a class="sub-slide-item" style="padding-left: 33px !important;" href="{{ route('setting.module.index') }}">
											<i class="fas fa-yin-yang pr-2"></i> Sales Module Setup
										</a>
									</li>
									<li>
										<a class="sub-slide-item" style="padding-left: 33px !important;" href="{{ route('setting.scrap.index') }}">
											<i class="fas fa-car-battery pr-2"></i> Scrap Module Setup
										</a>
									</li>
								</ul>
							</li>
                        @endif --}}
                    </ul>
                </li>
				
				<li class="slide">
					<a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
						<i class="fas fa-user-edit pr-3"></i>
						<span class="side-menu__label">Staff Managment</span>
						<i class="angle fe fe-chevron-down"></i>
					</a>
					<ul class="slide-menu">
						@if (Gate::check('user '))
							<li>
								<a class="slide-item" href="{{ route('users.index') }}">
									<i class="fas fa-user"></i>
									Users
								</a>
							</li>
						@endif
						@if (Gate::check('role '))
							<li>
								<a class="slide-item" href="{{ route('role.index') }}">
									<i class="fas fa-user-tag"></i>
									Roles
								</a>
							</li>
						@endif
						{{-- @if (Gate::check('permission '))
							<li>
								<a class="slide-item" href="{{ route('permission.index') }}">
									<i class="fas fa-user-lock"></i>
									Permissions
								</a>
							</li>
						@endif --}}

						@if (Gate::check('reset password '))
							<li>
								<a class="slide-item" href="{{ route('reset-password') }}">
									<i class="fas fa-unlock-alt"></i>
									Reset password
								</a>
							</li>
						@endif
					</ul>
				</li>
            </ul>
        @endif
        @if (\Auth::user()->type == 'technician')
            <ul class="side-menu">
                <li class="side-item side-item-category">Main</li>
                <li class="slide">
                    <a class="side-menu__item" href="/"><svg xmlns="http://www.w3.org/2000/svg"
						class="side-menu__icon" viewBox="0 0 24 24">
						<path d="M0 0h24v24H0V0z" fill="none" />
						<path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3" />
						<path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z" />
						</svg>
						<span class="side-menu__label">
							Dashboard
						</span>
					</a>
                </li>

				@if (Gate::check('technician chat '))
					<li class="slide">
						<a class="side-menu__item" href="{{ route('tech.chat') }}">
							<i class="far fa-comment-dots pr-3"></i>
							<span class="side-menu__label">Chat</span>
						</a>
					</li>
                @endif

                @if (Gate::check('technician calendar '))
                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('calender') }}">
							<i class="icon-event text-primary  mr-3" style="font-size:20px;"></i>
                            <span class="side-menu__label">My Calendar</span>
						</a>
                    </li>
                @endif

				@if (Gate::check('technician inventory '))
                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('servicepartadjustment.index') }}">
							<i class="icon-menu text-primary  mr-3" style="font-size:20px;"></i>
                            <span class="side-menu__label">My Inventory</span>
						</a>
                    </li>
                @endif
                
                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('workorders.technicianindex') }}">
							<i class="icon-rocket text-primary  mr-3" style="font-size:20px;"></i>
                            <span class="side-menu__label">All Workorders</span>
						</a>
                    </li>
               

                @if (Gate::check('technician invoice '))
                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('invoice.technicianindex') }}">
							<i class="icon-layers text-primary  mr-3" style="font-size:20px;"></i>
                            <span class="side-menu__label">Invoices</span>
						</a>
                    </li>
                @endif

                @if (Gate::check('technician payment '))
                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('payment.technicianindex') }}">
							<i class="icon-paypal text-primary  mr-3" style="font-size:20px;"></i>
                            <span class="side-menu__label">Payments</span>
						</a>
                    </li>
                @endif
            </ul>
        @endif
    </div>
</aside>