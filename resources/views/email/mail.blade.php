@extends('layouts.master')
@section('title', 'Mail')
<style>
	.main-mail-list {
		height: 70vh !important;
		overflow-y: auto !important;
	}
</style>

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">Mail</h5>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Mail</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Mail</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row main-content-mail">
        @include('email.leftSideMail')

        <div class="col-lg-8 col-xl-9 col-md-12">
            <div class="tab-content" id="mailTabContent">
                <div class="tab-pane fade show active" id="inbox" role="tabpanel">
                    <div class="card">
                        <div class="main-content-body main-content-body-mail card-body">
                            <div class="main-mail-header">
                                <div>
                                    <h4 class="main-content-title mb-0">Inbox</h4>
                                </div>
                            </div>
                            <div class="main-mail-list border-top">
                                @foreach ($emails->where('status', 'send') as $item)
                                    <div class="main-mail-item"
										onclick="showEmailDetails(this)"
										data-id="{{ $item->id }}"
										data-recipient="{{ $item->recipient }}"
										data-subject="{{ $item->subject }}"
										data-body="{{ htmlentities($item->body) }}"
										data-date="{{ $item->created_at }}"
										data-type="inbox">
                                        <div class="main-mail-body">
                                            <div class="main-mail-from">
                                                {{ $item->recipient }}
                                            </div>
                                            <div class="main-mail-subject">
                                                <strong>{{ $item->subject }}</strong>
                                                <br>
                                                <span>{{ $item->body }}</span>
                                            </div>
                                        </div>
                                        <div class="main-mail-date">
                                            {{ $item->created_at ? $item->created_at->diffForHumans() : '' }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="sent" role="tabpanel">
                    <div class="card">
                        <div class="main-content-body main-content-body-mail card-body">
                            <div class="main-mail-header">
                                <div>
                                    <h4 class="main-content-title mb-0">Send email</h4>
                                </div>
                            </div>
                            <div class="main-mail-list border-top">
                                @foreach ($emails->where('status', 'send') as $item)
                                    <div class="main-mail-item"
										onclick="showEmailDetails(this)"
										data-id="{{ $item->id }}"
										data-recipient="{{ $item->recipient }}"
										data-subject="{{ $item->subject }}"
										data-body="{{ htmlentities($item->body) }}"
										data-date="{{ $item->created_at }}"
										data-type="sent">
                                        <div class="main-mail-body">
                                            <div class="main-mail-from">
                                                {{ $item->recipient }}
                                            </div>
                                            <div class="main-mail-subject">
                                                <strong>{{ $item->subject }}</strong>
                                                <br>
                                                <span>{{ $item->body }}</span>
                                            </div>
                                        </div>
                                        <div class="main-mail-date">
                                            {{ $item->created_at ? $item->created_at->diffForHumans() : '' }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="draft" role="tabpanel">
                    <div class="card">
                        <div class="main-content-body main-content-body-mail card-body">
                            <div class="main-mail-header">
                                <div>
                                    <h4 class="main-content-title mb-0">Draft</h4>
                                </div>
                            </div>
                            <div class="main-mail-list border-top">
                                @foreach ($emails->where('status', 'draft') as $item)
                                    <div class="main-mail-item" 
										onclick="showEmailDetails(this)"
										data-id="{{ $item->id }}"
										data-recipient="{{ $item->recipient }}"
										data-subject="{{ $item->subject }}"
										data-body="{{ htmlentities($item->body) }}"
										data-date="{{ $item->created_at }}"
										data-type="draft">
                                        <div class="main-mail-body">
                                            <div class="main-mail-from">
                                                {{ $item->recipient }}
                                            </div>
                                            <div class="main-mail-subject">
                                                <strong>{{ $item->subject }}</strong>
                                                <br>
                                                <span>{{ $item->body }}</span>
                                            </div>
                                        </div>
                                        <div class="main-mail-date">
                                            {{ $item->created_at ? $item->created_at->diffForHumans() : '' }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="trash" role="tabpanel">
                    <div class="card">
                        <div class="main-content-body main-content-body-mail card-body">
                            <div class="main-mail-header">
                                <div>
                                    <h4 class="main-content-title mb-0">Trash</h4>
                                </div>
                            </div>
                            <div class="main-mail-list border-top">
                                @foreach ($emails->where('status', 'trash') as $item)
                                    <div class="main-mail-item">
                                        <div class="main-mail-body">
                                            <div class="main-mail-from">
                                                {{ $item->recipient }}
                                            </div>
                                            <div class="main-mail-subject">
                                                <strong>{{ $item->subject }}</strong>
                                                <br>
                                                <span>{{ $item->body }}</span>
                                            </div>
                                        </div>
                                        <div class="main-mail-date">
                                            {{ $item->created_at ? $item->created_at->diffForHumans() : '' }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="compose" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Compose new message</h3>
                        </div>
                        <form method="POST" action="{{ route('makeEmail') }}">
                            @csrf
							<input type="hidden" id="draftId" name="draftId" value="">
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <div class="row align-items-center">
                                        <label class="col-sm-2">To</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="recipient" name="recipient"
                                                value="user@gmail.com" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="row align-items-center">
                                        <label class="col-sm-2">Subject</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="subject" name="subject" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="row ">
                                        <label class="col-sm-2">Message</label>
                                        <div class="col-sm-10">
                                            <textarea rows="10" class="form-control" id="body" name="body" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-center">
                                <div class="btn-list">
                                    <button type="submit" class="btn btn-success" name="draft">Draft</button>
                                    <button type="submit" class="btn btn-danger" name="send">Send</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="d-none">
                    <div class="card">
                        <div class="card-header">
                            <h4>Email title
								<span class="badge bg-primary text-light">inbox</span>
							</h4>
                        </div>
                        <div class="card-body">
                            <div class="email-media">
                                <div class="mt-0 d-sm-flex">                                   
                                    <div class="media-body">
                                        <div class="float-end d-none d-md-flex">
                                            <span class="me-3">Sep 13 , 2019 12:45 pm</span>                                            
                                        </div>
                                        <div class="media-title fw-bold mt-3">
											Form: <span class="text-muted">(alicnestle@gmail.com)</span>
                                        </div>
                                        <p class="mb-0">To: (adamcotter@gmail.com) </p>
                                    </div>
                                </div>
                            </div>
                            <div class="eamil-body mt-5">
                                <h6>Hi Sir/Madam</h6>

                                <p>Body</p>

                                <p class="mb-3">Thanking you Sir/Madam</p>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="btn-list">
                                <a class="btn btn-primary text-light back" href="">
									<i class="fa fa-reply me-1"></i>
                                    Back
								</a>
                                <a class="btn btn-info d-none" href="javascript:void(0);">
									<i class="fa fa-share me-1"></i>
                                    Forward
								</a>
                            </div>
                        </div>
                    </div>
                </div>

				<div id="email-detail-card" class="d-none">
					<div class="card">
						<div class="card-header">
							<h4 id="email-title">
								Email title
								<span class="badge bg-primary text-light" id="email-status">inbox</span>
							</h4>
						</div>
						<div class="card-body">
							<div class="email-media">
								<div class="mt-0 d-sm-flex">
									<div class="media-body">
										<div class="float-end d-none d-md-flex">
											<span class="me-3" id="email-date">Date</span>
										</div>
										<div class="media-title fw-bold mt-3">
											From: <span class="text-muted" id="email-from">(sender@example.com)</span>
										</div>
										<p class="mb-0">To: <span id="email-to">(recipient@example.com)</span></p>
									</div>
								</div>
							</div>
							<div class="eamil-body mt-5">
								<h6>Subject:</h6>
								<p id="email-subject">Subject text</p>
				
								<h6>Message:</h6>
								<p id="email-body">Body text</p>
				
								<p class="mb-3">Thanking you Sir/Madam</p>
							</div>
						</div>
						<div class="card-footer">
							<div class="btn-list">
								<a class="btn btn-primary text-light back" href="javascript:void(0);" onclick="hideEmailDetail()">
									<i class="fa fa-reply me-1"></i>
									Back
								</a>
								<a class="btn btn-info d-none" href="javascript:void(0);">
									<i class="fa fa-share me-1"></i>
									Forward
								</a>
							</div>
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
@endsection

{{-- @section('scripts')
    @vite('resources/assets/js/checkall-mail.js')
@endsection --}}

@section('js')
	<script>
		let emailType = null;
		function showEmailDetails(element) {
			const emailData = {
				type: element.dataset.type,
				id: element.dataset.id,
				recipient: element.dataset.recipient,
				subject: element.dataset.subject,
				body: element.dataset.body,
				date: element.dataset.date,
			};

			emailType = emailData.type; // ✅ Assign to global variable

			// Populate the detail section
			document.getElementById('email-from').textContent = emailData.recipient;
			document.getElementById('email-to').textContent = "you@example.com"; // change if dynamic
			document.getElementById('email-subject').textContent = emailData.subject;
			document.getElementById('email-body').textContent = emailData.body;
			document.getElementById('email-date').textContent = new Date(emailData.date).toLocaleString();

			document.getElementById('email-title').firstChild.textContent = emailData.subject + " ";
			document.getElementById('email-status').textContent = emailType;

			if (emailType === 'draft') {			
				document.getElementById('draftId').value = emailData.id;
				document.getElementById('recipient').value = emailData.recipient;
				document.getElementById('subject').value = emailData.subject;
				document.getElementById('body').value = emailData.body;
				document.getElementById('compose-tab').click();
			}else{
				// Show the detail card
				document.getElementById('email-detail-card').classList.remove('d-none');
				$('#'+emailType).hide();
			}
		}

		function hideEmailDetail() {
			$('#'+emailType).show();
			document.getElementById('email-detail-card').classList.add('d-none');
		}
	</script>
@endsection
