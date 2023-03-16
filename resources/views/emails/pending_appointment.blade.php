@component('mail::message')
<h2>New Appointment Notification</h2>
<p>Hi {{ $body['doctor_name'] }},</p>
<p>This is to inform you that {{ $body['patient_name'] }} has scheduled an appointment with you on <strong>{{ $body['appointment_date'] }}</strong>.</p>
<p>Below are the appointment details;<br>
Appointment Number: <strong>{{ $body['appointment_number'] }}</strong><br>
Appointment Date: {{ $body['appointment_date'] }}<br>
Patient Name: {{ $body['patient_name'] }}<br>
Patient Telephone Number: {{ $body['patient_phone_number'] }}<br>
Patient Address: {{ $body['patient_address'] }}<br>
Reason for appointment: {{ $body['reason'] }}<br>
</p>
<p>Please make sure to be available for the appointment.</p>
<p>Thank you</p>
 
Reagrds,<br>
System Medical Notifications.
@endcomponent