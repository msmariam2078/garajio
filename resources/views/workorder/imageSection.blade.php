<div class="">
	<div class="accor bg-primary" id="imagesSection">
		<h4 class="m-0">
			<a href="#collapseImagesSection" class="collapsed" data-toggle="collapse"
				aria-expanded="false" aria-controls="collapseImagesSection">
				<i class="si si-cursor-move mr-2"></i>Images Section
			</a>
		</h4>
	</div>
	<div id="collapseImagesSection" class="collapse b-b0" aria-labelledby="imagesSection" data-parent="#accordion">
		<div class="border p-3">
			

			@if(Auth::user()->type == 'technician')
				<form action="{{ route('workOrderImage') }}" method="POST" enctype="multipart/form-data">
					@csrf
					<input type="hidden" name="order_id" value="{{$workOrder->id}}">
					<div class="row justify-content-center">
						<div class="col-md-4 mb-3">
							<label>Image upload</label>
							<input type="file" name="image[]" class="form-control" multiple>
						</div>

						<div class="col-md-4">
							<label for=""></label>
							<button class="btn btn-primary mt-4 btn-block" type="submit">Image upload now</button>
						</div>
					</div>
				</form>	
				<hr>								
			@endif
			
			<div class="row justify-content-md-center">
				@foreach ($workOrderImg as $item)
					<div class="my-2 text-center" style="width: 33%;">
						<div clas>
							<img src="{{asset($item->image)}}" alt="" width="150" height="120">
						</div>
						<div class="py-2">
							<a class="btn btn-sm btn-outline-danger" href="{{route('workOrderImageDelete', $item->id)}}">
								<i class="fas fa-trash-alt pr-1"></i>Delete
							</a>
							@if(Auth::user()->type != 'technician')
								<a class="download btn btn-sm btn-outline-success" href="{{asset($item->image)}}" download aria-label="Download file"
									class="btn btn-outline-success">
									<i class="fas fa-download pr-1"></i>Download
								</a>
							@endif
						</div>
					</div>
				@endforeach
			</div>

			@if(Auth::user()->type != 'technician' && count($workOrderImg) == 0)
				<h5>No image found</h5>
			@endif
		</div>
	</div>
</div>