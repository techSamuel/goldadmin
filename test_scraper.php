<?php
$html = file_get_contents('bajus_content.html');

libxml_use_internal_errors(true);
$dom = new \DOMDocument();
$dom->loadHTML($html);
libxml_clear_errors();

$xpath = new \DOMXPath($dom);

$labels = [
    '22 KARAT Gold',
    '21 KARAT Gold',
    '18 KARAT Gold',
    'TRADITIONAL Gold',
    '22 KARAT Silver',
    'TRADITIONAL Silver'
];

echo "--- START TEST ---\n";
foreach ($labels as $label) {
    // Exact same query as service
    $query = "//tr[contains(., '$label')]//span[contains(@class, 'price')]";
    $nodes = $xpath->query($query);

    if ($nodes->length > 0) {
        $raw = $nodes->item(0)->textContent;
        // Exact same cleanup as service
        $price = (float) preg_replace('/[^0-9.]/', '', $raw);
        echo "Label: [$label] -> Raw: [" . trim($raw) . "] -> Parsed: [$price]\n";
    } else {
        echo "Label: [$label] -> NOT FOUND\n";
    }
}
echo "--- END TEST ---\n";
