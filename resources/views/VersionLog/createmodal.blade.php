
    <form action="/versionlog" method="post" class="form-horizontal row-border"
          data-validate="parsley" id="validate-form">
        {!! csrf_field() !!}
        <div class="modal fade" id="createmodal"
             tabindex="-1" role="dialog"
             aria-labelledby="favoritesModalLabel">

            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close"
                                data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"
                            id="favoritesModalLabel">New entry</h4>
                    </div>
                    <div class="modal-body">

                        <div id="toolsVersionForm" class="panel-body"
                             style="border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px;">

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Title</label>

                                <div class="col-md-8">
                                    <input type="text" data-minlength="3" placeholder="Title"
                                           name="title"
                                           required="required" class="form-control parsley-validated"
                                            >
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Visible for externs?</label>

                                <div class="col-md-8">
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="" name="extern"></label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Draft?</label>

                                <div class="col-md-8">
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="" name="draft"></label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tool</label>

                                <div class="col-md-8">
                                    <select class="form-control" name="product_type" autocomplete="off" >
                                        <?php
                                        echo  $html ;
                                        ?>

                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Circles</label>

                                <div class="col-md-8">
                                    <select multiple="multiple" class="multi-select" name="circles[]" autocomplete="off">
                                        @foreach( $circles as $circle )
                                            <option value="{{ $circle->id }}">

                                                {{ $circle->title, ENT_QUOTES, "UTF-8"
                                                    }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Developer</label>

                                <div class="col-md-8">
                                    <select multiple="multiple" class="multi-select" name="users[]" autocomplete="off">
                                        @foreach( $users as $user )
                                            <option value="{{ $user->id }}">
                                                {{ $user->display_name, ENT_QUOTES, "UTF-8"
                                                    }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Content</label>

                                <div class="col-md-8">
                                    <textarea placeholder="Lorem ipsum..." id="versioncontent" class="form-control" rows="6" cols="50" name="content"></textarea>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-3">
                                <div class="btn-toolbar">
                                    <button class="btn-primary btn custombtn">
                                        Submit
                                    </button>
                                    <button type="reset"
                                            onclick="javascript:window.location.href = '{{\URL::to('/intern')}}'"
                                            class="btn-default btn custombtn">Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
