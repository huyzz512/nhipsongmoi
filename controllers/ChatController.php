<?php
class ChatController
{
    public function process()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['reply' => 'Lỗi: Yêu cầu không hợp lệ.']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $userMessage = isset($input['message']) ? trim($input['message']) : '';

        if (empty($userMessage)) {
            echo json_encode(['reply' => 'Bạn muốn hỏi gì nào?']);
            exit;
        }

        // paste api vào đây
        $apiKey = '';


        $url = 'https://openrouter.ai/api/v1/chat/completions';

        $systemInstruction = "Bạn là Trợ lý AI chính thức của tòa soạn báo Nhịp Sống Mới, tên của bạn là Tiêu Tiêu. Nhiệm vụ của bạn là tư vấn, trả lời ngắn gọn, lịch sự, thân thiện bằng tiếng Việt.";

        $data = [
            // Lưu ý tên model
            "model" => "nvidia/nemotron-3-super-120b-a12b:free",
            "messages" => [
                ["role" => "system", "content" => $systemInstruction],
                ["role" => "user", "content" => $userMessage]
            ]
        ];

        $options = [
            'http' => [
                'header' => "Content-Type: application/json\r\n" .
                    "Authorization: Bearer " . $apiKey . "\r\n" .
                    "HTTP-Referer: http://localhost\r\n" .
                    "X-Title: Nhịp Sống Mới\r\n",
                'method' => 'POST',
                'content' => json_encode($data),
                'ignore_errors' => true
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false
            ]
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        if ($response === false) {
            echo json_encode(['reply' => '<strong style="color:red;">Lỗi nội bộ:</strong> Không thể kết nối Internet.']);
            exit;
        }

        $result = json_decode($response, true);

        if (isset($result['error'])) {
            $error_msg = is_array($result['error']) ? json_encode($result['error']) : $result['error'];
            echo json_encode(['reply' => '<strong style="color:red;">Lỗi API:</strong> ' . $error_msg]);
            exit;
        }

        if (isset($result['choices'][0]['message']['content'])) {
            $botReply = $result['choices'][0]['message']['content'];
        } else {
            $botReply = '<strong style="color:orange;">Dữ liệu lỗi:</strong> ' . $response;
        }

        echo json_encode(['reply' => $botReply]);
        exit;
    }
}
?>