<div class="modal" id="choose_file_modal">

    <div class="modal-dialog">

        <div class="modal-content">



            <!-- Modal Header -->

            <div class="modal-header">

                <h4 class="modal-title">Media</h4>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

            </div>



            <!-- Modal body -->

            <div class="modal-body">

                <input type="hidden" value="" id="type_id">
                <input type="hidden" value="" id="data_id">


                <nav>

                    <div class="nav nav-pills mb-3" id="nav-tab" role="tablist">

                        <button class="nav-link nav-home-tab" id="nav-home-tab" data-bs-toggle="tab"

                            data-bs-target="#nav-uploads" type="button" role="tab" aria-controls="nav-home"

                            aria-selected="true">Upload Files</button>

                        <button class="nav-link active nav-profile-tab" id="nav-profile-tab" data-bs-toggle="tab"

                            data-bs-target="#nav-media" type="button" role="tab"

                            aria-controls="nav-profile" aria-selected="false">Media Library</button>

                    </div>

                </nav>

                <div class="tab-content" id="nav-tabContent">

                    <div class="tab-pane fade" id="nav-uploads" role="tabpanel" aria-labelledby="nav-home-tab">

                        <form id="image-upload-form" action="{{ route('media.upload') }}" method="post"

                            enctype="multipart/form-data">

                            @csrf

                            <div class="dropzone needsclick form-control dropzone_style all_img_dropzone" id=""

                                name="dropzone_demo">



                            </div>

                        </form>

                    </div>

                    <div class="tab-pane fade active show nav-media" id="nav-media" role="tabpanel"

                        aria-labelledby="nav-profile-tab">

                        @include('includes.all_img_sec')

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>