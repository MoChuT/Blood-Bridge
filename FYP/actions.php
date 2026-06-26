<?php
declare(strict_types=1);

require __DIR__ . '/includes/storage.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('index.php');
}

$action = post_value('action');
$back = post_value('back', 'index.php');

switch ($action) {
    case 'admin_login':
    $username = post_value('username');
    $password = post_value('password');

    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;

        flash('Login successful', 'Welcome Admin.');
        redirect_to('admin/index.php');
    }

    flash('Login failed', 'Invalid username or password.', 'bad');
    redirect_to($back);

    case 'admin_logout':
    unset($_SESSION['admin_logged_in']);
    unset($_SESSION['admin_username']);

    flash('Logged out', 'You have successfully logged out.');
    redirect_to('admin/login.php');

    case 'register':
        $password = post_value('password');
        $email = strtolower(post_value('email'));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    flash('Registration failed', 'Please enter a valid email address.', 'bad');
    redirect_to($back);
}
        $phone = post_value('phone');
        $donors = read_records('donors');

        foreach ($donors as $donor) {
            if (strtolower((string)($donor['email'] ?? '')) === $email || (string)($donor['phone'] ?? '') === $phone) {
                flash('Registration failed', 'This email or phone number is already registered. Please login instead.', 'bad');
                redirect_to($back);
            }
        }

        append_record('donors', [
            'full_name' => post_value('full_name'),
            'blood_type' => post_value('blood_type'),
            'phone' => $phone,
            'email' => $email,
            'address' => post_value('address'),
            'date_of_birth' => post_value('date_of_birth'),
            'emergency_contact' => post_value('emergency_contact'),
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'status' => 'Pending verification',
        ]);
        flash('Registration submitted', 'Your account has been created. Please login using your email or phone number.');
        redirect_to($back);

    case 'login':
        $identifier = strtolower(post_value('login_identifier'));
        $password = post_value('password');
        $donors = read_records('donors');
        $matchedDonor = null;
        foreach ($donors as $donor) {
            if (strtolower((string)($donor['email'] ?? '')) === $identifier || (string)($donor['phone'] ?? '') === $identifier) {
                $matchedDonor = $donor;
                break;
            }
        }

        if (!$matchedDonor || !password_verify($password, (string)($matchedDonor['password_hash'] ?? ''))) {
            flash('Login failed', 'Only registered donors can login. Please check your email or phone number and password.', 'bad');
            redirect_to($back);
        }

        $_SESSION['donor_email'] = (string)($matchedDonor['email'] ?? '');
        $_SESSION['donor_phone'] = (string)($matchedDonor['phone'] ?? '');
        $_SESSION['donor_name'] = (string)($matchedDonor['full_name'] ?? '');
        flash('Login verified', 'Welcome back, ' . (string)($matchedDonor['full_name'] ?? $identifier) . '.');
        redirect_to('donor.php');

    case 'screening':
        $answers = $_POST['answers'] ?? [];
        append_record('health_records', [
            'donor_key' => post_value('donor_key'),
            'answers' => is_array($answers) ? array_values($answers) : [],
            'eligibility_status' => count((array)$answers) >= 4 ? 'Eligible' : 'Review needed',
            'result' => post_value('result', 'Pending admin review'),
        ]);
        flash('Screening saved', 'Eligibility status has been updated.');
        redirect_to($back);

    case 'update_profile':
        append_record('profile_updates', [
            'full_name' => post_value('full_name'),
            'blood_type' => post_value('blood_type'),
            'phone' => post_value('phone'),
            'email' => post_value('email'),
            'address' => post_value('address'),
            'emergency_contact' => post_value('emergency_contact'),
            'status' => 'Pending admin review',
        ]);
        flash('Profile update submitted', 'Your profile update request has been saved.');
        redirect_to($back);

    case 'upload_document':
        $fileName = '';
        if (!empty($_FILES['document_file']['name']) && is_uploaded_file($_FILES['document_file']['tmp_name'])) {
            $uploads = __DIR__ . '/uploads';
            if (!is_dir($uploads)) {
                mkdir($uploads, 0775, true);
            }
            $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', basename((string)$_FILES['document_file']['name']));
            $fileName = date('YmdHis') . '_' . $safeName;
            move_uploaded_file($_FILES['document_file']['tmp_name'], $uploads . '/' . $fileName);
        }

        append_record('documents', [
            'document_type' => post_value('document_type'),
            'upload_date' => post_value('upload_date'),
            'notes' => post_value('notes'),
            'file_name' => $fileName,
        ]);
        flash('Document uploaded', 'Document storage record has been created.');
        redirect_to($back);

        case 'add_slot':
    append_record('appointment_slots', [
        'slot_date' => post_value('slot_date'),
        'slot_time' => post_value('slot_time'),
        'venue' => post_value('venue'),
        'status' => 'Open',
    ]);

    flash('Slot added', 'Available date, time and venue have been added.');
    redirect_to($back);

case 'delete_slot':
    $slotId = (int) post_value('slot_id');

    if ($slotId <= 0) {
        flash('Delete failed', 'Invalid slot ID.', 'bad');
        redirect_to($back);
    }

    delete_record('appointment_slots', $slotId);

    flash('Slot deleted', 'Available slot has been removed.');
    redirect_to($back);

    case 'appointment':
    $slotId = (int) post_value('slot_id');

    if ($slotId <= 0) {
        flash('Appointment failed', 'Please select an available session.', 'bad');
        redirect_to($back);
    }

    $statement = db()->prepare("SELECT * FROM appointment_slots WHERE id = :id");
    $statement->bindValue(':id', $slotId, PDO::PARAM_INT);
    $statement->execute();
    $slot = $statement->fetch();

    if (!$slot) {
        flash('Appointment failed', 'Selected session was not found.', 'bad');
        redirect_to($back);
    }

    append_record('appointments', [
        'donor_name' => $_SESSION['donor_name'] ?? '',
        'donor_email' => $_SESSION['donor_email'] ?? '',
        'preferred_date' => $slot['slot_date'],
        'preferred_time' => $slot['slot_time'],
        'venue' => $slot['venue'],
        'status' => 'Pending approval',
    ]);

    flash('Appointment requested', 'Your appointment is waiting for admin approval.');
    redirect_to($back);

        case 'verify_donor':
    $donorId = (int) post_value('donor_id');

    if ($donorId <= 0) {
        flash('Verification failed', 'Invalid donor ID.', 'bad');
        redirect_to($back);
    }

    $statement = db()->prepare("UPDATE donors SET status = 'Verified' WHERE id = :id");
    $statement->bindValue(':id', $donorId, PDO::PARAM_INT);
    $statement->execute();

    flash('Donor verified', 'The donor status has been updated to Verified.');
    redirect_to($back);

    case 'update_profile_request_status':
    $requestId = (int) post_value('request_id');
    $status = post_value('status');

    if ($requestId <= 0 || !in_array($status, ['Approved', 'Rejected'], true)) {
        flash('Update failed', 'Invalid profile update status.', 'bad');
        redirect_to($back);
    }

    $statement = db()->prepare("UPDATE profile_updates SET status = :status WHERE id = :id");
    $statement->bindValue(':status', $status);
    $statement->bindValue(':id', $requestId, PDO::PARAM_INT);
    $statement->execute();

    flash('Profile update reviewed', 'Profile update request has been ' . strtolower($status) . '.');
    redirect_to($back);

case 'update_document_status':
    $documentId = (int) post_value('document_id');
    $status = post_value('status');

    if ($documentId <= 0 || !in_array($status, ['Approved', 'Rejected'], true)) {
        flash('Update failed', 'Invalid document status.', 'bad');
        redirect_to($back);
    }

    $statement = db()->prepare("UPDATE documents SET status = :status WHERE id = :id");
    $statement->bindValue(':status', $status);
    $statement->bindValue(':id', $documentId, PDO::PARAM_INT);
    $statement->execute();

    flash('Document reviewed', 'Document has been ' . strtolower($status) . '.');
    redirect_to($back);

    case 'inventory':
        append_record('inventory_updates', [
            'blood_type' => post_value('blood_type'),
            'quantity_update' => post_value('quantity_update'),
            'last_updated' => post_value('last_updated'),
        ]);
        flash('Inventory updated', 'Blood inventory record has been saved.');
        redirect_to($back);

    case 'update_appointment_status':
    $appointmentId = (int) post_value('appointment_id');
    $status = post_value('status');

    if ($appointmentId <= 0 || !in_array($status, ['Approved', 'Rejected'], true)) {
        flash('Update failed', 'Invalid appointment status.', 'bad');
        redirect_to($back);
    }

    $statement = db()->prepare("UPDATE appointments SET status = :status WHERE id = :id");
    $statement->bindValue(':status', $status);
    $statement->bindValue(':id', $appointmentId, PDO::PARAM_INT);
    $statement->execute();

    flash('Appointment updated', 'Appointment has been ' . strtolower($status) . '.');
    redirect_to($back);

    case 'approval':
        append_record('appointment_decisions', [
            'appointment' => post_value('appointment'),
            'decision' => post_value('decision'),
            'reviewed_by' => post_value('reviewed_by'),
            'remarks' => post_value('remarks'),
        ]);
        flash('Decision saved', 'Appointment status has been updated.');
        redirect_to($back);

    case 'announcement':
        append_record('announcements', [
            'title' => post_value('title'),
            'status' => post_value('status'),
            'event_date' => post_value('event_date'),
            'details' => post_value('details'),
        ]);
        flash('Announcement saved', 'Announcement record has been updated.');
        redirect_to($back);

        case 'delete_announcement':
    $announcementId = (int) post_value('announcement_id');

    if ($announcementId <= 0) {
        flash('Delete failed', 'Invalid announcement ID.', 'bad');
        redirect_to($back);
    }

    delete_record('announcements', $announcementId);

    flash('Announcement deleted', 'The announcement has been removed.');
    redirect_to($back);

    case 'donation':
    $bloodType = post_value('blood_type');
    $result = post_value('result');

    append_record('donation_records', [
        'donor_name' => post_value('donor_name'),
        'blood_type' => $bloodType,
        'donation_date' => post_value('donation_date'),
        'result' => $result,
    ]);

    if ($result === 'Completed' && $bloodType !== '') {
        append_record('inventory_updates', [
            'blood_type' => $bloodType,
            'quantity_update' => 1,
            'last_updated' => date('Y-m-d H:i:s'),
        ]);
    }

    flash('Donation record saved', 'Donation result has been stored. Completed donations add 1 bag to inventory.');
    redirect_to($back);

    case 'alert':
        append_record('matching_alerts', [
    'blood_type' => post_value('blood_type'),
    'message' => post_value('message'),
]);
        flash('Matching alert sent', 'Suitable donors have been notified.');
        redirect_to($back);
}

flash('Action not found', 'The request could not be processed.', 'bad');
redirect_to($back);
