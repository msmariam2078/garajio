<div class="col-lg-4 col-xl-3 col-md-12 col-sm-12">
    <div class="card mb-4">
        <div class="main-content-left main-content-left-mail card-body">
            <a class="btn btn-primary btn-compose" href="#" id="btnCompose">Email</a>
            <div class="main-mail-menu">
                <nav class="nav main-nav-column mb-4" class="nav main-nav-column mb-4" id="mailTab" role="tablist">
					<a class="nav-link active" id="inbox-tab" data-bs-toggle="tab" data-bs-target="#inbox" role="tab" href="javascript:void(0);">
						<i class="bx bxs-inbox"></i> Inbox <span class="badge bg-primary text-light">{{ $emailStatus['send'] ?? 0 }}</span>
					</a>
					<a class="nav-link" id="sent-tab" data-bs-toggle="tab" data-bs-target="#sent" role="tab" href="javascript:void(0);">
						<i class="bx bx-send"></i> Sent Mail <span class="badge bg-info text-light">{{ $emailStatus['send'] ?? 0 }}</span>
					</a>

					<a class="nav-link" id="draft-tab" data-bs-toggle="tab" data-bs-target="#draft" role="tab" href="javascript:void(0);">
						<i class="bx bx-edit"></i> Drafts <span class="badge bg-secondary text-light">{{ $emailStatus['draft'] ?? 0 }}</span>
					</a>
					<a class="nav-link" id="trash-tab" data-bs-toggle="tab" data-bs-target="#trash" role="tab" href="javascript:void(0);">
						<i class="bx bx-trash"></i> Trash <span class="badge bg-dark text-light">{{ $emailStatus['trash'] ?? 0 }}</span>
					</a>

					<a class="nav-link" id="compose-tab" data-bs-toggle="tab" data-bs-target="#compose" role="tab" href="javascript:void(0);">
						<i class="bx bx-edit"></i> Email compose
					</a>
                </nav>
            </div>
        </div>
    </div>
</div>