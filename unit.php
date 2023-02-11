<?php

$url = "<URL>";
$html = file_get_contents($url);

$doc = new DOMDocument();
@$doc->loadHTML($html);

$paragraphs = $doc->getElementsByTagName('p');
$content = array();

foreach ($paragraphs as $paragraph) {
    $content[] = $paragraph->nodeValue;
}

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n  \"model\": \"text-davinci-003\",\n  \"prompt\": \"Devine le domaine dactivité de cette entreprise partir des informations suivantes et renvoi moi uniquement le domaine d'activité sans phrase: " . implode(" ", $content) . "\",\n  \"max_tokens\": 128,\n  \"temperature\": 0.5\n}");

$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Authorization: Bearer <API_Key>';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}

$result = json_decode($result, true);

echo $result["choices"][0]["text"];


curl_close($ch);
?>
