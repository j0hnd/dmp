<?php
require_once 'libs/autoload.php';

try {

    if (isset($_REQUEST['module'])) {
        $module = filter_var($_REQUEST['module'], FILTER_SANITIZE_STRING);
    } else {
        $module = null;
    }

    if (isset($_REQUEST['action'])) {
        $action = filter_var($_REQUEST['action'], FILTER_SANITIZE_STRING);
    } else {
        $action = null;
    }

    if (isset($_REQUEST['data'])) {
        $creature_id = filter_var($_REQUEST['data']['id'], FILTER_SANITIZE_NUMBER_INT);

        if (isset($_REQUEST['data']['form'])) {
            $data = filter_var($_REQUEST['data']['form'], FILTER_SANITIZE_STRING);
        } else {
            $data = filter_var($_REQUEST['data'], FILTER_SANITIZE_STRING);
        }

    } else {
        $data = null;
    }

    switch ($module) {
        case "creatures":
            if ($action == 'save') {
                $form_data = null;
                parse_str($data, $form_data);

                if (!is_null($form_data)) {
                    $form_data['date_of_birth'] = date('Y-m-d', strtotime($form_data['date_of_birth']));
                    $form_data['ever_carried_the_ring'] = isset($form_data['ever_carried_the_ring']) ? 1 : 0;
                    $form_data['enslaved_by_sauron'] = isset($form_data['enslaved_by_sauron']) ? 1 : 0;
                    $form_data['created_at'] = date('Y-m-d H:i:s');

                    if ($creatures_obj->save($form_data)) {
                        $response = ['success' => true, 'message' => 'Added new creature'];
                    } else {
                        $response = ['success' => false, 'message' => 'Error in saving creature'];
                    }
                } else {
                    $response = ['success' => false, 'message' => 'Invalid data'];
                }

            } elseif ($action == 'reload') {
                $creatures = $creatures_obj->get();
                ob_start();
                include('partials/creatures-list.php');
                $html = ob_get_clean();

                $response = ['success' => true, 'html' => $html];

            } elseif ($action == 'filter-is-punished') {
                $is_punished = ($_REQUEST['flag'] == 'true') ? 1 : 0;
                $no_of_crimes = filter_var($_REQUEST['no_of_crimes'], FILTER_SANITIZE_NUMBER_INT);
                if (($is_punished == 0 and $no_of_crimes == '') or ($is_punished == 0 and $no_of_crimes == 0)) {
                    $criteria = null;
                } else {
                    $criteria = ['is_punished' => $is_punished];
                    if ($no_of_crimes > 0) {
                        $criteria['no_of_crimes'] = $no_of_crimes;
                    }
                }

                $creatures = $creatures_obj->get(null, $criteria);

                ob_start();
                include('partials/creatures-list.php');
                $html = ob_get_clean();

                $response = ['success' => true, 'html' => $html];

            } elseif ($action == 'filter-race') {
                if ($_REQUEST['race'] != 'Select Race') {
                    $criteria = ['race' => $_REQUEST['race']];
                } else {
                    $criteria = null;
                }

                $creatures = $creatures_obj->get(null, $criteria);

                ob_start();
                include('partials/creatures-list.php');
                $html = ob_get_clean();

                $response = ['success' => true, 'html' => $html];

            } elseif ($action == 'mark-deceased') {
                $creature_id = filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);
                if ($creatures_obj->save(['is_deceased' => 1], $creature_id)) {
                    $creatures = $creatures_obj->get();
                    ob_start();
                    include('partials/creatures-list.php');
                    $html = ob_get_clean();

                    $response = ['success' => true, 'message' => 'Creature has been marked as deceased', 'html' => $html];
                } else {
                    $response = ['success' => false, 'message' => 'Error in marking selected creature as deceased'];
                }

            } else {
                $response = ['success' => false, 'message' => 'Invalid request'];

            }

            break;

        case "crimes":
            if ($action == 'save') {
                $form_data = null;
                parse_str($data, $form_data);

                if (!is_null($form_data)) {
                    $form_data['creature_id'] = $creature_id;
                    $form_data['is_punished'] = isset($form_data['is_punished']) ? 1 : 0;
                    $form_data['created_at']  = date('Y-m-d H:i:s');

                    if ($crimes_obj->save($form_data)) {
                        $response = ['success' => true, 'message' => 'Added crime'];
                    } else {
                        $response = ['success' => false, 'message' => 'Error in saving crime'];
                    }

                } else {
                    $response = ['success' => false, 'message' => 'Invalid data'];
                }
            }
            break;

        case "notes":
            if ($action == 'save') {
                $form_data = null;
                parse_str($data, $form_data);

                if (!is_null($form_data)) {
                    $form_data['creature_id'] = $creature_id;
                    $form_data['created_at']  = date('Y-m-d H:i:s');

                    if ($notes_obj->save($form_data)) {
                        $response = ['success' => true, 'message' => 'Added notes'];
                    } else {
                        $response = ['success' => false, 'message' => 'Error in saving notes'];
                    }

                } else {
                    $response = ['success' => false, 'message' => 'Invalid data'];
                }

            }
            break;

        case "password":
            if ($action == 'validate') {
                $passwd = include('config/passwd.php');
                parse_str($data, $form_data);

                if (password_verify($form_data['password'], $passwd[0])) {
                    $response = ['success' => true];

                } else {
                    $response = ['success' => false, 'message' => 'Invalid password'];

                }
            }
            break;

        case "user":
            session_start();
            if ($action == 'logout') {
                unset($_SESSION['dmp']);

                session_destroy();

                $response = ['success' => true];

            } else {
                $passwd = include('config/passwd.php');
                parse_str($data, $form_data);

                if (password_verify($form_data['password'], $passwd[1])) {
                    $_SESSION['dmp'] = 1;
                    $response = ['success' => true];

                } else {
                    $response = ['success' => false, 'message' => 'Invalid password'];

                }

            }
            break;

        default:
            throw new Exception('Invalid module.');
            break;
    }

} catch (Exception $e) {
    throw $e;
}

echo json_encode($response);
