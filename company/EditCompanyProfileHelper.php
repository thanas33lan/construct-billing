<?php
ob_start();
session_start();
include('../includes/MysqliDb.php');
define('UPLOAD_PATH', realpath(__DIR__ . DIRECTORY_SEPARATOR . '../uploads'));
$tableName = "company_profile";
try {
    if (trim($_POST['companyName']) != '') {
        $data = array(
            'company_name'      => $_POST['companyName'],
            'company_email'     => $_POST['companyEmail'],
            'website'           => $_POST['companySite'],
            'lat'               => $_POST['companylat'],
            'lng'               => $_POST['companylng'],
            'company_phone'     => $_POST['companyPhone'],
            'gst_number'        => $_POST['gstIn'],
            'address_line_one'  => $_POST['addressL1'],
            'address_line_two'  => $_POST['addressL2'],
            'company_code'      => $_POST['cmyCode'],
            'alt_number'        => $_POST['altNumber'],
            'accounter_name'    => $_POST['acName'],
            'accounte_branch'   => $_POST['acBranch'],
            'accounte_no'       => $_POST['acNumber'],
            'accounte_ifsc'     => $_POST['acIfsc'],
            'declaration'       => $_POST['cusDeclaration']
        );
        if ($_POST['companyId'] != '') {
            $companyId = $_POST['companyId'];
            $db = $db->where('company_id', $companyId);
            $id = $db->update($tableName, $data);
        } else {
            $id = $db->insert($tableName, $data);
            $companyId = $db->getInsertId();
        }

        if (isset($_POST['removedLogoImage']) && trim($_POST['removedLogoImage']) != "" && file_exists(UPLOAD_PATH . DIRECTORY_SEPARATOR . 'member' . DIRECTORY_SEPARATOR . $memberId . DIRECTORY_SEPARATOR . $_POST['removedLogoImage'])) {
            unlink(UPLOAD_PATH . DIRECTORY_SEPARATOR . 'logo' . DIRECTORY_SEPARATOR . $_POST['removedLogoImage']);
            $data = array('company_logo' => '');
            $db = $db->where('company_id', $companyId);
            $db->update($tableName, $data);
        }
        if (isset($_FILES['logoImage']['name']) && $_FILES['logoImage']['name'] != "") {
            if (!file_exists(UPLOAD_PATH) && !is_dir(UPLOAD_PATH)) {
                mkdir(UPLOAD_PATH);
            }
            if (!file_exists(UPLOAD_PATH . DIRECTORY_SEPARATOR . 'logo') && !is_dir(UPLOAD_PATH . DIRECTORY_SEPARATOR . 'logo')) {
                mkdir(UPLOAD_PATH . DIRECTORY_SEPARATOR . 'logo');
            }

            $extension = strtolower(pathinfo(UPLOAD_PATH . DIRECTORY_SEPARATOR . 'logo' . DIRECTORY_SEPARATOR . $_FILES['logoImage']['name'], PATHINFO_EXTENSION));
            $imageName = "logo" . $memberId . "." . $extension;
            if (move_uploaded_file($_FILES["logoImage"]["tmp_name"], UPLOAD_PATH . DIRECTORY_SEPARATOR . 'logo' . DIRECTORY_SEPARATOR . $imageName)) {
                $data = array('company_logo' => $imageName);
                $db = $db->where('company_id', $companyId);
                $db->update($tableName, $data);
            }
        }
        //print_r($data);die;

        if ($companyId > 0) {
            $_SESSION['alertMsg'] = "Company details updated successfully";
        } else {
            $_SESSION['alertMsg'] = "Please try again!";
        }
    }
    header("location:profile.php");
} catch (Exception $exc) {
    error_log($exc->getMessage());
    error_log($exc->getTraceAsString());
}
