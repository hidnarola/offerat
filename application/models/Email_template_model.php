<?php

class Email_template_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function send_password_format($first_name = NULL, $last_name = NULL, $new_password = NULL) {
        $login_link = SITEURL . 'login';
        $message_text = '<p>Hello {first_name} {last_name},</p>

<p>Your account has been created on Offerat, your new temporary password is {new_password}</p>

<p>Please login with your email address to <a href="' . $login_link . '">' . $login_link . '</a> and remember to change your password.</p>

<p>For support, contact us on support@offerat.sale</p>

<p>Regards</p>
<p>Offerat Team</p>';

        $find = array("{first_name}", "{last_name}", "{new_password}");
        $replace = array($first_name, $last_name, $new_password);
        $message = str_replace($find, $replace, $message_text);

        return $this->mail_format($message);
    }

    public function forgot_password_format($link = NULL) {

        $message_text = '<p>Dear Sir, </p>

<p>We received a reset password request for your account. Please click the link below to reset your password.<p> 

<p><a href="{reset_link}">{reset_link}</a></p>

<p>If clicking the link does not work then you can copy the link into your browser window or type it there directly.</p>

<p>If you did not make this request, please report this suspicious activity to info@offerat.sale</p>

<p>&nbsp;</p>

<p>Regards</p>
<p>Your Offerat Team</p>';

        $find = array("{reset_link}");
        $replace = array($link);
        $message = str_replace($find, $replace, $message_text);

        return $this->mail_format($message);
    }

    public function account_verification_format($link = NULL) {

        $message_text = '<p>Dear Sir,</p>
<p>Thank you for partnering with us.</p>

<p>Click the following link to complete the registration process.</p>

<p><a href="{verification_link}">{verification_link}</a></p>


<p>For help kindly contact our support on:</p>
<p>Email: ' . site_support_email . '</p>
<p>Mob: +961 70 113 143</p>


<p>Best Regards,</p>
<p>Offerat Team</p>';

        $find = array("{verification_link}");
        $replace = array($link);
        $message = str_replace($find, $replace, $message_text);

        return $this->mail_format($message);
    }

    public function email_change_verification($link = NULL) {

        $message_text = '<p>Hi!</p>

<p>You&#39;ve requested to change your email.&nbsp; 

<p>Click the following link to verify your email address.<p>

<p><a href="{verification_link}">{verification_link}</a></p>

<p>For help kindly contact our support on:</p>
<p>Email: ' . site_support_email . '</p>
<p>Mob: +961 70 113 143</p>
<p>&nbsp;</p>

<p>Best Regards,</p>
<p>Offerat Team</p>';

        $find = array("{verification_link}");
        $replace = array($link);
        $message = str_replace($find, $replace, $message_text);

        return $this->mail_format($message);
    }

    public function mail_format($content = NULL) {

        $offerat_logo = '<div class="logo_image"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 1024 294.9" style="enable-background:new 0 0 1024 294.9;width: 250px;" xml:space="preserve">
<style type="text/css">
	.st0{fill:#FFFFFF;}
</style>
<g>
	<path class="st0" d="M149.3,89.6c-45.9,0-83.3,37.3-83.3,83.3s37.3,83.3,83.3,83.3s83.3-37.3,83.3-83.3S195.2,89.6,149.3,89.6z
		 M149.3,219.8c-25.9,0-46.9-21.1-46.9-46.9s21.1-46.9,46.9-46.9s46.9,21.1,46.9,46.9S175.1,219.8,149.3,219.8z"/>
	<path class="st0" d="M550.7,203c-8.1,10-20.5,16.1-34.2,16.7c-7.1,0.3-13.7-0.7-19.8-3c-1,0.7,60.1-60.1,75.4-75.5
		c11.6-11.7,3.9-23.6,0.7-26.9c-32.5-32.1-85.1-32-117.4,0.3c-32.3,32.3-32.4,85-0.3,117.4c15.2,15.4,36.5,24,58.9,24
		c1.4,0,2.9,0,4.4-0.1c24-1.2,46.1-12.2,60.7-30.2c9.6-11.9,15.8-26.2,17.8-41.3l-36-4.8C559.6,188.2,556.2,196.3,550.7,203z
		 M481,140.3c9.1-9.1,21.1-13.7,33.1-13.7c6,0,11.9,1.1,17.6,3.4c-15.2,15.4-39.6,39.8-60.9,61.2C463.9,174.2,467.3,154,481,140.3z"
		/>
	<path class="st0" d="M249.7,89v167H286v-135l39.6,0.6l0.6-36.3l-39.8-0.6c2.2-21,19.8-37.5,41.3-37.5V11
		C284.7,11,249.7,46,249.7,89z"/>
	<path class="st0" d="M345,89v167h36.3V120.2l39.6,0.6l0.6-36.3l-39.7-0.6c2.6-20.6,20-36.6,41.2-36.6V11C380,11,345,46,345,89z"/>
	<path class="st0" d="M913.3,178V99.8l28.5,0.5l0.6-36.3l-29.1-0.5V11H877v167c0,43.1,35,78.1,78.1,78.1v-36.3
		C932.1,219.8,913.3,201,913.3,178z"/>
	<path class="st0" d="M611.5,180.7v75.4h36.3v-75.4c0-27,22-49,49-49V95.4C649.8,95.4,611.5,133.7,611.5,180.7z"/>
	<path class="st0" d="M782.3,85.4c-30.8,0-58.4,17.3-70.2,44.1l33.2,14.7c6-13.6,20.5-22.5,37-22.5c18,0,33,10.8,37.9,25.4h-61
		c-29.6,0-53.7,23.5-53.7,52.3s24.1,52.3,53.7,52.3h42.6c7.1,0,13.9-1.5,20.2-4v8.3h36.3V157C858.2,117.5,824.2,85.4,782.3,85.4z
		 M820.7,196.4c0,10.5-8.5,19-19,19h-42.6c-9.3,0-17.4-7.5-17.4-16s8.1-16,17.4-16h61.6V196.4z"/>
</g>
</svg></div>';

        $mail_format = '
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />                
                <style type="text/css">
                    .mail-body,.mail-head{ display:inline-block;}    
                </style>
            </head>
        <body style="margin-top:0; margin-right:0; margin-bottom:0; margin-left:0; padding-top:0; padding-right:0; padding-bottom:0; padding-left:0;">
            <div style="margin-top:0; margin-right:0; margin-bottom:0; margin-left:0; padding-top:30px; padding-right:0; padding-bottom:30px; padding-left:0; background:#f6f6f6;">
                <div style="margin-top:0; margin-right:auto; margin-bottom:a; margin-left:auto; padding-top:0; padding-right:0; padding-bottom:0; padding-left:0; max-width:664px;">
                    <div style="margin-top:0; margin-right:0; margin-bottom:10px; margin-left:0; padding-top:0; padding-right:0; padding-bottom:0; padding-left:0; border:1px solid #d6d6d6; background:#fff;">
                        <div class="mail-head" style="margin-top:0; margin-right:0; margin-bottom:0; margin-left:0; padding-top:0; padding-right:0; padding-bottom:0; padding-left:0; background:#f5f5f5; display:inline-block; vertical-align:top; width:100%;">
                            <table style="width:100%; vertical-align:middle;background: #F14150;">
                                <tr>
                                    <td style="margin-top:0; margin-right:0; margin-bottom:0; margin-left:0; padding-top:0; padding-right:0; padding-bottom:0; padding-left:15px;; text-align:left; color:#000; font-size:20px; font-weight:700; font-family: Roboto, sans-serif; line-height:50px;"></td>
                                    <td style="margin-top:0; margin-right:0; margin-bottom:0; margin-left:0; padding-top:0; padding-right:15px; padding-bottom:0; padding-left:0; text-align:right;"><a href="' . SITEURL . '" style="display:block; height:70px;padding-top:7px">' . $offerat_logo . '</a></td>
                                </tr>
                            </table>
                        </div>  

                        <div style="margin-top:0; margin-right:0; margin-bottom:0; margin-left:0; padding-top:40px; padding-right:50px; padding-bottom:20px; padding-left:50px;">
                            <table style="width:100%; vertical-align:middle; margin:0 0 30px;">
                                <tr>
                                    <td style=" margin-top:0; margin-right:0; margin-bottom:0; margin-left:0; padding-top:0; padding-right:0; padding-bottom:0; padding-left:0; position:relative; display:inline-block; vertical-align:top; width:100%; ">
                                        <img src="" alt="" />
                                    </td>
                                    <td>' . $content . '</td>
                                </tr>
                            </table>

                            <div style="margin-top:0; margin-right:0; margin-bottom:0; margin-left:0; padding-top:15px; padding-right:0; padding-bottom:15px; padding-left:0; background:#f6f6f6; text-align:center;">
                                <a href="' . SITEURL . '" style="font-family: Roboto, sans-serif; color:#333; font-size:16px; line-height:18px; text-decoration:none;">Visit our site</a>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
          </body>
          </html>';
        return $mail_format;
    }

    public function send_email($from = NULL, $to = NULL, $subject = NULL, $content = NULL) {
        if (is_null($from))
            $from = site_info_email;
        $this->load->library('email');
        $this->email->from($from);
        $this->email->to($to);
//        $this->email->to('kek@narola.email');
        $this->email->subject($subject);
        $this->email->message($content);
//        $this->email->message($content . ' ' . $to);
        if ($this->email->send())
            $success = 'yes';
        else {
            $success = $this->email->print_debugger(array('headers'));
            $error = $this->email->print_debugger(array('headers'));
        }
        return $success;
    }

}
