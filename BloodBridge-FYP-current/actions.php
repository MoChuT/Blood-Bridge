<?php
declare(strict_types=1);

require __DIR__ . '/includes/storage.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('index.php');
}

$action = post_value('action');
$back = post_value('back', 'index.php');

switch ($action) {
    case 'register':
        $password = post_value('password');
        $email = strtolower(post_value('email'));
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

    case 'appointment':
        append_record('appointments', [
            'preferred_date' => post_value('preferred_date'),
            'preferred_time' => post_value('preferred_time'),
            'venue' => post_value('venue'),
            'status' => 'Pending approval',
        ]);
        flash('Appointment requested', 'Appointment record is waiting for confirmation.');
        redirect_to($back);

    case 'inventory':
        append_record('inventory_updates', [
            'blood_type' => post_value('blood_type'),
            'quantity_update' => post_value('quantity_update'),
            'last_updated' => post_value('last_updated'),
        ]);
        flash('Inventory updated', 'Blood inventory record has been saved.');
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

    case 'donation':
        append_record('donation_records', [
            'donor_name' => post_value('donor_name'),
            'blood_type' => post_value('blood_type'),
            'donation_date' => post_value('donation_date'),
            'result' => post_value('result'),
        ]);
        flash('Donation record saved', 'Donation result has been stored.');
        redirect_to($back);

    case 'alert':
        append_record('matching_alerts', [
            'blood_type' => post_value('blood_type'),
            'radius' => post_value('radius'),
            'message' => post_value('message'),
        ]);
        flash('Matching alert sent', 'Suitable donors have been notified.');
        redirect_to($back);
}

flash('Action not found', 'The request could not be processed.', 'bad');
redirect_to($back);
