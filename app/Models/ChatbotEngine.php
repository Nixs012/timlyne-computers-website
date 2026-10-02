<?php
namespace App\Models;

class ChatbotEngine {
    public static function answer($message, $settings): array {
        $message = is_string($message) ? $message : '';
        $normalizedMessage = strtolower($message);
        $containsAny = static function ($terms) use ($normalizedMessage) {
            foreach ($terms as $term) {
                if (strpos($normalizedMessage, $term) !== false) {
                    return true;
                }
            }
            return false;
        };
        $fallback = static function () {
            $chatbotSettings = ChatbotSetting::getChatbotSettings();
            return [
                'reply' => (string)($chatbotSettings['fallback_message'] ?? ''),
                'matched' => 'fallback',
                'handoff' => !empty($chatbotSettings['whatsapp_handoff_enabled']),
            ];
        };

        // Step A: stock questions always take priority to avoid implying live inventory.
        if ($containsAny(['stock', 'available', 'availability', 'in stock'])) {
            return [
                'reply' => "I can't confirm live stock levels for specific items here, but I can connect you with our team on WhatsApp to check right away.",
                'matched' => 'stock_hedge',
                'handoff' => true,
            ];
        }

        // Step B: location.
        if ($containsAny(['location', 'located', 'where', 'address', 'shop', 'find you'])) {
            $address = $settings['business_address'] ?? '';
            if (is_string($address) && trim($address) !== '') {
                return ['reply' => $address, 'matched' => 'location', 'handoff' => false];
            }
            return $fallback();
        }

        // Step C: opening hours.
        if ($containsAny(['hours', 'open', 'close', 'time'])) {
            $openingHours = $settings['opening_hours'] ?? '';
            if (is_string($openingHours) && trim($openingHours) !== '') {
                return ['reply' => $openingHours, 'matched' => 'hours', 'handoff' => false];
            }
            return $fallback();
        }

        // Step D: contact details.
        if ($containsAny(['contact', 'phone', 'call', 'email', 'reach'])) {
            $phone = $settings['business_phone_1'] ?? '';
            $email = $settings['business_email'] ?? '';
            if (is_string($phone) && trim($phone) !== '' && is_string($email) && trim($email) !== '') {
                return ['reply' => "You can call us at {$phone} or email us at {$email}.", 'matched' => 'contact', 'handoff' => false];
            }
            return $fallback();
        }

        // Step E: published FAQ matching by significant whole words.
        $stopwords = ['the', 'and', 'for', 'you', 'your', 'what', 'how', 'with', 'this', 'that', 'are', 'can', 'does'];
        $tokenize = static function ($text) use ($stopwords) {
            $words = preg_split('/[^a-z0-9]+/i', strtolower((string)$text), -1, PREG_SPLIT_NO_EMPTY);
            $words = array_filter($words, static function ($word) use ($stopwords) {
                return strlen($word) > 3 && !in_array($word, $stopwords, true);
            });
            return array_values(array_unique($words));
        };
        $messageWords = $tokenize($message);
        $bestFaq = null;
        $bestFaqScore = 0;
        foreach (Faq::getPublished() as $faq) {
            $faqWords = $tokenize($faq['question'] ?? '');
            $score = count(array_intersect($messageWords, $faqWords));
            if ($score > $bestFaqScore) {
                $bestFaq = $faq;
                $bestFaqScore = $score;
            }
        }
        if ($bestFaqScore >= 2) {
            return ['reply' => (string)$bestFaq['answer'], 'matched' => 'faq', 'handoff' => false];
        }

        // Step F: full names take priority over specific word-level matches.
        $priceIntent = $containsAny(['price', 'cost', 'how much']);
        $products = Product::getAllPublic();
        $fullNameMatches = [];
        foreach ($products as $product) {
            $productName = trim((string)($product['name'] ?? ''));
            $normalizedName = strtolower($productName);
            if ($normalizedName !== '' && strpos($normalizedMessage, $normalizedName) !== false) {
                $fullNameMatches[] = $product;
            }
        }

        $matchedProducts = $fullNameMatches;
        if (!$fullNameMatches) {
            $genericProductTerms = ['laptop', 'desktop', 'printer', 'router', 'cable', 'adapter'];
            $messageWords = $tokenize($message);
            foreach ($products as $product) {
                $productName = trim((string)($product['name'] ?? ''));
                $productWords = array_values(array_filter($tokenize($productName), static function ($word) use ($genericProductTerms) {
                    return !in_array($word, $genericProductTerms, true);
                }));
                if ($productWords && count(array_diff($productWords, $messageWords)) === 0) {
                    $matchedProducts[] = $product;
                }
            }
            if (count($matchedProducts) > 3) {
                return $fallback();
            }
        }
        if ($matchedProducts && ($priceIntent || !$containsAny(['stock', 'available', 'availability', 'in stock']))) {
            $lines = [];
            foreach ($matchedProducts as $product) {
                $lines[] = trim($product['name']) . ' is priced at Ksh ' . number_format((float)$product['price']) . '.';
            }
            return ['reply' => implode("\n", $lines), 'matched' => 'product', 'handoff' => false];
        }

        // Step G: configured fallback and handoff preference.
        return $fallback();
    }
}