<?php
/**
 * LightRAG PHP wrapper — uses the REAL LightRAG REST API endpoints
 *
 * Actual endpoints (confirmed from LightRAG source):
 *   POST /documents/text   → insert a document
 *   POST /query            → query/search
 *   DELETE /documents/{id} → delete a document
 *   GET  /health           → health check
 */

class LightRAG {
    private $host;
    private $apiKey;

    public function __construct($config) {
        $this->host   = rtrim($config['host'] ?? 'http://172.28.85.61:8080', '/');
        $this->apiKey = $config['api_key'] ?? '';
    }

    // ── Core HTTP request ─────────────────────────────────────
    private function request($method, $endpoint, $payload = null) {
        $url = $this->host . $endpoint;
        $ch  = curl_init($url);

        $headers = ['Content-Type: application/json'];
        if (!empty($this->apiKey)) {
            $headers[] = 'Authorization: Bearer ' . $this->apiKey;
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        } elseif ($method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        $response  = curl_exec($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            throw new Exception('LightRAG curl error: ' . $curlError);
        }

        $decoded = json_decode($response, true);

        // Log non-200 responses for debugging
        if ($httpCode < 200 || $httpCode >= 300) {
            $msg = "LightRAG HTTP $httpCode on $method $endpoint: $response";
            file_put_contents(__DIR__ . '/../chat_error.log', date('Y-m-d H:i:s') . " - $msg\n", FILE_APPEND);
            return null;
        }

        return $decoded;
    }

    // ── Insert a document as plain text ──────────────────────
    // LightRAG will handle chunking, embedding, and graph building itself
    // $doc = ['id' => '...', 'text' => '...', 'metadata' => [...]]
    public function insert($doc) {
        return $this->request('POST', '/documents/text', [
            'text'        => $doc['text'],          // full text LightRAG will embed
            'id'          => $doc['id'] ?? null,     // optional custom ID
            'description' => $doc['description'] ?? null
        ]);
    }

    // ── Query LightRAG (replaces your old /search) ────────────
    // mode options: "hybrid" (default), "local", "global", "naive", "mix"
    public function query($queryText, $mode = 'hybrid', $topK = 5) {
        return $this->request('POST', '/query', [
            'query'  => $queryText,
            'mode'   => $mode,
            'top_k'   => $topK,
            'stream' => false
        ]);
    }

    // ── Delete a document by ID ───────────────────────────────
    public function delete($id) {
        return $this->request('DELETE', '/documents/' . urlencode($id));
    }

    // ── Health check ──────────────────────────────────────────
    public function health() {
        $ch = curl_init($this->host . '/health');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
}