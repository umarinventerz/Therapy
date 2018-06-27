
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 align="center" class="modal-title" id="exampleModalLabel">AUTHORIZATION</h4>
            </div>
            <div id="newUserHTMLBody">
                <form method="post" action="#" role="form" id="new_user_form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" class="nofocus">
                    <div class="modal-body">
                        
                                    <div class="row">

                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                <input type="text"
                                                       ondrop="return false;"
                                                       id="last_name"
                                                       name="last_name"
                                                       class="form-control"
                                                       placeholder=""
                                                       maxlength=30
                                                       minlength=3
                                                       required="required">
                                                <label for="last_name">
                                                    INSURER
                                                </label>
                                            </div>
                                        </div>
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                <input type="text"
                                                       id="first_name"
                                                       name="first_name"
                                                       class="form-control"
                                                       placeholder=""
                                                       maxlength=30
                                                       minlength=3
                                                       required="required">
                                                <label for="first_name">
                                                    # AUTH
                                                </label>
                                            </div>
                                        </div>
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                <input type="text"
                                                       id="username"
                                                       name="username"
                                                       class="form-control tooltips"
                                                       placeholder=""
                                                       data-original-title=""
                                                       maxlength=20
                                                       minlength=3
                                                       required="required">
                                                <label for="username">
                                                    CPT
                                                </label>
                                            </div>
                                        </div>
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                <input type="email"
                                                       id="email"
                                                       name="email"
                                                       class="form-control tooltips"
                                                       placeholder=""
                                                       data-original-title=""
                                                       maxlength=50
                                                       minlength=3
                                                       required="required">
                                                <label for="email">
                                                    DISCIPLINE
                                                </label>
                                            </div>
                                        </div>
                                      
                                           <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                <input type="text"
                                                       ondrop="return false;"
                                                       id="last_name"
                                                       name="last_name"
                                                       class="form-control"
                                                       placeholder=""
                                                       maxlength=30
                                                       minlength=3
                                                       required="required">
                                                <label for="last_name">
                                                    START DATE
                                                </label>
                                            </div>
                                        </div>
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                <input type="text"
                                                       id="first_name"
                                                       name="first_name"
                                                       class="form-control"
                                                       placeholder=""
                                                       maxlength=30
                                                       minlength=3
                                                       required="required">
                                                <label for="first_name">
                                                    END DATE
                                                </label>
                                            </div>
                                        </div>
                               
                                         <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                <input type="text"
                                                       ondrop="return false;"
                                                       id="last_name"
                                                       name="last_name"
                                                       class="form-control"
                                                       placeholder=""
                                                       maxlength=30
                                                       minlength=3
                                                       required="required">
                                                <label for="last_name">
                                                    AMOUNT
                                                </label>
                                            </div>
                                        </div>
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                <input type="text"
                                                       id="first_name"
                                                       name="first_name"
                                                       class="form-control"
                                                       placeholder=""
                                                       maxlength=30
                                                       minlength=3
                                                       required="required">
                                                <label  for="first_name">
                                                    TYPE: visit or units
                                                </label>
                                            </div>
                                        </div>

                                        <div align="center" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                          <div  class="checkbox checkbox-success checkbox-circle ">
                                                <input id="checkbox12" class="styled" type="checkbox" checked>
                                                <label style="font-weight:bold;" for="checkbox12">
                                                ACTIVE
                                                </label>
                                            </div>
                                        </div>
                                          <br>

                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                            <div class="">
      
                                <a class="btn btn-default" data-dismiss="modal" id="cancel" name="cancel">
                                    <i class="fa fa-ban"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary" name="save" id="newUserButton">
                                    <i class="fa fa-floppy-o"></i> Save
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
      