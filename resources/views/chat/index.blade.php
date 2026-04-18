@extends('layouts.master')
@section('title', 'Chat')
@section('css')
    <style>
        #ChatBody {
            height: calc(100vh - 350px);
            overflow-y: auto;
        }

        #main-chat-content {
            height: 100%;
            overflow-y: auto;
        }

        .main-chat-list {
            height: calc(100% - 68px);
            position: relative;
            overflow: auto;
        }
    
        #searchOutput {
            position: absolute;
            background: #fff;
            width: 99%;
            /* left: 0; */
            /* right: 0; */
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            margin-top: 1px;
        }

        #searchUser {
            padding: 10px;
            border: #a8d4b1 1px solid;
            border-radius: 4px;
        }

        #searchOutput.with-border {
            border: 1px solid #c2b5b5 !important;
            border-radius: 3px;
        }

        .search-item {
            padding:5px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }

        .search-item:hover {
            background-color: #f1f1f1;
        }

    
        #searchList {
            float: left;
            list-style: none;
            margin-top: -3px;
            padding: 0;
            width: 100%;
            position: absolute;
            overflow-y: auto;
            height: auto;
            z-index: 1000;
        }

        #searchList option {
            padding: 8px;
            border-bottom: #bbb9b9 1px solid;
        }

        #searchList option:hover {
            background: #ece3d2;
            cursor: pointer;
        }
        
    </style>
@endsection

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">Chat</h5>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Mail</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chat</li>
                </ol>
            </nav>
        </div>    
    </div>
    <div class="row main-chart-wrapper">
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card custom-card">
                <div class="main-content-app pt-0">
                    <div class="main-content-left main-content-left-chat">
                        <span class="nav-link active btn-block btn-danger py-1">Recent Chat</span>
                        <div class="p-1 {{ Auth::user()->type == 'technician' ? 'd-none' : '' }}">
                            <input type="text" id="searchUser" class="form-control" placeholder="Search user by name, email, phone" style="height: 30px !important;">
                            <div id="searchOutput"></div>
                        </div>					                 
                        <div class="tab-content main-chat-list">
                            <div class="tab-pane p-0 border-0 active" id="recent-chat">
                                <div class="chat-users-tab" id="chat-msg-scroll">
                                    <div class="main-chat-list">
                                        @if ($users->isNotEmpty())
                                            @foreach ($users as $user)
                                                <a class="media new" href="#" onclick="loadMessages({{ $user->id }})"
                                                    data-id="{{ $user->id }}">
                                                    <div class="main-img-user">
                                                        <img alt="" src="{{ asset($user->profile) }}">
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="media-contact-name flex-wrap">
                                                            <span>{{ $user->first_name }} {{ $user->last_name }}</span>
                                                            <span
                                                                class="ps-2">{{ $user->last_time ? $user->last_time->diffForHumans() : '' }}
                                                        </div>
                                                        <p>
                                                            <strong>
                                                                {{ $user->last_sender_id == Auth::id() ? 'You:' : '' }}
                                                            </strong>
                                                            {{ Str::limit($user->last_message ?? '', 28) }}
                                                        </p>
                                                    </div>
                                                </a>
                                            @endforeach
                                        @else
                                            <div class="text-center mt-4">
                                                <p class="text-muted">No conversations found.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="card custom-card">
                <div class="main-content-app pt-0">
                    <div class="main-content-body main-content-body-chat">
                        <div class="main-chat-header pt-3">
                            <div class="main-img-user online">
                                <img class="targrtUserImage" src="{{ asset(@$user->profile) }}" alt="avatar">
                            </div>
                            <div class="main-chat-msg-name ms-2">
                                <h6 class="mb-0 fs-15 fw-semibold targrtUserName">{{ @$user->name }}</h6>
                                <span class="dot-label bg-success"></span>
                                <small class="me-3 fs-12 text-muted d-none">Last seen: 2 minutes ago</small>
                            </div>
                        </div>

                        <div class="main-chat-body" id="ChatBody">
                            <div class="content-inner chat-content" id="main-chat-content">
                                <!-- Messages will be loaded here via AJAX -->
                            </div>
                        </div>

                        <div class="main-chat-footer">
                            <input id="message" type="text" class="form-control" placeholder="Type your message here...">
                            <a class="main-msg-clip me-2 d-none" href="javascript:void(0);">
                                <i class="fe fe-paperclip"></i>
                            </a>
                            <a id="send" class="main-msg-send" href="javascript:void(0);">
                                <i class="fe fe-send"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

		<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card custom-card chat-account">
                <div class="main-content-app d-block pt-0">
                    <div class="chat-user-details" id="chat-user-details">
                        <div class="card-body text-center">
                            <div class="user-lock text-center">
                                <a href="#">
                                    <img id="targrtUserImage" alt="avatar" class="rounded-circle">
                                </a>
                            </div>
                            <a href="#">
                                <h5 id="profile-name" class="mb-1 mt-3 fs-17"></h5>
                            </a>
                            <p id="profile-type" class="mb-0 fs-13 text-muted"></p>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-3">Contact Details</h6>
                            <div class="d-flex">
                                <div>
                                    <p class="contact-icon text-primary m-0"><i class="fe fe-phone"></i></p>
                                </div>
                                <div class="ms-3">
                                    <p class="tx-13 mb-0">Phone</p>
                                    <p id="profile-phone" class="fs-12 text-muted"></p>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div>
                                    <p class="contact-icon text-primary m-0"><i class="fe fe-mail"></i></p>
                                </div>
                                <div class="ms-3">
                                    <p class="tx-13 mb-0">Email</p>
                                    <p id="profile-email" class="fs-12 text-muted"></p>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div>
                                    <p class="contact-icon text-primary m-0"><i class="fe fe-map-pin"></i></p>
                                </div>
                                <div class="ms-3">
                                    <p class="tx-13 mb-0">Address</p>
                                    <p id="profile-address" class="fs-12 text-muted mb-0"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

<script>
	var currentUserId = null;

	$(document).ready(function() {
		@if ($lastUser)
			let lastUserId = {{ $lastUser->id }};
			loadMessages(lastUserId);
		@endif
	});

	function loadMessages(userId) {        
		currentUserId = userId;
		fetchMessages(userId);
		fetchUserProfile(userId);

		$('.main-chat-list .media').removeClass('selected');
		$('.main-chat-list .media[data-id="' + userId + '"]').addClass('selected');
	}

	function fetchMessages(userId) {
		$.get(`/fetch-messages/${userId}`, function(response) {
			if (response.html && response.html.trim() !== '') {
				console.log(response);
				$('#main-chat-content').html(response.html);
				$('#main-chat-content').scrollTop($('#main-chat-content')[0].scrollHeight);
			} else {
				$('#main-chat-content').html(`
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <b class="text-center text-muted">No messages yet. Start the conversation!</b>
                    </div>
                `);
			}
			$('#message').focus();

		}).fail(function(xhr) {
			console.error('Error fetching messages:', xhr.responseText);
		});
	}

	$('#send').on('click', function() {
		const message = $('#message').val();
		if (message.trim() === '') return;

		$.post('/send-message', {
			_token: $('meta[name="csrf-token"]').attr('content'),
			receiver_id: currentUserId,
			content: message
		}, function(response) {
			$('#message').val('');
			fetchMessages(currentUserId);
		});
	});

	function fetchUserProfile(userId) {
		$.get(`/fetch-users/${userId}`, function(user) {
			//Middle part
			$('.targrtUserName').text(user.first_name);
			$('.targrtUserImage').attr('src', `/${user.profile}`);

			//Right part
            $('#targrtUserImage').attr('src', `/${user.profile}`);
			$('#profile-name').text(user.first_name);
			$('#profile-type').text(user.type);
			$('#profile-phone').text(user.phone_number);
			$('#profile-email').text(user.email);
			$('#profile-address').text(user.clients.service_address);
		});
	}

	function scrollToBottom() {
		const chatBox = $('#main-chat-content');
		chatBox.scrollTop(chatBox[0].scrollHeight);
	}

	$("#message").keypress(function(event) {
		if (event.which === 13) {
			event.preventDefault();
			$("#send").click();
		}
	});

    //Search user 
    $('#searchUser').on("keyup", function() {
        var getName = $(this).val();
        if (getName.length > 0) {
            $.ajax({
                url: '{{ url('search-user') }}',
                method: 'GET',
                data: {
                    name: getName
                },
                beforeSend: function() {
                    $("#searchUser").css("background", "#FFF url('/loader.gif') no-repeat 165px");
                },
                success: function(response) {
                    $("#searchOutput").empty().show();

                    if (response.length > 0) {
                        $.each(response, function(index, customer) {
                            var item = $('<div class="search-item"></div>')
                                .text(customer.first_name + ' | ' + customer.email)
                                .on('click', function() {
                                    selectName(customer.id);
                                });

                            $("#searchOutput").append(item);
                        });
                        $("#searchOutput").addClass('with-border').show();

                    } else {
                        $("#searchOutput").html('<p>No results found</p>').addClass('with-border').show();
                    }
                    $("#searchUser").css("background", "#FFF");
                }
            });
        } else {
            $("#searchOutput").hide();
        }
    });
    
    function selectName(userId) {
        $("#searchUser").val('');
        $("#searchOutput").hide().removeClass('with-border');
        loadMessages(userId);
    }
    
    // Refresh matter
    let messageInterval = null;
    function startMessageInterval() {
        stopMessageInterval();
        messageInterval = setInterval(function() {
            if (currentUserId !== null) {
                fetchMessages(currentUserId);
            }
        }, 3000);
    }

    function stopMessageInterval() {
        if (messageInterval !== null) {
            clearInterval(messageInterval);
            messageInterval = null;
        }
    }

    startMessageInterval();
    $('#searchUser').on('focus keyup', function() {
        if ($(this).val().trim().length > 0) {
            stopMessageInterval();
        } else {
            startMessageInterval();
        }
    });

    $('#searchUser').on('blur', function() {
        if ($(this).val().trim().length === 0) {
            startMessageInterval();
        }
    });
</script>
@endsection