<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>DMP</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="assets/css/jquery-ui.min.css">
    <link rel="stylesheet" href="assets/css/tablesorter/style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <!-- bootstrap validator -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>

    <!-- bootstrap datepicker -->
    <script src="assets/js/bootstrap-datepicker.min.js"></script>

    <!-- jquery ui -->
    <script src="assets/js/jquery-ui.min.js"></script>

    <!-- table sorter -->
    <script src="assets/js/jquery.tablesorter.min.js"></script>
  </head>

  <body>

<?php
require 'libs/autoload.php';
session_start();
$creatures = $creatures_obj->get();
?>

    <?php if (!isset($_SESSION['dmp'])): ?>
    <div class="container-fluid">
        <div class="row mt-120">
            <div class="col-md-2 col-md-offset-5">
                <form id="password-form" method="post" role="form">
                    <div class="form-group">
                        <label for="creature">Enter Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required />
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-primary" id="toggle-login">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="container-fluid">
      <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-6">
            <button type="button" id="toggle-add-creature" class="btn btn-link">Add Creature</button>
            <button type="button" id="toggle-logout" class="btn btn-link">Logout</button>
        </div>
      </div>

      <div class="row">
          <div class="col-md-12">
              <div class="alert alert-success hidden" role="alert"></div>
              <div class="alert alert-danger hidden" role="alert"></div>
          </div>
      </div>

      <div class="row">
        <div class="col-md-12" id="creature-container">
            <h2>Creatures</h2>
            <div class="form-inline mt-20">
                <div class="form-group mr-20" style="padding-top:3px">
                    <label>
                        <input type="checkbox" id="is-punished" /> Not yet Punished
                    </label>

                </div>
                <div class="form-group mr-20">
                    <label>No of Crimes: </label>
                    <input type="text" class="form-control" id="no-of-crimes" name="no_of_crimes" value="0" style="width: 50px;" />
                </div>
                <div class="form-group">
                    <label>Race: </label>
                    <select name="race" id="filter-race" class="form-control">
                        <option>Select Race</option>
                        <option value="elf">Elf</option>
                        <option value="dwarf">Dwarf</option>
                        <option value="hobbit">Hobbit</option>
                        <option value="human">Human</option>
                        <option value="orc">Orc</option>
                        <option value="ghost">Ghost</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>

            <table id="creatures-list-wrapper" class="table table-hover tablesorter">
                <thead>
                    <tr>
                        <th style="5px">ID</th>
                        <th>Creature Name</th>
                        <th>Gender</th>
                        <th>Race</th>
                        <th>Date Of Birth</th>
                        <th>Place Of Birth</th>
                        <th class="text-center">Ever Carried The Ring?</th>
                        <th class="text-center">Enslaved By Sauron</th>
                        <th>Date Added</th>
                        <th class="action-wrapper" style="width: 270px"></th>
                    </tr>
                </thead>
                <tbody id="creatures-list-container">
                    <?php include('partials/creatures-list.php'); ?>
                </tbody>
            </table>
        </div>
      </div>

      <input type="hidden" id="creature" />
      <input type="hidden" id="page" />
    </div>

    <!-- creatures modal -->
    <div class="modal fade" id="creatures-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Add Creature</h4>
          </div>
          <div class="modal-body">
              <form id="creature-form" method="post" role="form">
                  <div class="form-group">
                      <label for="creature">Creature</label>
                      <input type="text" class="form-control" id="creature" name="creature_name" placeholder="Creature" required />
                      <div class="help-block with-errors"></div>
                  </div>

                  <div class="form-group">
                      <label for="race">Gender</label>
                      <select name="gender" id="gender" class="form-control" required>
                          <option>Select Gender</option>
                          <option value="male">Male</option>
                          <option value="female">Female</option>
                          <option value="unknown">Unknown</option>
                          <option value="other">Other</option>
                      </select>
                      <div class="help-block with-errors"></div>
                  </div>

                  <div class="form-group">
                      <label for="gender">Race</label>
                      <select name="race" id="race" class="form-control" required>
                          <option>Select Race</option>
                          <option value="elf">Elf</option>
                          <option value="dwarf">Dwarf</option>
                          <option value="hobbit">Hobbit</option>
                          <option value="human">Human</option>
                          <option value="orc">Orc</option>
                          <option value="ghost">Ghost</option>
                          <option value="other">Other</option>
                      </select>
                      <div class="help-block with-errors"></div>
                  </div>

                  <div class="form-group">
                      <label for="date_of_birth">Date of Birth</label>
                      <input type="text" class="form-control" id="date-of-birth" name="date_of_birth" placeholder="Date of Birth" data-provide="datepicker" required />
                      <div class="help-block with-errors"></div>
                  </div>

                  <div class="form-group">
                      <label for="place_of_birth">Place of Birth</label>
                      <input type="text" class="form-control" id="place-of-birth" name="place_of_birth" placeholder="Place of Birth" required />
                      <div class="help-block with-errors"></div>
                  </div>

                  <div class="form-group">
                      <label><input type="checkbox" id="ever-carried-the-ring" name="ever_carried_the_ring" /> Ever Carried The Ring?</label>
                  </div>

                  <div class="form-group">
                      <label><input type="checkbox" id="enslaved-by-sauron" name="enslaved_by_sauron" /> Enslaved By Sauron</label>

                  </div>
              </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="toggle-new-creature">Save</button>
          </div>
        </div>
      </div>
    </div>

    <!-- crimes modal -->
    <div class="modal fade" id="crimes-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add Crimes</h4>
                </div>
                <div class="modal-body">
                    <form id="crimes-form" method="post" role="form">
                        <div class="form-group">
                            <label for="creature">Crime</label>
                            <textarea name="notes" id="notes" cols="30" rows="10" class="form-control" required></textarea>
                            <div class="help-block with-errors"></div>
                        </div>

                        <div class="form-group">
                            <label><input type="checkbox" name="is_punished" /> Punished?</label>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="toggle-save-crime">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- notes modal -->
    <div class="modal fade" id="notes-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add notes</h4>
                </div>
                <div class="modal-body">
                    <form id="notes-form" method="post" role="form">
                        <div class="form-group">
                            <label for="creature">Notes</label>
                            <textarea name="notes" id="notes" cols="30" rows="10" class="form-control" required></textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="toggle-save-note">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- password modal -->
    <div class="modal fade" id="password-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Enter Password</h4>
                </div>
                <div class="modal-body">
                    <form id="password-form" method="post" role="form">
                        <div class="form-group">
                            <label for="creature">Enter Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required />
                            <div class="help-block with-errors"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="toggle-validate">Okay</button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

  </body>
  <style type="text/css">
      .ui-autocomplete {
          z-index: 5000;
      }
      .collapse-details {
          cursor: pointer;
      }
      .crimes-container, .notes-container {
          height: 200px;
          overflow-x: hidden;
          overflow-y: auto;
      }
      .punished-text {
          color: red;
      }
      .crime-details-wrapper, .notes-details-wrapper {
          margin-bottom: 12px;
          padding: 7px;
      }
      .mr-20 {
          margin-right: 20px;
      }
      .mt-20 {
          margin-top: 20px;
      }
      .mt-120 {
          margin-top: 120px;
      }
  </style>
  <script type="text/javascript">var place_of_birth = <?php echo $creatures_obj->get_place_of_births() ?></script>
  <script type="text/javascript" src="assets/js/main.js"></script>
</html>
