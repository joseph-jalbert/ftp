<?php
/**
 * The header for landing pages (v2).
 *
 * @package ForThePeople
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php the_title(); ?></title>
<?php wp_head(); ?>
<style>
body#landingv2 {
	font-family: Helvetica, Arial, sans-serif !important;
	letter-spacing: 0.5px !important;
}
header#headerv2 {
	background: #fff;
}
header div.info {
	text-align: right;
	margin-top: 40px;
}
header div.info span {
	color: #fff;
}
header a.hdr-btn {
	font-size: 48px;
	text-transform: uppercase;
	text-decoration: none;
	color: #004b82;
	font-weight: 600;
	position: relative;
	top: 10px;
}
header a.hdr-btn span {
	font-size: 18px;
}
#herov2 {
	min-height: 450px;
	background: #7c7f82 url('/wp-content/themes/forthepeople/assets/landing/images/herov2rev2.jpg') no-repeat center -120px / cover;
}
#herov2 h1 {
	color: #fff;
	padding-top: 400px;
	font-weight: 600;
	line-height: 1.5em;
	text-transform: uppercase;
	font-size: 36px;
	letter-spacing: 0.5px;
}
#herov2 h1 span {
	background: #ff0002;
	padding: 5px 10px;
}
#herov2 h3 {
    background: none;
    color: #fff;
    font-size: 18px;
    font-style: italic;
    font-weight: 700;
    letter-spacing: 0.125px;
    line-height: 1.25;
    text-align: left;
    text-shadow: none;
}
#topv2 {
	padding-top: 40px;
}
#topv2 p {
	font-size: 33px;
	text-align: center;
	line-height: 1.5em;
	margin-bottom: 10px;
}
#formv2 {
	border: 12px solid #949494;
	-webkit-box-shadow: 0px 0px 18px 0px rgba(0,0,0,0.6);
	-moz-box-shadow: 0px 0px 18px 0px rgba(0,0,0,0.6);
	box-shadow: 0px 0px 18px 0px rgba(0,0,0,0.6);
	margin: 40px auto 120px auto;
}
#formv2 input[type="text"], #formv2 textarea {
    display: block;
    margin: 15px auto;
    width: 80%;
	padding: 10px;
	font-size: 18px;
	text-transform: uppercase;
}
#formv2 p.disclaimer {
	display: block;
	width: 80%;
	margin: auto;
}
#formv2 label {
	display: none;
}
#formv2 div.gform_footer {
	margin-left: 0;
}
#formv2 .gform_wrapper li.gfield.gfield_error, #formv2 .gform_wrapper li.gfield.gfield_error.gfield_contains_required.gfield_creditcard_warning {
	background: none;
	border: none;
	margin-bottom: 0 !important;
}
#formv2 div.validation_message {
	width: 80%;
	margin: auto;
	display: block;
	padding-top: 0;
}
#formv2 div.validation_error {
	border: none;
    display: block;
    margin: auto;
    padding: 0;
    text-align: center;
}
#formv2 h2 {
	color: #ff1618;
	font-weight: 600;
	font-family: Helvetica, Arial, sans-serif !important;
	font-size: 24px;
	text-transform: uppercase;
	letter-spacing: 0;
}
#formv2 input[type="submit"] {
	background: darkorange;
    border: 1px solid darkorange;
    border-radius: 0;
    bottom: -120px;
    box-shadow: none;
    color: #fff;
    font-size: 28px;
    font-weight: 600;
    margin-left: -12px;
    padding: 25px 11px;
    position: absolute;
    text-shadow: none;
    width: 100%;
	white-space: normal;
	-webkit-box-shadow: 0px 0px 18px 0px rgba(0,0,0,0.6);
	-moz-box-shadow: 0px 0px 18px 0px rgba(0,0,0,0.6);
	box-shadow: 0px 0px 18px 0px rgba(0,0,0,0.6);
}
#formv2 input[type="submit"]:hover {
	background-color: #00BB00;
	border-color: #00BB00;
}
.greybg {
	background: #e1e1e1;
	padding: 20px 0;
}
#bulletsv2 h2 {
	font-family: Helvetica, Arial, sans-serif !important;
	color: #ff1618;
	text-align: center;
	font-size: 30px;
	font-weight: 600;
	text-transform: uppercase;
	line-height: 1.25em;
}
#bulletsv2 p {
	font-size: 18px;
	line-height: 1.5;
}
#bulletsv2 ul {
	margin-left: 0;
}
#bulletsv2 ul li {
	background: rgba(0, 0, 0, 0) url('/wp-content/themes/forthepeople/assets/landing/images/bullet.png') no-repeat scroll left top / 26px auto;
    font-size: 15px;
    font-weight: 600;
    line-height: 1.5;
    list-style-type: none;
    margin-bottom: 20px;
    margin-left: 0;
    padding-left: 40px;
}	
#testimonialsv2 {
	padding: 40px 0;
}
#testimonialsv2 > div.container {
	background: #fff;
	padding: 40px;
}
#testimonialsv2 h2 {
	font-family: Helvetica, Arial, sans-serif !important;
	color: #ff1618;
	font-size: 39px;
	font-weight: 600;
	text-transform: uppercase;
	line-height: 1;
	margin-top: 0;
}
#testimonialsv2 div.testimonials {
	position: relative;
	min-height: 135px;
}
#testimonialsv2 div.testimonial p.quote {
	font-size: 18px;
	text-align: left;
	background: url('/wp-content/themes/forthepeople/assets/landing/images/open-quote.png') no-repeat left top / 34px, url('/wp-content/themes/forthepeople/assets/landing/images/close-quote.png') no-repeat right bottom / 34px;
}
#testimonialsv2 div.testimonial p.testsource {
	text-align: right;
	font-weight: bold;
	text-transform: uppercase;
}
#serving {
	background: #000;
}
#serving h3 {
 	background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
    color: #fff;
	font-family: Helvetica, Arial, sans-serif !important;
    font-size: 18px;
    letter-spacing: 1px;
    line-height: 1.25;
    margin: 0;
    padding: 20px 0;
    text-shadow: none;
    text-transform: uppercase;
}
#v2footer {
	padding: 20px 0;
}
#v2footer h5 {
	font-family: Helvetica, Arial, sans-serif !important;
	font-size: 14px;
	color: #1a395d;
	font-weight: 600;
	text-transform: uppercase;
	margin: 10px 0;
	background: none;
}
#v2footer h6 {
	font-family: Helvetica, Arial, sans-serif !important;
	font-size: 12px;
	color: #1a395d;
	font-weight: 600;
	text-transform: uppercase;
	margin: 5px 0;
	background: none;
}
#v2footer h5 a, #v2footer h6 a {
	color: #1a395d;
}
span.fbstack {
	background: #455698;
}
span.twstack {
	background: #48a9f1;
}
#v2footer span.fa-stack i {
	font-size: 1.5em;
	line-height: 1.5em;
}
#soclinks {
	margin: 20px 0;
}
.soclink {
	margin-right: 5px;
}
.soclink:hover span {
	background: #1a395d;
}
.ta-rt {
	text-align: right;
}
#splitsec #bulletsv2 {
    	margin-left: 0;
}
#splitsec #bulletsv2 {
	font-size: 24px;
	margin-top: 40px;
}
#splitsec #bulletsv2 ul li {
	font-size: 16px;
	margin-bottom: 40px;
}
#splitsec #formv2 {
	margin-left: 10px;
	box-shadow: none;
}
#splitsec #formv2 input[type="submit"] {
	position: inherit;
	padding: 25px 0;
	margin-left: 0;
	margin-bottom: 0px;
}
#splitsec #formv2 div.gform_footer {
    	padding: 0;
    	width: 100%;
}
#splitsec #formv2 form {
    	margin-bottom: 0;
}
#splitsec #formv2 div.gform_wrapper {
    	margin: 0;
    	max-width: 100%;
}
#splitsec #bulletsv2 .inner {
	padding-right: 20px;
}
.span6.first {
	margin-left: 0 !important;
}
.verdicts dl {
	margin-bottom: 0;
}
.verdicts dt {
    font-size: 33px;
    line-height: 1;
    padding: 0;
}
.verdicts dd {
    font-size: 16px;
    line-height: 22px;
    margin: 0 0 42px;
}
.verdicts dd:last-of-type {
	margin-bottom: 0px;
}
@media only screen and (max-width: 1160px) {
#herov2 {
	min-height: inherit;
	background-position: center top;
	background-size: cover;
}
}
@media only screen and (max-width: 762px) {
#testimonialsv2 h2 {
	font-size: 24px;
}
#herov2 h1 {
	padding-top: 0;
	font-size: 28px;
	line-height: 1.25em;
}
#formv2 h2 {
	font-size: 20px;
}
#formv2 input[type="submit"] {
	font-size: 20px;
}
#testimonialsv2 div.testimonials {
	min-height: 400px;
}
#serving h3 {
font-size: 12px;
line-height: 1.5em;
}
.mobta-cnt {
	text-align: center !important;
}
div.container > div.inner {
	padding: 0 20px;
}
#headerv2 .info.pull-right {
	float: none;
}
#headerv2 a.hdr-btn {
	position: inherit;
	font-size: 22px;
}
}
</style>
</head>
<body id="landingv2">
<?php get_template_part( 'template-parts/google-tag-manager' ); ?>
