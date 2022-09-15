<!-- Essay Popup Modal -->
  <div id="essay-popup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="essay-popup" aria-hidden="true">
  	<div class="essay-popup-wrapper modal-dialog">
    <!-- Panel -->
      <div class="panel panel-default">
          <!-- Panel heading 1 -->
          <div class="panel-heading row" role="tab">
              <div class="col-xs-12 col-sm-6">
                  <div class="panel-title text-uppercase text-blue" id="essay-title">

                  </div>
                  <div class="subtitle pull-left" id="essay-scholarship">
                  </div>
              </div>
              <div class="col-xs-12 col-sm-6">
                  <div class="deadline text-right">
                      <small>
                          <strong>Deadline:</strong><br class="visible-xs-inline-block" />
                          <span id="essay-deadline-small"></span>
                      </small>
                  </div>
              </div>
          	<div class="dot-divider"><span></span></div>
          </div>
          <!-- / Panel heading 1-->
          <!-- Panel body -->
          <div class="panel-body">
              <div role="tabpanel">
                  <!-- Tab panes -->
                  <div class="tab-content">
                      <div role="tabpanel" class="tab-pane active" id="write">
                          <div class="row">
                              <div class="col-xs-12">
                                  <span class="words-count">
                                      <strong class="text-uppercase">Words:</strong>
                                      <span id="essay-complexity"></span>
                                  </span>
                                  <br class="visible-xs-inline-block" />
                                  <span class="text-left">
                              	      <strong class="text-uppercase">Essay goal:</strong>
                                      <p id="essay-description"></p>
                                  </span>
                              </div>
                              <div class="row-height top-row-upload">
                                  <form class="col-xs-12 col-sm-6 col-height col-middle uploadVsWrite">

                                  </form>
                                  <div class="col-xs-12 col-sm-6 col-height col-middle filterSearch hideThis">
                                      <input type="text" id="filterFiles" class="form-control" placeholder="Search for...">
                                  </div>
                              </div>

                              <div class="col-xs-12 hideThis addGrid">
                                  @include("users/profile/files")
                              </div>
                              <div class="col-xs-12 enterEssayText" id="essay-text">
                                  <textarea class="form-control tinymce" name="essay-text"></textarea>
                              </div>
                          	</div>
                            <div class="write-footer">
                                <div class="saveSubmitBtns">
                                    <div class="pull-left" id="essay-button"></div>
                                    <div class="pull-left" id="submitEssayNow"></div>
                                </div>
                            </div>
                              <div id="essay-status" class="col-xs-12 col-sm-3 hidden">
                                  <label class="pull-right essayFinishedLab">
                                     <input class="blabbla" id="essay-finished" type="checkbox" >
                                     <span class="lbl padding-8">Essay finished</span>
                                  </label>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <!-- / Panel body -->
      </div>
    <!-- / Panel -->
    </div>
  </div>

<!-- / Essay Popup Modal -->