<?php
	$require_css[] = "faq";
	$common_area = '<span class="faq">';
	$common_area .= '<h2>问题与解答</h2>';
	require_once("service/faq.class.php");
	$objFAQ = new FAQ();
	$arrFAQ = $objFAQ->getAllRecord();
	if (is_array($arrFAQ) && count($arrFAQ) > 0) {
		foreach ($arrFAQ as $keyFAQ => $valueFAQ) {
			$common_area .= '<div class="faqbox">';
			$common_area .= '<div class="faqquestion">';
			$common_area .= '&nbsp;'.htmlspecialchars($valueFAQ["question"]);
			$common_area .= '</div>';
			$common_area .= '<div class="faqanswer">';
			$arrAnswer = explode("\r\n", $valueFAQ["answer"]);
			if (is_array($arrAnswer) && count($arrAnswer) > 0) {
				foreach ($arrAnswer as $keyAnswer => $valueAnswer) {
					$common_area .= '<p>'.$valueAnswer.'</p>';
				}
			}
			$common_area .= '</div>';
			$common_area .= '</div>';
		}
	}
	$common_area .= '</span>';
?>