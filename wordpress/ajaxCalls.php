<?php
// -- MailChimp Functionality ----------------------------------------------------------- //
	function mailChimp() {
		post_to_mail_chimp($_POST);
		
		wp_die();
	}

	function post_to_mail_chimp($POST){
		$macacochave	=	'ef120e4a3413f7255bd018cf336505b5-us17';
		$list			=	$POST['macacoList'];

		if (isset($POST['message']))
			unset($POST['message']);

		unset($POST['action']);
		unset($POST['macacoList']);

		$payload	=	json_encode($POST);
		$curl		=	curl_init();

		curl_setopt_array($curl, [
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $payload,
			CURLOPT_URL => "https://us17.api.mailchimp.com/3.0/lists/$list/members",
			CURLOPT_USERPWD => "anystring:$macacochave",
			CURLOPT_HTTPHEADER => [
				'Content-Type: application/json',
			],
		]);

		$resp	=	curl_exec($curl);
	}

	add_action('wp_ajax_mailChimp', 'mailChimp');
	add_action('wp_ajax_nopriv_mailChimp', 'mailChimp');
// ----------------------------------------------------------- MailChimp Functionality -- //

// -- Contact message functionality ----------------------------------------------------- //
	function send_contact_email(){	
		$from_name	=	$_POST['merge_fields']['FNAME'] . ' ' . $_POST['merge_fields']['LNAME'];
		$from_email	=	$_POST['email_address'];

		$headers[]	=	'From: Phyto Animal Health Website <contact@phytoanimalhealth.com>';

		apply_filters('wp_mail_from_name', 'Phyto Animal Health Website');
		apply_filters('wp_mail_from', 'contact@phytoanimalhealth.com');

		$to			=	get_field('contact_form_e-mail', get_page_by_path('contact')->ID);
		$subject	=	'[Phyto Animal Health] New website contact';

		$message	=	"Name: $from_name\r\n";
		$message	.=	"E-mail: $from_email\r\n";
		$message	.=	'Phone: ' . $_POST['merge_fields']['CPHONE'] . "\r\n";
		$message	.=	'Company: ' . $_POST['merge_fields']['COMPANY'] . "\r\n";
		$message	.=	'State: ' . $_POST['merge_fields']['STATE'] . "\r\n";
		$message	.=	'Message: ' . $_POST['message'] . "\r\n";
		
		wp_mail($to, $subject, $message, $headers);
		
		post_to_mail_chimp($_POST);

		wp_die();
	}

	add_action('wp_ajax_send_contact_email', 'send_contact_email');
	add_action('wp_ajax_nopriv_send_contact_email', 'send_contact_email');
// ----------------------------------------------------- Contact message functionality -- //