<?php
$url = 'https://moderntouchbd.com/product/custom-counter-height-dining-table-tailored-design-perfect-fit-by-moderntouch/';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$html = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "cURL Error: " . $error . PHP_EOL;
} else {
    echo "Success! Length: " . strlen($html) . PHP_EOL;
    // Let's try to extract the title, price, description and main image.
    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    $title = $xpath->query('//h1[contains(@class, "product_title")]')->item(0)?->nodeValue;
    $price = $xpath->query('//p[contains(@class, "price")]//bdi')->item(0)?->nodeValue;
    $desc = $xpath->query('//div[contains(@class, "woocommerce-product-details__short-description")]')->item(0)?->nodeValue;
    $img = $xpath->query('//div[contains(@class, "woocommerce-product-gallery__image")]//img')->item(0)?->getAttribute('src');

    echo "Title: $title\nPrice: $price\nDesc: " . trim($desc) . "\nImage: $img\n";
}
